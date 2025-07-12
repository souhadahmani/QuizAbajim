<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>لوحة متابعة الطالب</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        :root {
            --primary: #1e88e5; /* أزرق جذاب */
            --secondary: #00acc1; /* أزرق فاتح */
            --accent: #ff7043; /* برتقالي للتأكيد */
            --light: #f5f5f5; /* فاتح للخلفيات */
            --dark: #263238; /* غامق للنصوص */
            --shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        body {
            font-family: 'Tajawal', Arial, sans-serif;
            background-color: #fafafa;
            margin: 0;
            padding: 0;
            color: var(--dark);
            direction: rtl;
        }
        .header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 10px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow);
        }
        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .student-info {
            text-align: right;
        }
        .student-name {
            margin: 0;
            font-size: 1.2rem;
            font-weight: bold;
        }
        .student-role {
            margin: 0;
            font-size: 0.8rem;
            opacity: 0.9;
        }
        .actions {
            display: flex;
            gap: 8px;
        }
        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 4px;
            transition: all 0.3s;
            font-size: 0.9rem;
        }
        .btn-primary { background-color: white; color: var(--primary); }
        .btn-secondary { background-color: white; color: var(--secondary); }
        .btn-accent { background-color: white; color: var(--accent); }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .dashboard-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 15px;
            padding: 15px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 12px;
            box-shadow: var(--shadow);
            transition: all 0.3s;
            border: 1px solid #e0e0e0;
        }
        .chart-container:hover {
            transform: translateY(-4px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .chart-title {
            margin: 0 0 10px 0;
            color: var(--primary);
            font-size: 1em;
            padding-bottom: 6px;
            border-bottom: 2px dashed #e0e0e0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .period-selector {
            grid-column: 1 / -1;
            padding: 10px;
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            display: flex;
            justify-content: center;
        }
        .form-select {
            padding: 6px 12px;
            border-radius: 15px;
            border: 1px solid #e0e0e0;
            width: 180px;
            background-color: white;
            font-family: 'Tajawal', Arial, sans-serif;
            font-size: 0.9rem;
        }
        .engagement-metrics {
            display: flex;
            justify-content: space-around;
            gap: 8px;
        }
        .metric-card {
            flex: 1;
            padding: 10px;
            border-radius: 8px;
            background: linear-gradient(135deg, #e3f2fd, #f5f5f5);
            border: 1px solid #e0e0e0;
            text-align: center;
        }
        .metric-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary);
            margin: 4px 0;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        .metric-label {
            font-size: 0.8rem;
            color: #666;
        }
        .weak-area {
            margin-bottom: 10px;
            padding: 8px;
            border-radius: 6px;
            background-color: #f5f5f5;
        }
        .weak-area-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            font-weight: bold;
            font-size: 0.9rem;
        }
        .progress-bar {
            height: 8px;
            background: #e0e0e0;
            border-radius: 4px;
            margin: 6px 0;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            border-radius: 4px;
            background: var(--accent);
            transition: width 1s ease-in-out;
        }
        #subjectDetail {
            margin-top: 8px;
            padding: 8px;
            background: #f5f5f5;
            border-radius: 6px;
            display: none;
            animation: fadeIn 0.5s;
            font-size: 0.9rem;
        }
        .chart-wrapper {
            height: 200px;
            position: relative;
        }
        .priority-1 { grid-column: span 2; }
        .priority-2 { grid-column: span 1; }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes growWidth {
            from { width: 0; }
            to { width: inherit; }
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-4px); }
        }
        .bounce:hover {
            animation: bounce 0.5s;
        }
        @media (max-width: 768px) {
            .priority-1, .priority-2 { grid-column: span 1; }
            .chart-wrapper { height: 180px; }
            .header { flex-direction: column; gap: 10px; text-align: center; }
            .profile { flex-direction: column; text-align: center; }
            .actions { flex-wrap: wrap; justify-content: center; }
        }
        .icon {
            width: 16px;
            height: 16px;
            vertical-align: middle;
        }
        .star {
            color: gold;
            font-size: 1.1rem;
            text-shadow: 0 0 3px rgba(0,0,0,0.3);
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
@auth
    <div class="header">
        <div class="profile">
        <img src="{{ auth()->user()->avatar ? asset('storage/'.auth()->user()->avatar) : asset('images/default-avatar.png') }}" 
     alt="صورة الطالب" 
     class="avatar bounce">
<div class="student-info">
    <h2 class="student-name">{{ auth()->user()->full_name ?? auth()->user()->name }}</h2>
</div>
        </div>
        @endauth

        <div class="actions">
            <button class="btn btn-primary" onclick="toggleFullscreen()">
                <img src="https://cdn-icons-png.flaticon.com/512/2089/2089670.png" class="icon" alt="ملء الشاشة">
                <span>ملء الشاشة</span>
            </button>
            <button class="btn btn-secondary" onclick="generatePDF()">
                <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" class="icon" alt="تحميل">
                <span>حفظ التقرير</span>
            </button>
            <button class="btn btn-accent" onclick="window.print()">
                <img src="https://cdn-icons-png.flaticon.com/512/337/337958.png" class="icon" alt="طباعة">
                <span>طباعة</span>
            </button>
        </div>
    </div>

    <div class="dashboard-container">
        <div class="period-selector">
            <form method="GET" action="">
                <label for="period">اختر الفترة:</label>
                <select name="period" id="period" class="form-select" onchange="this.form.submit()">
                    <option value="7" {{ $period == 7 ? 'selected' : '' }}>آخر أسبوع</option>
                    <option value="30" {{ $period == 30 ? 'selected' : '' }}>آخر شهر</option>
                    <option value="90" {{ $period == 90 ? 'selected' : '' }}>آخر 3 أشهر</option>
                </select>
            </form>
        </div>

        <!-- 1. مؤشرات المشاركة (الأولوية الأولى) -->
        <div class="chart-container priority-1" style="background: linear-gradient(135deg, #e3f2fd, white);">
            <h2 class="chart-title">أدائي التعليمي <span class="star">★</span></h2>
            <div class="engagement-metrics">
                <div class="metric-card bounce">
                    <span class="metric-value">{{ $engagement['days_active'] }}</span>
                    <span class="metric-label">يوم نشط</span>
                    <img src="https://cdn-icons-png.flaticon.com/512/2436/2436636.png" width="35" style="opacity: 0.7;">
                </div>
                <div class="metric-card bounce">
                    <span class="metric-value">{{ round($engagement['quizzes_per_day'], 1) }}</span>
                    <span class="metric-label">اختبار كل يوم</span>
                    <img src="https://cdn-icons-png.flaticon.com/512/2936/2936886.png" width="35" style="opacity: 0.7;">
                </div>
            </div>
        </div>

        <!-- 2. المجالات التي تحتاج تحسين -->
        <div class="chart-container priority-1">
            <h2 class="chart-title">مجالات تحتاج تركيز <img src="https://cdn-icons-png.flaticon.com/512/2693/2693461.png" width="18" class="icon"></h2>
            <div class="weak-areas">
                @foreach($weakAreas as $area)
                <div class="weak-area" style="animation: fadeIn {{ $loop->index * 0.2 + 0.5 }}s;">
                    <div class="weak-area-header">
                        <span>{{ $area->name }}</span>
                        <span>{{ round($area->avg_score) }}%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" 
                             style="width: {{ $area->avg_score }}%; background: {{ $area->avg_score < 50 ? 'var(--accent)' : 'var(--secondary)' }};">
                        </div>
                    </div>
                    <small>{{ $area->attempt_count }} محاولات</small>
                </div>
                @endforeach
                @if(count($weakAreas) === 0)
                <p style="text-align: center; color: var(--secondary);">لا توجد مجالات ضعيفة! أداء رائع 👏</p>
                @endif
            </div>
        </div>

        <!-- 3. تقدم الأداء -->
        <div class="chart-container priority-1">
            <h2 class="chart-title">تقدمي في التعلم <img src="https://cdn-icons-png.flaticon.com/512/3132/3132735.png" width="18" class="icon"></h2>
            <div class="chart-wrapper">
                <canvas id="progressChart"></canvas>
            </div>
        </div>

        <!-- 4. توزيع النجاح/الرسوب -->
        <div class="chart-container priority-2">
            <h2 class="chart-title">نجاحي في المواد <img src="https://cdn-icons-png.flaticon.com/512/190/190411.png" width="18" class="icon"></h2>
            <h3 class="chart-title" style="text-align: center;">" انقر على الرسم البياني "</h3>
            <div class="chart-wrapper">
                <canvas id="subjectPassFailChart"></canvas>
            </div>
            <div id="subjectDetail">
                <p><strong>المادة:</strong> <span id="selectedSubject"></span></p>
                <p><strong>نجح في:</strong> <span id="passedCount"></span> اختبار</p>
                <p><strong>احتياج تحسين في:</strong> <span id="failedCount"></span> اختبار</p>
            </div>
        </div>

        <!-- 5. المحاولات حسب مستوى الصعوبة -->
        <div class="chart-container priority-2">
            <h2 class="chart-title"> محاولاتي حسب مستوى الصعوبة <img src="https://cdn-icons-png.flaticon.com/512/2933/2933245.png" width="18" class="icon"></h2>
            <div class="chart-wrapper">
                <canvas id="difficultyChart"></canvas>
            </div>
        </div>

        <!-- 6. متوسط الدرجات حسب المادة -->
        <div class="chart-container">
            <h2 class="chart-title">مستواي في كل مادة <img src="https://cdn-icons-png.flaticon.com/512/2232/2232688.png" width="18" class="icon"></h2>
            <div class="chart-wrapper">
                <canvas id="successRateBySubjectChart"></canvas>
            </div>
        </div>

        <!-- 7. الاختبارات الناجحة حسب المادة -->
        <div class="chart-container">
            <h2 class="chart-title">اختباراتي الناجحة <img src="https://cdn-icons-png.flaticon.com/512/190/190411.png" width="18" class="icon"></h2>
            <div class="chart-wrapper">
                <canvas id="passedQuizzesChart"></canvas>
            </div>
        </div>

        <!-- 8. محاولات الاختبار حسب المادة -->
        <div class="chart-container">
            <h2 class="chart-title">نشاطي في المواد <img src="https://cdn-icons-png.flaticon.com/512/3281/3281289.png" width="18" class="icon"></h2>
            <div class="chart-wrapper">
                <canvas id="attemptsChart"></canvas>
            </div>
        </div>

        <!-- 9. متوسط الدرجات حسب مستوى الصعوبة -->
        <div class="chart-container">
            <h2 class="chart-title">أدائي حسب الصعوبة <img src="https://cdn-icons-png.flaticon.com/512/2933/2933245.png" width="18" class="icon"></h2>
            <div class="chart-wrapper">
                <canvas id="successDifficultyChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        const chartColors = {
            primary: 'rgba(30, 136, 229, 0.7)',       // Blue (main color)
    secondary: 'rgba(0, 172, 193, 0.7)',      // Teal (complementary to blue)
    accent: 'rgba(255, 112, 67, 0.7)',        // Orange (contrast)
    success: 'rgba(46, 204, 113, 0.7)',       // Green (for positive metrics)
    warning: 'rgba(241, 196, 15, 0.7)',       // Yellow (for warnings)
    purple: 'rgba(155, 89, 182, 0.7)',        // Purple (new complementary color)
    pink: 'rgba(233, 30, 99, 0.7)' 
        };

        function createResponsiveChart(canvasId, config) {
            const ctx = document.getElementById(canvasId).getContext('2d');
            return new Chart(ctx, {
                ...config,
                options: {
                    ...config.options,
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: { font: { family: 'Tajawal' } }
                        }
                    }
                }
            });
        }

        createResponsiveChart('progressChart', {
    type: 'line',
    data: {
        labels: @json($progressDates),
        datasets: [{
            label: 'المتوسط اليومي %',
            data: @json($progressScores),
            borderColor: chartColors.primary,
            tension: 0.3,
            fill: true,
            backgroundColor: 'rgba(30, 136, 229, 0.1)',
            borderWidth: 2,
            pointBackgroundColor: chartColors.primary,
            pointRadius: 4
        }]
    },
    options: {
        scales: {
            y: {
                min: 0,
                max: 100,
                ticks: {
                    suffix: '%',
                    font: { family: 'Tajawal' }
                },
                title: {
                    display: true,
                    text: 'النسبة المئوية',
                    font: { family: 'Tajawal', size: 16 }
                }
            },
            x: {
                ticks: {
                    font: { family: 'Tajawal' }
                },
                title: {
                    display: true,
                    text: 'الأيام',
                    font: { family: 'Tajawal', size: 16 }
                }
            }
        }
    }
});

        const subjectPassFailChart = createResponsiveChart('subjectPassFailChart', {
        type: 'pie',
        data: {
            labels: @json($passedQuizzesLabels),
            datasets: [{
                data: @json($passedQuizzesData),
                backgroundColor: [
                    chartColors.primary,
                    chartColors.warning,
                    chartColors.success,
                    chartColors.accent,
                    chartColors.pink,
                    chartColors.purple,
                ],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: { position: 'right', rtl: true },
                tooltip: { callbacks: { label: context => `${context.label}: ${context.raw} اختبار` } }
            },
            onClick: (event, elements) => {
                if (elements.length > 0) {
                    const chart = subjectPassFailChart;
                    const index = elements[0].index;
                    const subject = chart.data.labels[index];
                    const passFailData = @json($subjectPassFailData);
                    const data = passFailData[index];
                    document.getElementById('selectedSubject').textContent = subject;
                    document.getElementById('passedCount').textContent = data.passed;
                    document.getElementById('failedCount').textContent = data.failed;
                    document.getElementById('subjectDetail').style.display = 'block';
                }
            }
        }
    });
    createResponsiveChart('difficultyChart', {
    type: 'doughnut',
    data: {
        labels: @json($difficultyChartLabels).map(label => {
            switch (label.toLowerCase()) {
                case 'easy': return 'سهل';
                case 'medium': return 'متوسط';
                case 'hard': return 'صعب';
                default: return 'غير معروف';
            }
        }),
        datasets: [{
            data: @json($difficultyChartData),
            backgroundColor: [
                chartColors.success, // Green for easy
                chartColors.warning, // Yellow for medium
                chartColors.accent   // Orange for hard
            ],
            borderWidth: 1
        }]
    },

        options: {
            plugins: { legend: { position: 'right', rtl: true } }
        }
    });        
    createResponsiveChart('successRateBySubjectChart', {
    type: 'bar',
    data: {
        labels: @json($passedQuizzesLabels),
        datasets: [{
            label: 'متوسط الدرجات %',
            data: @json($subjectAverageScores),
            backgroundColor: chartColors.purple, // Using the new purple color
            borderColor: chartColors.purple.replace('0.7', '1'),
            borderWidth: 1
        }]
    },

            options: {
                scales: {
                    y: { beginAtZero: true, max: 100, ticks: { suffix: '%', font: { family: 'Tajawal' } },title: {
                        display: true,
                        text: ' النسبة المائوية ',
                        font: { size: 14 }
                    } },
                    x: { ticks: { font: { family: 'Tajawal' } },title: {
                        display: true,
                        text: ' المواد ',
                        font: { size: 14 }
                    } }
                }
            }
        });

        createResponsiveChart('passedQuizzesChart', {
            type: 'bar',
            data: {
                labels: @json($passedQuizzesLabels),
                datasets: [{
                    label: 'الاختبارات الناجحة',
                    data: @json($passedQuizzesData),
                    backgroundColor: chartColors.secondary,
                    borderColor: chartColors.secondary.replace('0.7', '1'),
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true, ticks: { font: { family: 'Tajawal' } },title: {
                        display: true,
                        text: ' عدد الإختبارات الناجحة ',
                        font: { size: 14 }
                    } },
                    x: { ticks: { font: { family: 'Tajawal' } },title: {
                        display: true,
                        text: ' المواد ',
                        font: { size: 14 }
                    } }
                }
            }
        });

        createResponsiveChart('attemptsChart', {
            type: 'bar',
            data: {
                labels: @json($attemptsChartLabels),
                datasets: [{
                    label: 'المحاولات',
                    data: @json($attemptsChartData),
                    backgroundColor: chartColors.primary,
                    borderColor: chartColors.primary.replace('0.7', '1'),
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true, ticks: { font: { family: 'Tajawal' } },title: {
                        display: true,
                        text: ' عدد المحاولات ',
                        font: { size: 14 }
                    } },
                    x: { ticks: { font: { family: 'Tajawal' } } ,title: {
                        display: true,
                        text: 'المواد ',
                        font: { size: 14 }
                    } }
                }
            }
        });

        createResponsiveChart('successDifficultyChart', {
            type: 'bar',
            data: {
                labels: @json($difficultyPerformanceLabels).map(label => {
            // Convert English labels to Arabic
            switch(label.toLowerCase()) {
                case 'easy': return 'سهل';
                case 'medium': return 'متوسط';
                case 'hard': return 'صعب';
                default: return label; // fallback for any other values
            }
        }),
                datasets: [{
                    label: 'متوسط الدرجات ',
                    data: @json($difficultyPerformanceData),
                    backgroundColor: chartColors.warning,
                    borderColor: chartColors.warning.replace('0.7', '1'),
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true, max: 100, ticks: { suffix: '', font: { family: 'Tajawal' } },title: {
                        display: true,
                        text: ' متوسط الدرجات ',
                        font: { size: 14 }
                    } },
                    x: { ticks: { font: { family: 'Tajawal' } },title: {
                        display: true,
                        text: ' مستوى الصعوبة ',
                        font: { size: 14 }
                    } }
                }
            }
        });

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    console.error(`خطأ في تفعيل وضع ملء الشاشة: ${err.message}`);
                });
            } else if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }

        function generatePDF() {
            const element = document.querySelector('.dashboard-container');
            const opt = {
                margin: 10,
                filename: 'تقرير_الطالب.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };
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
            html2pdf().from(element).set(opt).save().then(() => {
                document.body.removeChild(loadingMsg);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.chart-container');
            cards.forEach((card, index) => {
                card.style.animation = `fadeIn ${index * 0.1 + 0.3}s ease-out`;
            });
        });
    </script>
</body>
</html>