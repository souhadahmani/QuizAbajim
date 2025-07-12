<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Q_resultat;
use App\Models\SchoolLevel;
use App\Models\Materials;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\UserMatiere;
use Carbon\Carbon;

class EnseignantController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function dashboard(Request $request)
    {
        \Log::info('Accessing teacher dashboard', [
            'user_id' => Auth::id(),
            'user_role' => Auth::user()->role_name ?? 'none',
            'email_verified_at' => Auth::user()->email_verified_at ?? 'not verified',
        ]);

        try {
            $teacher = Auth::user();
            $usermatierelevel = UserMatiere::where('teacher_id', $teacher->id)->get();

            if (!$teacher) {
                \Log::warning('Teacher not authenticated, redirecting to login');
                return redirect()->route('login')->with('error', 'يرجى تسجيل الدخول أولاً.');
            }

            // Fetch only quizzes created by the teacher
            $quizIds = Quiz::where('creator_id', $teacher->id)->pluck('id');

            if ($quizIds->isEmpty()) {
                $quizIds = [0]; // Avoid empty array for whereIn clause
                \Log::info('No quizzes found for teacher', ['teacher_id' => $teacher->id]);
            }

            $totalStudents = User::where('role_name', 'organization')->count();
            $studentsAttempted = Q_resultat::whereIn('quiz_id', $quizIds)->distinct('user_id')->count('user_id');
            $quizCompletions = Q_resultat::whereIn('quiz_id', $quizIds)->count(); // Total attempts
            $uniqueQuizzesConsumed = DB::table('quizzes')
            ->where('creator_id', $teacher->id)
            ->whereIn('id', function ($query) {
                $query->select('quiz_id')
                      ->from('quizzes_results')
                      ->distinct();
            })
            ->count();
        
            // Fetch only teacher's quizzes with related data
            $quizzes = Quiz::where('creator_id', $teacher->id)
                ->with('level', 'materials')
                ->paginate(10);

            // Average Results for teacher's quizzes
            $averageScores = Q_resultat::whereIn('quiz_id', $quizIds)
                ->select('quiz_id', DB::raw('AVG(score) as avg_score'))
                ->groupBy('quiz_id')
                ->get()
                ->map(function ($result) {
                    $quiz = Quiz::find($result->quiz_id);
                    return [
                        'title' => $quiz ? $quiz->title : 'اختبار غير معروف',
                        'avg_score' => $result->avg_score ?? 0,
                    ];
                });
                /*
                $averagefailure = Q_resultat::whereIn('quiz_id', $quizIds)
                ->join('quizzes', 'quizzes_results.quiz_id', '=', 'quizzes.id') // Join the quizzes table
                ->where('quizzes.total_mark', '>', 0) // Ensure total_mark exists and is positive
                ->whereColumn('score', '<', DB::raw('quizzes.total_mark * 0.5')) // Compare with joined table
                ->select('quizzes_results.quiz_id', DB::raw('COUNT(*) as failure_count'))
                ->groupBy('quizzes_results.quiz_id')
                ->get()
                ->map(function ($result) {
                    $quiz = Quiz::find($result->quiz_id);
                    return [
                        'title' => $quiz ? $quiz->title : 'اختبار غير معروف',
                        'failure_count' => $result->failure_count ?? 0,
                    ];
                });
                
                */
                // Progression Over Time (Last 7 days) for teacher's quizzes
            $progressionData = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->toDateString();
                $avgScore = Q_resultat::whereIn('quiz_id', $quizIds)
                    ->whereDate('created_at', $date)
                    ->avg('score') ?? 0;
                $progressionData[$date] = $avgScore;
            }

            

            // Number of Students Participated (already using teacher's quizzes)
            $registeredStudents = $studentsAttempted;

            \Log::info('Rendering teacher dashboard view', [
                'quizzes_count' => $quizzes->count(),
                'average_scores_count' => $averageScores->count(),
                'progression_data' => $progressionData,
            ]);


            return view('enseignant.quiz.quiz', compact(
                'usermatierelevel',
                'quizzes',
                'averageScores',
                'progressionData',
                'registeredStudents',
                'uniqueQuizzesConsumed'
                
            ));
        } catch (\Exception $e) {
            \Log::error('Failed to load teacher dashboard', [
                'error' => $e->getMessage(),
                'teacher_id' => $teacher->id ?? null,
            ]);
            return view('errors.custom', ['error' => 'حدث خطأ أثناء تحميل لوحة التحكم: ' . $e->getMessage()]);
        }
    }
}