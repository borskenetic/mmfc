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
        <div class="scan-hint" id="scanHint">
          <span class="scan-dot"></span>
          <span id="scanHintText">Ready to scan</span>
        </div>
      </div>

      <div class="profile-pic">
        <div class="scan-animation"></div>
        @if(isset($student) && $student->profile_picture)
          <img src="{{ asset($student->profile_picture) }}" alt="Profile">
        @elseif(isset($employee) && $employee->formal_picture)
          <img src="{{ asset($employee->formal_picture) }}" alt="Profile">
        @else
          <img src="{{ asset('images/2x2_undifined_gender.jpg') }}" alt="Default Profile">
        @endif
      </div>

      @if(isset($student))
        <div class="name-box">
          <div class="name-box-label">Student</div>
          <div class="student-name">{{ $student->firstname }} {{ $student->lastname }}</div>
          <div class="status-button {{ strtolower($status) === 'out' ? 'status-out' : 'status-in' }}">
            {{ $status }}
          </div>
          <div class="timestamp">
            {{ isset($log) ? \Carbon\Carbon::parse($log->scanned_at)->format('M d, Y · h:i A') : '' }}
          </div>
        </div>
      @endif

      @if(isset($employee))
        <div class="name-box">
          <div class="name-box-label">Employee</div>
          <div class="student-name">{{ $employee->firstname }} {{ $employee->lastname }}</div>
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

      @php $scanError = $error ?? session('error') ?? ($errors->first('qrcode') ?? null); @endphp
      @if($scanError)
        <div class="name-box name-box-error">
          <div class="name-box-label">Notice</div>
          <div class="student-name">{{ $scanError }}</div>
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
      const SCAN_COOLDOWN_MS = 3000;
      const input = document.querySelector('.scan-input');
      const form = document.querySelector('.scan-form');
      const hintText = document.getElementById('scanHintText');
      let ready = false;

      function setHint(text) {
        if (hintText) hintText.textContent = text;
      }

      function startCooldown() {
        ready = false;
        if (input) input.disabled = true;

        let remaining = Math.ceil(SCAN_COOLDOWN_MS / 1000);
        setHint('Please wait ' + remaining + 's…');

        const tick = setInterval(() => {
          remaining -= 1;
          if (remaining > 0) {
            setHint('Please wait ' + remaining + 's…');
          } else {
            clearInterval(tick);
            ready = true;
            if (input) {
              input.disabled = false;
              input.focus();
            }
            setHint('Ready to scan');
          }
        }, 1000);
      }

      if (input) {
        input.focus();
        setInterval(() => {
          if (ready && input && !input.disabled) input.focus();
        }, 500);
        startCooldown();
      }

      if (form) {
        form.addEventListener('submit', function (e) {
          if (!ready || (input && input.disabled)) {
            e.preventDefault();
            return;
          }
          // Do not disable the input here — disabled fields are omitted from POST,
          // so qrcode never reaches the server and the scan looks like a no-op.
          const value = (input?.value || '').trim();
          if (!value) {
            e.preventDefault();
            return;
          }
          ready = false;
          setHint('Processing…');
        });
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
