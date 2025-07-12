<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Enseignant\EnseignantController;
use App\Http\Controllers\Enseignant\QuizController;
use App\Http\Controllers\Enseignant\AccountController;
use App\Http\Controllers\Student\StudentQuizController;
use App\Http\Controllers\Enseignant\SetupController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TeacherProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Student\StudentDashboardController;

Route::get('/', function () {
    return view('welcome');
});

// Teacher dashboard route
Route::get('/teacher/dashboard', [EnseignantController::class, 'dashboard'])
    ->name('teacher.dashboard')
    ->middleware(['auth', 'verified']);

// Student dashboard route

Route::prefix('student')->middleware('restrict.role:organization')->group(function () {
    Route::get('/dashboard', [StudentQuizController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/quiz/{id}', [StudentQuizController::class, 'show'])->name('quiz.show');
    Route::get('/quiz/{id}/data', [StudentQuizController::class, 'getQuiz'])->name('quiz.data');
    Route::post('/quiz/{quizId}/submit', [StudentQuizController::class, 'submit'])->name('quiz.submit');
    Route::get('/subjects/by-school-level', [StudentQuizController::class, 'getSubjectsBySchoolLevel'])->name('subjects.bySchoolLevel');
    Route::post('/quiz/{id}/start', [StudentQuizController::class, 'startQuiz'])->name('quiz.start');
    Route::get('/results', [StudentQuizController::class, 'getResults'])->name('quiz.results');
    Route::post('/ai-feedback', [StudentQuizController::class, 'getAiFeedback'])->name('student.quiz.ai-feedback');
    Route::get('/student-results', [StudentQuizController::class, 'studentResults'])->name('student.results');
    //Route::get('/index/{user_id}', [StudentDashboardController::class, 'index'])->name('student.index');
    Route::get('/quiz/{id}/ai-feedback', [StudentQuizController::class, 'showAI'])->name('quiz.feedback');
    Route::get('/quiz/result', function (Illuminate\Http\Request $request) {
        $result = $request->query('result');
        if (!$result) {
            return redirect()->route('student.dashboard')->with('error', 'لا توجد نتيجة لعرضها.');
        }
        return view('parent.quiz_result', ['result' => $result]);
    })->name('quiz.result');
});

Route::get('/student/performance', [StudentDashboardController::class, 'index'])->middleware('auth')->name('student.performance');Route::get('/student/{user_id}/{sectionFilter?}/{subjectFilter?}', [StudentDashboardController::class, 'index'])->name('student.index');
Route::get('/student/subjects-by-level', [StudentDashboardController::class, 'getSubjectsBySchoolLevel'])->name('student.subjects.by.level');
// Dynamic dashboard route based on user role
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->role_name === 'teacher') {
            return redirect()->route('teacher.dashboard');
        }
        if ($user->role_name === 'organization') {
            return redirect()->route('student.dashboard');
        }
        return redirect()->route('login')->with('error', 'غير مسموح لك بالوصول إلى هذه الصفحة.');
    })->name('dashboard');
});
    // Teacher-specific routes (quiz creation and management)
    Route::middleware('restrict.role:teacher')->group(function () {
        Route::get('/quiz/create', [QuizController::class, 'create'])->name('quiz.create');
        Route::post('/quiz/store', [QuizController::class, 'store'])->name('quiz.store');
        Route::post('/quiz/{quiz_id}/save-question', [QuizController::class, 'saveQuestion'])->name('quiz.saveQuestion');
        Route::get('/quiz/{quiz_id}/preview', [QuizController::class, 'preview'])->name('quiz.preview');
        Route::put('/enseignant/quiz/update/{quiz_id}', [QuizController::class, 'update'])->name('quiz.update');
        Route::post('/quiz/generate-from-pdf', [QuizController::class, 'generateFromPDF'])->name('quiz.generateFromPDF');
        Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.index');
        Route::delete('/delete-quiz/{id}', [QuizController::class, 'destroy'])->name('quiz.destroy');
        Route::post('/quiz/{id}', [QuizController::class, 'updateStatus'])->name('quiz.update.status');
        

        Route::get('/quiz/{quiz_id}/question/{question_id}/edit', [QuizController::class, 'editQuestion'])->name('quiz.editQuestion');
        Route::put('/quiz/{quiz_id}/question/{question_id}/update', [QuizController::class, 'updateQuestion'])->name('quiz.updateQuestion');
        Route::delete('/quiz/{quiz_id}/question/{question_id}/delete', [QuizController::class, 'deleteQuestion'])->name('quiz.deleteQuestion');
        Route::get('/profile/create', [TeacherProfileController::class, 'create'])->name('teacher.profile.create');
        Route::post('/profile', [TeacherProfileController::class, 'store'])->name('teacher.profile.store');
        Route::get('/getmaterialbylevel/{id}',[TeacherProfileController::class, 'getMaterialsByLevel'])->name('teacher.getmaterialbylevel');
        Route::get('/quiz/get-materials/{levelId}', [QuizController::class, 'getMaterialsByLevel'])->name('quiz.getMaterials');
        //Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    //Route::delete('/user/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
    });

    // Account settings and profile routes
    Route::get('/account/settings', [AccountController::class, 'edit'])->name('enseignant.edit');
    Route::put('/update-profile', [AccountController::class, 'updateProfile'])->name('update.profile');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Organization-specific dashboard (parent dashboard)
    Route::middleware('restrict.role:organization')->group(function () {
        Route::get('/pdashboard', [StudentQuizController::class, 'dashboard'])->name('pdashboard');

    });

    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'getNotifications'])->name('notifications.index');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unreadCount');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/{id}/view', [NotificationController::class, 'view'])->name('notifications.view');
    Route::get('/notifications/json', [NotificationController::class, 'getNotifications'])->name('notifications.json');
// Teacher Profile Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/teacher/profile/create', [TeacherProfileController::class, 'create'])
        ->name('teacher.profile.create')
        ->middleware('restrict.role:teacher');
    Route::post('/teacher/profile/store', [TeacherProfileController::class, 'store'])
        ->name('teacher.profile.store')
        ->middleware('restrict.role:teacher');
});

Route::prefix('admin')->middleware('restrict.role:admin')->group(function () {
    Route::get('/adashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::delete('/user/{id}', [AdminController::class, 'deleteUser'])->name('deleteUser');
    Route::put('/users/{id}/update', [AdminController::class, 'update'])->name('updateUser');
    Route::post('/upload-photo', [AdminController::class, 'uploadPhoto'])->name('uploadPhoto');
});
require __DIR__.'/auth.php'; 