<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نتائج الاختبار</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/student.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">نتائج الاختبارات</h1>

        @forelse ($results as $result)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ $result['quiz_title'] }}</h5>
                </div>
                <div class="card-body">
                    <p>الدرجة: {{ $result['score'] }} / {{ $result['total'] }}</p>
                    <p>النسبة: {{ round($result['percentage'], 2) }}%</p>
                    <p>الحالة: {{ $result['status'] == 'passed' ? 'ناجح' : 'راسب' }} ({{ $result['score'] }})</p>
                    <p>تاريخ الإكمال: {{ $result['created_at'] }}</p>

                    <h6 class="mt-3">تفاصيل الأسئلة:</h6>
                    @forelse ($result['results'] as $questionId => $answerData)
                        <div class="mb-3 p-3 border rounded">
                            <p><strong>السؤال:</strong> {{ $answerData['question_text'] }} {{ $answerData['is_correct'] ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times text-danger"></i>' }}</p>
                            <p><strong>إجابتك:</strong> {{ $answerData['student_answer'] ?? 'لم تُجب' }}</p>
                            <p><strong>الإجابة الصحيحة:</strong> {{ $answerData['correct_answer'] }}</p>
                            <p><strong>التوضيح:</strong> {{ $answerData['explanation'] }}</p>
                        </div>
                    @empty
                        <p>لا توجد تفاصيل أسئلة متاحة.</p>
                    @endforelse
                </div>
            </div>
        @empty
            <p class="text-center">لا توجد نتائج متاحة.</p>
        @endforelse

        <div class="text-center">
            <a href="{{ route('student.dashboard') }}" class="btn btn-primary">العودة إلى لوحة التحكم</a>
        </div>
    </div>
</body>
</html>