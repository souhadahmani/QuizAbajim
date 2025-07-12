<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تأكيد البريد الإلكتروني</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <link rel="shortcut icon" href="{{ asset('images/abajimLOGO.png') }}">

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include Toastr.js CSS -->
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
        .container {
            max-width: 600px;
            text-align: center;
        }
        .card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.15);
            border-top: 6px solid #00bcd4;
        }
        .btn-primary {
            background-color: #003366;
            color: white;
            padding: 14px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        .btn-primary:hover {
            background-color: #002244;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h2 class="text-2xl font-bold text-blue-900 mb-4">تأكيد البريد الإلكتروني</h2>
            <p class="text-gray-500 mb-4">
                تم إرسال رابط التأكيد إلى بريدك الإلكتروني. يرجى التحقق من بريدك الإلكتروني لتفعيل حسابك.
            </p>

            @if (session('status') == 'verification-link-sent')
                <p class="text-gray-500 mb-4">
                    تم إرسال رابط تأكيد جديد إلى بريدك الإلكتروني.
                </p>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn-primary">إعادة إرسال رابط التأكيد</button>
            </form>

            <p class="text-center mt-3 text-gray-500">
                <a href="{{ route('logout') }}" class="text-blue-500"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                   تسجيل الخروج
                </a>
            </p>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>

    <!-- Include Popper.js and Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>

    <!-- Include Toastr.js JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Configure Toastr and display success message -->
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000",
        };

        // Display success message if registration is successful
        @if (session('success'))
            toastr.success('{{ session('success') }}');
        @endif
    </script>
</body>
</html>