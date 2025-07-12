<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب جديد</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <link rel="shortcut icon" href="images/abajimLOGO.png" />

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include Toastr.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8fcff;
            font-family: 'Tajawal', sans-serif;
        }

        .form-box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.15);
            border-top: 6px solid #00bcd4;
            width: 100%;
        }

        .input-field {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            background: #f1faff;
            transition: 0.3s;
        }

        .input-field:focus {
            border-color: #00bcd4;
            background: white;
        }

        .btn-primary {
            background-color: #003366;
            color: white;
            padding: 14px;
            width: 100%;
            border-radius: 8px;
            font-size: 18px;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background-color: #002244;
            transform: scale(1.05);
        }

        .checkbox-label {
            font-size: 14px;
            color: #555;
            text-align: right;
        }

        .form-box {
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .image-section img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            object-fit: contain;
        }

        .navbar-custom {
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .logo-container {
            display: flex;
            align-items: center;
        }

        .logo img {
            max-height: 60px;
            width: auto;
            object-fit: contain;
        }

        .sign-up-btn, .login-btn {
            padding: 10px 20px;
            border: 2px solid #00bcd4;
            border-radius: 8px;
            color: #00bcd4;
            background-color: white;
            text-decoration: none;
            transition: 0.3s;
            font-weight: 500;
            margin-left: 10px;
        }

        .sign-up-btn:hover, .login-btn:hover {
            background-color: #00bcd4;
            color: white;
            text-decoration: none;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-col {
            flex: 1;
        }

        .register-container {
            display: flex;
            align-items: stretch;
            gap: 30px;
            max-width: 1200px;
            margin: 40px auto 0;
            padding-bottom: 40px;
        }

        .form-section, .image-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        label {
            text-align: right;
            display: block;
        }

        .checkbox-container {
            margin-top: 10px;
            text-align: right;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-label {
            font-size: 14px;
            color: #555;
            margin: 0;
        }

        .checkbox-label a {
            color: #003366;
            text-decoration: none;
        }

        .checkbox-label a:hover {
            text-decoration: underline;
        }

        @media (max-width: 1024px) {
            .register-container {
                flex-direction: column;
                padding: 20px;
                margin-top: 20px;
            }
            .form-box {
                padding: 20px;
            }
            .navbar-custom {
                padding: 10px 15px;
            }
            .logo img {
                max-height: 50px;
            }
            .sign-up-btn, .login-btn {
                padding: 8px 15px;
            }
            .form-row {
                flex-direction: column;
                gap: 15px;
            }
            .checkbox-container {
                text-align: right;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar-custom py-4 px-6">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center w-100">
                <div class="logo-container">
                    <div class="logo">
                        <img src="{{ asset('images/abajim.png') }}" alt="Abajim">
                    </div>
                </div>
                <div>
                    <a href="{{ route('login') }}" class="login-btn">تسجيل الدخول</a>
                    <a href="{{ route('register') }}" class="sign-up-btn">سجل مجانًا</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container min-h-screen flex items-center justify-center">
        <div class="register-container">
            <div class="image-section">
                <img src="{{ asset('images/kids.png') }}" alt="Kids Learning">
            </div>

            <div class="form-section">
                <div class="form-box">
                    <h2 class="text-2xl font-bold text-center mb-4 text-blue-900">إنشاء حساب جديد</h2>
                    <p class="text-gray-500 text-center mb-6">(اختر ولي أمر أو معلم)</p>

                    <form action="{{ route('register') }}" method="POST" novalidate>
                        @csrf

                        <div class="form-row">
                            <div class="form-col">
                                <label class="block text-gray-700">حدد نوع الحساب:</label>
                                <select name="role" class="input-field" required>
                                    <option value="">إختر</option>
                                    @foreach($role as $r)
                                        <option value="{{ $r->id }}" {{ old('role') == $r->id ? 'selected' : '' }}>
                                            @if($r->id == 3) الولي @else معلم @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-col">
                                <label class="block text-gray-700">الاسم الكامل:</label>
                                <input type="text" name="name" class="input-field" placeholder="الاسم الكامل" value="{{ old('name') }}" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-col">
                                <label class="block text-gray-700">البريد الإلكتروني:</label>
                                <input type="text" name="email" class="input-field" placeholder="البريد الإلكتروني" value="{{ old('email') }}" required>
                            </div>
                            <div class="form-col">
                                <label class="block text-gray-700">كلمة المرور:</label>
                                <input type="password" name="password" class="input-field" placeholder="********" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-col">
                                <label class="block text-gray-700">أعد كتابة كلمة المرور:</label>
                                <input type="password" name="password_confirmation" class="input-field" placeholder="********" required>
                                <div class="checkbox-container">
                                    <input type="checkbox" name="terms" {{ old('terms') ? 'checked' : '' }} required>
                                    <label class="checkbox-label">أوافق على <a href="#" class="text-blue-500">الشروط والقوانين</a></label>
                                </div>
                            </div>
                            <div class="form-col">
                                <!-- Empty column to maintain layout -->
                            </div>
                        </div>

                        <button type="submit" class="btn-primary">إنشاء حساب جديد</button>

                        <p class="text-center mt-3 text-gray-500">
                            لديك حساب بالفعل؟ <a href="{{ route('login') }}" class="text-blue-500">تسجيل الدخول</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Popper.js and Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>

    <!-- Include Toastr.js JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Configure Toastr and display validation errors -->
<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000",
    };

    // Check for validation errors and display them as toaster messages
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            toastr.error('{{ $error }}');
        @endforeach
    @endif

    // Display success message if registration is successful
    @if (session('success'))
        toastr.success('{{ session('success') }}');
    @endif

    // Client-side validation
    $('form').on('submit', function (e) {
        let name = $('input[name="name"]').val().trim();
        let email = $('input[name="email"]').val().trim();
        let password = $('input[name="password"]').val();
        let passwordConfirmation = $('input[name="password_confirmation"]').val();
        let role = $('select[name="role"]').val();
        let terms = $('input[name="terms"]').is(':checked');

        let hasError = false;

        if (!name) {
            toastr.error('الاسم الكامل مطلوب.');
            hasError = true;
        }

        if (!email) {
            toastr.error('البريد الإلكتروني مطلوب.');
            hasError = true;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            toastr.error('البريد الإلكتروني يجب أن يكون صالحًا.');
            hasError = true;
        }

        if (!password) {
            toastr.error('كلمة المرور مطلوبة.');
            hasError = true;
        } else if (password.length < 8) {
            toastr.error('كلمة المرور يجب أن تكون 8 أحرف على الأقل.');
            hasError = true;
        }

        if (password !== passwordConfirmation) {
            toastr.error('تأكيد كلمة المرور غير متطابق.');
            hasError = true;
        }

        if (!role) {
            toastr.error('نوع الحساب مطلوب.');
            hasError = true;
        }

        if (!terms) {
            toastr.error('يجب الموافقة على الشروط والقوانين.');
            hasError = true;
        }

        if (hasError) {
            e.preventDefault(); // Prevent form submission if there are errors
        }
    });
</script></body>
</html>