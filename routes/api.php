<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Student\StudentQuizController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
    //Route::post('/student/ai-feedback', [StudentQuizController::class, 'getAiFeedback'])->name('student.ai.feedback');
});
Route::post('/proxy-gemini', function (Request $request) {
    $apiKey = env('GEMINI_API_KEY');
    if (!$apiKey) {
        return response()->json(['error' => 'API key not configured'], 500);
    }

    $response = Http::withHeaders(['Content-Type' => 'application/json'])
        ->post("https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key={$apiKey}", $request->all());
    return $response->json();
});