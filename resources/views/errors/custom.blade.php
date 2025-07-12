<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; background-color: #f8f9fa; }
        .error-message { color: #dc3545; font-size: 1.2em; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>خطأ</h1>
    <p class="error-message">{{ $error }}</p>
    <p><a href="{{ route('dashboard') }}">العودة إلى لوحة التحكم</a></p>
</body>
</html>