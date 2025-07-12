<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>اختبار: {{ $quiz->title }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="shortcut icon" href="{{ asset('images/abajim.png') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <style>
        body {
            background: #E6F0FA;
            font-family: 'Tajawal', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .navbar-custom {
            background: #fff;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .logo img {
            max-height: 60px;
            transition: transform 0.3s ease;
        }

        .logo img:hover {
            transform: scale(1.1);
        }

        .quiz-container {
            width: 90%;
            max-width: 900px;
            margin: 90px auto 30px;
            padding: 20px;
            text-align: center;
        }

        .welcome-screen {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease;
        }

        .welcome-screen h2 {
            color: #005B99;
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .welcome-screen p {
            font-size: 1.5rem;
            color: #444;
            margin-bottom: 25px;
        }

        .welcome-screen .start-btn {
            background: #005B99;
            padding: 15px 30px;
            border-radius: 10px;
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            border: none;
            transition: transform 0.3s ease;
            box-shadow: 0 3px 10px rgba(0, 91, 153, 0.3);
        }

        .welcome-screen .start-btn:hover {
            transform: scale(1.05);
            background: #004080;
        }

        .quiz-content {
            background: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            animation: slideUp 0.5s ease;
            display: none;
            max-height: calc(100vh - 150px);
            overflow: auto;
            box-sizing: border-box;
        }

        .result-screen {
            background: linear-gradient(135deg, #fff, #f0f8ff);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            animation: fadeIn 0.5s ease;
            display: none;
            position: relative;
        }

        .result-screen h2 {
            color: #005B99;
            font-size: 2.5rem;
            margin-bottom: 20px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        .result-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .result-item {
            background: #e6f0fa;
            padding: 10px;
            border-radius: 10px;
            font-size: 1.2rem;
            color: #333;
            text-align: center;
            animation: slideUp 0.5s ease;
        }

        .result-item.success {
            color: #28a745;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .result-item.failure {
            color: #dc3545;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .details-toggle {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            margin: 10px 0;
            transition: transform 0.3s ease;
        }

        .details-toggle:hover {
            transform: scale(1.05);
            background: #218838;
        }

        .question-details {
            display: none;
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .question-details ul {
            list-style: none;
            padding: 0;
        }

        .question-details li {
            margin: 10px 0;
            padding: 10px;
            border-left: 4px solid #005B99;
            background: #fff;
            border-radius: 5px;
        }

        .correct {
            color: #28a745;
            font-weight: 600;
        }

        .incorrect {
            color: #dc3545;
            font-weight: 600;
        }

        .explanation {
            color: #444;
            font-size: 1.1rem;
            margin-top: 5px;
            font-style: italic;
        }

        .feedback-section {
            margin-top: 20px;
            padding: 15px;
            background: #e6f0fa;
            border-radius: 10px;
            font-size: 1.2rem;
            color: #333;
        }

        .badges-section {
            margin-top: 20px;
        }

        .badge-item {
            background: #ffd700;
            color: #333;
            padding: 10px 20px;
            border-radius: 20px;
            margin: 5px;
            font-size: 1.2rem;
            display: inline-block;
            animation: bounce 0.5s ease;
        }

        .retry-btn {
            background: #ff6f61;
            color: white;
            padding: 12px 25px;
            border-radius: 15px;
            font-size: 1.3rem;
            font-weight: 700;
            border: none;
            margin-top: 15px;
            transition: transform 0.3s ease;
        }

        .retry-btn:hover {
            transform: scale(1.05);
            background: #e65b50;
        }

        .progress-circle {
            width: 100px;
            height: 100px;
            margin: 20px auto;
            position: relative;
        }

        .progress-circle svg {
            width: 100%;
            height: 100%;
            transform: rotate(-90deg);
        }

        .progress-circle .circle-bg {
            fill: none;
            stroke: #e6e6e6;
            stroke-width: 10;
        }

        .progress-circle .circle-fg {
            fill: none;
            stroke: #28a745;
            stroke-width: 10;
            stroke-linecap: round;
            transition: stroke-dashoffset 0.5s ease;
        }

        .progress-circle span {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 1.5rem;
            color: #005B99;
            font-weight: 700;
        }

        .timer-container {
            position: absolute;
            top: 10px;
            left: 10px;
        }

        .timer {
            width: 60px;
            height: 60px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .timer::before {
            content: '';
            position: absolute;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 3px solid #005B99;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
        }

        .timer span {
            font-size: 1rem;
            color: #005B99;
            font-weight: 700;
        }

        .timer.warning::before {
            border-color: #dc3545;
            border-top-color: transparent;
        }

        .question-card {
            margin: 20px 0;
        }

        .question {
            font-size: 2rem;
            color: #333;
            margin-bottom: 10px;
            font-weight: 700;
            line-height: 1.4;
        }

        .question-media {
            max-width: 600px;
            width: 100%;
            height: auto;
            margin: 10px auto;
            border-radius: 10px;
            display: block;
        }

        .question-media-fallback {
            font-size: 1.2rem;
            color: #dc3545;
            margin: 10px 0;
        }

        .points {
            font-size: 1.2rem;
            font-weight: 600;
            color: #005B99;
            background: #e6f0fa;
            padding: 8px 15px;
            border-radius: 8px;
            margin-bottom: 12px;
            display: inline-block;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .options-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }

        .option-btn {
            width: 45%;
            max-width: 350px;
            padding: 8px;
            margin: 5px 0;
            font-size: 1.3rem;
            background: #f1f1f1;
            border: 2px solid #ddd;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .option-btn:hover {
            background: #e9ecef;
            border-color: #005B99;
        }

        .option-btn.selected {
            background: #005B99;
            color: white;
            border-color: #004080;
        }

        .next-btn, .submit-btn {
            background: #005B99;
            padding: 12px 25px;
            border-radius: 10px;
            color: white;
            font-size: 1.3rem;
            font-weight: 700;
            border: none;
            transition: transform 0.3s ease;
            box-shadow: 0 3px 10px rgba(0, 91, 153, 0.3);
            margin: 10px;
        }

        .next-btn:hover, .submit-btn:hover {
            transform: scale(1.05);
            background: #004080;
        }

        .next-btn:disabled, .submit-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .progress-stars {
            margin: 15px 0;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .star {
            font-size: 1.3rem;
            color: #ccc;
            transition: color 0.3s ease;
        }

        .star.active {
            color: #FFD700;
        }

        .star.completed {
            color: #28a745;
        }

        .badge-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }

        .badge-modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            animation: bounce 0.5s ease;
        }

        .badge-modal-content h3 {
            color: #28a745;
            font-size: 2rem;
            margin-bottom: 15px;
        }

        .badge-modal-content p {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 20px;
        }

        .badge-modal-content button {
            background: #005B99;
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .badge-modal-content button:hover {
            transform: scale(1.05);
            background: #004080;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        @keyframes spin {
            to { transform: rotate(-360deg); }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-custom">
        <div class="container">
            <a class="navbar-brand logo" href="{{ route('student.dashboard') }}">
                <img src="{{ asset('images/abajimLOGO.png') }}" alt="Abajim Logo">
            </a>
        </div>
    </nav>

    <div class="quiz-container">
        <div class="welcome-screen">
            <h2>مرحبًا بك في اختبار: {{ $quiz->title }}</h2>
            <p>اضغط على زر "ابدأ الان" لتبدأ!</p>
            <button class="start-btn" onclick="startQuiz()">ابدأ الان</button>
        </div>

        <div class="quiz-content" id="quizContent">
            <div class="timer-container">
                <div class="timer" id="timer">
                    <span id="timeLeft">10:00</span>
                </div>
            </div>
            <div class="progress-stars" id="progressStars"></div>
            <div class="question-card" id="questionCard"></div>
            <button class="next-btn" id="prevBtn" onclick="prevQuestion()" disabled>السابق</button>
            <button class="next-btn" id="nextBtn" onclick="nextQuestion()" disabled>التالي</button>
            <button class="submit-btn" id="submitBtn" onclick="submitQuiz()" style="display: none;">إنهاء الاختبار</button>
        </div>

        <div class="result-screen" id="resultScreen">
            <canvas id="confettiCanvas" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;"></canvas>
        </div>
    </div>
    <div id="resultScreenHidden" style="display: none;"></div>
    <canvas id="performanceChart" width="400" height="200" style="display: none;"></canvas>
    <div id="badgeModal" class="badge-modal">
        <div class="badge-modal-content">
            <h3>تهانينا! 🎉</h3>
            <p id="badgeMessage"></p>
            <button onclick="nextBadge()">التالي</button>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    let quizId = {{ $quiz->id }};
    let questions = [];
    let currentQuestionIndex = 0;
    let score = 0;
    let userAnswers = {};
    let timeLeft = 10;
    let timerInterval;
    let startTime;
    let earnedBadges = [];
    let currentBadgeIndex = 0;
    const quizTitle = "{{ $quiz->title }}"; // Pass quiz title to JavaScript
    const geminiApiKey = $('meta[name="gemini-api-key"]').attr('content');

    $(document).ready(function() {
        toastr.options = {
            "positionClass": "toast-top-right",
            "timeOut": "3000",
            "progressBar": true,
        };
        $(document).on('keydown', function(e) {
            if ($('.option-btn').is(':visible')) {
                if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                    let $selected = $('.option-btn.selected');
                    let $next = e.key === 'ArrowDown' ? $selected.next('.option-btn') : $selected.prev('.option-btn');
                    if ($next.length) {
                        $selected.removeClass('selected');
                        $next.addClass('selected');
                        userAnswers[currentQuestionIndex] = $next.text();
                    }
                } else if (e.key === 'Enter') {
                    if (userAnswers[currentQuestionIndex]) nextQuestion();
                }
            }
        });
    });

    function startQuiz() {
        startTime = Date.now();
        localStorage.setItem('quizStartTime', startTime);
        console.log('Quiz started at:', startTime);
        $.ajax({
            url: '/student/quiz/' + quizId + '/start',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                toastr.success(response.message);
                fetchQuizData();
                $('.welcome-screen').hide();
                $('#quizContent').show();
                startTimer();
            },
            error: function(xhr) {
                console.log('Error starting quiz:', xhr.status, xhr.responseText);
                if (xhr.status === 401) {
                    toastr.error('يرجى تسجيل الدخول أولاً.');
                    setTimeout(() => window.location.href = '/login', 2000);
                } else if (xhr.status === 404) {
                    toastr.error('الاختبار غير موجود.');
                } else {
                    toastr.error('فشل بدء الاختبار: ' + (xhr.responseJSON?.message || xhr.statusText));
                }
            }
        });
    }

    function fetchQuizData() {
        $.ajax({
            url: '/student/quiz/' + quizId + '/data',
            method: 'GET',
            success: function(response) {
                console.log('API Response:', response);
                questions = response.questions || [];
                timeLeft = response.time ? response.time / 60 : 10;
                $('#timeLeft').text(formatTime(timeLeft));
                displayProgressStars();
                if (questions.length > 0) {
                    displayQuestion(currentQuestionIndex);
                } else {
                    toastr.error('لا يوجد أسئلة!');
                    $('#questionCard').html('<p class="text-danger">لا يوجد أسئلة متاحة.</p>');
                }
            },
            error: function(xhr) {
                console.log('Error fetching quiz data:', xhr.status, xhr.responseText);
                toastr.error('فشل جلب البيانات: ' + (xhr.responseJSON?.message || xhr.statusText));
            }
        });
    }

    function displayProgressStars() {
        let starsHtml = '';
        for (let i = 0; i < questions.length; i++) {
            const isActive = i === currentQuestionIndex;
            const isCompleted = userAnswers[i] !== undefined && i < currentQuestionIndex;
            starsHtml += `<span class="star ${isActive ? 'active' : ''} ${isCompleted ? 'completed' : ''}" 
                           title="السؤال ${i + 1}">${i + 1} ☆</span>`;
        }
        $('#progressStars').html(starsHtml);
    }

    function displayQuestion(index) {
        if (index < 0 || index >= questions.length) {
            if (index >= questions.length) {
                $('#nextBtn').hide();
                $('#submitBtn').show();
            }
            if (index < 0) index = 0;
            return;
        }

        currentQuestionIndex = index;
        const question = questions[index];
        let questionHtml = `
            <div class="question">السؤال ${index + 1}: ${question.question}</div>
            <div class="points">النقاط: ${question.points}</div>`;

        if (question.image) {
            questionHtml += `<img src="${question.image}" alt="Question Image" class="question-media">`;
        } else if (question.video) {
            questionHtml += `<video controls class="question-media"><source src="${question.video}" type="video/mp4">غير مدعوم</video>`;
        } else {
            questionHtml += `<div class="question-media-fallback">لا يوجد وسائط</div>`;
        }

        questionHtml += `<div class="options-container">`;
        question.options.forEach((option, i) => {
            const isSelected = userAnswers[index] === option;
            questionHtml += `
                <button class="option-btn ${isSelected ? 'selected' : ''}" 
                        aria-label="الخيار ${i + 1}: ${option}" 
                        onclick="selectOption(${index}, ${i}, '${option}')">
                    ${option}
                </button>`;
        });
        questionHtml += `</div>`;

        $('#questionCard').html(questionHtml);
        displayProgressStars();
        $('#prevBtn').prop('disabled', index === 0);
        $('#nextBtn').prop('disabled', !userAnswers[index]);
    }

    function selectOption(questionIndex, optionIndex, optionText) {
        userAnswers[questionIndex] = optionText;
        $('.option-btn').removeClass('selected');
        $(`.option-btn`).eq(optionIndex).addClass('selected');

        const motivationalMessages = [
            'عظيم! استمر في العطاء! 🌟',
            'رائع! أنت في الطريق الصحيح، تواصل! 🚀',
            'ممتاز! لديك القدرة، استمر! 🏆',
            'حسن جدًا! لا تتوقف، تقدم! 💪',
            'مذهل! أنت تفعل ذلك، واصل! 🎉'
        ];
        const randomMessage = motivationalMessages[Math.floor(Math.random() * motivationalMessages.length)];
        toastr.success(randomMessage);

        $('#nextBtn').prop('disabled', false);
    }

    function prevQuestion() {
        if (currentQuestionIndex > 0) {
            displayQuestion(currentQuestionIndex - 1);
        }
    }

    function nextQuestion() {
        if (!userAnswers[currentQuestionIndex]) {
            toastr.warning('اختر إجابة أولاً! 😊');
            return;
        }
        displayQuestion(currentQuestionIndex + 1);
    }

    function startTimer() {
    timerInterval = setInterval(function () {
        let minutes = Math.floor(timeLeft);
        let seconds = Math.floor((timeLeft % 1) * 60);
        $('#timeLeft').text(formatTime(timeLeft));
        $('#timer').toggleClass('warning', timeLeft <= 1).attr('aria-label', `الوقت المتبقي: ${minutes} دقيقة ${seconds} ثانية`);
        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            toastr.error('انتهى الوقت');
            setTimeout(submitQuiz, 1000);;
        }
        timeLeft -= 1 / 60;
    }, 1000);
}

function formatTime(minutes) {
    let wholeMinutes = Math.floor(minutes);
    let seconds = Math.floor((minutes % 1) * 60);
    return `${wholeMinutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
}

async function submitQuiz() {
    clearInterval(timerInterval);
    $('#quizContent').hide();

    let finalScore = 0;
    let answerDetails = [];
    let incorrectTopics = [];
    let unansweredCount = 0;

    let userAnswersForDB = {};

    for (let i = 0; i < questions.length; i++) {
        const userAnswer = userAnswers[i];
        const correctAnswer = questions[i].correctAnswer;
        const isCorrect = userAnswer === correctAnswer;

        if (!userAnswer) unansweredCount++; // count unanswered

        if (isCorrect) finalScore += questions[i].points;
        else incorrectTopics.push(questions[i].topic);

        answerDetails.push({
            question: questions[i].question,
            userAnswer: userAnswer || 'غير محدد',
            correctAnswer: correctAnswer,
            isCorrect: isCorrect,
            points: questions[i].points,
            explanation: questions[i].explanation || 'لا يوجد تفسير متاح لهذا السؤال.'
        });

        userAnswersForDB[i] = userAnswer || ''; // send even unanswered
    }

    if (unansweredCount > 0) {
        console.log(`تم إرسال الاختبار مع ${unansweredCount} سؤالًا بدون إجابة.`);
    }

    const endTime = Date.now();
    const timeTaken = (endTime - startTime) / 60000;

    let aiFeedback = "لا توجد تعليقات متاحة.";
    if (incorrectTopics.length > 0) {
        const prompt = `كطالب صغير، قدم تعليقًا قصيرًا (جملة واحدة) مشجعًا ومناسبًا للأطفال بناءً على الأخطاء في المواضيع التالية فقط:  في اختبار "${quizTitle}"
        ، مع تجنب ذكر أي موضوع نجحت فيه مثل اللغة العربية إذا كنت قد نجحت فيه. اقترح نصيحة ممتعة ومحددة مثل: "راجع درسًا معينًا 📚"، "مارس مسائل ${quizTitle} ✏️"
        ، "استخدم بطاقات تعليمية ملونة لـ 🎴"، "شاهد فيديوهات ممتعة عن 📺"، أو "ادرس مع صديق 👭". استخدم أسلوبًا إيجابيًا ومحفزًا باللغة العربية.`;

        try {
            const response = await fetch('/api/proxy-gemini', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: JSON.stringify({
                    contents: [{
                        parts: [{ text: prompt }]
                    }],
                    generationConfig: {
                        temperature: 0.7,
                        maxOutputTokens: 50
                    }
                })
            });

            if (!response.ok) {
                const errorText = await response.text();
                throw new Error(`HTTP error! status: ${response.status}, message: ${errorText}`);
            }

            const data = await response.json();
            aiFeedback = data.candidates?.[0]?.content?.parts?.[0]?.text || "لا توجد تعليقات متاحة.";
        } catch (error) {
            console.error('Error fetching AI feedback:', {
                message: error.message,
                status: error.status || 'N/A',
                response: error.response ? await error.response.text() : 'No response'
            });
            aiFeedback = "حدث خطأ أثناء جلب التعليقات. تحقق من اتصالك بالإنترنت أو حاول مرة أخرى لاحقًا.";
        }
    } else {
        aiFeedback = "ممتاز! لم ترتكب أي أخطاء، استمر في العمل الجيد! 🎉";
    }

    try {
        console.log('Submitting quiz with score:', finalScore, 'time_taken:', timeTaken, 'user_answers:', userAnswersForDB);
        const response = await fetch(`/student/quiz/${quizId}/submit`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            credentials: 'include',
            body: JSON.stringify({
                score: finalScore,
                time_taken: timeTaken,
                user_answers: userAnswersForDB
            })
        });

        const data = await response.json();
        if (response.ok) {
            const result = data.result || {};
            const badges = data.result.badges || [];
            earnedBadges = badges;
            displayResults(result, badges, answerDetails, timeTaken, aiFeedback);
            if (earnedBadges.length > 0) {
                showBadgePopUp();
            }
        } else {
            toastr.error(data.message || 'فشل الإرسال. 😔');
        }
    } catch (error) {
        toastr.error('خطأ أثناء الإرسال. حاول مجددًا! 😊');
        console.error('Error:', error);
    }
}

function displayResults(result, badges, answerDetails, timeTaken, aiFeedback) {
        const quizTitle = result.quiz_title || 'اختبار غير معروف';
        const score = result.score || 0;
        const total = result.total || 0;
        const percentage = result.percentage || 0;
        const status = result.status || 'unknown';
        const createdAt = result.created_at || new Date().toLocaleString('ar-EG');

        const radius = 45;
        const circumference = 2 * Math.PI * radius;
        const offset = circumference - (percentage / 100) * circumference;

        let html = `
            <h2>🎉 نتيجتك في ${quizTitle} 🎉</h2>
            <div class="progress-circle">
                <svg>
                    <circle class="circle-bg" cx="50" cy="50" r="${radius}"></circle>
                    <circle class="circle-fg" cx="50" cy="50" r="${radius}" style="stroke-dasharray: ${circumference}; stroke-dashoffset: ${offset};"></circle>
                </svg>
                <span>${percentage}%</span>
            </div>
            <div class="result-grid">
                <div class="result-item">الدرجة: ${score} / ${total} 🏅</div>
                <div class="result-item">النسبة: ${percentage}% 🌟</div>
                <div class="result-item">الوقت: ${timeTaken.toFixed(2)} دقيقة ⏰</div>
                <div class="result-item ${status === 'passed' ? 'success' : 'failure'}">
                    ${status === 'passed' ? 'نجحت! 🎈' : 'حاول مجددًا! 💪'}
                </div>
                <div class="result-item">التاريخ: ${createdAt} 📅</div>
            </div>
            <button class="details-toggle" onclick="$('.question-details').slideToggle();" aria-label="عرض تفاصيل الأسئلة">عرض التفاصيل 📋</button>
            <div class="question-details">
                <h4>تفاصيل الأسئلة 📚</h4>
                <ul>`;
        
        answerDetails.forEach((detail, i) => {
            html += `
                <li>
                    ${i + 1}. ${detail.question}<br>
                    إجابتك: <span class="${detail.isCorrect ? 'correct' : 'incorrect'}">${detail.userAnswer}</span><br>
                    الإجابة الصحيحة: <span class="correct">${detail.correctAnswer}</span><br>
                    النقاط: ${detail.points} ${detail.isCorrect ? '(صحيحة ✅)' : '(خاطئة ❌)'}
                    <div class="explanation">💡 التفسير: ${detail.explanation || 'لا يوجد تفسير متاح.'}</div>
                </li>`;
        });

        html += `
                </ul>
            </div>
            <h4>تعليق ذكي 🤖</h4>
            <div class="feedback-section">
                ${aiFeedback}
            </div>
            <h4>الأوسمة 🏆</h4>
            <div id="badgesList">
                ${badges.length > 0 ? badges.map(b => `<div class="badge-item">${b.badge_name} 🎖️</div>`).join('') : 'لا يوجد أوسمة بعد، استمر في المحاولة! 😊'}
            </div>
            <button class="retry-btn" onclick="retryQuiz()" aria-label="حاول مجددًا">حاول مجددًا! 🚀</button>`;

        $('#resultScreen').html(html).show();

        if (status === 'passed') {
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 },
                zIndex: 1000
            });
        }

        openCanvas();
        drawPerformanceChart(percentage, timeTaken);
    }

    function showBadgePopUp() {
        currentBadgeIndex = 0;
        if (earnedBadges.length > 0) {
            displayBadge();
            $('#badgeModal').show();
        }
    }

    function displayBadge() {
        if (currentBadgeIndex < earnedBadges.length) {
            const badge = earnedBadges[currentBadgeIndex];
            $('#badgeMessage').text(`لقد حصلت على وسام: ${badge.badge_name}! 🏅`);
        } else {
            $('#badgeModal').hide();
        }
    }

    function nextBadge() {
        currentBadgeIndex++;
        if (currentBadgeIndex < earnedBadges.length) {
            displayBadge();
        } else {
            $('#badgeModal').hide();
        }
    }

    function retryQuiz() {
        window.location.href = `/student/quiz/${quizId}`;
    }

    function openCanvas() {
        console.log('Canvas opened for chart');
    }

    function drawPerformanceChart(percentage, timeTaken) {
        console.log('Drawing chart with percentage:', percentage, 'timeTaken:', timeTaken);
        let ctx = document.getElementById('performanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['النسبة', 'الوقت (دقيقة)'],
                datasets: [{
                    label: 'أداء',
                    data: [percentage, timeTaken * 60],
                    backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                    borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                scales: { y: { beginAtZero: true } }
            }
        });
    }
    </script>
</body>
</html>
