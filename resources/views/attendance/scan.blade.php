<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Library Attendance & Book RFID</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/attendance/scan.css') }}">
</head>
<body>

  <header class="site-header">
    <div class="header-inner">
      <div class="brand">
        <img src="{{ asset('images/pantasLogo.png') }}" alt="Pantas Logo" class="brand-logo">
        <div class="brand-text">
          <span class="brand-eyebrow">MMFC Library</span>
          <h1 class="brand-title">Powered by Pantas</h1>
        </div>
      </div>
      <a href="{{ route('book.index') }}" class="home-button">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
          <path d="M3 9.5L12 3l9 6.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V9.5z"/>
        </svg>
        Home
      </a>
    </div>
  </header>

  <main class="main">
    <aside class="sidebar">
      <div class="clock-card">
        <div class="date" id="currentDate">Date</div>
        <div class="time" id="currentTime">--:--:--</div>
        <div class="scan-hint">
          <span class="scan-dot"></span>
          Ready to scan
        </div>
      </div>

      <div class="profile-pic">
        <div class="scan-animation"></div>
        @if(isset($student) && $student->profile_picture)
          <img src="{{ asset($student->profile_picture) }}" alt="Profile">
        @else
          <img src="{{ asset('images/2x2_undifined_gender.jpg') }}" alt="Default Profile">
        @endif
      </div>

      @if(isset($student))
        <div class="name-box">
          <div class="name-box-label">Patron</div>
          <div class="student-name">{{ $student->firstname }} {{ $student->lastname }}</div>
          <div class="status-button {{ strtolower($status) === 'out' ? 'status-out' : 'status-in' }}">
            {{ $status }}
          </div>
          <div class="timestamp">
            {{ isset($log) ? \Carbon\Carbon::parse($log->scanned_at)->format('M d, Y · h:i A') : '' }}
          </div>
        </div>
      @endif

      @if(isset($book))
        <div class="name-box">
          <div class="name-box-label">Book</div>
          <div class="student-name book-title">{{ $book->title_statement }}</div>
          <div class="status-button {{ strtolower($bookStatus) === 'not checked out' ? 'status-out' : 'status-in' }}">
            {{ $bookStatus }}
          </div>
        </div>
      @endif

      @if(session('error'))
        <div class="name-box name-box-error">
          <div class="name-box-label">Notice</div>
          <div class="student-name">{{ session('error') }}</div>
        </div>
      @endif
    </aside>

    <section class="right-content">
      <form method="POST" action="{{ route('attendance.process') }}" class="scan-form">
        @csrf
        <input type="text" name="qrcode" class="scan-input" autofocus autocomplete="off" aria-label="RFID scan input">
      </form>

      <div class="video-frame">
        <video autoplay loop muted playsinline class="ads-vid">
          <source src="{{ asset('videos/area51_product_slideshow.mp4') }}" type="video/mp4">
        </video>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="marquee" aria-label="Welcome message">
      <div class="marquee-track">
        <span>Welcome to Mindanao Medical Foundation College Library</span>
        <span aria-hidden="true">Welcome to Mindanao Medical Foundation College Library</span>
        <span aria-hidden="true">Welcome to Mindanao Medical Foundation College Library</span>
        <span aria-hidden="true">Welcome to Mindanao Medical Foundation College Library</span>
      </div>
    </div>
  </footer>

  <audio id="alertSound" src="{{ asset('sounds/alert.wav') }}" preload="auto"></audio>

  <script>
    function updateDateTime() {
      const now = new Date();
      const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
      document.getElementById('currentDate').innerText = now.toLocaleDateString('en-GB', options);
      document.getElementById('currentTime').innerText = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }
    setInterval(updateDateTime, 1000);
    updateDateTime();

    window.onload = function () {
      const input = document.querySelector('.scan-input');
      if (input) {
        input.focus();
        setInterval(() => input.focus(), 500);
        input.disabled = true;
        setTimeout(() => {
          input.disabled = false;
          input.focus();
        }, 1000);
      }

      @if(isset($bookStatus) && strtolower($bookStatus) === 'not checked out')
        document.getElementById('alertSound')?.play();
      @endif

      setTimeout(() => {
        const profileImg = document.querySelector('.profile-pic img');
        if (profileImg) {
          profileImg.src = "{{ asset('images/2x2_undifined_gender.jpg') }}";
        }
        document.querySelectorAll('.name-box').forEach(box => {
          box.style.display = 'none';
        });
      }, 3000);
    };
  </script>
</body>
</html>
