<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلب إعادة تعيين كلمة المرور</title>
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
        .session-status {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            background-color: #f0f9ff;
            border-left: 4px solid #00bcd4;
            color: #003366;
        }
        .text-gray-600 {
            color: #666;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2 class="text-2xl font-bold text-center mb-4 text-blue-900">إعادة تعيين كلمة المرور</h2>

        <p class="mb-6 text-sm text-gray-600">
            نسيت كلمة المرور؟ لا مشكلة! أدخل بريدك الإلكتروني وسنرسل لك رابطاً لإعادة تعيين كلمة المرور الخاصة بك.
        </p>

        <!-- Session Status -->
        @if (session('status'))
            <div class="session-status mb-6">
                {{ session('status') }}
            </div>
        @endif

        <!-- Password Reset Form -->
        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-6">
                <label for="email" class="block text-gray-700 font-medium mb-2">البريد الإلكتروني:</label>
                <input type="email" id="email" name="email" class="input-field" 
                    placeholder="أدخل بريدك الإلكتروني" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <p class="mt-2 text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-primary">
                إرسال رابط إعادة تعيين كلمة المرور
            </button>
        </form>
    </div>

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
        
        @if (session('status'))
            toastr.success('{{ session('status') }}');
        @endif
    </script>
</body>
</html>