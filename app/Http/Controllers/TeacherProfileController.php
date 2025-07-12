<?php

namespace App\Http\Controllers;

use App\Models\SchoolLevel;
use App\Models\Materials;
use App\Models\UserLevel;
use App\Models\UserMatiere;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TeacherProfileController extends Controller
{
    public function create()
    {
        if (Auth::user()->materials()->exists()) {
            return redirect()->route('dashboard')->with('success', 'لقد تم تهيئة ملفك الشخصي بالفعل.');
        }
        $levels = SchoolLevel::all();
        $materials = Materials::all();
        return view('profile.create', compact('levels', 'materials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'level_id' => 'required|exists:school_levels,id',
            'matiere_ids' => 'required|array|min:1',
            'matiere_ids.*' => 'exists:materials,id',
        ], [
            'level_id.required' => 'يرجى اختيار مستوى التعليم.',
            'level_id.exists' => 'المستوى المختار غير صالح.',
            'matiere_ids.required' => 'يرجى اختيار مادة واحدة على الأقل.',
            'matiere_ids.min' => 'يرجى اختيار مادة واحدة على الأقل.',
            'matiere_ids.*.exists' => 'المادة المختارة غير صالحة.',
        ]);
    
        try {
            $user = Auth::user();
            // new adding method to delete 
            UserMatiere::where('teacher_id', $user->id)->delete();
   // dd($request->matiere_ids);
            // Sync the level (single value, so we use sync with an array of one item)
           // $user->levels()->sync([$request->level_id]);
           foreach ($request->matiere_ids as $matiere_id) {
                    UserMatiere::create([
                        'teacher_id' => $user->id,
                        'level_id' => $request->level_id,
                        'matiere_id' => $matiere_id, // Use individual matiere_id
                    ]);
                }
            // Sync the materials
            //$user->materials()->sync($request->matiere_ids);
    
            Log::info('Teacher profile setup completed', [
                'user_id' => $user->id,
                'level_id' => $request->level_id,
                'matiere_ids' => $request->matiere_ids,
            ]);
    
            return redirect()->route('dashboard')->with('success', 'تم تهيئة الملف الشخصي بنجاح! يمكنك الآن استخدام لوحة التحكم.');
        } catch (\Exception $e) {
            Log::error('Failed to save teacher profile', [
                'error' => $e->getMessage(),
                'input' => $request->all(),
            ]);
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء تهيئة الملف الشخصي: ' . $e->getMessage()]);
        }
    }
    public function getMaterialsByLevel(Request $request,$id)
    {
       
        $levelId = $id;

        if (!empty($levelId)) {
            $matieres = Materials::whereHas('section', function($query) use ($levelId) {
                $query->where('level_id', $levelId);
            })->get();
            return response()->json($matieres);
        }
        return response()->json([]);
    }

}