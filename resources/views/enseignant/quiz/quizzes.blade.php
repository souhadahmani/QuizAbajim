<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قائمة الاختبارات</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Google Fonts (Tajawal) -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: #f8fcff;
            font-family: 'Tajawal', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            overflow: auto;
            position: relative;
        }

        /* Navbar */
        .navbar-custom {
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 10px 20px;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-custom .logo img {
            max-height: 50px;
            width: auto;
            object-fit: contain;
        }

        .navbar-custom .nav-icons i {
            color: #00bcd4;
            font-size: 1.2rem;
            margin-left: 30px;
            cursor: pointer;
        }

        /* Sidebar */
        .sidebar {
            background: #fff;
            width: 200px;
            height: 100vh;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05);
            z-index: 2;
            position: fixed;
            top: 0;
            right: 0;
            margin-top: 65px;
        }

        .sidebar h4 {
            color: #00bcd4;
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }

        .sidebar .teacher-profile {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar .teacher-profile img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid #00bcd4;
            margin-bottom: 10px;
        }

        .sidebar .teacher-profile span {
            display: block;
            color: #00bcd4;
            font-size: 1.2rem;
        }

        .sidebar .levels-materials {
            margin-top: 20px;
            text-align: center;
        }

        .sidebar .levels-materials h5 {
            color: #00bcd4;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .sidebar .levels-materials p {
            color: #333;
            font-size: 1rem;
        }

        .sidebar .levels-materials ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar .levels-materials ul li {
            color: #333;
            font-size: 1rem;
            margin: 5px 0;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 10px 0;
        }

        .sidebar ul li a {
            color: #00bcd4;
            font-size: 1.2rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .sidebar ul li a:hover {
            background: #e1f5fe;
        }

        .sidebar ul li a i {
            margin-left: 10px;
        }

        /* Main Content */
        .content {
            margin-right: 220px;
            margin-top: 70px;
            padding: 20px;
            min-height: calc(100vh - 70px);
        }

        /* Search Bar */
        .search-bar {
            margin-bottom: 20px;
            text-align: center;
        }

        .search-bar .form-control {
            max-width: 300px;
            display: inline-block;
            direction: rtl;
        }

        /* Quizzes Section */
        .quizzes-section {
            margin-top: 30px;
            text-align: center;
        }

        .quizzes-section h4 {
            color: #00bcd4;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .quizzes-section .add-quiz-btn {
            background: #00bcd4;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 50%;
            font-size: 1.5rem;
            margin-bottom: 15px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            text-decoration: none;
            transition: transform 0.3s ease, background 0.3s ease;
            position: relative;
            animation: pulse 2s infinite;
        }

        .quizzes-section .add-quiz-btn:hover {
            background: #0288d1;
            transform: scale(1.1);
            animation: none; /* Stop animation on hover */
        }

        .quizzes-section .add-quiz-btn i {
            margin: 0;
        }

        .quizzes-section .add-quiz-btn::after {
            content: "إضافة اختبار";
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: #00bcd4;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 1rem;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease;
            z-index: 1;
        }

        .quizzes-section .add-quiz-btn:hover::after {
            opacity: 1;
            visibility: visible;
        }

        /* Pulse Animation */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .table-container {
            background: #fff;
            border: 3px solid #00bcd4;
            border-radius: 15px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            padding: 20px;
            margin-top: 20px;
        }

        .t-header {
            color: #00bcd4;
            font-size: 1.8rem;
            text-align: center;
            margin-bottom: 15px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }

        .custom-table th, .custom-table td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #e1f5fe;
        }

        .custom-table th {
            background: #00bcd4;
            color: #fff;
            font-weight: 700;
        }

        .custom-table td {
            color: #333;
            font-size: 1rem;
        }

        .btn-primary {
            background: #00bcd4;
            border: none;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background: #0288d1;
        }

        .btn-outline-danger {
            background: #dc3545;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s ease;
        }

        .btn-outline-danger:hover {
            background: #c82333;
        }

        .btn-outline-success {
            background: #28a745;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s ease;
        }

        .btn-outline-success:hover {
            background: #218838;
        }

        .btn-danger {
            background: #dc3545;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s ease;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .no-quizzes {
            text-align: center;
            color: #dc3545;
            font-size: 1.5rem;
            padding: 20px;
        }

        /* Mascot Section */
        .mascot-container {
            position: fixed;
            bottom: 10px;
            left: 10px;
            z-index: 1000;
        }

        .mascot {
            width: 80px;
            height: 80px;
        }

        .mascot-message {
            position: absolute;
            top: -40px;
            left: 90px;
            background: #ff6f61;
            color: white;
            padding: 5px 10px;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 700;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 1024px) {
            .content {
                margin-right: 0;
                padding: 10px;
            }

            .sidebar {
                width: 150px;
            }

            .quizzes-section h4 {
                font-size: 1.3rem;
            }

            .t-header {
                font-size: 1.5rem;
            }

            .custom-table th, .custom-table td {
                font-size: 0.9rem;
                padding: 8px;
            }

            .mascot {
                width: 60px;
                height: 60px;
            }

            .mascot-message {
                font-size: 0.9rem;
                padding: 5px 8px;
                top: -30px;
                left: 70px;
            }
        }
        .logout-btn {
    background: none;
    border: none;
    color: #00bcd4;
    font-size: 1.2rem;
    margin-left: 30px;
    cursor: pointer;
    padding: 0;
    transition: color 0.3s ease;
}

.logout-btn:hover {
    color: #0097a7;
}

.logout-btn:focus {
    outline: none;
}
.nav-icons .back-arrow {
    color: #00bcd4; /* Match existing icon color */
    font-size: 1.5rem; /* Larger size for visibility */
    margin-left: 30px; /* Consistent spacing */
    cursor: pointer;
    transition: transform 0.3s ease, color 0.3s ease;
}

.nav-icons .back-arrow:hover {
    color: #0288d1; /* Darker shade on hover */
    transform: scale(1.2); /* Slight scale effect for emphasis */
}
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar-custom">
    <div class="nav-icons">
    <i class="fas fa-arrow-left back-arrow" onclick="window.history.back()" title="رجوع"></i>
        <i class="fas fa-bell"></i>

        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="logout-btn" title="تسجيل الخروج" >
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </form>
    </div>
    
    <div class="logo">
        <img src="{{ asset('images/abajim.png') }}" alt="Abajim Logo">
    </div>
</div>    <!-- Sidebar -->
    <div class="sidebar">
        <h4>لوحة تحكم المعلم</h4>
        <div class="teacher-profile">
            <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('images/default-avatar.jpg') }}" alt="Teacher Photo">
            <span>المعلم(ة) {{ Auth::user()->full_name }}</span>
        </div>

        <!-- School Level and Materials -->
        <div class="levels-materials">
    <h5>المستويات والمواد</h5>
    @if (Auth::user()->materials->isNotEmpty())
        @php
            $materialsByLevel = Auth::user()->materials->groupBy('level_id');
        @endphp
        @foreach($materialsByLevel as $levelId => $materials)
            @php
                $level = $materials->first()->section->level;
               
            @endphp
          
            <div class="level-section">
                <h6>المستوى: {{ $level->name ?? 'غير محدد' }}</h6>
                <ul>
             
                    @foreach($materials as $userMatiere)
                        <li>{{ $userMatiere->name ?? 'مادة غير محددة' }}</li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    @else
        <p>لم يتم تحديد المستويات بعد.</p>
        <a href="{{ route('teacher.profile.create') }}" class="btn btn-primary">إعداد الملف الشخصي</a>
    @endif
</div>
        <ul>
            <li><a href="{{ url('/teacher/dashboard') }}"><i class="fas fa-home"></i> الرئيسية</a></li>
            <li><a href="{{ url('/quiz') }}"><i class="fas fa-book"></i> الاختبارات</a></li>
            <li><a href="{{ url('/account/settings') }}"><i class="fas fa-cog"></i> الإعدادات</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="search-bar mt-2">
            <input type="text" class="form-control" id="searchQuiz" placeholder="ابحث عن اختبار..." onkeyup="searchQuiz()">
        </div>
        <!-- Quizzes Section -->
        <div class="quizzes-section">
            <h4>إضافة تحدي جديد</h4>
            <a href="{{ route('quiz.create') }}" class="add-quiz-btn">
                <i class="fas fa-plus"></i>
            </a>
            <h4>قائمة الاختبارات</h4>

            <div class="table-container">
                <div class="t-header">الاختبارات</div>
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>عنوان</th>
                                <th>وقت</th>
                               
                                <th>الدرجة الكلية</th>
                                <th>المستوى</th>
                                <th>المادة</th>
                                <th>تم إنشاؤها في</th>
                                <th>فعل</th>
                            </tr>
                        </thead>
                        <tbody id="quizTableBody">
                            @if ($quizzes->isNotEmpty())
                                @foreach ($quizzes as $quiz)
                                    <tr>
                                        <td>{{ $quiz->title ?? 'N/A' }}</td>
                                        <td>
                                            @php
                                                $time = $quiz->time ?? 'N/A';
                                                if (strpos($time, 'mn') !== false) {
                                                    $minutes = intval(str_replace('mn', '', $time));
                                                    echo $minutes . ' دقيقة';
                                                } else {
                                                    echo $time;
                                                }
                                            @endphp
                                        </td>
                                       
                                        <td>{{ $quiz->total_mark ?? 'N/A' }}</td>
                                        <td>{{ $quiz->schoolLevel ? $quiz->schoolLevel->name : 'N/A' }}</td>
                                        <td>{{ $quiz->subject ? $quiz->subject->name : 'N/A' }}</td>                                        

                                        <td>{{ $quiz->created_at ? date('Y-m-d h:i:s', strtotime($quiz->created_at)) : 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('quiz.create', ['quiz_id' => $quiz->id]) }}" class="btn btn-primary">تعديل</a>
                                            <a href="javascript:void(0)" module="quiz" moduleid="{{ $quiz->id }}" title="quiz" class="confirmDelete">
                                                <span class="btn btn-danger">حذف</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9" class="no-quizzes">لا يوجد اختبارات حالياً!</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="pagination justify-content-center mt-3">
                    {{ $quizzes->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Mascot Section -->
    <div class="mascot-container">
        <img src="{{ asset('images/book.png') }}" alt="Mascot" class="mascot">
        <div class="mascot-message" id="mascotMessage">
            مرحبًا! في منصة أبجيم
        </div>
    </div>

    <!-- Audio for Sound Effects -->
    <audio id="schoolBellSound" src="https://www.soundjay.com/buttons/school-bell.mp3"></audio>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        // DOM Elements
        const schoolBellSound = document.getElementById('schoolBellSound');

        // Configure Toastr
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: 5000,
            rtl: true
        };

        // Play School Bell on Load
        window.onload = function() {
            schoolBellSound.play();
        };

        // Update Quiz Status
        $(document).on('click', '.updateQuizStatus', function() {
            let quizId = $(this).attr('quiz_id');
            let status = $(this).find('span').text() === 'إلغاء' ? 'inactive' : 'active';
            $.ajax({
                url: '{{ url('/quiz') }}/' + quizId,
                method: 'POST',
                data: { quiz_id: quizId, status: status, _token: '{{ csrf_token() }}' },
                success: function(response) {
                    toastr.success("تم تحدييث حالة الاختبار!", "نجاح");
                    location.reload();
                },
                error: function() {
                    toastr.error("فشل تحديث حالة الاختبار!", "خطأ");
                }
            });
        });

        // Confirm Delete
        $(document).on('click', '.confirmDelete', function() {
            if (confirm("هل أنت متأكد من حذف هذا الاختبار؟")) {
                let quizId = $(this).attr('moduleid');
                $.ajax({
                    url: '{{ url('/delete-quiz') }}/' + quizId,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        toastr.success("تم حذف الاختبار بنجاح!", "نجاح");
                        location.reload();
                    },
                    error: function() {
                        toastr.error("فشل حذف الاختبار!", "خطأ");
                    }
                });
            }
        });

        // Search Quiz Function
        function searchQuiz() {
            let input = document.getElementById('searchQuiz').value.toLowerCase();
            let table = document.getElementById('quizTableBody').getElementsByTagName('tr');

            for (let i = 0; i < table.length; i++) {
                let found = false;
                let cells = table[i].getElementsByTagName('td');
                for (let j = 0; j < cells.length; j++) {
                    let text = cells[j].textContent || cells[j].innerText;
                    if (text.toLowerCase().indexOf(input) > -1) {
                        found = true;
                        break;
                    }
                }
                table[i].style.display = found ? '' : 'none';
            }
        }
    </script>
</body>
</html>