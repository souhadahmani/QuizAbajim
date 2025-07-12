<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Q_resultat;
use App\Models\StudentAnswer;
use App\Models\Materials;
use App\Models\Schoollevel;
use App\Models\User;
use App\Models\StudentBadge;
use App\Notifications\BadgeEarnedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Notification;

class StudentQuizController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showAI($id)
    {
        $quiz = Quiz::findOrFail($id);
        $geminiApiKey = env('GEMINI_API_KEY'); 
        return view('parent.pass', compact('quiz', 'geminiApiKey'));
    }

    public function dashboard(Request $request)
    {
        try {
            $student = Auth::user();

            if (!$student) {
                return redirect()->route('login')->with('error', 'يرجى تسجيل الدخول أولاً.');
            }

            $results = Q_resultat::where('user_id', $student->id)
                ->with('quiz')
                ->get();

            $query = Quiz::query()->with(['results' => function ($query) use ($student) {
                $query->where('user_id', $student->id);
            }]);

            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where('title', 'like', "%{$search}%");
            }

            if ($request->filled('subject')) {
                $query->where('subject_id', $request->input('subject'));
            }

            if ($request->filled('level')) {
                $query->where('school_level_id', $request->input('level'));
            }

            if ($request->filled('type')) {
                $query->where('type', $request->input('type'));
            }

            if ($request->filled('difficulty')) {
                $query->where('difficulty_level', $request->input('difficulty'));
            }
//aading new filter 
/*
if ($request->filled('duration')) {
    if ($request->duration === 'custom' && $request->filled('min_duration') && $request->filled('max_duration')) {
        $query->whereBetween('time', [
            $request->min_duration,
            $request->max_duration
        ]);
    } elseif ($request->duration !== 'custom') {
        $query->where('time', '=', $request->duration);
    }
}
*/

    // Total mark filter (new)
    /*
    if ($request->filled('total_mark')) {
        if ($request->total_mark === 'custom' && $request->filled('min_mark') && $request->filled('max_mark')) {
            $query->whereBetween('total_mark', [
                $request->min_mark,
                $request->max_mark
            ]);
        } elseif ($request->total_mark !== 'custom') {
            $query->where('total_mark', $request->total_mark);
        }
    }
    */  
    $quizzes = $query->paginate(10);

            $subjects = Materials::all();
            $schoolLevels = SchoolLevel::all();

            $totalQuizzes = $query->count();
            $completedQuizzes = $results->count();
            $progress = $results->isNotEmpty() 
                ? $results->avg(function ($result) {
                    $resultData = $result->results;
                    $total = $resultData['total'] ?? 1;
                    return ($resultData['score'] / $total) * 100;
                }) 
                : 0;
            $averageTime = $results->isNotEmpty() 
                ? $results->avg('time_taken') 
                : 0;

            $badges = StudentBadge::where('user_id', $student->id)
                ->orderBy('awarded_at', 'desc')
                ->get();
            $notification=Notification::where('sender_id',$student->id)->get();
            
            return view('parent.parentdash', compact(
                'results',
                'quizzes',
                'subjects',
                'schoolLevels',
                'totalQuizzes',
                'completedQuizzes',
                'progress',
                'averageTime',
                'badges',
                'notification'
            ));
        } catch (\Exception $e) {
            Log::error('Error in StudentQuizController@dashboard', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحميل لوحة التحكم. يرجى المحاولة مرة أخرى.');
        }
    }

    public function getSubjectsBySchoolLevel(Request $request)
    {
        try {
            $schoolLevelId = $request->input('school_level_id');

            if (!$schoolLevelId) {
                return response()->json(['subjects' => []], 400);
            }

            $subjects = Materials::whereHas('section', function ($query) use ($schoolLevelId) {
                $query->where('level_id', $schoolLevelId);
            })->get();

            return response()->json([
                'subjects' => $subjects->map(function ($subject) {
                    return [
                        'id' => $subject->id,
                        'name' => $subject->name,
                    ];
                }),
            ]);
        } catch (\Exception $e) {
            Log::error('Error in StudentQuizController@getSubjectsBySchoolLevel', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'حدث خطأ أثناء جلب المواد.'], 500);
        }
    }

    public function show($id)
    {
        $quiz = Quiz::with('questions.answers')->findOrFail($id);
        return view('parent.pass', compact('quiz'));
    }

    public function startQuiz($id)
    {
        $quiz = Quiz::findOrFail($id);

        session([
            'quiz_id' => $quiz->id,
            'quiz_start_time' => now(),
        ]);

        return response()->json([
            'message' => 'تم بدء الاختبار بنجاح!'
        ]);
    }

    public function getQuiz($id)
    {
        $quiz = Quiz::with('questions.answers')->findOrFail($id);
    
        if ($quiz->questions->isEmpty()) {
            return response()->json([
                'message' => 'هذا الاختبار لا يحتوي على أسئلة!'
            ], 400);
        }
    
        return response()->json([
            'quiz' => $quiz,
            'questions' => $quiz->questions->map(function ($question) {
                return [
                    'id' => $question->id, // Ajout de l'ID pour pouvoir l'enregistrer
                    'question' => $question->question,
                    'options' => $question->answers->pluck('answer'),
                    'correctAnswer' => $question->answers->where('correct', true)->first()->answer ?? null,
                    'points' => $question->grade,
                    'image' => $question->image ? asset('storage/' . $question->image) : null,
                    'video' => $question->video ? asset('storage/' . $question->video) : null,
                    'explanation' => $question->explanation,
                    'topic' => $question->topic ?? 'غير محدد'
                ];
            }),
            'time' => $quiz->time,
        ]);
    }

    public function submit(Request $request, $quizId)
    {
        \Log::info('submit method called', ['quizId' => $quizId, 'request' => $request->all()]);
    
        try {
            $user = auth()->user();
            $userAnswers = $request->input('user_answers', []);
            $timeTaken = (float) $request->input('time_taken');
    
            $quiz = \App\Models\Quiz::with('questions.answers')->findOrFail($quizId);
            $totalPoints = $quiz->questions->sum('grade');
    
            // Validation temps
            if ($timeTaken < 0) {
                \Log::warning('Invalid time_taken', ['timeTaken' => $timeTaken]);
                $timeTaken = 0;
            }
    
            // ❌ Supprimer les anciennes réponses pour éviter un score cumulé
            StudentAnswer::where('user_id', $user->id)
                ->where('quiz_id', $quizId)
                ->delete();
    
            // ✅ Enregistrement des nouvelles réponses
            foreach ($userAnswers as $questionIndex => $selectedAnswer) {
                if (empty($selectedAnswer)) {
                    \Log::info("Réponse vide ignorée", ['index' => $questionIndex]);
                    continue;
                }
            
                $question = $quiz->questions->skip($questionIndex)->first();
            
                if ($question) {
                    $correctAnswerObj = $question->answers->where('correct', true)->first();
                    $correctAnswer = $correctAnswerObj ? $correctAnswerObj->answer : null;
                    $isCorrect = $selectedAnswer === $correctAnswer;
            
                    StudentAnswer::create([
                        'user_id' => $user->id,
                        'quiz_id' => $quizId,
                        'question_id' => $question->id,
                        'selected_answer' => $selectedAnswer,
                        'is_correct' => $isCorrect,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    \Log::warning("Question not found for index: $questionIndex");
                }
            }
            
            
    
            // 🔁 Récupération du score réel depuis les réponses enregistrées
            $studentAnswers = StudentAnswer::where('user_id', $user->id)
                ->where('quiz_id', $quizId)
                ->get();
    
            $score = 0;
    
            foreach ($studentAnswers as $answer) {
                $question = $quiz->questions->where('id', $answer->question_id)->first();
                if ($answer->is_correct && $question) {
                    $score += $question->grade;
                }
            }
    
            // Calcul du pourcentage et grade
            $percentage = $totalPoints > 0 ? min(100, ($score / $totalPoints) * 100) : 0;
            $status = $percentage >= 60 ? 'passed' : 'failed';
            $userGrade = $percentage >= 90 ? 'A' :
                         ($percentage >= 80 ? 'B' :
                         ($percentage >= 70 ? 'C' :
                         ($percentage >= 60 ? 'D' : 'F')));
    
            // Résultat global
            $quizResult = Q_resultat::create([
                'user_id' => $user->id,
                'quiz_id' => $quizId,
                'score' => $score,
                'time_taken' => $timeTaken,
                'results' => ['score' => $score, 'total' => $totalPoints],
                'user_grade' => $userGrade,
                'status' => $status,
            ]);
    
            // Badges et notifications - Pass the quiz result ID
            $badges = $this->awardBadges($user, $score, $percentage, $timeTaken, $quiz, $quizResult->id);
    
            foreach ($badges as $b) {
                try {
                    Notification::create([
                        'user_id' => $user->id,
                        'title' => $b['badge_name'],
                        'sender_id' => $user->id,
                        'message' => 'لقد حصلت على وسام جديد: ' . $b['badge_name'] . ' 🎉',
                        'data' => '',
                        'created_at' => now(),
                        'updated_at' => now(),
                        "read_at"=> now(),
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Failed to create notification for badge: ' . $b['badge_name'], ['error' => $e->getMessage()]);
                }
            }
    
            return response()->json([
                'result' => [
                    'quiz_title' => $quiz->title,
                    'score' => $score,
                    'total' => $totalPoints,
                    'percentage' => round($percentage, 2),
                    'created_at' => $quizResult->created_at,
                    'status' => $status,
                    'badges' => $badges,
                ]
            ], 200);
    
        } catch (\Exception $e) {
            \Log::error('Quiz submission failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['message' => 'حدث خطأ أثناء حفظ النتيجة.'], 500);
        }
    }

    protected function awardBadges($user, $score, $percentage, $timeTaken, $quiz, $resultId)
    {
        $badges = [];
        $existingBadges = StudentBadge::where('user_id', $user->id)->pluck('badge_type')->toArray();
    
        // Badge: Time Master (complete in under 30 seconds with 100% score)
        if ($timeTaken <= 30 && $percentage == 100 && !in_array('TimeMaster', $existingBadges)) {
            $badges[] = [
                'badge_type' => 'TimeMaster', 
                'badge_name' => 'المتعلم المتمكن', 
                'awarded_at' => now(),
                'result_id' => $resultId
            ];
        }

        // Badge: Perfect Score (100% score)
        if ($percentage == 100 && !in_array('PerfectScore', $existingBadges)) {
            $badges[] = [
                'badge_type' => 'PerfectScore', 
                'badge_name' => 'درجة كاملة', 
                'awarded_at' => now(),
                'result_id' => $resultId
            ];
        }

        // Badge: Quick Learner (complete in under 1 minute with 80% or higher)
        if ($timeTaken <= 60 && $percentage >= 80 && !in_array('QuickLearner', $existingBadges)) {
            $badges[] = [
                'badge_type' => 'QuickLearner', 
                'badge_name' => 'متعلم سريع', 
                'awarded_at' => now(),
                'result_id' => $resultId
            ];
        }

        // Badge: Streak Master (complete 5 quizzes in a row with 80% or higher)
        $recentResults = Q_resultat::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        if ($recentResults->count() >= 5 && !in_array('StreakMaster', $existingBadges)) {
            $streak = true;
            foreach ($recentResults as $result) {
                $resultData = $result->results;
                $total = $resultData['total'] ?? 1;
                $resultPercentage = ($resultData['score'] / $total) * 100;
                if ($resultPercentage < 80) {
                    $streak = false;
                    break;
                }
            }
            if ($streak) {
                $badges[] = [
                    'badge_type' => 'StreakMaster', 
                    'badge_name' => 'سلسلة انتصارات', 
                    'awarded_at' => now(),
                    'result_id' => $resultId
                ];
            }
        }

        // Badge: First Try (pass on the first attempt of a quiz with 60% or higher)
        $previousAttempts = Q_resultat::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->count();
        if ($previousAttempts == 1 && $percentage >= 60 && !in_array('FirstTry', $existingBadges)) {
            $badges[] = [
                'badge_type' => 'FirstTry', 
                'badge_name' => 'محاولة أولى ناجحة', 
                'awarded_at' => now(),
                'result_id' => $resultId
            ];
        }

        // Create badge records in database
        foreach ($badges as $badge) {
            StudentBadge::create([
                'user_id' => $user->id,
                'badge_type' => $badge['badge_type'],
                'badge_name' => $badge['badge_name'],
                'awarded_at' => $badge['awarded_at'],
                'result_id' => $badge['result_id'], // Add the result_id reference
            ]);
        }
    
        return $badges;
    }

    protected function awardBadge($user, $badgeType, $badgeName, $description, $awardedAt, $resultId = null)
    {
        $exists = StudentBadge::where('user_id', $user->id)
            ->where('badge_type', $badgeType)
            ->exists();

        if (!$exists) {
            StudentBadge::create([
                'user_id' => $user->id,
                'badge_type' => $badgeType,
                'badge_name' => $badgeName,
                'description' => $description,
                'awarded_at' => $awardedAt,
                'result_id' => $resultId, // Add the result_id reference
            ]);

            try {
                $this->sendNotification($user, 'لقد حصلت على وسام جديد!', "تهانينا! لقد حصلت على وسام: $badgeName", 'single', [
                    'badge_name' => $badgeName,
                    'description' => $description,
                ]);
            } catch (\Exception $e) {
                \Log::error('Error sending badge notification:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }
    }

    protected function sendNotification($user, $title, $message, $type, $data = [])
    {
        $user->notify(new \App\Notifications\BadgeEarnedNotification($title, $message, $data));
    }

    public function getResults()
    {
        $user = Auth::user();

        $results = Q_resultat::where('user_id', $user->id)
            ->with('quiz')
            ->get()
            ->map(function ($result) {
                $resultData = json_decode($result->results, true);
                return [
                    'quiz_title' => $result->quiz ? $result->quiz->title : 'Unknown Quiz',
                    'score' => $resultData['score'] ?? 0,
                    'total' => $resultData['total'] ?? 0,
                    'percentage' => $resultData['total'] > 0 ? ($resultData['score'] / $resultData['total']) * 100 : 0,
                    'status' => $result->status,
                    'created_at' => \Carbon\Carbon::createFromTimestamp($result->created_at)->toDateTimeString(),
                ];
            });

        return view('parent.quiz_result', compact('results'));
    }

    /**
     * Récupérer les réponses détaillées d'un étudiant pour un quiz
     */
    public function getStudentAnswers($quizId, $userId = null)
    {
        $user = $userId ? \App\Models\User::findOrFail($userId) : Auth::user();
        
        $answers = StudentAnswer::where('user_id', $user->id)
            ->where('quiz_id', $quizId)
            ->with(['question', 'quiz'])
            ->get();

        return response()->json([
            'answers' => $answers->map(function ($answer) {
                return [
                    'question' => $answer->question->question,
                    'selected_answer' => $answer->selected_answer,
                    'is_correct' => $answer->is_correct,
                    'points_earned' => $answer->points_earned,
                    'answered_at' => $answer->answered_at,
                ];
            }),
        ]);
    }

    public function studentResults()
    {
        // Récupérer tous les utilisateurs ayant le rôle "student"
        $students = User::where('role_name', 'organization')->get();
    
        // Récupérer les résultats pour chaque élève avec le titre du quiz
        $results = Q_resultat::with(['quiz', 'user'])
            ->whereIn('user_id', $students->pluck('id'))
            ->get()
            ->map(function ($result) {
                return [
                    'quiz_id'=>$result->quiz_id,
                    'student_name' => $result->user ? $result->user->full_name : 'طالب غير معروف',
                    'quiz_title' => $result->quiz ? $result->quiz->title : 'اختبار غير معروف',
                    'score' => $result->score ?? 0,
                    'total_mark' => $result->quiz ? $result->quiz->total_mark : 'غير متوفر',
                    'time_taken' => $result->time_taken ?? 'غير متوفر',
                    'created_at'=>$result->created_at,

                ];
            });
    
        // Retourner une vue avec les résultats
        return view('parent.resultat', compact('results'));
    }


}