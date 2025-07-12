<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>معاينة الكويز</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    <style>
        body {
            background: #2a5298; /* Solid blue background for Abajim */
            font-family: 'Cairo', sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .quiz-container {
            max-width: 900px;
            width: 100%;
        }
        .quiz-settings-card {
            background: #fff;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .quiz-settings-card h1 {
            font-size: 1.8rem;
            color: #1e3c72; /* Dark blue for Abajim */
            margin-bottom: 15px;
        }
        .quiz-settings-card p {
            margin: 5px 0;
            font-size: 1rem;
            color: #5a5a5a;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .quiz-settings-card p i {
            margin-left: 8px;
            color: #2a5298; /* Medium blue for Abajim */
        }
        .quiz-content-card {
            background: #fff;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        .timer {
            font-size: 1.1rem;
            color: #fff;
            background: #2a5298; /* Medium blue for Abajim */
            padding: 8px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            text-align: center;
        }
        .timer.warning {
            background: #dc3545; /* Red for warning */
            animation: pulse 1s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .question-counter {
            text-align: center;
            font-size: 1.1rem;
            color: #5a5a5a;
            margin-bottom: 15px;
        }
        .progress-container {
            margin-bottom: 20px;
        }
        .progress {
            height: 10px;
            border-radius: 5px;
            background: #e0e0e0;
        }
        .progress-bar {
            background: #2a5298; /* Medium blue for Abajim */
            transition: width 0.3s ease;
        }
        .quiz-box {
            background: transparent;
            padding: 0;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }
        .quiz-box.active {
            opacity: 1;
            transform: translateY(0);
        }
        .question h3 {
            font-size: 2rem; /* Larger font size for question */
            color: #1e3c72; /* Dark blue for Abajim */
            margin-bottom: 20px;
            text-align: center; /* Centered question */
            font-weight: 700;
        }
        .question h3 i {
            margin-left: 10px;
            color: #2a5298; /* Medium blue for Abajim */
        }
        .question-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 1rem;
            color: #5a5a5a;
        }
        .question-details p {
            margin: 0;
        }
        .question-media {
            text-align: center;
            margin-bottom: 20px;
        }
        .question-media img, .question-media video {
            max-width: 300px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .answers-list {
            list-style: none;
            padding: 0;
        }
        .answer-card {
            background: #f8f9fa;
            padding: 15px 20px;
            border-radius: 10px;
            margin: 10px 0;
            font-size: 1.1rem;
            cursor: pointer;
            border: 2px solid transparent;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .answer-card:hover {
            background: #e8f0fe; /* Very light blue for Abajim */
            border-color: #2a5298; /* Medium blue for Abajim */
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .answer-card.selected-correct {
            background: #d4edda; /* Green for correct answer */
            border-color: #28a745;
            color: #155724;
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }
        .answer-card.selected-incorrect {
            background: #f8d7da; /* Red for incorrect answer */
            border-color: #dc3545;
            color: #721c24;
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }
        .answer-label {
            background: #2a5298; /* Medium blue for Abajim */
            color: #fff;
            padding: 5px 10px;
            border-radius: 50%;
            margin-left: 10px;
            font-size: 0.9rem;
            font-weight: bold;
        }
        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .btn-nav, .btn-finish {
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 8px;
            border: none;
            color: #fff;
            background: #2a5298; /* Medium blue for Abajim */
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .btn-nav:hover, .btn-finish:hover {
            background: #1e3c72; /* Dark blue for Abajim */
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .btn-nav:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        @media (max-width: 576px) {
            .quiz-container {
                padding: 10px;
            }
            .quiz-settings-card h1 {
                font-size: 1.5rem;
            }
            .question h3 {
                font-size: 1.6rem;
            }
            .answer-card {
                font-size: 1rem;
                padding: 10px 15px;
            }
            .btn-nav, .btn-finish {
                padding: 8px 15px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="quiz-container">
        <!-- Quiz Settings Card -->
        <div class="quiz-settings-card">
            <h1>معاينة الكويز: {{ $quiz->title }}</h1>
            <p><i class="fas fa-clock"></i><strong>⏳ الوقت المحدد:</strong> {{ $quiz->time }} ثانية</p>
            <p><i class="fas fa-star"></i><strong>🎯 درجة النجاح:</strong> {{ $quiz->pass_mark }}%</p>
            <p><i class="fas fa-certificate"></i><strong>🏆 شهادة:</strong> {{ $quiz->certificate ? 'نعم' : 'لا' }}</p>
            <p><i class="fas fa-signal"></i><strong>📊 الحالة:</strong> {{ $quiz->status == 'active' ? 'نشط' : 'غير نشط' }}</p>
            <p><i class="fas fa-calendar-alt"></i><strong>📅 تاريخ الإنشاء:</strong> {{ $quiz->created_at }}</p>
        </div>

        <!-- Quiz Content Card -->
        <div class="quiz-content-card">
            @if($quiz->questions->count() > 0)
                <div class="timer" id="timer">
                    الوقت المتبقي: <span id="time-remaining">{{ $quiz->time }}</span> ثانية
                </div>

                <div class="question-counter" id="question-counter">
                    سؤال 1 من {{ $quiz->questions->count() }}
                </div>

                <div class="progress-container">
                    <div class="progress">
                        <div class="progress-bar" id="progress-bar" role="progressbar" style="width: {{ 100 / $quiz->questions->count() }}%;" aria-valuenow="1" aria-valuemin="0" aria-valuemax="{{ $quiz->questions->count() }}"></div>
                    </div>
                </div>

                <div id="questions-container">
                    @foreach($quiz->questions as $index => $question)
                        <div class="quiz-box question" id="question-{{ $index }}" style="display: none;">
                            <h3><i class="fas fa-question-circle"></i> {!! $question->question !!}</h3>
                            <div class="question-details">
                                <p><i class="fas fa-info-circle"></i> <strong>النوع:</strong> {{ $question->type == 'multiple' ? 'اختيار متعدد' : 'صحيح/خطأ' }}</p>
                                <p><i class="fas fa-star"></i> <strong>الدرجة:</strong> {{ $question->grade }}</p>
                            </div>
                            @if($question->image)
                                <div class="question-media">
                                    <img src="{{ Storage::url($question->image) }}" alt="Question Image">
                                </div>
                            @endif
                            @if($question->video)
                                <div class="question-media">
                                    <video src="{{ Storage::url($question->video) }}" controls></video>
                                </div>
                            @endif
                            <h4><i class="fas fa-list-ul"></i> الإجابات:</h4>
                            <ul class="answers-list">
                                @foreach($question->answers as $answerIndex => $answer)
                                    <li class="answer-card" data-question="{{ $index }}" data-answer="{{ $answerIndex }}" data-correct="{{ $answer->correct ? 'true' : 'false' }}">
                                        <span class="answer-label">
                                            {{ ['أ', 'ب', 'ج', 'د'][$answerIndex] }}
                                        </span>
                                        <span>{{ $answer->answer }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>

                <div class="navigation-buttons">
                    <button class="btn btn-nav" id="prev-btn" disabled onclick="showPreviousQuestion()">السابق</button>
                    <button class="btn btn-nav" id="next-btn" onclick="showNextQuestion()">التالي</button>
                    <button class="btn btn-finish" id="finish-btn" style="display: none;" onclick="finishQuiz()">إنهاء المعاينة</button>
                </div>
            @else
                <div class="quiz-box">
                    <p class="text-center">لا توجد أسئلة بعد.</p>
                    <div class="text-center">
                        <a href="{{ route('quiz.create') }}" class="btn btn-primary">إنشاء كويز جديد</a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Sound Effects
        const tickSound = new Audio('https://www.soundjay.com/buttons/sounds/beep-01a.mp3'); // Replace with your own ticking sound URL
        const finishSound = new Audio('https://www.soundjay.com/misc/sounds/magic-chime-01.mp3'); // Replace with your own finish sound URL
        const correctSound = new Audio('https://www.soundjay.com/buttons/sounds/button-3.mp3'); // Replace with your own correct sound URL
        const incorrectSound = new Audio('https://www.soundjay.com/buttons/sounds/button-2.mp3'); // Replace with your own incorrect sound URL

        // Timer
        let timeRemaining = {{ $quiz->time }};
        const timerElement = document.getElementById('time-remaining');
        const timerContainer = document.getElementById('timer');
        const timerInterval = setInterval(() => {
            if (timeRemaining <= 0) {
                clearInterval(timerInterval);
                timerElement.textContent = '0';
                alert('انتهى الوقت!');
                finishQuiz();
            } else {
                timeRemaining--;
                timerElement.textContent = timeRemaining;
                if (timeRemaining <= 10) {
                    timerContainer.classList.add('warning');
                    tickSound.play();
                }
            }
        }, 1000);

        // Quiz Navigation
        let currentQuestionIndex = 0;
        const totalQuestions = {{ $quiz->questions->count() }};
        const questions = document.querySelectorAll('.question');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const finishBtn = document.getElementById('finish-btn');
        const progressBar = document.getElementById('progress-bar');
        const questionCounter = document.getElementById('question-counter');

        // Answer Selection with Feedback
        const answerCards = document.querySelectorAll('.answer-card');
        answerCards.forEach(card => {
            card.addEventListener('click', () => {
                const questionIndex = card.getAttribute('data-question');
                const isCorrect = card.getAttribute('data-correct') === 'true';

                // Reset feedback for all answers in the same question
                const siblings = document.querySelectorAll(`.answer-card[data-question="${questionIndex}"]`);
                siblings.forEach(sibling => {
                    sibling.classList.remove('selected-correct', 'selected-incorrect');
                });

                // Apply feedback based on correctness
                if (isCorrect) {
                    card.classList.add('selected-correct');
                    correctSound.play();
                } else {
                    card.classList.add('selected-incorrect');
                    incorrectSound.play();
                }

                // Disable further clicks on this question's answers
                siblings.forEach(sibling => {
                    sibling.style.pointerEvents = 'none';
                });
            });
        });

        // Show the first question on page load
        if (totalQuestions > 0) {
            showQuestion(currentQuestionIndex);
        }

        function showQuestion(index) {
            // Hide all questions
            questions.forEach(q => {
                q.style.display = 'none';
                q.classList.remove('active');
            });

            // Show the current question
            const currentQuestion = document.getElementById(`question-${index}`);
            currentQuestion.style.display = 'block';
            setTimeout(() => {
                currentQuestion.classList.add('active');
            }, 10);

            // Reset answer feedback and enable clicks for the new question
            const answerCardsInQuestion = currentQuestion.querySelectorAll('.answer-card');
            answerCardsInQuestion.forEach(card => {
                card.classList.remove('selected-correct', 'selected-incorrect');
                card.style.pointerEvents = 'auto';
            });

            // Update progress bar and counter
            const progress = ((index + 1) / totalQuestions) * 100;
            progressBar.style.width = `${progress}%`;
            progressBar.setAttribute('aria-valuenow', index + 1);
            questionCounter.textContent = `سؤال ${index + 1} من ${totalQuestions}`;

            // Update button states
            prevBtn.disabled = index === 0;
            if (index === totalQuestions - 1) {
                nextBtn.style.display = 'none';
                finishBtn.style.display = 'inline-block';
            } else {
                nextBtn.style.display = 'inline-block';
                finishBtn.style.display = 'none';
            }
        }

        function showNextQuestion() {
            if (currentQuestionIndex < totalQuestions - 1) {
                currentQuestionIndex++;
                showQuestion(currentQuestionIndex);
            }
        }

        function showPreviousQuestion() {
            if (currentQuestionIndex > 0) {
                currentQuestionIndex--;
                showQuestion(currentQuestionIndex);
            }
        }

        function finishQuiz() {
            clearInterval(timerInterval); // Stop the timer
            finishSound.play(); // Play finish sound
            // Trigger confetti animation
            confetti({
                particleCount: 150,
                spread: 60,
                origin: { y: 0.6 },
                colors: ['#1e3c72', '#2a5298', '#a3bffa'], /* Blue shades for Abajim */
            });

            setTimeout(() => {
                window.location.href = '{{ route('quiz.create') }}';
            }, 2000);
        }
    </script>
</body>
</html>