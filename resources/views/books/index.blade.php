<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>📚 Book Kiosk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/books/index.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(function () {
            $('#program').on('change', function () {
                const program = $(this).val();
                $('#year').html('<option>Loading...</option>').prop('disabled', true);
                $('#course').html('<option>Select Course</option>').prop('disabled', true);

                if (program) {
                    $.get("{{ url('/filter/years') }}", { program }, function (data) {
                        let opts = '<option value="">Select Year</option>';
                        data.forEach(y => opts += `<option value="${y}">${y}</option>`);
                        $('#year').html(opts).prop('disabled', false);
                    });
                }
            });

            $('#year').on('change', function () {
                const program = $('#program').val();
                const year = $(this).val();
                $('#course').html('<option>Loading...</option>').prop('disabled', true);

                if (program && year) {
                    $.get("{{ url('/filter/courses') }}", { program, year }, function (data) {
                        let opts = '<option value="">Select Course</option>';
                        data.forEach(c => opts += `<option value="${c}">${c}</option>`);
                        $('#course').html(opts).prop('disabled', false);
                    });
                }
            });
        });
    </script>


</head>

<body>
    <!-- Header with Left Logo and Right Logout Button -->
    <div class="d-flex align-items-center px-4 py-2 flex-wrap" style="background-color: white; position: relative;">
        <img src="{{ asset('images/pantasLogo.png') }}" alt="New Logo" class="header-logo-img" />
        <h1 class="school-name mb-0 ms-2"></h1>

        <!-- Hamburger Toggle (visible only on small screens) -->
        <button id="customMenuToggle" class="d-md-none toggle-btn">
            &#9776;
        </button>

        <!-- Navigation Wrapper -->
        <div id="routeWrapper" class="d-flex gap-2 flex-wrap ms-auto responsive-nav">
            <!-- Close Button (for mobile view) -->
            <button id="customMenuClose" class="d-md-none close-btn">
                &times;
            </button>

            <a class="btn0 btn-sm">Home</a>
            <a class="btn2 btn-sm" href="{{ route('attendance.scan') }}">Attendance</a>
            <a class="btn2 btn-sm" href="{{ route('attendance_logs.index') }}">Attendance-logs</a>



            <a href="{{ route('landing') }}"
                class="btn2 btn-sm {{ request()->routeIs('books.landing') ? 'active-btn' : '' }}" hidden> OPAC</a>

            <div class="logs_dropdown" hidden>
                <button class="logs_dropdown-button">Create Account</button>
                <div class="logs_dropdown-content">
                    <a href="{{ route('users.create') }}">Create Account</a>
                    <a href="{{ route('users.index') }}">View Users</a>

                </div>
            </div>

            <a href="{{ route('prospectus.index') }}" class="btn3 btn-sm" hidden>Prospectus Manager</a>

            <div class="logs_dropdown" hidden>
                <button class="logs_dropdown-button">Logs</button>
                <div class="logs_dropdown-content">
                    <a href="{{ route('logs.index') }}">Logs</a>
                    <a href="{{ route('rfid.scanner') }}">RFID Scanner</a>
                    <a href="{{ route('book.report.download') }}">Download Book Report</a>
                    <a href="{{ route('students.report') }}">ID Generation</a>
                </div>
            </div>
            <a href="{{ route('students.report') }}" class="btn2 btn-sm">ID Generation</a>
            

            <a href="{{ route('files.index') }}" class="btn4 btn-sm" hidden>Repository</a>
            <form action="{{ route('logout') }}" method="POST" class="mb-0">
                @csrf
                <button type="submit" class="btn5">Logout</button>
            </form>
        </div>
    </div>

    <!-- ✅ JavaScript Toggle Functions -->
    <script>
        const toggleBtn = document.getElementById('customMenuToggle');
        const closeBtn = document.getElementById('customMenuClose');
        const routeWrapper = document.getElementById('routeWrapper');

        toggleBtn.addEventListener('click', () => {
            routeWrapper.classList.add('open');
        });

        closeBtn.addEventListener('click', () => {
            routeWrapper.classList.remove('open');
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                routeWrapper.classList.remove('open');
            }
        });
    </script>


    <div class="head">
        <img src="{{ asset('images/Bannernew.jpg') }}" alt="Banner" class="banner-img">
    </div>

    <div class="container py-3" hidden>

        <div class="row mb-3 g-2 align-items-center custom-margin">
            <div class="col-md-8">
                <form action="{{ route('book.index') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control me-2" placeholder="🔍 Search books..."
                        value="{{ request('search') }}" />
                    <button class="btn btn-search">Search</button>

                </form>

            </div>

            <div class="col-md-4 button_div text-end">
                <a href="{{ route('book.create') }}" class="btn btn-addbook"> Cataloging</a>
                <a href="https://area51lmslibrary.com/user-account/?fbclid=IwY2xjawLvE-xleHRuA2FlbQIxMABicmlkETFHTzhpTjBrRURpVWFFdW9hAR7tC4LGq_N7YomZscUpiyZKJxd0BCy69WYZuj5CxaseF8G5ctGQnauMPJnheg_aem_ZvE4NOhe8ZwtNtoumemmyg"
                    class="btn btn-51_learn" target="_blank" rel="noopener noreferrer" hidden>
                    51 Learned
                </a>
            </div>
        </div>

        <!-- Top Filters + Actions -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-3">
            <form action="{{ route('book.index') }}" method="GET" class="d-flex gap-2 flex-wrap flex-grow-1">
                <select id="program" name="program" class="form-select w-auto">
                    <option value="">Select Program</option>
                    @foreach($programs as $program)
                    <option value="{{ $program->id }}">{{ $program->program_name }}</option>
                    @endforeach
                </select>

                <select id="year" name="year" class="form-select w-auto" disabled>
                    <option value="">Select Year</option>
                    @foreach($years as $year)
                    <option value="{{ $year }}" {{ request('year')==$year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>

                <select id="course" name="course" class="form-select w-auto" disabled>
                    <option value="">Select Course</option>
                    @foreach($courses as $course)
                    <option value="{{ $course }}" {{ request('course')==$course ? 'selected' : '' }}>{{ $course }}
                    </option>
                    @endforeach
                </select>

                <button class="btn btn-filter">Apply Filters</button>
            </form>


        </div>

        <!-- Status + Utility Actions -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap2" style="margin-top: 50px;">
            <div class="btn-status">
                <a href="{{ route('book.index') }}" class="btn btn-all">📚 All</a>
                <a href="{{ route('book.index', ['status' => 'Available']) }}" class="btn btn-available">✅ Available</a>
                <a href="{{ route('book.index', ['status' => 'Borrowed']) }}" class="btn btn-borrowed">❌ Borrowed</a>
            </div>

            <div class="d-flex gap-2" >

                <a href="{{ route('ebooks.index') }}" class="btn btn-e-book">View E-Resources</a>
                <div class="dropdown" hidden>
                    <button class="dropdown-button">Accreditation</button>
                    <div class="dropdown-content">
                        <a href="#">PAASCU</a>
                        <a href="#">ALCU</a>
                        <a href="#">PACUCOA</a>
                        <a href="#">ISO</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Book Table -->
        <div class="card p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>

                            <th>Title</th>
                            <th>Author</th>
                            <th>Year Published</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($books as $book)
                        <tr>
                            <td>{{ $book->title_statement }}</td>
                            <td>{{ $book->main_author }}</td>
                            <td>{{ $book->pub_year }}</td>
                            <td class="{{ $book->availability === 'Available' ? 'text-success' : 'text-danger' }}">
                                {{ $book->availability }}
                            </td>
                            <td>
                                <!--Actions dropdown-->

                                <div class="dropdown1">
                                    <button class="dropdown1-button">Actions</button>
                                    <div class="dropdown1-content">
                                        <a href="{{ route('book.show', $book->id) }}" class="dropdown-item1">View</a>
                                        <a href="{{ route('book.edit', $book->id) }}" class="dropdown-item2">Edit</a>
                                        <button class="dropdown-item3" type="button" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $book->id }}">
                                            Delete
                                        </button>
                                    </div>
                                </div>

                                <!-- Modal -->
                                <div class="modal fade" id="deleteModal{{ $book->id }}" tabindex="-1"
                                    aria-labelledby="deleteModalLabel{{ $book->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-3 shadow-lg">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $book->id }}">Confirm
                                                    Delete</h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete <strong>{{ $book->title }}</strong>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>

                                                <!-- Actual Delete Form -->
                                                <form action="{{ route('books.destroy', $book->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
            </div>
            </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center text-muted">No books found.</td>
            </tr>
            @endforelse
            </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $books->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
    
    
   
    

    <!-- Import / Export Section -->
    <div class="card mt-4 p-4">
        <form action="{{ route('books.import') }}" method="POST" enctype="multipart/form-data"
            class="row g-2 align-items-center">
            @csrf
            <div class="col-md-6">
                <input type="file" name="file" class="form-control" required accept=".csv,.xlsx" />
            </div>
            <div class="col-md-6 d-flex gap-2">
                <button class="btn btn-import w-50">Import Books</button>
                <a href="{{ url('/export-books') }}" class="btn btn-export w-50">Export Books</a>
            </div>
        </form>
    </div>

    </div>
     <!-- Frequently Asked Questions -->
    
        <body>
  <section class="faq-section">
    <div class="faq-container">
      <div class="faq-header">
        <h2>Frequently asked questions</h2>
        <button class="read-more-btn">Read more</button>
      </div>

      <h3 class="faq-subtitle">Getting Started</h3>

      <div class="faq-list">
        <div class="faq-item">
          <p><strong>How can I register?</strong></p>
        </div>
        <div class="faq-item">
          <p><strong>Who can register?</strong></p>
        </div>
      </div>
    </div>
  </section>
    
    <!-- END of Frequently Asked Questions -->

    <footer>
        <div class="a51-footer">
            <h4 style="color: white; font-size:15px">Pantas © 2025. All Rights Reserved.</h4>
        </div>

    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>



</body>

</html>