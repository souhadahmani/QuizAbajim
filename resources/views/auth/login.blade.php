<!DOCTYPE html>
<html lang="ar" dir="rtl"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>

    {{-- Laravel & Bootstrap CSS --}}
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <link rel="shortcut icon" href="{{ asset('images/abajimLOGO.png') }}" />

    <!-- Include Toastr.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <style>
        /* 🌟 Global Styles */
        body {
            background-color: #f8fcff;
            font-family: 'Tajawal', sans-serif;
        }

        /* 🏆 Form Box Styling */
        .form-box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.15);
            border-top: 6px solid #00bcd4;
            width: 100%;
            max-width: 450px;
        }

        /* 🎨 Input Fields */
        .input-field {
            width: 100%;
            padding: 14px;
            border: 2px solid #ddd;
            border-radius: 8px;
            background: #f1faff;
            transition: 0.3s;
        }

        .input-field:focus {
            border-color: #00bcd4;
            background: white;
        }

        /* 🚀 Buttons */
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

        /* 🖼️ Image Section */
        .image-section img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
        }

        /* 🔵 Navbar Styling */
        .navbar-custom {
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* 🔵 Logo Fix */
        .logo-container {
            display: flex;
            align-items: center;
        }

        .logo img {
            max-height: 60px;
            width: auto;
            object-fit: contain;
        }

        /* Sign-Up Button Styling */
        .sign-up-btn {
            padding: 10px 20px;
            border: 2px solid #00bcd4;
            border-radius: 8px;
            color: #00bcd4;
            background-color: white;
            text-decoration: none;
            transition: 0.3s;
            font-weight: 500;
        }

        .sign-up-btn:hover {
            background-color: #00bcd4;
            color: white;
            text-decoration: none;
        }

        /* Error message styling */
        .error-message {
            font-size: 14px;
            margin-top: 5px;
        }

        /* 📱 Mobile Responsiveness */
        @media (max-width: 1024px) {
            .image-section {
                margin-top: 20px;
            }
            .form-box {
                padding: 15px;
            }
            .navbar-custom {
                padding: 10px 15px;
            }
            .logo img {
                max-height: 50px;
            }
            .sign-up-btn {
                padding: 8px 15px;
            }
            .mb-4 {
                margin-bottom: 1rem; /* Maintain margin-bottom from Tailwind or your custom spacing */
            }

            .flex {
                display: flex;
                align-items: center; /* Keep vertical alignment */
            }

            .justify-end {
                justify-content: flex-end; /* Push content to the right (RTL-compatible) */
            }

            input[type="checkbox"] {
                margin-right: 0.5rem; /* Space between checkbox and label (RTL equivalent of mr-2) */
                cursor: pointer;
            }

            .checkbox-label {
                font-size: 14px;
                color: #555;
                margin: 0; /* Remove any default margins */
                direction: rtl; /* Ensure RTL for Arabic text */
            }

            /* Ensure the form-box doesn’t override right alignment */
            .form-box {
                background: white;
                padding: 30px;
                border-radius: 12px;
                box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.15);
                border-top: 6px solid #00bcd4;
                width: 100%;
                max-width: 450px;
                text-align: right; /* Add right alignment for RTL, but allow specific elements to override */
            }
        }        
    </style>
</head>
<body>

    {{-- 🟦 Navbar --}}
    <nav class="navbar-custom py-4 px-6">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center w-100">
                <div class="logo-container">
                    <div class="logo">
                        <img src="{{ asset('images/abajim.png') }}" alt="Abajim">
                    </div>
                </div>
                <div>
                    <a href="{{ route('register') }}" class="sign-up-btn">سجل مجانًا</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- 🌟 Login Section --}}
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="row w-100">
            
            {{-- 📝 Left Side: Login Form --}}
            <div class="col-md-6 d-flex justify-content-center">
                <div class="form-box">
                    <h2 class="text-2xl font-bold text-center mb-4 text-blue-900">تسجيل الدخول</h2>

                    <form action="{{ route('login') }}" method="POST" novalidate>
                        @csrf

                        {{-- 📧 Email / Phone --}}
                        <div class="mb-4 text-right">
                            <label class="block text-gray-700">البريد الإلكتروني أو الهاتف:</label>
                            <input type="text" name="email" class="input-field" placeholder="أدخل البريد الإلكتروني أو رقم الهاتف" value="{{ old('email') }}" required>
                            <div class="error-message text-danger" id="email-error"></div>
                        </div>

                        {{-- 🔑 Password --}}
                        <div class="mb-4 text-right">
                            <label class="block text-gray-700">كلمة المرور:</label>
                            <input type="password" name="password" class="input-field" placeholder="********" required>
                            <div class="error-message text-danger" id="password-error"></div>
                        </div>

                        <div class="mb-4 flex items-center justify-end w-full">
                            <input type="checkbox" name="remember" class="ml-2" {{ old('remember') ? 'checked' : '' }}>
                            <label class="checkbox-label">تذكرني</label>
                        </div>

                        {{-- 🚀 Submit Button --}}
                        <button type="submit" class="btn-primary">تسجيل الدخول</button>

                        {{-- 🔄 Forgot Password --}}
                        <p class="text-center mt-3 text-gray-500">
                            <a href="{{ route('password.request') }}" class="text-blue-500">نسيت كلمة المرور؟</a>
                        </p>

                        {{-- 🆕 Register Link --}}
                        <p class="text-center mt-2 text-gray-500">
                            لا تملك حساب؟ <a href="{{ route('register') }}" class="text-blue-500 font-bold">إنشاء حساب جديد</a>
                        </p>
                    </form>
                </div>
            </div>

            {{-- 🖼️ Right Side: Image Section --}}
            <div class="col-md-6 d-flex justify-content-center align-items-center">
                <div class="image-section">
                    <img src="{{ asset('images/kids.png') }}" alt="Login Illustration">
                </div>
            </div>

        </div>
    </div>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>

    <!-- Include Toastr.js JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Configure Toastr and add client-side validation -->
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000",
        };

        // Display success message (e.g., after registration or logout)
        @if (session('success'))
            toastr.success('{{ session('success') }}');
        @endif

        // Display error messages (e.g., invalid credentials)
        @if ($errors->has('email'))
            toastr.error('{{ $errors->first('email') }}');
        @endif

        // Client-side validation for the login form
        $('form').on('submit', function (e) {
            // Reset previous error messages
            $('.error-message').text('');

            let email = $('input[name="email"]').val().trim();
            let password = $('input[name="password"]').val();

            let hasError = false;

            if (!email) {
                toastr.error('البريد الإلكتروني أو رقم الهاتف مطلوب.');
                $('#email-error').text('البريد الإلكتروني أو رقم الهاتف مطلوب.');
                hasError = true;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email) && !/^\d{10,15}$/.test(email)) {
                // Validate email format or phone number (10-15 digits)
                toastr.error('يرجى إدخال بريد إلكتروني صالح أو رقم هاتف صالح.');
                $('#email-error').text('يرجى إدخال بريد إلكتروني صالح أو رقم هاتف صالح.');
                hasError = true;
            }

            if (!password) {
                toastr.error('كلمة المرور مطلوبة.');
                $('#password-error').text('كلمة المرور مطلوبة.');
                hasError = true;
            }

            if (hasError) {
                e.preventDefault(); // Prevent form submission if there are errors
            }
        });
    </script>
</body>
</html>