<!DOCTYPE html>
<html>
<head>
  <title>Library Attendance & Book RFID</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{ asset('public/css/attendance/scan.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
  <header>
    <div class="header">
      <div class="logo-title">
        <img src="{{ asset('images/pantasLogo.png') }}" alt="Logo">
        <div class="system-title">SMART DIGITAL LIBRARY</div>
        <a href="{{ route('book.index') }}" class="home-button">Home</a>
      </div>
    </div>
  </header>

  <div class="main">
    <div class="sidebar">
      <div class="date" id="currentDate">Date</div>
      <div class="time" id="currentTime">--:--:--</div>

      <div class="profile-pic">
        @if(isset($student) && $student->profile_picture)
          <img src="{{ asset($student->profile_picture) }}" alt="Profile">
        @else
          <img src="{{ asset('images/2x2_undifined_gender.jpg') }}" alt="Default Profile">
        @endif
      </div>

      <!-- ✅ Student log -->
      @if(isset($student))
        <div class="name-box">
          <div class="student-name">{{ $student->firstname }} {{ $student->lastname }}</div>
          <div class="label">Name</div>
          <div class="status-button {{ strtolower($status) === 'out' ? 'status-out' : '' }}">
            {{ $status }}
          </div>
          <div class="timestamp">
            {{ isset($log) ? \Carbon\Carbon::parse($log->scanned_at)->format('Y-m-d h:i:s A') : '' }}
          </div>
        </div>
      @endif

      <!-- ✅ Book check -->
      @if(isset($book))
        <div class="name-box">
          <div class="student-name">{{ $book->title_statement }}</div>
          <div class="label">Book Title</div>
          <div class="status-button {{ strtolower($bookStatus) === 'not checked out' ? 'status-out' : '' }}">
            {{ $bookStatus }}
          </div>
        </div>
      @endif

      <!-- ❌ Error -->
      @if(session('error'))
        <div class="name-box">
          <div class="student-name">{{ session('error') }}</div>
          <div class="label">Error</div>
        </div>
      @endif

    </div>

    <div class="right-content">
      <form method="POST" action="{{ route('attendance.process') }}">
        @csrf
        <input type="text" name="qrcode" style="opacity:0; position:absolute;" autofocus>
      </form>

      <video autoplay loop controls muted class="ads-vid">
        <source src="{{ asset('videos/area51_product_slideshow.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
      </video>
    </div>
  </div>

  <footer>
    <div class="footer1">
      <div class="footer-logo">
        <div class="footer-title">Welcome To MINDANAO MEDICAL FOUNDATION COLLEGE</div>
      </div>
    </div>
  </footer>

  <!-- ✅ Add alert sound -->
  <audio id="alertSound" src="{{ asset('sounds/alert.wav') }}" type="audio/wav"></audio>

  <script>
    function updateDateTime() {
      const now = new Date();
      const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
      document.getElementById('currentDate').innerText = now.toLocaleDateString('en-GB', options);
      document.getElementById('currentTime').innerText = now.toLocaleTimeString('en-US');
    }
    setInterval(updateDateTime, 1000);
    updateDateTime();

    window.onload = function () {
      const input = document.querySelector('input[name="qrcode"]');
      if (input) {
        input.focus();
        setInterval(() => input.focus(), 500);
        input.disabled = true;
        setTimeout(() => {
          input.disabled = false;
          input.focus();
        }, 1000);
      }

      // ✅ Play alert sound if needed
      @if(isset($bookStatus) && strtolower($bookStatus) === 'not checked out')
        const alertSound = document.getElementById('alertSound');
        alertSound.play();
      @endif
      
      // ✅ After 3s, reset profile picture to default
        setTimeout(() => {
          const profileImg = document.querySelector('.profile-pic img');
          if (profileImg) {
            profileImg.src = "{{ asset('images/2x2_undifined_gender.jpg') }}";
          }
    
          // Optionally, also clear name/status/timestamp if student was shown
          const nameBoxes = document.querySelectorAll('.name-box');
          nameBoxes.forEach(box => {
            box.style.display = 'none';
          });
        }, 3000);
    };

  
  </script>
</body>
</html>
