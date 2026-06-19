<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Support\QrCodePng;
use App\Support\SignatureImage;
use Intervention\Image\Facades\Image;
use ZipArchive;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class IdCardController extends Controller
{
    private function drawText($img, $text, $x, $y, $size, $color = '#000', $align = 'center', $valign = 'top')
    {
        $fontPathBold = public_path('fonts/arialbd.ttf');
        $fontPathRegular = public_path('fonts/arialbd.ttf');

        // If bold font exists, use it
        if (file_exists($fontPathBold)) {
            $img->text($text, $x, $y, function ($font) use ($fontPathBold, $size, $color, $align, $valign) {
                $font->file($fontPathBold);
                $font->size($size);
                $font->color($color);
                $font->align($align);
                $font->valign($valign);
            });
        } else {
            // Otherwise draw text several times for a bolder effect
            foreach ([[-1,0], [1,0], [0,-1], [0,1]] as [$ox, $oy]) {
                $img->text($text, $x + $ox, $y + $oy, function ($font) use ($fontPathRegular, $size, $color, $align, $valign) {
                    $font->file($fontPathRegular);
                    $font->size($size);
                    $font->color($color);
                    $font->align($align);
                    $font->valign($valign);
                });
            }

            // Center text (main pass)
            $img->text($text, $x, $y, function ($font) use ($fontPathRegular, $size, $color, $align, $valign) {
                $font->file($fontPathRegular);
                $font->size($size);
                $font->color($color);
                $font->align($align);
                $font->valign($valign);
            });
        }
    }

    public function front($id)
    {
        $student = Student::findOrFail($id);

        // Background
        $img = Image::make(base_path('images/id_templates/front.png'));

        // Profile picture
        if ($student->profile_picture && file_exists(base_path($student->profile_picture))) {
            $profile = Image::make(base_path($student->profile_picture))->resize(1100, 1100);
            $img->insert($profile, 'center', 20, 20);
        }
        
        $fullName = strtoupper($student->firstname . ' ' . $student->lastname);

        $addName = strlen($fullName);
    
        // Base font size
        $addNameFont = 150;
    
        // Adjust font size based on name length
        if ($addName > 20 && $addName <= 25) {
            $addNameFont = 130;
        } elseif ($addName > 25 && $addName <= 30) {
            $addNameFont = 120;
        } elseif ($addName > 30) {
            $addNameFont = 100;
        }

        $img->text($fullName, 1125, 2725, function ($font) use ($addNameFont) {
            $font->file(public_path('fonts/arialbd.ttf'));
            $font->size($addNameFont);
            $font->color('#000');
            $font->align('center');
            $font->valign('top');
        });

        // ID number
        if ($student->student_id) {
            $this->drawText($img, "ID NO. : " . $student->student_id, 1165, 2555, 120, '#000');
        }
        
        $course = strtoupper($student->course);

        // Split at "/" or long courses
        $parts = preg_split('/\s*\/\s*/', $course);
        
        // Ensure we always have two lines
        $line1 = $parts[0] ?? '';
        $line2 = $parts[1] ?? '';
        
        // Length calculation based on the longest line
        $addLength = max(strlen($line1), strlen($line2));
        
        // Base font size — increased
        $addFontSize = 120;
        
        if ($addLength > 25 && $addLength <= 38) {
            $addFontSize = 110;
        }
        elseif ($addLength > 38 && $addLength <= 40) {
            $addFontSize = 84;
        }elseif ($addLength > 40) {
            $addFontSize = 71.5;
        }
        
        $centerX = 1105;  // your center X
        $startY  = 2915;  // first line Y
        $lineGap = $addFontSize + 15; // spacing between lines
        
        if ($line1) {
            $this->drawText($img, $line1, $centerX, $startY, $addFontSize, '#FFFFFF', 'center', 'top');
        }
        
        if ($line2) {
            $this->drawText($img, $line2, $centerX, $startY + $lineGap, $addFontSize, '#FFFFFF', 'center', 'top');
        }


        return $img->response('png');
    }

    public function back($id)
    {
        $student = Student::findOrFail($id);

        // Background
        $img = Image::make(base_path('images/id_templates/back.png'));

        // QR Code
        $qrPng = QrCodePng::generate($student->qrcode, 1300, 0);
        $qrImage = Image::make((string) $qrPng);

        // Birth date
        if ($student->birth_date) {
            $formattedDate = Carbon::parse($student->birth_date)->format('m-d-Y');
            $this->drawText($img, $formattedDate, 3000, 800, 300, '#000');
        }

        // Blood type
        if ($student->blood_type) {
            $this->drawText($img, $student->blood_type, 3000, 1550, 300, '#000');
        }

        // Emergency contact details
        if ($student->emergency_contact_name) {
            $this->drawText($img, $student->emergency_contact_name, 2190, 2650, 250, '#000');
        }

        if ($student->emergency_contact_relationship) {
            $this->drawText($img, $student->emergency_contact_relationship, 2250, 2900, 250, '#000');
        }

        if ($student->emergency_contact_number) {
            $this->drawText($img, $student->emergency_contact_number, 2230, 3200, 250, '#000');
        }
        if ($student->emergency_address) {

            $address = strtoupper($student->emergency_address);
        
            // --------- AUTO LINE WRAP (max ~30 chars per line) ---------
            $maxChars = 60; // adjust if needed
            $words = explode(' ', $address);
        
            $lines = [];
            $current = '';
        
            foreach ($words as $word) {
                if (strlen($current . ' ' . $word) <= $maxChars) {
                    $current .= ($current ? ' ' : '') . $word;
                } else {
                    $lines[] = $current;
                    $current = $word;
                }
            }
            if ($current) {
                $lines[] = $current;
            }
        
            // Measure longest line for font size logic
            $maxLength = max(array_map('strlen', $lines));
        
            // --------- FONT SIZE RESIZING ---------
            $fontSize = 250;
        
            if ($maxLength > 25 && $maxLength <= 35) {
                $fontSize = 200;
            } elseif ($maxLength > 35 && $maxLength <= 45) {
                $fontSize = 150;
            } elseif ($maxLength > 45) {
                $fontSize = 100;
            }
        
            // --------- DRAW CENTERED MULTI-LINE TEXT ---------
            $centerX = 2230;  
            $startY  = 3500;
            $spacing = $fontSize + 10; // dynamic vertical spacing
        
            foreach ($lines as $i => $line) {
                $this->drawText($img, $line, $centerX, $startY + ($i * $spacing), $fontSize, '#000');
            }
        }


        SignatureImage::insertOnStudentCard($img, $student->student_signature);

        // QR code
        $img->insert($qrImage, 'top-left', 620, 510);

        return $img->response('png');
    }

    public function download($id)
    {
        $student = Student::findOrFail($id);

        // Generate both sides
        $front = $this->front($id)->getContent();
        $back = $this->back($id)->getContent();

        // Paths
        $zipPath = storage_path("app/temp_id_{$id}.zip");
        $frontPath = storage_path("app/front_{$id}.png");
        $backPath = storage_path("app/back_{$id}.png");

        // Save temporary images
        file_put_contents($frontPath, $front);
        file_put_contents($backPath, $back);

        // Create zip
        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $zip->addFile($frontPath, "{$student->lastname}_{$student->firstname}_front.png");
            $zip->addFile($backPath, "{$student->lastname}_{$student->firstname}_back.png");
            $zip->close();
        }

        // Clean up
        unlink($frontPath);
        unlink($backPath);

        // Download
        return response()->download($zipPath, "{$student->lastname}_{$student->firstname}_ID.zip")
                         ->deleteFileAfterSend(true);
    }
}
