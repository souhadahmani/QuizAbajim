<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

class StudentDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $studentId = Auth::id();
        $period = $request->input('period', '30'); // Default: last 30 days

        // Query to get total attempts by material
        $totalAttemptsByMaterial = DB::table('quizzes_results')
            ->select(
                'materials.id',
                'materials.name',
                DB::raw('COUNT(*) as total_attempts')
            )
            ->join('quizzes', 'quizzes_results.quiz_id', '=', 'quizzes.id')
            ->join('materials', 'quizzes.subject_id', '=', 'materials.id')
            ->where('quizzes_results.user_id', $studentId)
            ->where('quizzes_results.created_at', '>=', Carbon::now()->subDays($period))
            ->groupBy('materials.id', 'materials.name')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->name,
                    'total_attempts' => $item->total_attempts,
                ];
            })
            ->values();

        $attemptsChartLabels = $totalAttemptsByMaterial->pluck('name')->toArray();
        $attemptsChartData = $totalAttemptsByMaterial->pluck('total_attempts')->toArray();

        // Query to get attempts by difficulty level (removed duplicate)
        $attemptsByDifficulty = DB::table('quizzes_results')
            ->select(
                'quizzes.difficulty_level as difficulty',
                DB::raw('COUNT(*) as total_attempts')
            )
            ->join('quizzes', 'quizzes_results.quiz_id', '=', 'quizzes.id')
            ->where('quizzes_results.user_id', $studentId)
            ->where('quizzes_results.created_at', '>=', Carbon::now()->subDays($period))
            ->groupBy('quizzes.difficulty_level')
            ->get()
            ->map(function ($item) {
                return [
                    'difficulty' => $item->difficulty ?? 'Unknown',
                    'total_attempts' => $item->total_attempts,
                ];
            })
            ->values();

        $difficultyChartLabels = $attemptsByDifficulty->pluck('difficulty')->toArray();
        $difficultyChartData = $attemptsByDifficulty->pluck('total_attempts')->toArray();

        // Average Score and Passed by Difficulty
        $successByDifficulty = DB::table('quizzes_results')
            ->select(
                'quizzes.difficulty_level as difficulty',
                DB::raw('COUNT(*) as total_attempts'),
                DB::raw('SUM(CASE WHEN quizzes_results.score IS NOT NULL AND quizzes.total_mark > 0 AND (quizzes_results.score * 100.0 / quizzes.total_mark) >= COALESCE(quizzes.pass_mark, 50) THEN 1 ELSE 0 END) as passed'),
                DB::raw('ROUND(AVG(CASE WHEN quizzes_results.score IS NOT NULL AND quizzes.total_mark > 0 THEN (quizzes_results.score * 100.0 / quizzes.total_mark) ELSE 0 END), 2) as average_score')
            )
            ->join('quizzes', 'quizzes_results.quiz_id', '=', 'quizzes.id')
            ->where('quizzes_results.user_id', $studentId)
            ->where('quizzes_results.created_at', '>=', Carbon::now()->subDays($period))
            ->groupBy('quizzes.difficulty_level')
            ->get()
            ->map(function ($item) {
                return [
                    'difficulty' => $item->difficulty ?? 'Unknown',
                    'average_score' => $item->average_score,
                    'total_attempts' => $item->total_attempts,
                    'passed' => $item->passed
                ];
            });

        // Passed Quizzes by Subject (with Average Score and Failed)
        $passedQuizzesBySubject = DB::table('quizzes_results')
            ->select(
                'materials.id',
                'materials.name',
                DB::raw('COUNT(*) as total_attempts'),
                DB::raw('SUM(CASE WHEN quizzes_results.score IS NOT NULL AND quizzes.total_mark > 0 AND (quizzes_results.score * 100.0 / quizzes.total_mark) >= COALESCE(quizzes.pass_mark, 50) THEN 1 ELSE 0 END) as passed'),
                DB::raw('SUM(CASE WHEN quizzes_results.score IS NOT NULL AND quizzes.total_mark > 0 AND (quizzes_results.score * 100.0 / quizzes.total_mark) < COALESCE(quizzes.pass_mark, 50) THEN 1 ELSE 0 END) as failed'),
                DB::raw('ROUND(AVG(CASE WHEN quizzes_results.score IS NOT NULL AND quizzes.total_mark > 0 THEN (quizzes_results.score * 100.0 / quizzes.total_mark) ELSE 0 END), 2) as average_score')
            )
            ->join('quizzes', 'quizzes_results.quiz_id', '=', 'quizzes.id')
            ->join('materials', 'quizzes.subject_id', '=', 'materials.id')
            ->where('quizzes_results.user_id', $studentId)
            ->where('quizzes_results.created_at', '>=', Carbon::now()->subDays($period))
            ->groupBy('materials.id', 'materials.name')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->name,
                    'passed' => $item->passed,
                    'failed' => $item->failed,
                    'total_attempts' => $item->total_attempts,
                    'average_score' => $item->average_score
                ];
            });

        // Define $subjectPassFailData for the pie chart
        $subjectPassFailData = $passedQuizzesBySubject->map(function ($item) {
            return ['passed' => $item['passed'], 'failed' => $item['failed']];
        })->values();

        // Progress Over Time (fixed to handle null/zero values)
        $progressOverTime = DB::table('quizzes_results')
            ->select(
                DB::raw('DATE(quizzes_results.created_at) as date'),
                DB::raw('COALESCE(AVG(CASE WHEN quizzes.total_mark > 0 THEN (quizzes_results.score / quizzes.total_mark) * 100 ELSE 0 END), 0) as daily_avg')
            )
            ->join('quizzes', 'quizzes_results.quiz_id', '=', 'quizzes.id')
            ->where('quizzes_results.user_id', $studentId)
            ->where('quizzes_results.created_at', '>=', Carbon::now()->subDays($period))
            ->groupBy(DB::raw('DATE(quizzes_results.created_at)'))
            ->orderBy('date')
            ->get();
            

        // Engagement Metrics
        $engagement = [
            'days_active' => DB::table('quizzes_results')
                ->where('user_id', $studentId)
                ->where('created_at', '>=', Carbon::now()->subDays($period))
                ->distinct()
                ->count(DB::raw('DATE(created_at)')),
            'quizzes_per_day' => DB::table('quizzes_results')
                ->where('user_id', $studentId)
                ->where('created_at', '>=', Carbon::now()->subDays($period))
                ->count() / $period
        ];

        // Weak Areas (Areas Needing Improvement)
        $weakAreas = DB::table('quizzes_results')
            ->select(
                'materials.name',
                DB::raw('ROUND(AVG(CASE WHEN quizzes.total_mark > 0 THEN (quizzes_results.score / quizzes.total_mark) * 100 ELSE 0 END), 2) as avg_score'),
                DB::raw('COUNT(*) as attempt_count')
            )
            ->join('quizzes', 'quizzes_results.quiz_id', '=', 'quizzes.id')
            ->join('materials', 'quizzes.subject_id', '=', 'materials.id')
            ->where('quizzes_results.user_id', $studentId)
            ->where('quizzes_results.created_at', '>=', Carbon::now()->subDays($period))
            ->groupBy('materials.name')
            ->having('avg_score', '<', 70)
            ->orderBy('avg_score')
            ->limit(3)
            ->get();

        // Detailed Quiz Results for Debugging
        $quizResultsSample = DB::table('quizzes_results')
            ->select(
                'quizzes_results.score',
                'quizzes.total_mark',
                'quizzes.pass_mark',
                'materials.name as subject',
                DB::raw('ROUND((quizzes_results.score * 100.0 / quizzes.total_mark), 2) as percentage'),
                DB::raw('CASE WHEN quizzes_results.score IS NOT NULL AND quizzes.total_mark > 0 AND (quizzes_results.score * 100 / quizzes.total_mark) >= COALESCE(quizzes.pass_mark, 50) THEN "Passed" ELSE "Failed" END as result')
            )
            ->join('quizzes', 'quizzes_results.quiz_id', '=', 'quizzes.id')
            ->join('materials', 'quizzes.subject_id', '=', 'materials.id')
            ->where('quizzes_results.user_id', $studentId)
            ->where('quizzes_results.created_at', '>=', Carbon::now()->subDays($period))
            ->limit(5)
            ->get();

        return view('dashboardBI.studentbi', [
            'attemptsChartLabels' => $totalAttemptsByMaterial->pluck('name')->toArray(),
            'attemptsChartData' => $totalAttemptsByMaterial->pluck('total_attempts')->toArray(),
            'difficultyChartLabels' => $attemptsByDifficulty->pluck('difficulty')->toArray(),
            'difficultyChartData' => $attemptsByDifficulty->pluck('total_attempts')->toArray(),
            'difficultyPerformanceLabels' => $successByDifficulty->pluck('difficulty')->toArray(),
            'difficultyPerformanceData' => $successByDifficulty->pluck('average_score')->toArray(),
            'passedQuizzesLabels' => $passedQuizzesBySubject->pluck('name')->toArray(),
            'passedQuizzesData' => $passedQuizzesBySubject->pluck('passed')->toArray(),
            'subjectAverageScores' => $passedQuizzesBySubject->pluck('average_score')->toArray(),
            'subjectPassFailData' => $subjectPassFailData,
            'progressDates' => $progressOverTime->pluck('date'),
            'progressScores' => $progressOverTime->pluck('daily_avg'),
            'engagement' => $engagement,
            'weakAreas' => $weakAreas,
            'period' => $period,
            'debugData' => [
                'successByDifficulty' => $successByDifficulty,
                'passedQuizzesBySubject' => $passedQuizzesBySubject,
                'quizResultsSample' => $quizResultsSample
            ]
        ]);
    }
}