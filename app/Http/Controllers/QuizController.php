<?php

namespace App\Http\Controllers;

use App\Notifications\NewQuizNotification;
use App\Models\User;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function store(Request $request)
    {
        // ... existing validation and quiz creation code ...
        
        $quiz = Quiz::create($request->all());
        
        // Notify all students
        $students = User::where('role_id', 'student')->get();
        foreach ($students as $student) {
            $student->notify(new NewQuizNotification($quiz));
        }
        
        return redirect()->route('quizzes.index')
            ->with('success', 'تم إنشاء الاختبار بنجاح');
    }
} 