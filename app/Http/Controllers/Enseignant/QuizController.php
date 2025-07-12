<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Questions;
use App\Models\Questions_answers;
use App\Models\SchoolLevel;
use App\Models\Materials;
use App\Models\UserMatiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Spatie\PdfToText\Pdf;

class QuizController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        // Fetch quizzes for the authenticated teacher
        $quizzes = Quiz::where('creator_id', Auth::id())
            ->with(['level', 'materials'])
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Adjust pagination as needed

        return view('enseignant.quiz.quizzes', compact('quizzes'));
    }
    /**
     * Display the quiz creation form or the question-adding page.
     */
    public function getMaterialsByLevel(Request $request, $levelId)
    {
     

        // Fetch materials for the given level_id, filtered by teacher's assignments
        $teacher = Auth::user();
        //$teacherMatiere=UserMatiere::where('teacher_id',$teacher->id)->where('level_id',$levelId)->get();

        //dump( $matiereIds);
       // $materialslist=[];
    //     foreach($teacherMatiere as $teacherM){
    //     $materials = Materials::where('id', $teacherM->matiere_id)->get();
    //    // $materialslist.push($materials);
    //     $materialslist []=$materials;
    //     }
    $matiereIds = UserMatiere::where('teacher_id', $teacher->id)
    ->where('level_id', $levelId)
    ->pluck('matiere_id'); // get just the IDs
        $materials = Materials::whereIn('id', $matiereIds)->get();
       //dump( $materialslist );

        return response()->json($materials);
    }


    public function create(Request $request)
    {
        $teacher = Auth::user();
        $schoolLevels = $teacher->materials->first()->section->level;
        //dd($schoolLevels );
        $materials = $teacher->materials;

        if ( $materials->isEmpty()) {
            return redirect()->route('dashboard')->with('error_message', 'يرجى تحديد مستويات دراسية ومواد في إعدادات المعلم قبل إنشاء كويز.');
        }

        $quiz_id = $request->query('quiz_id');
        $quiz = null;
        $suggested_time_limit = null;
        $current_total_grade = 0;

        if ($quiz_id) {
            $quiz = Quiz::with([
                'questions' => function ($query) {
                    $query->with('answers')->orderBy('order', 'asc')->orderBy('id', 'asc');
                },
                'schoolLevel',
                'subject'
            ])->find($quiz_id);

            if (!$quiz) {
                return redirect()->route('quiz.create')->with('error_message', 'الكويز غير موجود. يرجى إنشاء كويز جديد.');
            }

            if ($quiz->creator_id !== Auth::id()) {
                return redirect()->route('quiz.create')->with('error_message', 'ليس لديك إذن لتعديل هذا الكويز.');
            }

            if ($quiz->is_finalized) {
                return redirect()->route('dashboard')->with('error_message', 'لا يمكن تعديل هذا الكويز لأنه تم إنهاؤه بالفعل.');
            }

            $quiz->time_limit = $quiz->time/60 ;

            $questionCount = $quiz->questions->count();
            if ($questionCount > 0) {
                $suggested_time_limit = $questionCount * 5;
            }

            $current_total_grade = $quiz->questions()->sum('grade');
        }

        return view('enseignant.quiz.add_edit', compact('quiz', 'suggested_time_limit', 'current_total_grade', 'schoolLevels', 'materials'));
    }

    public function store(Request $request)
    {
    
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:self_training,evaluation',
            'school_level_id' => 'required|exists:school_levels,id',
            'subject_id' => 'required|exists:materials,id',
            'total_mark' => 'required|integer|min:1|max:1000',
            'time_limit' => 'required|integer|min:1|max:180',
            'correct_answers' => 'required|integer|min:1|max:3',
            'pass_mark' => 'required|integer|min:0|max:100',
            'distribute_points' => 'required|in:evenly,manually',
            'status' => 'required|in:active,inactive',
            'certificate' => 'nullable|boolean',
            'difficulty_level' => 'required|in:easy,medium,hard',
        ]);

        try {
            $attempts = $validated['type'] === 'evaluation' ? 1 : 9999;

            $quiz = Quiz::create([
                'creator_id' => Auth::id(),
                'chapter_id' => null,
                'type' => $validated['type'],
                'school_level_id' => $validated['school_level_id'],
                'subject_id' => $validated['subject_id'], // Use validated value
                'title' => $validated['title'],
                'time' => $validated['time_limit']*60 ,
                'attempt' => $attempts,
                'pass_mark' => $validated['pass_mark'],
                'total_mark' => $validated['total_mark'],
                'status' => $validated['status'],
                'certificate' => $request->has('certificate') ? 1 : 0,
                'is_finalized' => false,
                'created_at' => now()->timestamp,
                'updated_at' => now()->timestamp,
                'difficulty_level' => $validated['difficulty_level'],
            ]);

            Session::put('correct_answers', $validated['correct_answers']);
            Session::put('distribute_points', $validated['distribute_points']);

            return redirect()->route('quiz.create', ['quiz_id' => $quiz->id])
                ->with('success_message', 'تم إنشاء الكويز بنجاح');
        } catch (\Exception $e) {
            Log::error('Error creating quiz: ' . $e->getMessage());
            return redirect()->back()->with('error_message', 'حدث خطأ أثناء إنشاء الكويز. يرجى المحاولة مرة أخرى.');
        }
    }
    /**
     * Save a question for an existing quiz directly to the database.
     */
    public function saveQuestion(Request $request, $quiz_id)
    {
        //dd($request->all());
       // dd($request->all());
        $quiz = Quiz::findOrFail($quiz_id);

        if ($quiz->creator_id !== Auth::id()) {
            return redirect()->route('quiz.create')->with('error_message', 'ليس لديك إذن لتعديل هذا الكويز.');
        }

        if ($quiz->is_finalized) {
            return redirect()->route('dashboard')->with('error_message', 'لا يمكن تعديل هذا الكويز لأنه تم إنهاؤه بالفعل.');
        }

        $distributePoints = Session::get('distribute_points', 'manually');

        // Define validation rules
        $rules = [
            'quiz_id' => 'required|exists:quizzes,id',
            'questions' => 'required|array',
            'questions.*.question_text' => 'required|string',
            'questions.*.type' => 'required|in:multiple,descriptive',
            'questions.*.grade' => 'required|integer|min:1|max:100',
            'questions.*.image' => 'nullable|image|max:2048',
            'questions.*.video' => 'nullable|mimes:mp4,mov,avi|max:10240',
            'questions.*.explanation' => 'nullable|string',
        ];
        foreach ($request->questions as $index => $question) {
          
            if ($question['type'] === 'multiple') {
                    
                $rules["questions.$index.answers"] = 'required|array|min:2';
                $rules["questions.$index.answers.*"] = 'required_if:questions.*.type,multiple|string';
                $rules["questions.$index.correct_answer"] = 'required|integer|min:0';
            } else {
                $rules["questions.$index.correct_answer"] = 'required|in:0,1';
            }
        }
        $request->questions = array_map(function ($question) use ($request) {
            if (count($request->questions) === 1) {
          
                if ($question['type'] === 'multiple') {
                    $question['answers'] = array_filter($question['answers'], function ($key) {
                        

                        return $key >= 2;
                    }, ARRAY_FILTER_USE_KEY);
                }
            }
            return $question;
        }, $request->questions);
      
        $validated = $request->validate($rules);

        // Calculate current total grade and remaining mark
        $existingQuestions = $quiz->questions;
        $currentTotalGrade = $existingQuestions->sum('grade');
        $remainingMark = $quiz->total_mark - $currentTotalGrade;

        // For "manually" distribution, validate against remaining mark
        if ($distributePoints === 'manually') {
            if ($remainingMark <= 0) {
                return redirect()->back()->with('error_message', 'لقد تم الوصول إلى الحد الأقصى للعلامة الكلية للكويز (' . $quiz->total_mark . '). لا يمكن إضافة المزيد من الأسئلة.');
            }

            foreach ($request->questions as $index => $questionData) {
                $grade = $questionData['grade'];
                if ($grade > $remainingMark) {
                    return redirect()->back()->with('error_message', 'درجة السؤال (' . $grade . ') تتجاوز العلامة الكلية المتبقية للكويز. العلامة المتبقية: ' . $remainingMark . ' من ' . $quiz->total_mark);
                }

                $answers = isset($questionData['answers']) ? array_filter($questionData['answers'], fn($answer) => !empty(trim($answer))) : [];
                $answers = array_values($answers);

                if ($questionData['type'] === 'multiple') {
                    if (count($answers) < 2) {
                        return redirect()->back()->withErrors(['questions.' . $index . '.answers' => 'يجب إدخال إجابتين على الأقل لسؤال من نوع اختيار متعدد.']);
                    }

                    $correctAnswerIndex = $questionData['correct_answer'];
                    if (!isset($answers[$correctAnswerIndex])) {
                        return redirect()->back()->withErrors(['questions.' . $index . '.correct_answer' => 'الإجابة الصحيحة غير صالحة.']);
                    }
                } else {
                    $correctAnswerIndex = $questionData['correct_answer'];
                    if (!in_array($correctAnswerIndex, [0, 1])) {
                        return redirect()->back()->withErrors(['questions.' . $index . '.correct_answer' => 'الإجابة الصحيحة يجب أن تكون 0 أو 1 لسؤال من نوع صح/خطأ.']);
                    }
                    $answers = ['صح', 'خطأ'];
                }

                $imagePath = null;
                $videoPath = null;
                if ($request->hasFile("questions.$index.image")) {
                    $imagePath = $request->file("questions.$index.image")->store('questions/images', 'public');
                }
                if ($request->hasFile("questions.$index.video")) {
                    $videoPath = $request->file("questions.$index.video")->store('questions/videos', 'public');
                }

                try {
                    $maxOrder = Questions::where('quiz_id', $quiz->id)->max('order') ?? 0;
                    $question = Questions::create([
                        'quiz_id' => $quiz->id,
                        'creator_id' => Auth::id(),
                        'question' => $questionData['question_text'],
                        'grade' => $grade,
                        'type' => $questionData['type'],
                        'image' => $imagePath,
                        'video' => $videoPath,
                        'explanation' => $questionData['explanation'],
                        'order' => $maxOrder + 1,
                        'created_at' => now()->timestamp,
                        'updated_at' => now()->timestamp,
                    ]);

                    if ($questionData['type'] === 'multiple') {
                        foreach ($answers as $idx => $answer) {
                            Questions_answers::create([
                                'question_id' => $question->id,
                                'creator_id' => Auth::id(),
                                'answer' => $answer,
                                'image' => null,
                                'correct' => $idx == $correctAnswerIndex ? 1 : 0,
                                'created_at' => now()->timestamp,
                                'updated_at' => now()->timestamp,
                            ]);
                        }
                    } else {
                        Questions_answers::create([
                            'question_id' => $question->id,
                            'creator_id' => Auth::id(),
                            'answer' => 'صح',
                            'image' => null,
                            'correct' => $correctAnswerIndex == 0 ? 1 : 0,
                            'created_at' => now()->timestamp,
                            'updated_at' => now()->timestamp,
                        ]);
                        Questions_answers::create([
                            'question_id' => $question->id,
                            'creator_id' => Auth::id(),
                            'answer' => 'خطأ',
                            'image' => null,
                            'correct' => $correctAnswerIndex == 1 ? 1 : 0,
                            'created_at' => now()->timestamp,
                            'updated_at' => now()->timestamp,
                        ]);
                    }

                    $remainingMark -= $grade;

                } catch (\Exception $e) {
                    Log::error('Error saving question or answers: ' . $e->getMessage());
                    return redirect()->back()->with('error_message', 'حدث خطأ أثناء حفظ السؤال. يرجى المحاولة مرة أخرى.');
                }
            }
        } else {
            // For "evenly" distribution, calculate the new grade based on total questions (existing + new)
            $existingQuestionCount = $existingQuestions->count();
            $newQuestionCount = count($request->questions);
            $totalQuestionCount = $existingQuestionCount + $newQuestionCount;

            if ($totalQuestionCount === 0) {
                return redirect()->back()->with('error_message', 'لا يمكن توزيع الدرجات بدون أسئلة.');
            }

            // Calculate the new grade per question
            $newGradePerQuestion = max(1, floor($quiz->total_mark / $totalQuestionCount));

            // Adjust the total to not exceed total_mark
            $totalDistributed = $newGradePerQuestion * $totalQuestionCount;
            if ($totalDistributed > $quiz->total_mark) {
                $newGradePerQuestion = max(1, floor($quiz->total_mark / $totalQuestionCount));
            }

            // Start a transaction to ensure consistency
            DB::beginTransaction();
            try {
                // Mettre à jour les notes des questions existantes
                foreach ($existingQuestions as $existingQuestion) {
                    $existingQuestion->grade = $newGradePerQuestion;
                    $existingQuestion->save();
                }
            
                foreach ($request->questions as $index => $questionData) {
                    // Nettoyage des réponses
                    $answers = isset($questionData['answers']) ? array_filter($questionData['answers'], fn($a) => !empty(trim($a))) : [];
                    $answers = array_values($answers);
            
                    // Validation spécifique selon le type de question
                    $correctAnswerIndex = $questionData['correct_answer'] ?? null;
         
                    if ($questionData['type'] === 'multiple') {
                        if (count($answers) < 2) {
                            DB::rollBack();
                            return redirect()->back()->withErrors([
                                "questions.$index.answers" => 'يجب إدخال إجابتين على الأقل لسؤال من نوع اختيار متعدد.'
                            ]);
                        }
                        if (!$answers[$correctAnswerIndex]) {
                            DB::rollBack();
                            return redirect()->back()->withErrors([
                                "questions.$index.correct_answer" => 'الإجابة الصحيحة غير صالحة.'
                            ]);
                        }
                    } else {
                        if (!in_array($correctAnswerIndex, [0, 1])) {
                            DB::rollBack();
                            return redirect()->back()->withErrors([
                                "questions.$index.correct_answer" => 'الإجابة الصحيحة يجب أن تكون 0 أو 1 لسؤال من نوع صح/خطأ.'
                            ]);
                        }
                        $answers = ['صح', 'خطأ']; // réponses fixes pour les Vrai/Faux
                    }
            
                    // Upload image/vidéo
                    $imagePath = $request->hasFile("questions.$index.image")
                        ? $request->file("questions.$index.image")->store('questions/images', 'public')
                        : null;
            
                    $videoPath = $request->hasFile("questions.$index.video")
                        ? $request->file("questions.$index.video")->store('questions/videos', 'public')
                        : null;
            
                    // Calcul de l'ordre
                    $maxOrder = Questions::where('quiz_id', $quiz->id)->max('order') ?? 0;
            
                    // Création de la question
                    try {
                        $question = Questions::create([
                            'quiz_id' => $quiz->id,
                            'creator_id' => Auth::id(),
                            'question' => $questionData['question_text'],
                            'grade' => $newGradePerQuestion,
                            'type' => $questionData['type'],
                            'image' => $imagePath,
                            'video' => $videoPath,
                            'explanation' => $questionData['explanation'] ?? null,
                            'order' => $maxOrder + 1,
                            'created_at' => now()->timestamp,
                            'updated_at' => now()->timestamp,
                        ]);
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error('Error saving question: ' . $e->getMessage());
                        return redirect()->back()->with('error_message', 'حدث خطأ أثناء حفظ السؤال.');
                    }
            
                    // Création des réponses
                    try {
                        if ($questionData['type'] === 'multiple') {
                          if(count($request->questions) > 1){
                            $correctAnswer=$correctAnswerIndex-2 ;
                        }else{
                            $correctAnswer=$correctAnswerIndex;
                        }
            
                            foreach ($answers as $idx => $answer) {
                            if ($idx <= 3) {
                                Questions_answers::create([
                                    'question_id' => $question->id,
                                    'creator_id' => Auth::id(),
                                    'answer' => $answer,
                                    'image' => null,
                                    'correct' => ($idx == $correctAnswer) ? 1 : 0,
                                    'created_at' => now()->timestamp,
                                    'updated_at' => now()->timestamp,
                                ]);
                            }
                            }
                        } else {
                            Questions_answers::create([
                                'question_id' => $question->id,
                                'creator_id' => Auth::id(),
                                'answer' => 'صح',
                                'image' => null,
                                'correct' => ($correctAnswerIndex == 0) ? 1 : 0,
                                'created_at' => now()->timestamp,
                                'updated_at' => now()->timestamp,
                            ]);
                            Questions_answers::create([
                                'question_id' => $question->id,
                                'creator_id' => Auth::id(),
                                'answer' => 'خطأ',
                                'image' => null,
                                'correct' => ($correctAnswerIndex == 1) ? 1 : 0,
                                'created_at' => now()->timestamp,
                                'updated_at' => now()->timestamp,
                            ]);
                        }
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error('Error saving answers: ' . $e->getMessage());
                        return redirect()->back()->with('error_message', 'حدث خطأ أثناء حفظ الإجابات.');
                    }
                }
         
                // Ajustement du reste
                $totalAssigned = $newGradePerQuestion * $totalQuestionCount;
                $remainder = $quiz->total_mark - $totalAssigned;
                if ($remainder > 0) {
                    $lastQuestion = Questions::where('quiz_id', $quiz->id)->orderBy('order', 'desc')->first();
                    if ($lastQuestion) {
                        $lastQuestion->grade += $remainder;
                        $lastQuestion->save();
                    }
                }
            
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('General error saving quiz: ' . $e->getMessage());
                return redirect()->back()->with('error_message', 'حدث خطأ أثناء حفظ السؤال. يرجى المحاولة مرة أخرى.');
            }
            
        }

        return redirect()->route('quiz.create', ['quiz_id' => $quiz->id])
            ->with('success_message', 'تم إضافة السؤال بنجاح.');
    }

     //Preview the quiz
    public function preview($quiz_id)
    {
        $quiz = Quiz::with('questions.answers')->findOrFail($quiz_id);

        if ($quiz->creator_id !== Auth::id()) {
            return redirect()->route('quiz.create')->with('error_message', 'ليس لديك إذن لتعديل هذا الكويز.');
        }

        if ($quiz->questions->isEmpty()) {
            return redirect()->route('quiz.create', ['quiz_id' => $quiz->id])
                ->with('error_message', 'لا توجد أسئلة لعرضها. يرجى إضافة سؤال واحد على الأقل.');
        }

        return view('enseignant.quiz.preview', compact('quiz'));
    }

    /**
     * Update an existing quiz.
     */
    public function update(Request $request, $quiz_id)
    {
        $quiz = Quiz::findOrFail($quiz_id);
    
        if ($quiz->creator_id !== Auth::id()) {
            return response()->json([
                'error_message' => 'ليس لديك إذن لتعديل هذا الكويز.'
            ], 403);
        }
    
        if ($quiz->is_finalized) {
            return response()->json([
                'error_message' => 'لا يمكن تعديل هذا الكويز لأنه تم إنهاؤه بالفعل.'
            ], 403);
        }
    
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:self_training,evaluation',
            'school_level_id' => 'required|exists:school_levels,id',
            'subject_id' => 'required|exists:materials,id',
            'total_mark' => 'required|integer|min:1|max:1000',
            'time_limit' => 'required|integer|min:1|max:180',
            'correct_answers' => 'required|integer|min:1|max:3',
            'pass_mark' => 'required|integer|min:0|max:100',
            'distribute_points' => 'required|in:evenly,manually',
            'status' => 'required|in:active,inactive',
            'certificate' => 'nullable|boolean',
            'difficulty_level' => 'required|in:easy,medium,hard',
        ]);
    
        $totalMark = $quiz->questions()->sum('grade');
        if ($totalMark > $validated['total_mark']) {
            return response()->json([
                'error_message' => 'العلامة الكلية الجديدة أقل من مجموع درجات الأسئلة الحالية.'
            ], 422);
        }
    
        try {
            $attempts = $validated['type'] === 'evaluation' ? 1 : 9999;
            $quiz->update([
                'type' => $validated['type'],
                'school_level_id' => $validated['school_level_id'], // Use validated value
                'subject_id' => $validated['subject_id'], // Use validated value
                'title' => $validated['title'],
                'time' => $validated['time_limit'] * 60,
                'attempt' => $attempts,
                'total_mark' => $validated['total_mark'],
                'pass_mark' => $validated['pass_mark'],
                'status' => $validated['status'],
                'certificate' => $request->has('certificate') ? 1 : 0,
                'updated_at' => now()->timestamp,
                'difficulty_level' => $validated['difficulty_level'],
            ]);
    
            Session::put('correct_answers', $validated['correct_answers']);
            Session::put('distribute_points', $validated['distribute_points']);
    
            return redirect()->route('quiz.create', ['quiz_id' => $quiz->id])
    ->with('success_message', 'تم تحديث إعدادات الكويز بنجاح');
        } catch (\Exception $e) {
            Log::error('Error updating quiz: ' . $e->getMessage());
            return response()->json([
                'error_message' => 'حدث خطأ أثناء تحديث الكويز. يرجى المحاولة مرة أخرى.'
            ], 500);
        }
    }    /**
     * Show the form to edit a specific question.
     */
    public function editQuestion($quiz_id, $question_id)
    {
        Log::info('editQuestion called with quiz_id: ' . $quiz_id . ', question_id: ' . $question_id);
        Log::info('Authenticated user ID: ' . (Auth::check() ? Auth::id() : 'Not authenticated'));
    
        $quiz = Quiz::findOrFail($quiz_id);
        $question = Questions::with('answers')->findOrFail($question_id);
        if ($question->quiz_id !== $quiz->id) {
            Log::error('Question does not belong to this quiz');
            if (request()->expectsJson()) {
                return response()->json(['error_message' => 'السؤال لا ينتمي إلى هذا الكويز.'], 403);
            }
            return redirect()->route('quiz.create', ['quiz_id' => $quiz_id])
                ->with('error', 'السؤال لا ينتمي إلى هذا الكويز.');
        }
    
        if ($quiz->creator_id !== Auth::id()) {
            Log::error('User does not have permission to edit this quiz');
            if (request()->expectsJson()) {
                return response()->json(['error_message' => 'ليس لديك إذن لتعديل هذا الكويز.'], 403);
            }
            return redirect()->route('quiz.create', ['quiz_id' => $quiz_id])
                ->with('error', 'ليس لديك إذن لتعديل هذا الكويز.');
        }
    
        if (request()->expectsJson()) {
            return response()->json([
                'question' => $question,
                'answers' => $question->answers,
            ]);
        }
    
        return redirect()->route('quiz.create', ['quiz_id' => $quiz_id])
            ->with('error', 'هذا الطلب يتطلب استدعاء عبر AJAX.');
    }    /**
     * Update a specific question.
     */
    public function updateQuestion(Request $request, $quiz_id, $question_id)
    {
        Log::info('updateQuestion called with quiz_id: ' . $quiz_id . ', question_id: ' . $question_id);
        Log::info('Request data:', $request->all());
        $quiz = Quiz::findOrFail($quiz_id);
        $question = Questions::findOrFail($question_id);
    
        if ($question->quiz_id !== $quiz->id) {
            Log::error('Question does not belong to this quiz');
            return response()->json([
                'error_message' => 'السؤال لا ينتمي إلى هذا الكويز.'
            ], 403);
        }

        if ($quiz->creator_id !== Auth::id()) {
            Log::error('User does not have permission to edit this quiz');
            return response()->json([
                'error_message' => 'ليس لديك إذن لتعديل هذا الكويز.'
            ], 403);
        }

        // Validate the incoming request
        $validated = $request->validate([
            'question_text' => 'required|string',
            'type' => 'required|in:multiple,descriptive',
            'grade' => 'required|integer|min:1',
            'image' => 'nullable|image|max:2048',
            'video' => 'nullable|mimes:mp4,mov,avi|max:10240',
            'answers' => 'required|array|min:2',
            'answers.*' => 'required|string',
            'correct_answer' => 'required|integer',
            'explanation' => 'nullable|string',
        ]);
        
        Log::info('Validated data:', $validated);

        try {
  
            // Update question details
            $question->question = $validated['question_text'];
            $question->type = $validated['type'];
            $question->grade = $validated['grade'];
            $question->explanation = $validated['explanation'] ?? null;

            // Handle image upload
            if ($request->hasFile('image')) {
                if ($question->image) {
                    Storage::disk('public')->delete($question->image);
                }
                $imagePath = $request->file('image')->store('questions/images', 'public');
                $question->image = $imagePath;
            }

            // Handle video upload
            if ($request->hasFile('video')) {
                if ($question->video) {
                    Storage::disk('public')->delete($question->video);
                }
                $videoPath = $request->file('video')->store('questions/videos', 'public');
                $question->video = $videoPath;
            }

            $question->updated_at = now()->timestamp;
            $question->save();

            // Delete existing answers
            $question->answers()->delete();

            // Handle answers based on question type
            $answers = array_filter($validated['answers'], fn($answer) => !empty(trim($answer)));
            $answers = array_values($answers);
            if ($validated['type'] === 'multiple') {
                $answers = array_filter($validated['answers'], fn($answer) => !empty(trim($answer)));
                $answers = array_values($answers);
                $correctAnswerIndex = $validated['correct_answer'];
            
                if (count($answers) < 2) {
                    return response()->json([
                        'errors' => ['answers' => 'يجب إدخال إجابتين على الأقل لسؤال من نوع اختيار متعدد.']
                    ], 422);
                }
            
                if (!isset($answers[$correctAnswerIndex])) {
                    return response()->json([
                        'errors' => ['correct_answer' => 'الإجابة الصحيحة غير صالحة.']
                    ], 422);
                }
            
                foreach ($answers as $index => $answerText) {
                    Log::info("Saving answer for multiple-choice question {$question->id}: Answer: {$answerText}, Correct: " . ($index == $correctAnswerIndex ? 1 : 0));
                    Questions_answers::create([
                        'question_id' => $question->id,
                        'creator_id' => Auth::id(),
                        'answer' => $answerText,
                        'image' => null,
                        'correct' => $index == $correctAnswerIndex ? 1 : 0,
                        'created_at' => now()->timestamp,
                        'updated_at' => now()->timestamp,
                    ]);
                }
            }
            
            else {
                if (count($answers) !== 2) {
                    Log::error('Exactly 2 answers are required for true/false question');
                    return response()->json([
                        'errors' => ['answers' => 'يجب إدخال إجابتين بالضبط لسؤال من نوع صح/خطأ.']
                    ], 422);
                }

                if (!in_array($correctAnswerIndex, [0, 1])) {
                    Log::error('Invalid correct answer index for true/false question');
                    return response()->json([
                        'errors' => ['correct_answer' => 'الإجابة الصحيحة يجب أن تكون 0 أو 1 لسؤال من نوع صح/خطأ.']
                    ], 422);
                }

                Log::info("Saving first answer for true/false question {$question->id}: Answer: {$answers[0]}, Correct: " . ($correctAnswerIndex == 0 ? 1 : 0));
                Questions_answers::create([
                    'question_id' => $question->id,
                    'creator_id' => Auth::id(),
                    'answer' => $answers[0],
                    'image' => null,
                    'correct' => $correctAnswerIndex == 0 ? 1 : 0,
                    'created_at' => now()->timestamp,
                    'updated_at' => now()->timestamp,
                ]);

                Log::info("Saving second answer for true/false question {$question->id}: Answer: {$answers[1]}, Correct: " . ($correctAnswerIndex == 1 ? 1 : 0));
                Questions_answers::create([
                    'question_id' => $question->id,
                    'creator_id' => Auth::id(),
                    'answer' => $answers[1],
                    'image' => null,
                    'correct' => $correctAnswerIndex == 1 ? 1 : 0,
                    'created_at' => now()->timestamp,
                    'updated_at' => now()->timestamp,
                ]);
            }

            Log::info('Question updated successfully: ' . $question->id);

            Log::info('Returning success response');
            
            return redirect()->route('quiz.create', ['quiz_id' => $quiz->id])
                ->with('success_message', 'تم تعديل الأسئلة بنجاح');
         
        } catch (\Exception $e) {
            Log::error('Error updating question: ' . $e->getMessage());
            return response()->json([
                'error_message' => 'حدث خطأ أثناء تعديل السؤال. يرجى المحاولة مرة أخرى.'
            ], 500);
        }
    }

    /**
     * Delete a specific question.
     */
    public function deleteQuestion($quiz_id, $question_id)
    {
        $quiz = Quiz::findOrFail($quiz_id);
        $question = Questions::findOrFail($question_id);

        if ($question->quiz_id !== $quiz->id) {
            return response()->json([
                'error_message' => 'السؤال لا ينتمي إلى هذا الكويز.'
            ], 403);
        }

        if ($quiz->creator_id !== Auth::id()) {
            return response()->json([
                'error_message' => 'ليس لديك إذن لتعديل هذا الكويز.'
            ], 403);
        }

        try {
            // Delete associated media
            if ($question->image) {
                Storage::disk('public')->delete($question->image);
            }
            if ($question->video) {
                Storage::disk('public')->delete($question->video);
            }

            // Delete answers
            $question->answers()->delete();

            // Delete the question
            $question->delete();

            return response()->json([
                'success_message' => 'تم حذف السؤال بنجاح!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting question: ' . $e->getMessage());
            return response()->json([
                'error_message' => 'حدث خطأ أثناء حذف السؤال. يرجى المحاولة مرة أخرى.'
            ], 500);
        }
    }



//pdf generator with AI : 
public function generateFromPDF(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:pdf|max:50000',
    ]);

    $file = $request->file('file');
    $filePath = $file->store('pdfs');

    try {
        // Step 1: Extract text from the PDF
        $pdfPath = storage_path('app/' . $filePath);
        $pdf = (new Pdf('C:\poppler\poppler-24.08.0\Library\bin\pdftotext.exe'));
        $text = $pdf->setPdf($pdfPath)->text();

        if (empty(trim($text))) {
            \Log::warning('PDF contains no extractable text', ['file_path' => $filePath]);
            return response()->json([
                'error' => 'الملف PDF فارغ أو لا يحتوي على نص يمكن قراءته. يرجى تحميل ملف PDF يحتوي على نص.'
            ], 400);
        }



        // ✂️ Limiter la taille du texte envoyé à Gemini
        $text = mb_substr($text, 0, 2000);

        // Step 2: Prepare Gemini prompt
        $prompt = <<<PROMPT
        أنشئ بالضبط 5 أسئلة اختيار من متعدد بناءً على النص التالي.
         كل سؤال يحتوي على 4 اختيارات (أ، ب، ج، د) وإجابة صحيحة واحدة. استخدم هذا التنسيق دون أي إضافات أو تفسيرات:
        
        1. [نص السؤال]  
        أ) [الخيار الأول]  
        ب) [الخيار الثاني]  
        ج) [الخيار الثالث]  
        د) [الخيار الرابع]  
        الإجابة الصحيحة: [أ، ب، ج، أو د]
        
        ... كرر هذا التنسيق بدقة لـ 5 أسئلة.
        
        --- بداية النص ---
        $text
        --- نهاية النص ---
        PROMPT;
        

        // Step 3: Send prompt to Gemini
        $apiKey = env('GEMINI_API_KEY');
        $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey;

        \Log::info('Prompt envoyé à Gemini', ['prompt' => $prompt]);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($endpoint, [
            'contents' => [[
                'parts' => [['text' => $prompt]],
            ]],
        ]);

        if ($response->failed()) {
            \Log::error('Gemini API request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return response()->json([
                'error' => 'فشل في إنشاء الكويز من الملف: ' . $response->body()
            ], 500);
        }
        
        $quizData = $response->json();
        $generatedText = $quizData['candidates'][0]['content']['parts'][0]['text'] ?? null;

        \Log::info('Gemini API raw response:', ['generated_text' => $generatedText]);
        if (!$generatedText) {
            return response()->json([
                'error' => 'لم يتم العثور على أسئلة في الاستجابة من Gemini'
            ], 500);
        }

        // Step 4: Parse the generated text
        $questions = [];
        $questionBlocks = explode("\n\n", trim($generatedText));
        foreach ($questionBlocks as $block) {
            $lines = explode("\n", trim($block));
            if (count($lines) < 6) {
                \Log::warning('Skipping malformed question block:', ['block' => $block]);
                continue;
            }

            $questionText = trim(preg_replace('/^\d+\.\s*/', '', $lines[0]));
            $options = [
                trim(str_replace('أ) ', '', $lines[1])),
                trim(str_replace('ب) ', '', $lines[2])),
                trim(str_replace('ج) ', '', $lines[3])),
                trim(str_replace('د) ', '', $lines[4])),
            ];
            $correctAnswerLine = trim(str_replace('الإجابة الصحيحة: ', '', $lines[5]));
            $correctAnswer = match (true) {
                str_contains($correctAnswerLine, 'أ') => 0,
                str_contains($correctAnswerLine, 'ب') => 1,
                str_contains($correctAnswerLine, 'ج') => 2,
                str_contains($correctAnswerLine, 'د') => 3,
                default => 0,
            };
            //alert($questionText);
            $questions[] = [
                'question_text' => $questionText,
                'question_type' => 'multiple_choice',
                'options' => $options,
                'correct_answer' => $correctAnswer,
            ];
           
        }

        if (empty($questions)) {
            return response()->json([
                'error' => 'لم يتم العثور على أسئلة صالحة في الاستجابة من Gemini'
            ], 500);
        }
        

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء الأسئلة بنجاح من ملف PDF',
            'questions' => $questions
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Gemini API error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
        ]);
        return response()->json([
            'error' => 'حدث خطأ أثناء الاتصال بـ Gemini: ' . $e->getMessage()
        ], 500);
    } finally {
        \Illuminate\Support\Facades\Storage::delete($filePath);
    }
}

public function destroy($id)
{
    $quiz = Quiz::findOrFail($id);
    $quiz->delete();

    return response()->json(['message' => 'Quiz deleted successfully']);
}

public function updateStatus(Request $request, $id)
{
    $quiz = Quiz::findOrFail($id);
    $quiz->status = $request->status;
    $quiz->save();

    return response()->json(['message' => 'Quiz status updated successfully']);
}

}