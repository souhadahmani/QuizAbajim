<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Récupérer tous les utilisateurs
        $users = User::all();

        // Nouvelles données BI
        $totalUsers = User::count(); // Nombre total d'utilisateurs inscrits
        $newUsersThisPeriod = User::where('created_at', '>=', now()->subDays(7))->count(); // Nouveaux inscrits sur la dernière semaine
        $signupRateData = $this->getSignupRateData(); // Données d'inscription mensuelle

        // Données existantes (pour compatibilité)
        $activeUsers = User::count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
                                 ->whereYear('created_at', now()->year)
                                 ->count();

        $signupData = $this->getSignupData();
        $activityData = $this->getActivityData();
        $yearlyUserData = $this->getYearlyUserData();

        return view('admin.admin', compact(
            'users', 'totalUsers', 'newUsersThisPeriod', 'signupRateData',
            'activeUsers', 'newUsersThisMonth', 'signupData', 'activityData','yearlyUserData'
        ));
    }

    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if (auth()->user()->avatar && Storage::exists(str_replace('/storage', 'public', parse_url(auth()->user()->avatar, PHP_URL_PATH)))) {
                Storage::delete(str_replace('/storage', 'public', parse_url(auth()->user()->avatar, PHP_URL_PATH)));
            }

            $path = $request->file('avatar')->store('public/avatars');
            $url = Storage::url($path);

            $user = auth()->user();
            $user->avatar = $url;
            $user->save();

            return redirect()->back()->with('success', 'تم تحديث الصورة الشخصية بنجاح!');
        }

        return redirect()->back()->with('error', 'فشل تحديث الصورة الشخصيaة!');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users')->with('success', 'تم حذف المستخدم بنجاح!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
    
        \Log::info('Update attempt for user', ['user_id' => $id, 'request_data' => $request->all()]);
    
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|confirmed|min:8', // Optional, confirmed if provided
        ]);
    
        $updateData = [
            'full_name' => $request->full_name,
            'email' => $request->email,
        ];
    
        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password); // Hash new password
        }
    
        $user->update($updateData);
    
        \Log::info('User updated successfully', ['user_id' => $id, 'updated_data' => $updateData]);
    
        return redirect()->route('users')->with('success', 'تم تحديث المستخدم بنجاح!');
    }

    private function getSignupData()
    {
        $months = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
        $signupCounts = User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $data = array_fill(1, 12, 0);
        foreach ($signupCounts as $month => $count) {
            $data[$month] = $count;
        }

        return [
            'labels' => array_slice($months, 0, now()->month),
            'data' => array_slice($data, 1, now()->month, true),
        ];
    }

    private function getActivityData()
    {
        $days = ['الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة'];
        $startDate = now()->subDays(4);
        $activityCounts = User::selectRaw('DAYNAME(created_at) as day, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('day')
            ->pluck('count', 'day')
            ->toArray();

        $data = array_fill(0, 5, 0);
        $labels = [];
        for ($i = 4; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayName = $date->dayName;
            $arabicDay = $days[array_search($dayName, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'])];
            $labels[] = $arabicDay;
            $data[4 - $i] = $activityCounts[$dayName] ?? 0;
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    private function getSignupRateData()
    {
        $months = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 
                   'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
        
        $currentYear = now()->year;
        $data = [];
        
        // Get data for all 12 months
        for ($month = 1; $month <= 12; $month++) {
            $count = User::whereYear('created_at', $currentYear)
                        ->whereMonth('created_at', $month)
                        ->count();
            $data[$month] = $count;
        }
        
        return [
            'labels' => $months, // All 12 months
            'data' => array_values($data), // Data for all months
            'total' => array_sum($data),
            'currentMonth' => now()->month
        ];
    }

    // Fixed yearly user data method
    private function getYearlyUserData()
    {
        $startYear = 2021;
        $endYear = now()->year;
        $years = range($startYear, $endYear);
        
        $data = [];
        
        foreach ($years as $year) {
            $count = User::whereYear('created_at', $year)->count();
            $data[] = $count; 
        }
        
        return [
            'labels' => $years, 
            'data' => $data,    
            'total' => array_sum($data),
            'years' => $years   
        ];
    }
}