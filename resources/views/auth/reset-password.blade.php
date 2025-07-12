<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعادة تعيين كلمة المرور</title>
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
        .text-cyan-600 {
            color: #00bcd4;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2 class="text-2xl font-bold text-center mb-4 text-blue-900">إعادة تعيين كلمة المرور</h2>
        <p class="text-center text-gray-500 mb-6">الرجاء إدخال بريدك الإلكتروني وكلمة المرور الجديدة</p>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Password Reset Token (Hidden) -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium">البريد الإلكتروني</label>
                <input id="email" class="input-field" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                @error('email')
                    <p class="mt-2 text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- New Password -->
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-medium">كلمة المرور الجديدة</label>
                <input id="password" class="input-field" type="password" name="password" required autocomplete="new-password">
                @error('password')
                    <p class="mt-2 text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-6">
                <label for="password_confirmation" class="block text-gray-700 font-medium">تأكيد كلمة المرور</label>
                <input id="password_confirmation" class="input-field" type="password" name="password_confirmation" required autocomplete="new-password">
                @error('password_confirmation')
                    <p class="mt-2 text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-primary">
                تغيير كلمة العبور
            </button>
        </form>
    </div>

    <!-- Include Toastr.js JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
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