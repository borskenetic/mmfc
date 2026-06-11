<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prospectus extends Model
{
    protected $table = 'prospectus';

    protected $fillable = ['course', 'year', 'subject'];
}
