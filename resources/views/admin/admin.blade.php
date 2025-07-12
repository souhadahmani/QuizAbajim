<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - أباجم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- HTML2PDF Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #f0faff 0%, #e3f2fd 100%);
            font-family: 'Tajawal', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow-x: hidden;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            background: #2c3e50;
            padding-top: 20px;
            transition: all 0.3s;
            z-index: 1000;
        }
        .sidebar .nav-link {
            color: #fff;
            padding: 10px 20px;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
        }
        .sidebar .nav-link:hover {
            background: #34495e;
            color: #42a5f5;
        }
        .sidebar .nav-link i {
            margin-left: 10px;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s;
        }
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-250px);
            }
            .content {
                margin-left: 0;
            }
            .sidebar.active {
                transform: translateX(0);
            }
        }
        .navbar-custom {
            background-color: #2c3e50;
            color: white;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 10px 20px;
        }
        .navbar-custom .navbar-brand {
            color: white;
            font-weight: bold;
            font-size: 1.5rem;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }
        .cards-row {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 20px 0;
            flex-wrap: wrap;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .card-header {
            background: linear-gradient(90deg, #42a5f5, #1e88e5);
            color: white;
            border-radius: 10px 10px 0 0;
            font-weight: 600;
        }
        .bi-card {
            background-color: #fff;
            padding: 20px;
            margin-right:50px;
            margin-bottom: 20px;
            border-radius: 10px;
            text-align: center;
            transition: all 0.3s;
            height: 150px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .bi-card:hover {
            background: #f8f9fa;
        }
        .bi-card h6 {
            color: #666;
            font-size: 1rem;
        }
        .bi-card h3 {
            font-size: 2.5rem;
            color: #1e88e5;
            margin: 10px 0;
        }
        /* Responsive behavior */
        @media (max-width: 768px) {
            .cards-row {
                flex-direction: column;
                align-items: center;
            }
            .bi-card {
                width: 90%;
            }
        }
        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
            background-color: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .chart-container canvas {
            height: 100% !important;
            width: 100% !important;
        }
        .profile-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .upload-photo-btn {
            background: #42a5f5;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9rem;
            cursor: pointer;
        }
        .upload-photo-btn:hover {
            background: #1e88e5;
        }
        .stats .total {
            font-size: 1.2rem;
            color: #666;
        }
        
        /* Actions buttons styling */
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .action-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            font-weight: 500;
            color: white;
        }
        
        .btn-fullscreen {
            background-color: #6c757d;
        }
        
        .btn-pdf {
            background-color: #dc3545;
        }
        
        .btn-print {
            background-color: #28a745;
        }
        
        .action-btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        
        /* Dashboard container for PDF export */
        .dashboard-container {
            width: 100%;
        }
        
        /* Loading overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            color: white;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="text-center mb-3">
            <img src="{{ Auth::user()->avatar ?? 'https://via.placeholder.com/100' }}" alt="صورة المشرف" class="profile-photo">
            <form action="{{ route('uploadPhoto') }}" method="POST" enctype="multipart/form-data" id="photoForm">
                @csrf
                <input type="file" name="avatar" id="avatar" style="display: none;" onchange="document.getElementById('photoForm').submit()">
                <label for="avatar" class="upload-photo-btn">تغيير الصورة</label>
            </form>
            <h4 class="text-white mt-2">{{ Auth::user()->full_name ?? 'إدارة أباجم' }}</h4>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i> لوحة التحكم</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('users') }}"><i class="fas fa-users"></i> إدارة المستخدمين</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="content">
        <nav class="navbar navbar-expand-lg navbar-custom mb-4">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">لوحة التحكم - أباجم</a>
            </div>
        </nav>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        <!-- Action Buttons -->
        <div class="action-buttons">
            <button class="action-btn btn-fullscreen" onclick="toggleFullscreen()">
                <i class="fas fa-expand"></i> وضع ملء الشاشة
            </button>
            <button class="action-btn btn-print" onclick="window.print()">
                <i class="fas fa-print"></i> طباعة
            </button>
            <button class="action-btn btn-pdf" onclick="generatePDF()">
                <i class="fas fa-file-pdf"></i> تحميل PDF
            </button>
        </div>
        
        <!-- Dashboard container for PDF -->
        <div class="dashboard-container" id="dashboard-content">
            <!-- BI Section: Cards and Charts -->
            <div class="row">
                <div class="col-md-4">
                    <div class="bi-card card">
                        <h6>إجمالي عدد المستخدمين المسجلين</h6>
                        <h3>{{ $totalUsers }}</h3>
                        <i class="fas fa-users" style="font-size: 2rem; color: #1e88e5;"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bi-card card">
                        <h6>عدد المستخدمين الجدد خلال الفترة</h6>
                        <h3>{{ $newUsersThisPeriod }}</h3>
                        <i class="fas fa-users" style="font-size: 2rem; color: #1e88e5;"></i>
                    </div>
                </div>
            </div>
            <div class="card mt-4">
    <div class="card-header">
        <h4>تسجيلات المستخدمين حسب السنة</h4>
        <div class="stats">
            <span class="total">المجموع: {{ $yearlyUserData['total'] }}</span>
        </div>
    </div>
    <div class="card-body">
        <div class="chart-container">
            <canvas id="yearlyUserChart"></canvas>
        </div>
    </div>
</div>
            <div class="card">
                <div class="card-header">
                    <h4>إحصائية عدد المستخدمين المسجلين</h4>
                    <div class="stats">
                        <span class="total">المجموع: {{ $signupRateData['total'] }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="signupRateChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Define current month from PHP
        const currentMonth = {{ now()->month }};
        
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('signupRateChart');
            
            // Use the currentMonth from PHP data
            const currentMonth = {{ $signupRateData['currentMonth'] ?? now()->month }};
            const labels = @json($signupRateData['labels'] ?? []);
            const data = @json($signupRateData['data'] ?? []);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'عدد المسجلين',
                        data: data,
                        backgroundColor: 'rgba(66, 165, 245, 0.7)',
                        borderColor: 'rgba(66, 165, 245, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                font: {
                                    size: 14,
                                    family: 'Tajawal, sans-serif'
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `المسجلين: ${context.raw}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                font: {
                                    size: 12,
                                    family: 'Tajawal, sans-serif'
                                },
                                callback: function(value) {
                                    return value;
                                }
                            },
                            title: {
                                display: true,
                                text: 'عدد المستخدمين',
                                font: {
                                    size: 14,
                                    family: 'Tajawal, sans-serif'
                                }
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 12,
                                    family: 'Tajawal, sans-serif'
                                }
                            },
                            title: {
                                display: true,
                                text: 'الشهور',
                                font: {
                                    size: 14,
                                    family: 'Tajawal, sans-serif'
                                }
                            }
                        }
                    }
                }
            });
        });
        
// Yearly Signup Chart 

const yearlyUserCtx = document.getElementById('yearlyUserChart');
    
    const yearlyUserLabels = @json($yearlyUserData['labels'] ?? []);
    const yearlyUserDataValues = @json($yearlyUserData['data'] ?? []);
    
    console.log('Yearly Labels:', yearlyUserLabels);
    console.log('Yearly Data:', yearlyUserDataValues);
    
    if (yearlyUserCtx && yearlyUserLabels.length > 0 && yearlyUserDataValues.length > 0) {
        new Chart(yearlyUserCtx, {
            type: 'bar', 
            data: {
                labels: yearlyUserLabels,
                datasets: [{
                    label: 'عدد المستخدمين المسجلين',
                    data: yearlyUserDataValues,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4, 
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 14,
                                family: 'Tajawal, sans-serif'
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `المستخدمين في ${context.label}: ${context.raw}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 12,
                                family: 'Tajawal, sans-serif'
                            },
                            callback: function(value) {
                                return Math.floor(value); // Ensure integer values
                            }
                        },
                        title: {
                            display: true,
                            text: 'عدد المستخدمين',
                            font: {
                                size: 14,
                                family: 'Tajawal, sans-serif'
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12,
                                family: 'Tajawal, sans-serif'
                            }
                        },
                        title: {
                            display: true,
                            text: 'السنة',
                            font: {
                                size: 14,
                                family: 'Tajawal, sans-serif'
                            }
                        }
                    }
                }
            }
        });
    } else {
        console.error('Yearly chart data is missing or invalid');
        // Show error message in the chart container
        if (yearlyUserCtx) {
            const container = yearlyUserCtx.closest('.chart-container');
            if (container) {
                container.innerHTML = '<div style="text-align: center; padding: 50px; color: #666;">لا توجد بيانات لعرضها</div>';
            }
        }
    }

// Function to toggle fullscreen
        function toggleFullscreen() {
            const elem = document.getElementById('dashboard-content');
            
            if (!document.fullscreenElement) {
                if (elem.requestFullscreen) {
                    elem.requestFullscreen();
                } else if (elem.mozRequestFullScreen) { /* Firefox */
                    elem.mozRequestFullScreen();
                } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
                    elem.webkitRequestFullscreen();
                } else if (elem.msRequestFullscreen) { /* IE/Edge */
                    elem.msRequestFullscreen();
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.mozCancelFullScreen) { /* Firefox */
                    document.mozCancelFullScreen();
                } else if (document.webkitExitFullscreen) { /* Chrome, Safari and Opera */
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) { /* IE/Edge */
                    document.msExitFullscreen();
                }
            }
        }
        
        // Function to generate PDF
        function generatePDF() {
            // Create loading overlay
            const loadingOverlay = document.createElement('div');
            loadingOverlay.className = 'loading-overlay';
            loadingOverlay.innerHTML = '<div><i class="fas fa-spinner fa-spin mr-2"></i> جاري تحضير التقرير...</div>';
            document.body.appendChild(loadingOverlay);
            
            // Get the element to convert to PDF
            const element = document.querySelector('.dashboard-container');
            
            // Configure PDF options
            const opt = {
                margin: 10,
                filename: 'تقرير_لوحة_التحكم.pdf',
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
                    // Remove loading overlay when finished
                    document.body.removeChild(loadingOverlay);
                    
                    // Display success message (you can add a toast notification here)
                    alert('تم إنشاء التقرير بنجاح!');
                })
                .catch(error => {
                    // Remove loading overlay on error
                    document.body.removeChild(loadingOverlay);
                    console.error('PDF Generation Error:', error);
                    alert('حدث خطأ أثناء إنشاء التقرير');
                });
        }
    </script>
</body>
</html>