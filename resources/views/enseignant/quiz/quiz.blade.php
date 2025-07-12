<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المعلم</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Google Fonts (Tajawal) -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <!-- Confetti Library -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <!-- HTML2PDF Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
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

        .cards-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
        }

        /* Dashboard Cards */
        .dashboard-card {
            background: #fff;
            padding: 10px;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            width: 350px; /* Increased for better visibility */
            text-align: center;
            border-left: 6px solid #00bcd4;
            transition: transform 0.3s ease;
            //min-height: 400px; /* Ensure consistent height */
        }

        .dashboard-card:hover {
            transform: scale(1.05);
        }

        .dashboard-card h5 {
            color: #00bcd4;
            font-size: 1.8rem;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .dashboard-card .metric {
            font-size: 3rem;
            color: #333;
            font-weight: bold;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .average-scores-card canvas, .progression-card canvas {
            height: 250px !important; /* Increased chart height */
            width: 100% !important;
            margin: 0 auto;
        }
        .quizzes-participated-card{

            background: #fff;
            border-left: 6px solid #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;    
        }
        .quizzes-consumed-card {
            background: #fff;
            border-left: 6px solid #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
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
        }

        .quizzes-section .add-quiz-btn:hover {
            background: #0288d1;
            transform: scale(1.1);
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

        /* Action buttons */
        .actions {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
        }
        
        .actions button {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 8px 12px;
        }
        
        .actions button img {
            width: 20px;
            height: 20px;
        }
        
        .actions .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: #fff;
        }
        
        .actions .btn-accent {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: #fff;
        }
        
        /* Dashboard container for PDF export */
        .dashboard-container {
            width: 100%;
        }

        @media (max-width: 1024px) {
            .content {
                margin-right: 0;
                padding: 10px;
            }

            .sidebar {
                width: 150px;
            }

            .dashboard-card {
                width: 100%;
                max-width: 400px;
            }

            .average-scores-card canvas, .progression-card canvas {
                height: 200px !important;
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
        .logo {
    display: flex;
    align-items: center;
    gap: 15px; /* Space between logout icon and logo */
}

.logout-btn {
    background: none;
    border: none;
    color: #ff6f61; /* Brighter color for visibility */
    font-size: 1.5rem; /* Larger size */
    cursor: pointer;
    padding: 5px;
    transition: transform 0.3s ease, background 0.3s ease, color 0.3s ease;
}

.logout-btn:hover {
    color: #e65b50; /* Slightly darker shade on hover */
    background: #e1f5fe; /* Light background on hover */
    border-radius: 50%;
    transform: scale(1.2); /* Scale effect for emphasis */
}

.logout-btn:focus {
    outline: none;
}    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar-custom">
    <div class="nav-icons">
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
</div>
    <!-- Sidebar -->
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
            <li><a href="{{url('/teacher/dashboard')}}"><i class="fas fa-home"></i> الرئيسية</a></li>
            <li><a href="{{url('/quiz')}}"><i class="fas fa-book"></i> الاختبارات</a></li>
            <li><a href="{{ url('/account/settings') }}"><i class="fas fa-cog"></i> الإعدادات</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="actions">
            <button class="btn btn-primary" onclick="toggleFullscreen()">
                <i class="fas fa-expand" style="font-size: 16px;"></i>
                <span>ملء الشاشة</span>
            </button>
            <button class="btn btn-secondary" onclick="generatePDF()">
                <i class="fas fa-file-pdf" style="font-size: 16px;"></i>
                <span>حفظ التقرير</span>
            </button>
            <button class="btn btn-accent" onclick="window.print()">
                <i class="fas fa-print" style="font-size: 16px;"></i>
                <span>طباعة</span>
            </button>
        </div>
        
        <!-- Dashboard container for PDF -->
        <div class="dashboard-container">
            <!-- Cards Section -->
            <div class="cards-container">
                <!-- Average Results (Histogramme, Blue) -->
                <!-- Average Results (Histogramme, Blue) -->
                <div class="dashboard-card average-scores-card">
                    <h5>متوسط درجات التحديات</h5>
                    <div class="metric">
                        @if ($averageScores->isNotEmpty())
                            {{ round($averageScores->avg('avg_score')) }}
                        @else
                            0
                        @endif
                    </div>
                    <canvas id="averageScoresChart"></canvas>
                </div>


                <!-- Progression Over Time (Line Chart, Blue) -->
                <div class="dashboard-card progression-card">
                    <h5>تقدم التلاميذ في الوقت</h5>
                    <div class="metric">
                        @if (!empty($progressionData))
                            {{ round(array_sum($progressionData) / count($progressionData)) }}
                        @else
                            0
                        @endif
                    </div>
                    <canvas id="progressionChart"></canvas>
                </div>

                <!-- Number of Students Participated (Bar Chart, Green) -->
<!-- Number of Students Participated (Card, Green) -->
<div class="dashboard-card quizzes-participated-card">
    <h5>
        
        عدد التلاميذ المشاركين
    </h5>
    
    <div class="metric">{{ $registeredStudents ?? 0 }}
<p>
    <i class="fas fa-children" style=" color: #17a2b8;"></i> </p><!-- Users icon in green -->
    </div>
    
</div>

<!-- Number of Quizzes Consumed (Card, White) -->
<div class="dashboard-card quizzes-consumed-card">
    <h5>
        <i class="fas fa-book" style="margin-right: 8px; color: #17a2b8;"></i> <!-- Check circle icon in cyan -->
        عدد الاختبارات المجتازة
    </h5>
    <div class="metric">{{ $uniqueQuizzesConsumed ?? 0 }}</div>
</div>            </div>
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
    <audio id="achievementSound" src="https://www.soundjay.com/buttons/ding-01.mp3"></audio>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        // DOM Elements
        const schoolBellSound = document.getElementById('schoolBellSound');
        const achievementSound = document.getElementById('achievementSound');

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

        // Initialize Charts with Error Handling
        // Average Scores Chart (Bar, Blue)
        try {
    new Chart(document.getElementById('averageScoresChart'), {
        type: 'bar',
        data: {
            labels: @json($averageScores->pluck('title')),
            datasets: [{
                label: 'متوسط الدرجات',
                data: @json($averageScores->pluck('avg_score')),
                backgroundColor: 'rgba(0, 188, 212, 0.7)',
                borderColor: 'rgba(0, 188, 212, 1)',
                borderWidth: 2,
                barThickness: 30
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, max: 100, ticks: { font: { size: 16 } },title: {
                        display: true,
                        text: 'متوسط الدرجات ',
                        font: { size: 16 }
                    } },
                x: { ticks: { font: { size: 14 } },title: {
                        display: true,
                        text: 'عناوين الاختبارات',
                        font: { size: 16 }
                    } }
            },
            plugins: {
                legend: { display: true },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label = context.raw ; 
                            return label;
                        }
                    }
                }
            }
        }
    });
} catch (e) {
    console.error('Average Scores Chart Error:', e);
}
/*
try {
    new Chart(document.getElementById('averagefailure'), {
        type: 'bar',
        data: {
            labels: @json($averageScores->pluck('title')),
            datasets: [{
                label: 'متوسط الدرجات',
                data: @json($averageScores->pluck('avg_score')),
                backgroundColor: 'rgba(0, 188, 212, 0.7)',
                borderColor: 'rgba(0, 188, 212, 1)',
                borderWidth: 2,
                barThickness: 30
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, max: 100, ticks: { font: { size: 16 } },title: {
                        display: true,
                        text: 'متوسط الدرجات ',
                        font: { size: 16 }
                    } },
                x: { ticks: { font: { size: 14 } },title: {
                        display: true,
                        text: 'عناوين الاختبارات',
                        font: { size: 16 }
                    } }
            },
            plugins: {
                legend: { display: true },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label = context.raw ; 
                            return label;
                        }
                    }
                }
            }
        }
    });
} catch (e) {
    console.error('Average Scores Chart Error:', e);
}

*/

try {
    const ctx = document.getElementById('progressionChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json(array_keys($progressionData)),
            datasets: [{
                label: 'تقدم الطلاب',
                data: @json(array_values($progressionData)),
                borderColor: 'rgba(0, 188, 212, 1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        font: { size: 16 },
                        stepSize: 10,
                        callback: function(value) {
                            return value ; // Add % to y-axis values
                        }
                    },
                    title: {
                        display: true,
                        text: 'نسبة التقدم ', // Title for y-axis
                        font: { size: 16 }
                    }
                },
                x: {
                    ticks: {
                        font: { size: 14 },
                        maxRotation: 45,
                        minRotation: 0,
                        autoSkip: true
                    },
                    title: {
                        display: true,
                        text: 'الأيام',
                        font: { size: 16 }
                    }
                }
            },
            plugins: {
                legend: { display: true },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label = context.raw ;
                            return label;
                        }
                    }
                }
            }
        }
    });

    // Add click event listener
    document.getElementById('progressionChart').onclick = function(evt) {
        const points = chart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);
        if (points.length) {
            const index = points[0].index;
            const value = chart.data.datasets[0].data[index];
            const label = chart.data.labels[index];
            alert(`نسبة التقدم لـ ${label}: ${value}%`);
        }
    };
} catch (e) {
    console.error('Progression Chart Error:', e);
}
        
        // Fullscreen toggle function
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    console.error(`خطأ في تفعيل وضع ملء الشاشة: ${err.message}`);
                });
            } else if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }

        // PDF generation function
        function generatePDF() {
            // Create a loading message
            const loadingMsg = document.createElement('div');
            loadingMsg.style.position = 'fixed';
            loadingMsg.style.top = '50%';
            loadingMsg.style.left = '50%';
            loadingMsg.style.transform = 'translate(-50%, -50%)';
            loadingMsg.style.backgroundColor = 'rgba(0,0,0,0.7)';
            loadingMsg.style.color = 'white';
            loadingMsg.style.padding = '15px';
            loadingMsg.style.borderRadius = '8px';
            loadingMsg.style.zIndex = '9999';
            loadingMsg.innerHTML = '<p>جاري تحضير التقرير...</p>';
            document.body.appendChild(loadingMsg);
            
            // Get the element to convert to PDF
            const element = document.querySelector('.dashboard-container');
            
            // Configure PDF options
            const opt = {
                margin: 10,
                filename: 'تقرير_المعلم.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };
            
            // Generate the PDF
            html2pdf()
                .from(element)
                .set(opt)
                .save()
                .then(() => {
                    document.body.removeChild(loadingMsg);
                    //toastr.success("تم إنشاء التقرير بنجاح!", "نجاح");
                })
                .catch(error => {
                    document.body.removeChild(loadingMsg);
                    console.error('PDF Generation Error:', error);
                    toastr.error("حدث خطأ أثناء إنشاء التقرير", "خطأ");
                });
        }

        // Animation for dashboard elements
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.dashboard-card');
            cards.forEach((card, index) => {
                card.style.animation = `fadeIn ${index * 0.1 + 0.3}s ease-out`;
            });
        });
    </script>
</body>
</html>