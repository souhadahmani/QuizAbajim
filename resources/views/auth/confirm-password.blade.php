<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تأكيد كلمة المرور</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8fcff;
            font-family: 'Tajawal', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.15);
            border-top: 6px solid #00bcd4;
            width: 100%;
            max-width: 500px;
            text-align: right;
        }
        .btn-primary {
            background-color: #003366;
            color: white;
            padding: 14px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            width: 100%;
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #002244;
        }
        .input-field {
            border: 1px solid #ddd;
            padding: 12px 15px;
            border-radius: 8px;
            width: 100%;
            margin-top: 8px;
            font-size: 16px;
            transition: border 0.3s, box-shadow 0.3s;
        }
        .input-field:focus {
            border-color: #00bcd4;
            box-shadow: 0 0 0 2px rgba(0, 188, 212, 0.2);
            outline: none;
        }
        .text-gray-600 {
            color: #666;
        }
        .security-notice {
            background-color: #f0f9ff;
            border-right: 4px solid #00bcd4;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            color: #003366;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2 class="text-2xl font-bold text-center mb-4 text-blue-900">تأكيد كلمة المرور</h2>

        <div class="security-notice mb-6">
            <p class="text-sm">
                <i class="fas fa-shield-alt mr-2"></i> هذه منطقة آمنة في التطبيق. يرجى تأكيد كلمة المرور قبل المتابعة.
            </p>
        </div>

        <!-- Password Confirmation Form -->
        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <!-- Password Input -->
            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-medium mb-2">كلمة المرور:</label>
                <input type="password" id="password" name="password" class="input-field" 
                    placeholder="أدخل كلمة المرور الحالية" required autocomplete="current-password">
                @error('password')
                    <p class="mt-2 text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-primary">
                <i class="fas fa-check-circle mr-2"></i> تغيير كلمة العبور
            </button>
        </form>
    </div>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Toastr Notifications -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-left",
            "timeOut": "5000",
            "rtl": true
        };

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}');
            @endforeach
        @endif
    </script>
</body>
</html>