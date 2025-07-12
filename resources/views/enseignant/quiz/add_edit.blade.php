<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <title>إضافة أسئلة للكويز</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<style>
        /* Enhanced Modal Styling */
#questionPreviewModal .modal-content {
    border-radius: 20px;
    background: linear-gradient(145deg, #ffffff, #f0f4f8);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    transition: transform 0.3s ease;
}

#questionPreviewModal .modal-header {
    background: linear-gradient(90deg, #007bff, #0056b3);
    color: #ffffff;
    border-top-left-radius: 20px;
    border-top-right-radius: 20px;
    padding: 20px 30px;
    border-bottom: none;
}

#questionPreviewModal .modal-title {
    font-size: 1.5rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
}

#questionPreviewModal .modal-title::before {
    content: "📝";
    font-size: 1.8rem;
}

#questionPreviewModal .modal-body {
    padding: 30px;
    background: #ffffff;
    border-radius: 0 0 20px 20px;
}

#questionPreviewModal .preview-content {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 15px;
    box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

#questionPreviewModal .preview-content:hover {
    box-shadow: inset 0 4px 12px rgba(0, 0, 0, 0.1);
}

#questionPreviewModal .preview-content h4 {
    font-size: 1.6rem;
    color: #007bff;
    margin-bottom: 20px;
    text-align: center;
    position: relative;
}

#questionPreviewModal .preview-content h4::after {
    content: "";
    width: 50px;
    height: 3px;
    background: #007bff;
    position: absolute;
    bottom: -5px;
    left: 50%;
    transform: translateX(-50%);
    border-radius: 2px;
}

#questionPreviewModal .preview-content p {
    font-size: 1.1rem;
    color: #333;
    margin-bottom: 15px;
    line-height: 1.6;
}

#questionPreviewModal .preview-content p strong {
    color: #0056b3;
    font-weight: 600;
}

#questionPreviewModal .preview-content .question-text {
    background: #e6f0ff;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-size: 1.2rem;
    color: #333;
    border-left: 5px solid #007bff;
}

#questionPreviewModal .preview-content .media-container {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    justify-content: center;
    margin-bottom: 20px;
}

#questionPreviewModal .preview-content .media-container img,
#questionPreviewModal .preview-content .media-container video {
    max-width: 250px;
    border-radius: 12px;
    border: 2px solid #e0e0e0;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

#questionPreviewModal .preview-content .media-container img:hover,
#questionPreviewModal .preview-content .media-container video:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

#questionPreviewModal .preview-content h5 {
    font-size: 1.3rem;
    color: #007bff;
    margin-top: 20px;
    margin-bottom: 15px;
    position: relative;
}

#questionPreviewModal .preview-content h5::after {
    content: "";
    width: 30px;
    height: 2px;
    background: #007bff;
    position: absolute;
    bottom: -5px;
    left: 0;
    border-radius: 2px;
}

#questionPreviewModal .preview-content ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

#questionPreviewModal .preview-content ul li {
    background: #f1faff;
    padding: 12px 20px;
    margin-bottom: 10px;
    border-radius: 8px;
    font-size: 1.1rem;
    color: #333;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: background 0.3s ease, transform 0.3s ease;
}

#questionPreviewModal .preview-content ul li:hover {
    background: #e6f0ff;
    transform: translateX(5px);
}

#questionPreviewModal .preview-content ul li.correct {
    background: #e6ffed;
    border-left: 5px solid #28a745;
}

#questionPreviewModal .preview-content ul li.correct span {
    color: #28a745;
    font-weight: 600;
}

#questionPreviewModal .modal-footer {
    border-top: none;
    padding: 15px 30px;
    background: #f8f9fa;
    border-bottom-left-radius: 20px;
    border-bottom-right-radius: 20px;
}

#questionPreviewModal .modal-footer .btn {
    border-radius: 25px;
    padding: 10px 25px;
    font-weight: 500;
    transition: all 0.3s ease;
}

#questionPreviewModal .modal-footer .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

/* Dark Mode Adjustments */
body.dark-mode #questionPreviewModal .modal-content {
    background: linear-gradient(145deg, #2c3e50, #34495e);
}

body.dark-mode #questionPreviewModal .modal-body {
    background: #2c3e50;
}

body.dark-mode #questionPreviewModal .preview-content {
    background: #34495e;
    box-shadow: inset 0 2px 8px rgba(255, 255, 255, 0.05);
}

body.dark-mode #questionPreviewModal .preview-content:hover {
    box-shadow: inset 0 4px 12px rgba(255, 255, 255, 0.1);
}

body.dark-mode #questionPreviewModal .preview-content h4,
body.dark-mode #questionPreviewModal .preview-content h5 {
    color: #e0e0e0;
}

body.dark-mode #questionPreviewModal .preview-content p {
    color: #e0e0e0;
}

body.dark-mode #questionPreviewModal .preview-content p strong {
    color: #e0e0e0;
}

body.dark-mode #questionPreviewModal .preview-content .question-text {
    background: #4a69bd;
    color: #e0e0e0;
    border-left-color: #e6f0ff;
}

body.dark-mode #questionPreviewModal .preview-content ul li {
    background: #4a69bd;
    color: #e0e0e0;
}

body.dark-mode #questionPreviewModal .preview-content ul li:hover {
    background: #5a7abd;
}

body.dark-mode #questionPreviewModal .preview-content ul li.correct {
    background: #1a3c34;
    border-left-color: #34c759;
}

body.dark-mode #questionPreviewModal .preview-content ul li.correct span {
    color: #34c759;
}

body.dark-mode #questionPreviewModal .modal-footer {
    background: #34495e;
}
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Tajawal', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            transition: background 0.3s ease, color 0.3s ease;
        }
        body.dark-mode {
            background: linear-gradient(135deg, #2c3e50 0%, #4a69bd 100%);
            color: #e0e0e0;
        }
        body.dark-mode .navbar {
            background: #1a252f;
        }
        body.dark-mode .quiz-box,
        body.dark-mode .sidebar-section {
            background: #2c3e50;
            box-shadow: 0 6px 20px rgba(255, 255, 255, 0.1);
        }
        body.dark-mode .nav-tabs .nav-link {
            background: #34495e;
            color: #e0e0e0;
        }
        body.dark-mode .nav-tabs .nav-link:hover,
        body.dark-mode .nav-tabs .nav-link.active {
            background: #4a69bd;
            color: #ffffff;
        }
        body.dark-mode .ql-editor {
            background: #34495e;
            color: #e0e0e0;
        }
        body.dark-mode .answer-option {
            background: #34495e;
            border-color: #4a69bd;
        }
        body.dark-mode .answer-option:hover {
            background: #4a69bd;
        }
        body.dark-mode .pdf-upload {
            background: #34495e;
            border-color: #4a69bd;
        }
        body.dark-mode .pdf-upload:hover {
            background: #4a69bd;
        }
        body.dark-mode .modal-content {
            background: #2c3e50;
            color: #e0e0e0;
        }
        body.dark-mode .modal-header {
            background: #4a69bd;
        }
        body.dark-mode .alert-success {
            background: #1a3c34;
            color: #34c759;
        }
        body.dark-mode .alert-danger {
            background: #3c1a1a;
            color: #ff5555;
        }
        .navbar {
            background: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar img {
            transition: transform 0.3s ease;
        }
        .navbar img:hover {
            transform: scale(1.1);
        }
        .navbar .btn {
            margin-left: 10px;
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .navbar .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        .container-fluid {
            padding: 30px;
        }
        .quiz-box {
            background: #ffffff;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .quiz-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        .quiz-box h2 {
            font-size: 1.8rem;
            color: #007bff;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .quiz-box h2 i {
            margin-left: 10px;
            color: #007bff;
        }
        .ql-editor {
            min-height: 120px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background: #f8f9fa;
            transition: border-color 0.3s ease;
        }
        .ql-editor:focus {
            border-color: #007bff;
        }
        .answer-options {
            margin-top: 25px;
        }
        .answer-option {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            font-size: 16px;
            margin-bottom: 15px;
            position: relative;
            width: 100%;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        .answer-option:hover {
            background-color: #e9ecef;
            border-color: #007bff;
        }
        .answer-option input[type="text"] {
            width: 85%;
            padding: 10px;
            border: none;
            outline: none;
            font-size: 14px;
            background: transparent;
            text-align: right;
            border-radius: 8px;
        }
        .answer-option img {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 24px;
            height: 24px;
            opacity: 0.8;
        }
        .answer-option input::placeholder {
            color: #adb5bd;
        }
        .answer-option-row {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }
        .answer-option-col {
            width: 48%;
            margin-bottom: 15px;
        }
        .answer-option label {
            margin-right: 10px;
            cursor: pointer;
        }
        .correct-answer-radio {
            margin-left: 10px;
        }
        .pdf-upload {
            border: 2px dashed #007bff;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            margin-top: 15px;
            border-radius: 10px;
            background: #f1faff;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        .pdf-upload:hover {
            background: #e6f0ff;
            border-color: #0056b3;
        }
        .pdf-upload input {
            opacity: 0;
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            cursor: pointer;
        }
        .pdf-file-name {
            margin-top: 10px;
            font-size: 16px;
            color: #007bff;
            font-weight: 600;
        }
        p img {
            display: block;
            margin: 15px auto;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid #007bff;
            padding: 6px;
            background-color: #f8f9fa;
            transition: transform 0.3s ease;
        }
        p img:hover {
            transform: rotate(360deg);
        }
        p.new-feature {
            font-size: 16px;
            font-weight: 600;
            color: #007bff;
            text-align: center;
            margin: 20px auto;
            background: #e6f0ff;
            padding: 10px;
            border-radius: 8px;
        }
        .btn-sidebar {
            width: 100%;
            margin-bottom: 15px;
            background: linear-gradient(90deg, #007bff, #0056b3);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 500;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: all 0.3s ease;
        }
        .btn-sidebar:hover {
            background: linear-gradient(90deg, #0056b3, #003d82);
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }
        #addbtn i {
            margin-right: 10px;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        label {
            font-size: 1.2rem;
            font-weight: 600;
            color: #444;
            margin-bottom: 8px;
        }
        button[type="submit"], .btn-success, .btn-preview, .btn-duplicate, .btn-undo, .btn-warning, .btn-danger {
            background: linear-gradient(90deg, #007bff, #0056b3);
            color: white;
            border-radius: 25px;
            font-size: 1.1rem;
            padding: 12px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        button[type="submit"]:hover, .btn-success:hover, .btn-preview:hover, .btn-duplicate:hover, .btn-undo:hover, .btn-warning:hover, .btn-danger:hover {
            background: linear-gradient(90deg, #0056b3, #003d82);
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }
        .btn-warning {
            background: linear-gradient(90deg, #ffca28, #ffb300);
        }
        .btn-warning:hover {
            background: linear-gradient(90deg, #ffb300, #e6a700);
        }
        .btn-danger {
            background: linear-gradient(90deg, #dc3545, #c82333);
        }
        .btn-danger:hover {
            background: linear-gradient(90deg, #c82333, #b21f2d);
        }
        #multiple-choice-section, #true-false-section {
            transition: opacity 0.3s ease-in-out, max-height 0.3s ease-in-out;
            max-height: 500px;
            overflow: hidden;
        }
        #multiple-choice-section[style*="display: none"], #true-false-section[style*="display: none"] {
            max-height: 0;
        }
        .form-control.is-invalid {
            border-color: #dc3545;
        }
        .invalid-feedback {
            display: none;
            color: #dc3545;
            font-size: 0.875rem;
        }
        .form-control.is-invalid ~ .invalid-feedback {
            display: block;
        }
        .sidebar-section {
            background: #ffffff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }
        .sidebar-section h4 {
            font-size: 1.5rem;
            color: #007bff;
            margin-bottom: 20px;
        }
        .sidebar-section p {
            font-size: 1rem;
            color: #555;
            margin-bottom: 10px;
        }
        .modal-content {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        .modal-header {
            background: #007bff;
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        .modal-body .background-option,
        .modal-body .template-option {
            margin-bottom: 15px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .modal-body .background-option:hover,
        .modal-body .template-option:hover {
            transform: scale(1.05);
        }
        .modal-body .background-option img {
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            width: 100%;
            height: 120px;
            object-fit: cover;
        }
        .modal-body .background-option p,
        .modal-body .template-option p {
            text-align: center;
            font-weight: 500;
            color: #555;
            margin-top: 10px;
        }
        .alert {
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #e6ffed;
            color: #28a745;
        }
        .alert-danger {
            background: #ffe6e6;
            color: #dc3545;
        }
        .nav-tabs {
            border-bottom: 2px solid #e0e0e0;
            margin-bottom: 20px;
            padding: 0 10px;
        }
        .nav-tabs .nav-item {
            margin-bottom: -2px;
        }
        .nav-tabs .nav-link {
            border: none;
            border-bottom: 2px solid transparent;
            background: #f8f9fa;
            color: #555;
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 10px 10px 0 0;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            cursor: move;
        }
        .nav-tabs .nav-link:hover {
            background: #e6f0ff;
            color: #007bff;
        }
        .nav-tabs .nav-link.active {
            border-bottom: 2px solid #007bff;
            background: #e6f0ff;
            color: #007bff;
        }
        .nav-tabs .nav-link i {
            margin-left: 8px;
            color: #dc3545;
            cursor: pointer;
        }
        .tab-content {
            padding: 20px 0;
        }
        .tab-pane {
            display: none;
        }
        .tab-pane.active {
            display: block;
        }
        .progress-container {
            margin-bottom: 20px;
            text-align: center;
        }
        .progress-container p {
            font-size: 1.1rem;
            color: #555;
            margin-bottom: 10px;
        }
        .progress {
            height: 10px;
            border-radius: 5px;
            background: #e0e0e0;
        }
        .progress-bar {
            background: linear-gradient(90deg, #007bff, #0056b3);
            transition: width 0.3s ease;
        }
        .media-preview {
            margin-top: 15px;
            text-align: center;
        }
        .media-preview img,
        .media-preview video {
            max-width: 200px;
            border-radius: 10px;
            margin-top: 10px;
        }
        /* RTL Styling for Tab Content */
.tab-content[dir="rtl"] {
    text-align: right;
}

.tab-content[dir="rtl"] h2 {
    font-size: 24px;
    font-weight: 600;
    color: #333;
    margin-bottom: 20px;
}

.tab-content[dir="rtl"] .question-details {
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
}

.tab-content[dir="rtl"] .question-details .card-body {
    padding: 20px;
}

.tab-content[dir="rtl"] .question-item {
    margin-bottom: 15px;
}

.tab-content[dir="rtl"] .question-item .label {
    font-weight: 600;
    color: #555;
    display: block;
    margin-bottom: 5px;
}

.tab-content[dir="rtl"] .question-item .content {
    font-size: 16px;
    color: #333;
    margin: 0;
}

.tab-content[dir="rtl"] .question-item .badge {
    font-size: 14px;
    padding: 5px 10px;
}

.tab-content[dir="rtl"] .answers-section .section-title {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
}

.tab-content[dir="rtl"] .list-group {
    padding-right: 0;
}

.tab-content[dir="rtl"] .list-group-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f8f9fa;
    border: none;
    border-radius: 8px;
    margin-bottom: 8px;
    padding: 10px 15px;
    font-size: 16px;
    color: #333;
}

.tab-content[dir="rtl"] .list-group-item .badge {
    font-size: 12px;
    padding: 5px 10px;
}

.tab-content[dir="rtl"] .action-buttons {
    display: flex;
    justify-content: space-between;
    flex-direction: row-reverse; /* Reverse button order for RTL */
}

.tab-content[dir="rtl"] .btn-custom {
    transition: transform 0.2s ease, background-color 0.3s ease;
}

.tab-content[dir="rtl"] .btn-custom:hover {
    transform: translateY(-2px);
    background-color: #e0a800; /* For warning button */
}

.tab-content[dir="rtl"] .btn-danger:hover {
    background-color: #c82333;
}

/* Dark Mode Adjustments */
body.dark-mode .tab-content[dir="rtl"] .question-details {
    background: #2c3e50;
    box-shadow: 0 6px 20px rgba(255, 255, 255, 0.1);
}

body.dark-mode .tab-content[dir="rtl"] h2,
body.dark-mode .tab-content[dir="rtl"] .question-item .label,
body.dark-mode .tab-content[dir="rtl"] .answers-section .section-title {
    color: #e0e0e0;
}

body.dark-mode .tab-content[dir="rtl"] .question-item .content,
body.dark-mode .tab-content[dir="rtl"] .list-group-item {
    color: #d1d1d1;
}

body.dark-mode .tab-content[dir="rtl"] .list-group-item {
    background: #34495e;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .tab-content[dir="rtl"] .question-details .card-body {
        padding: 15px;
    }

    .tab-content[dir="rtl"] .action-buttons {
        flex-direction: column;
        gap: 10px;
    }

    .tab-content[dir="rtl"] .btn-custom {
        width: 100% !important;
    }
}
.question-container {
        font-family: 'Tajawal', sans-serif;
        margin: 15px 0;
        padding: 10px;
        border-right: 4px solid #00bcd4;
        background-color: #f8fcff;
    }
    
    .question-label {
        font-weight: bold;
        color: #333;
        margin-left: 10px;
    }
    
    .new-question-tag {
        display: inline-block;
        background-color: #e1f5fe;
        color: #0097a7;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.9em;
        margin-right: 10px;
        border: 1px dashed #00bcd4;
    }
    
    .new-question-tag i {
        margin-left: 5px;
    }

    </style>
</head>
<body>
    <nav class="navbar">
        <img src="{{ url('/images/abajim.png') }}" alt="Abajim" height="40">
        <div>
        <a href="{{ route('teacher.dashboard') }}" class="btn btn-danger">🚪 خروج</a>

            <a href="{{ route('quiz.create') }}" class="btn btn-primary">➕ إنشاء كويز جديد</a>
            <button class="btn btn-secondary" id="toggleDarkMode"><i class="fas fa-moon"></i> الوضع الداكن</button>
        </div>
    </nav>
    <div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-3">
            <div class="sidebar-section" dir="rtl">
                <h4 class="text-center mb-4">⚙ إعدادات الكويز</h4>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(Session::has('success_message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ Session::get('success_message') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                @endif

                @if(Session::has('error_message'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ Session::get('error_message') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                @endif

                @if($quiz)
    <div>
        <h3>{{ $quiz->title }}</h3>
        <p><strong>📋 نوع الكويز: </strong> {{ in_array($quiz->type, ['self_training']) ? 'تدريب ذاتي' : 'تحدي' }}</p>
        <p><strong>📊 المستوى الدراسي: </strong> {{ $quiz->schoolLevel ? $quiz->schoolLevel->name : 'غير محدد' }}</p>
        <p><strong>📚 المادة: </strong> {{ $quiz->subject ? $quiz->subject->name : 'غير محدد' }}</p>
        <p><strong>⏳ الوقت المحدد: </strong> {{ $quiz->time_limit }} دقيقة</p>
        <p><strong>📊 العلامة الكلية: </strong> {{ $quiz->total_mark }}</p>
        <p><strong>📈 مستوى الصعوبة: </strong> 
    {{ $quiz->difficulty_level == 'easy' ? 'سهل' : ($quiz->difficulty_level == 'medium' ? 'متوسط' : 'صعب') }}
</p>
        <p><strong>🎯 درجة النجاح: </strong> {{ $quiz->pass_mark }}%</p>
        <p><strong>🏆 شهادة: </strong> {{ $quiz->certificate ? 'نعم' : 'لا' }}</p>
        <p><strong>📅 تاريخ الإنشاء: </strong> {{ \Carbon\Carbon::createFromTimestamp($quiz->created_at)->format('Y-m-d H:i:s') }}</p>
        <button class="btn btn-sidebar mt-3" data-toggle="modal" data-target="#editQuizModal">
            <i class="fas fa-edit"></i> تعديل إعدادات الكويز
        </button>
    </div>

    <!-- Edit Quiz Modal -->
    <div class="modal fade" id="editQuizModal" tabindex="-1" role="dialog" aria-labelledby="editQuizModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" dir="rtl">
                <div class="modal-header">
                    <h5 class="modal-title" id="editQuizModalLabel">تعديل إعدادات الكويز</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="edit-quiz-form" action="{{ route('quiz.update', $quiz->id) }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="edit-quiz-title">عنوان الكويز:</label>
                            <input type="text" name="title" id="edit-quiz-title" class="form-control" value="{{ $quiz->title }}" required>
                            <div class="invalid-feedback">يرجى إدخال عنوان الكويز.</div>
                        </div>

                        <div class="form-group">
                            <label for="edit-quiz-type">نوع الكويز:</label>
                            <select name="type" id="edit-quiz-type" class="form-control" required>
                                <option value="self_training" {{ $quiz->type == 'self_training' ? 'selected' : '' }}>تدريب ذاتي</option>
                                <option value="evaluation" {{ $quiz->type == 'evaluation' ? 'selected' : '' }}>تحدي</option>
                            </select>
                            <div class="invalid-feedback">يرجى اختيار نوع الكويز.</div>
                        </div>

                      <div class="form-group" dir="rtl">
                            <label for="school_level_id">المستوى الدراسي:</label>
                            <select name="school_level_id" id="school_level_id" class="form-control" required>
                                <option value="">-- اختر المستوى --</option>
                                @if(!empty($schoolLevels))
                                  
                                  <option value="{{ $schoolLevels->id }}" data-section-id="{{ $schoolLevels->section_id }}" {{ old('school_level_id') == $schoolLevels->id ? 'selected' : '' }}>{{ $schoolLevels->name }}</option>
                           
                          
                                @else
                                    <option value="">لا توجد مستويات دراسية متاحة</option>
                                @endif
                            </select>
                            <div class="invalid-feedback">يرجى اختيار المستوى الدراسي.</div>
                        </div>

                        <div class="form-group">
                            <label for="subject_id">المادة:</label>
                            <select name="subject_id" id="subject_id" class="form-control" required>
                                <option value="">-- اختر المادة --</option>
                            </select>
                            <div class="invalid-feedback">يرجى اختيار المادة.</div>
                        </div>

                        <div class="form-group">
                            <label for="edit-total-mark">العلامة الكلية:</label>
                            <input type="number" name="total_mark" id="edit-total-mark" class="form-control" value="{{ $quiz->total_mark }}" min="1" max="1000" required>
                            <div class="invalid-feedback">يرجى إدخال العلامة الكلية.</div>
                        </div>

                        <div class="form-group">
                            <label for="edit-distribute-points">توزيع الدرجات:</label>
                            <select name="distribute_points" id="edit-distribute-points" class="form-control" required>
                                <option value="evenly" {{ Session::get('distribute_points', 'evenly') == 'evenly' ? 'selected' : '' }}>توزيع متساوٍ</option>
                                <option value="manually" {{ Session::get('distribute_points', 'evenly') == 'manually' ? 'selected' : '' }}>توزيع يدوي</option>
                            </select>
                            <div class="invalid-feedback">يرجى اختيار طريقة توزيع الدرجات.</div>
                        </div>

                        <div class="form-group">
                            <label for="edit-quiz-time">مدة الكويز (بالدقائق):</label>
                            <input type="number" name="time_limit" id="edit-quiz-time" class="form-control" value="{{ $quiz->time_limit }}" min="1" max="180" required>
                            <div class="invalid-feedback">يرجى إدخال مدة الكويز.</div>
                        </div>

                        <div class="form-group">
                            <label for="edit-correct-answers">عدد الإجابات الصحيحة المطلوبة:</label>
                            <input type="number" name="correct_answers" id="edit-correct-answers" class="form-control" value="{{ Session::get('correct_answers', 1) }}" min="1" max="3" required>
                            <div class="invalid-feedback">يرجى إدخال عدد الإجابات الصحيحة (بين 1 و3).</div>
                        </div>
                        <div class="form-group">
        <label for="edit-difficulty-level">📈 مستوى الصعوبة:</label>
        <select name="difficulty_level" id="edit-difficulty-level" class="form-control" required>
            <option value="easy" {{ $quiz->difficulty_level == 'easy' ? 'selected' : '' }}>سهل</option>
            <option value="medium" {{ $quiz->difficulty_level == 'medium' ? 'selected' : '' }}>متوسط</option>
            <option value="hard" {{ $quiz->difficulty_level == 'hard' ? 'selected' : '' }}>صعب</option>
        </select>
        <div class="invalid-feedback">يرجى اختيار مستوى الصعوبة.</div>
    </div>
                        <div class="form-group">
                            <label for="edit-quiz-pass-mark">درجة النجاح:</label>
                            <input type="number" name="pass_mark" id="edit-quiz-pass-mark" class="form-control" value="{{ $quiz->pass_mark }}" min="0" max="100" required>
                            <div class="invalid-feedback">يرجى إدخال درجة النجاح (0-100).</div>
                        </div>

                        <div class="form-group">
                            <label for="edit-quiz-status">حالة الكويز:</label>
                            <select name="status" id="edit-quiz-status" class="form-control" required>
                                <option value="active" {{ $quiz->status == 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="inactive" {{ $quiz->status == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                            </select>
                            <div class="invalid-feedback">يرجى اختيار حالة الكويز.</div>
                        </div>

                        <div class="form-group">
                            <label for="edit-quiz-certificate">
                                <input type="checkbox" name="certificate" id="edit-quiz-certificate" value="1" {{ $quiz->certificate ? 'checked' : '' }}>
                                إصدار شهادة عند النجاح
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Create New Quiz Form -->
    <div class="quiz-metadata">
        <form id="quiz-metadata-form" action="{{ route('quiz.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="form-group" dir="rtl">
                <label for="quiz-title">عنوان الكويز:</label>
                <input type="text" name="title" id="quiz-title" class="form-control" value="{{ old('title', 'بيري انا الكويز') }}" required>
                <div class="invalid-feedback">يرجى إدخال عنوان الكويز.</div>
            </div>

            <div class="form-group">
                <label for="quiz-type">نوع الكويز:</label>
                <select name="type" id="quiz-type" class="form-control" required>
                    <option value="self_training" {{ old('type', 'self_training') == 'self_training' ? 'selected' : '' }}>تدريب ذاتي</option>
                    <option value="evaluation" {{ old('type', 'self_training') == 'evaluation' ? 'selected' : '' }}>تحدي</option>
                </select>
                <div class="invalid-feedback">يرجى اختيار نوع الكويز.</div>
            </div>

                         <div class="form-group" dir="rtl">
                            <label for="school_level_id">المستوى الدراسي:</label>
                            <select name="school_level_id" id="school_level_id" class="form-control" required>
                                <option value="">-- اختر المستوى --</option>
                                @if(!empty($schoolLevels))
                                  
                                        <option value="{{ $schoolLevels->id }}" data-section-id="{{ $schoolLevels->section_id }}" {{ old('school_level_id') == $schoolLevels->id ? 'selected' : '' }}>{{ $schoolLevels->name }}</option>
                                 
                                @else
                                    <option value="">لا توجد مستويات دراسية متاحة</option>
                                @endif
                            </select>
                            <div class="invalid-feedback">يرجى اختيار المستوى الدراسي.</div>
                        </div>

                        <div class="form-group">
                            <label for="subject_id">المادة:</label>
                            <select name="subject_id" id="subject_id" class="form-control" required>
                                <option value="">-- اختر المادة --</option>
                            </select>
                            <div class="invalid-feedback">يرجى اختيار المادة.</div>
                        </div>

            <div class="form-group">
                <label for="total-mark">العلامة الكلية:</label>
                <input type="number" name="total_mark" id="total-mark" class="form-control" value="{{ old('total_mark', 100) }}" min="1" max="1000" required>
                <div class="invalid-feedback">يرجى إدخال العلامة الكلية.</div>
            </div>

            <div class="form-group">
                <label for="distribute-points">توزيع الدرجات:</label>
                <select name="distribute_points" id="distribute-points" class="form-control" required>
                    <option value="evenly" {{ old('distribute_points', 'evenly') == 'evenly' ? 'selected' : '' }}>توزيع متساوٍ</option>
                    <option value="manually" {{ old('distribute_points', 'evenly') == 'manually' ? 'selected' : '' }}>توزيع يدوي</option>
                </select>
                <div class="invalid-feedback">يرجى اختيار طريقة توزيع الدرجات.</div>
            </div>

            <div class="form-group">
                <label for="quiz-time">مدة الكويز (بالدقائق):</label>
                <input type="number" name="time_limit" id="quiz-time" class="form-control" value="{{ old('time_limit', 27) }}" min="1" max="180" required>
                <div class="invalid-feedback">يرجى إدخال مدة الكويز.</div>
            </div>

            <div class="form-group">
                <label for="correct-answers">عدد الإجابات الصحيحة المطلوبة:</label>
                <input type="number" name="correct_answers" id="correct-answers" class="form-control" value="{{ old('correct_answers', 1) }}" min="1" max="3" required>
                <div class="invalid-feedback">يرجى إدخال عدد الإجابات الصحيحة (بين 1 و3).</div>
            </div>

            <div class="form-group">
                <label for="quiz-pass-mark">🎯 درجة النجاح:</label>
                <input type="number" name="pass_mark" id="quiz-pass-mark" class="form-control" value="{{ old('pass_mark', 20) }}" min="0" max="100" step="1" required>
                <div class="invalid-feedback">يرجى إدخال درجة النجاح (0-100).</div>
            </div>
            <div class="form-group">
    <label for="difficulty_level">📊 مستوى الصعوبة:</label>
    <select id="difficulty_level" name="difficulty_level" class="form-control" required>
        <option value="easy" {{ old('difficulty_level', $quiz->difficulty_level ?? 'medium') == 'easy' ? 'selected' : '' }}>سهل</option>
        <option value="medium" {{ old('difficulty_level', $quiz->difficulty_level ?? 'medium') == 'medium' ? 'selected' : '' }}>متوسط</option>
        <option value="hard" {{ old('difficulty_level', $quiz->difficulty_level ?? 'medium') == 'hard' ? 'selected' : '' }}>صعب</option>
    </select>
    <div class="invalid-feedback">يرجى اختيار مستوى الصعوبة.</div>
</div>
            <div class="form-group">
                <label for="quiz-status">حالة الكويز:</label>
                <select name="status" id="quiz-status" class="form-control" required>
                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ old('status', 'active') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                </select>
                <div class="invalid-feedback">يرجى اختيار حالة الكويز.</div>
            </div>

            <div class="form-group">
                <label for="quiz-certificate">
                    <input type="checkbox" name="certificate" id="quiz-certificate" value="1" {{ old('certificate') ? 'checked' : '' }}>
                    إصدار شهادة عند النجاح
                </label>
            </div>

            <button type="submit" class="btn create-quiz-btn">حفظ التحدي</button>
        </form>
    </div>
@endif            </div>
        </div>          
        <div class="col-md-6">
                @if($quiz)
                    <div id="question-fields">
                        <form action="{{ route('quiz.saveQuestion', ['quiz_id' => $quiz->id]) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate id="question-form">
                            @csrf
                            <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">

                            <div class="progress-container">
                                <p>عدد الأسئلة: <span id="question-count">{{ $quiz->questions->count() + 1 }}</span></p>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: 10%;" id="progress-bar"></div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-undo mb-3" id="undoBtn" style="display: none;" onclick="undoDelete()"><i class="fas fa-undo"></i> تراجع عن الحذف</button>

                            <ul class="nav nav-tabs" id="question-tabs">
                                @php
                                    $totalQuestions = $quiz->questions->count() + 1;
                                @endphp
                                @if($quiz->questions->count() > 0)
                                    @foreach($quiz->questions as $index => $question)
                                        <li class="nav-item" id="tab-{{ $index + 1 }}">
                                            <a class="nav-link {{ $index == 0 ? 'active' : '' }}" data-toggle="tab" href="#page-{{ $index + 1 }}">السؤال {{ $index + 1 }} <i class="fas fa-times" onclick="deleteTab({{ $index + 1 }})"></i></a>
                                        </li>
                                    @endforeach
                                @endif
                                @php
                                    $questionIndex = $totalQuestions;
                                    $formQuestionIndex = 0;
                                @endphp
                                <li class="nav-item" id="tab-{{ $questionIndex }}">
                                    <a class="nav-link {{ $totalQuestions == 1 ? 'active' : '' }}" data-toggle="tab" href="#page-{{ $questionIndex }}">السؤال {{ $questionIndex }} <i class="fas fa-times" onclick="deleteTab({{ $questionIndex }})"></i></a>
                                </li>
                            </ul>
                       

                           
                                 <div id="divAi"  style="display:none">   
                                 
                                 <p class="question-container">
    <span class="question-label">الأسئلة:</span>
    <span class="new-question-tag">
        <i class="fas fa-robot"></i> سؤال جديد (إجابة مولدة بالذكاء الإصطناعي)
    </span>
</p>
                                    <ul class="nav nav-tabs" id="question-tabsAi">
                                       
                                    </ul>
                                    <div class="tab-content" id="pages-containerNewAI"dir="rtl">
                                    </div>  

                                    <!-- <div class="tab-content" id="pages-containerAI"dir="rtl">
                                    </div>  -->
                                </div> 
                            <div class="tab-content" id="pages-container"dir="rtl">
                                @if($quiz->questions->count() > 0)
                                    @foreach($quiz->questions as $index => $question)
                                        <div class="tab-pane quiz-box {{ $index == 0 ? 'active' : '' }}" id="page-{{ $index + 1 }}">
                                            <h2 class="text-center">السؤال {{ $index + 1 }} <i class="fas fa-pen"></i></h2>

                                            <div class="question-item">
                            <strong class="label">نص السؤال:</strong>
                            <p class="content">{!! $question->question ?? 'غير متوفر' !!}</p>
                        </div>
                        @if($question->explanation)
                        <div class="question-item">
                            <strong class="label">تفسير السؤال:</strong>
                            <p class="content">{!! $question->explanation ?? 'غير متوفر' !!}</p>
                        </div>
                        @endif
                                            <div class="question-item">
                            <strong class="label">النوع:</strong>
                            <span class="badge badge-info">
                                {{ $question->type == 'multiple' ? 'اختيار متعدد' : 'صح/خطأ' }}
                            </span>
                        </div>                                            
                        <div class="question-item">
                            <strong class="label">الدرجة:</strong>
                            <span class="badge badge-success">{{ $question->grade }}</span>
                        </div>                                            @if($question->image)
                            <div class="question-item">
                                <strong class="label">الصورة:</strong>
                                <div class="media-content">
                                    <img src="{{ Storage::url($question->image) }}" alt="Question Image" class="img-fluid rounded" style="max-width: 200px;">
                                </div>
                            </div>
                        @endif
                                            @if($question->video)
                                                <p><strong>الفيديو:</strong> </p>
                                                <p><video src="{{ Storage::url($question->video) }}" controls style="max-width: 200px; border-radius: 10px;"></video></p>
                                            @endif
                                            
                                            @php
    $correctAnswerText = null;
@endphp

@php
    $correctIndex = -1;
@endphp
<p><strong>الإجابات:</strong> </p>

<ul>
    @foreach($question->answers as $key => $answer)
        @if($answer->correct)
            @php
                $correctIndex = $key;
            @endphp
        @endif

        <li>
          {{$answer->answer }}
                @if($answer->correct == 1 )
            <span style="color: #28a745;"><strong>(الإجابة الصحيحة)</strong></span>
            @endif
        </li>
    @endforeach
</ul>
                                            <div class="d-flex justify-content-between mt-3">
                                                <!-- Added Edit and Delete Buttons -->
                                                <button type="button" class="btn btn-warning w-25 mr-2" onclick="editQuestion({{ $question->id }}, {{ $index + 1 }})"><i class="fas fa-edit"></i> تعديل</button>
                                                <button type="button" class="btn btn-danger w-25" onclick="deleteQuestion({{ $question->id }}, {{ $index + 1 }})"><i class="fas fa-trash"></i> حذف</button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                <div class="tab-pane quiz-box {{ $totalQuestions == 1 ? 'active' : '' }}" id="page-{{ $questionIndex }}">
                                    <h2 class="text-center">السؤال {{ $questionIndex }} <i class="fas fa-pen"></i></h2>
                                    <div class="form-group">
                                        <label for="question-text-{{ $questionIndex }}">نص السؤال:</label>
                                        <div id="question-editor-{{ $questionIndex }}"></div>
                                        <input type="hidden" name="questions[{{ $formQuestionIndex }}][question_text]" id="question-text-{{ $questionIndex }}" class="form-control @error('questions.' . $formQuestionIndex . '.question_text') is-invalid @enderror" required>
                                        @error('questions.' . $formQuestionIndex . '.question_text')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback">يرجى إدخال نص السؤال.</div>
                                        @enderror
                                    </div>
                                    <!-- Simplified Explanation Field -->
                        <div class="form-group">
                            <label for="explanation-{{ $questionIndex }}">إضافة شرح للإجابة (اختياري):</label>
                            <textarea name="questions[{{ $formQuestionIndex }}][explanation]" id="explanation-{{ $questionIndex }}" class="form-control" rows="3" placeholder="سجل شرح الإجابة الصحيحة..."></textarea>
                            <small class="form-text text-muted">سيتم عرض هذا الشرح للطالب إذا أجاب بشكل خاطئ.</small>
                        </div>
                                    <div class="form-group">
                                        <label for="question-type-{{ $questionIndex }}">نوع السؤال:</label>
                                        <select id="question-type-{{ $questionIndex }}" class="form-control" name="questions[{{ $formQuestionIndex }}][type]" onchange="handleQuestionTypeChange(this, {{ $questionIndex }})">
                                            <option value="multiple">اختيار متعدد</option>
                                            <option value="descriptive">صح/خطأ</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="grade-{{ $questionIndex }}">🎯 درجة السؤال:</label>
                                        <input type="number" name="questions[{{ $formQuestionIndex }}][grade]" id="grade-{{ $questionIndex }}" class="form-control @error('questions.' . $formQuestionIndex . '.grade') is-invalid @enderror" value="{{ old('questions.' . $formQuestionIndex . '.grade', 10) }}" min="1" required>
                                        @error('questions.' . $formQuestionIndex . '.grade')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback">يرجى إدخال درجة السؤال (رقم أكبر من 0).</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="image-{{ $questionIndex }}">📸 صورة (اختياري):</label>
                                        <input type="file" name="questions[{{ $formQuestionIndex }}][image]" id="image-{{ $questionIndex }}" class="form-control @error('questions.' . $formQuestionIndex . '.image') is-invalid @enderror" accept="image/*" onchange="previewMedia('image', {{ $questionIndex }})">
                                        @error('questions.' . $formQuestionIndex . '.image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="media-preview" id="image-preview-{{ $questionIndex }}"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="video-{{ $questionIndex }}">🎥 فيديو (اختياري):</label>
                                        <input type="file" name="questions[{{ $formQuestionIndex }}][video]" id="video-{{ $questionIndex }}" class="form-control @error('questions.' . $formQuestionIndex . '.video') is-invalid @enderror" accept="video/*" onchange="previewMedia('video', {{ $questionIndex }})">
                                        @error('questions.' . $formQuestionIndex . '.video')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="media-preview" id="video-preview-{{ $questionIndex }}"></div>
                                    </div>
                                    <div id="multiple-choice-section-{{ $questionIndex }}">
                                        <div class="answer-options" id="answer-options-{{ $questionIndex }}">
                                            <div class="answer-option-row">
                                                <div class="answer-option-col">
                                                    <div class="answer-option">
                                                        <img src="{{ asset('images/diviseurs.png') ?: 'https://via.placeholder.com/24' }}" alt="icon">
                                                        <input type="text" name="questions[{{ $formQuestionIndex }}][answers][2]" placeholder="إجابة 1" class="form-control answer-input" data-index="0" required>
                                                        <label>
                                                            <input type="radio" name="questions[{{ $formQuestionIndex }}][correct_answer]" value="0" class="correct-answer-radio" required>
                                                            صحيح
                                                        </label>
                                                    </div>
                                                    @error('questions.' . $formQuestionIndex . '.answers.0')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @else
                                                        <div class="invalid-feedback d-block" style="display: none;">يرجى إدخال الإجابة الأولى.</div>
                                                    @enderror
                                                </div>
                                                <div class="answer-option-col">
                                                    <div class="answer-option">
                                                        <img src="{{ asset('images/regle.png') ?: 'https://via.placeholder.com/24' }}" alt="icon">
                                                        <input type="text" name="questions[{{ $formQuestionIndex }}][answers][3]" placeholder="إجابة 2" class="form-control answer-input" data-index="1" required>
                                                        <label>
                                                            <input type="radio" name="questions[{{ $formQuestionIndex }}][correct_answer]" value="1" class="correct-answer-radio">
                                                            صحيح
                                                        </label>
                                                    </div>
                                                    @error('questions.' . $formQuestionIndex . '.answers.1')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @else
                                                        <div class="invalid-feedback d-block" style="display: none;">يرجى إدخال الإجابة الثانية.</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="answer-option-row">
                                                <div class="answer-option-col">
                                                    <div class="answer-option">
                                                        <img src="{{ asset('images/surligneur.png') ?: 'https://via.placeholder.com/24' }}" alt="icon">
                                                        <input type="text" name="questions[{{ $formQuestionIndex }}][answers][4]" placeholder="إجابة 3" class="form-control answer-input" data-index="2">
                                                        <label>
                                                            <input type="radio" name="questions[{{ $formQuestionIndex }}][correct_answer]" value="2" class="correct-answer-radio">
                                                            صحيح
                                                        </label>
                                                    </div>
                                                    @error('questions.' . $formQuestionIndex . '.answers.2')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="answer-option-col">
                                                    <div class="answer-option">
                                                        <img src="{{ asset('images/trombone.png') ?: 'https://via.placeholder.com/24' }}" alt="icon">
                                                        <input type="text" name="questions[{{ $formQuestionIndex }}][answers][5]" placeholder="إجابة 4" class="form-control answer-input" data-index="3">
                                                        <label>
                                                            <input type="radio" name="questions[{{ $formQuestionIndex }}][correct_answer]" value="3" class="correct-answer-radio">
                                                            صحيح
                                                        </label>
                                                    </div>
                                                    @error('questions.' . $formQuestionIndex . '.answers.3')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        @error('questions.' . $formQuestionIndex . '.answers')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback d-block answers-error" style="display: none;">يرجى إدخال إجابتين على الأقل.</div>
                                        @enderror
                                        @error('questions.' . $formQuestionIndex . '.correct_answer')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback d-block correct-answer-error" style="display: none;">يرجى اختيار الإجابة الصحيحة.</div>
                                        @enderror
                                    </div>
                                    <!-- True/False Section for Descriptive Type -->
                                    <div id="true-false-section-{{ $questionIndex }}" style="display: none;">
                                        <div class="answer-options">
                                            <div class="answer-option-row">
                                                <div class="answer-option-col">
                                                    <div class="answer-option">
                                                        <img src="{{ asset('images/diviseurs.png') ?: 'https://via.placeholder.com/24' }}" alt="icon">
                                                        <span>صح</span>
                                                        <input type="hidden" name="questions[{{ $formQuestionIndex }}][answers][0]" value="صح">
                                                        <label>
                                                            <input type="radio" name="questions[{{ $formQuestionIndex }}][correct_answer]" value="0" class="correct-answer-radio" required>
                                                            صحيح
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="answer-option-col">
                                                    <div class="answer-option">
                                                        <img src="{{ asset('images/regle.png') ?: 'https://via.placeholder.com/24' }}" alt="icon">
                                                        <span>خطأ</span>
                                                        <input type="hidden" name="questions[{{ $formQuestionIndex }}][answers][1]" value="خطأ">
                                                        <label>
                                                            <input type="radio" name="questions[{{ $formQuestionIndex }}][correct_answer]" value="1" class="correct-answer-radio">
                                                            صحيح
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @error('questions.' . $formQuestionIndex . '.correct_answer')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback d-block correct-answer-error" style="display: none;">يرجى اختيار الإجابة الصحيحة.</div>
                                        @enderror
                                    </div>
                                    <div class="d-flex justify-content-between mt-3">
                                        <button type="button" class="btn btn-duplicate w-50" onclick="duplicateQuestion({{ $questionIndex }})"><i class="fas fa-copy"></i> تكرار السؤال</button>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4" >
                                <button id="button-save-question" type="submit" class="btn btn-primary w-50 mr-2">✔ إضافة السؤال</button>
                                <a href="{{ route('quiz.preview', ['quiz_id' => $quiz->id]) }}" class="btn btn-success w-50" id="finishQuizBtn">🏁 معاينة الكويز</a>
                            </div>

                            <div class="d-flex justify-content-between mt-4" >
                                <button id="button-save-all-question" type="submit" class="btn btn-primary w-50 mr-2">✔ إضافة  allالسؤال</button>
                            </div>


                            
                        </form>
                    </div>
                @else
                    <p class="text-center">يرجى إنشاء الكويز أولاً لإضافة الأسئلة.</p>
                @endif
            </div>

            <div class="col-md-3">

                    @if($quiz)
                        <button class="btn btn-sidebar" id="addbtn" onclick="addNewPage()">
                            <i class="fas fa-plus-circle"></i> أضف سؤال جديد
                        </button>
                        <button class="btn btn-sidebar" data-toggle="modal" data-target="#templateModal">
                            <i class="fas fa-book"></i> اختر قالب سؤال
                        </button>
                        <button type="button" id="generateQuizFromPDF" class="btn btn-primary mt-2 w-100" disabled>إنشاء كويز من PDF</button>

                    @else
                        <button class="btn btn-sidebar" id="addbtn" onclick="alert('يرجى إنشاء الكويز أولاً لإضافة الأسئلة.')">
                            <i class="fas fa-plus-circle"></i> أضف سؤال جديد
                        </button>
                        <button class="btn btn-sidebar" onclick="alert('يرجى إنشاء الكويز أولاً لإضافة الأسئلة.')">
                            <i class="fas fa-book"></i> اختر قالب سؤال
                        </button>
                    @endif
                    <div class="col-md-12">
                    <div class="sidebar-section" >

                        <p class="new-feature">✨ يمكنك الآن إضافة محتوى PDF بتقنية الذكاء الإصطناعي! 🚀</p>
                        <p><img src="{{ asset('images/assistant-ia.gif') ?: 'https://via.placeholder.com/80' }}" alt="icon"></p>
                        <div class="pdf-upload">
                            <label for="pdfInput">📂 انقر هنا لتحميل ملف PDF</label>
                            <input type="file" id="pdfInput" accept="application/pdf">
                            <div class="pdf-file-name" id="pdfFileName">لم يتم اختيار ملف</div>
                            <button type="button" id="generateQuizFromPDF" class="btn btn-primary mt-2 w-100" disabled>إنشاء كويز من PDF</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="backgroundModal" tabindex="-1" aria-labelledby="backgroundModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="backgroundModalLabel">اختر خلفية</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    @php
                        $backgrounds = [
                            'bg1' => '/images/abajim.png',
                            'bg2' => '/images/bg1.jpg',
                            'bg3' => '/images/bg2.jpg',
                        ];
                    @endphp
                    @foreach ($backgrounds as $key => $bg)
                        <div class="background-option" data-bg="{{ $bg }}">
                            <img src="{{ $bg }}" alt="{{ $key }}">
                            <p>{{ $key }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="questionPreviewModal" tabindex="-1" aria-labelledby="questionPreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="questionPreviewModalLabel">معاينة السؤال</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="preview-content"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="templateModal" tabindex="-1" aria-labelledby="templateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="templateModalLabel">اختر قالب سؤال</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <div class="template-option" onclick="applyTemplate('multiple')">
                        <p>سؤال اختيار متعدد</p>
                    </div>
                    <div class="template-option" onclick="applyTemplate('descriptive')">
                        <p>سؤال صح/خطأ</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Added Edit Question Modal -->
    <div class="modal fade" id="editQuestionModal" tabindex="-1" aria-labelledby="editQuestionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editQuestionModalLabel">تعديل السؤال</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="edit-question-form" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="quiz_id" id="edit-quiz-id">
                        <input type="hidden" name="question_id" id="edit-question-id">

                        <div class="form-group">
                            <label for="edit-question-text">نص السؤال:</label>
                            <div id="edit-question-editor"></div>
                            <input type="hidden" name="question_text" id="edit-question-text" class="form-control" required>
                            <div class="invalid-feedback">يرجى إدخال نص السؤال.</div>
                        </div>
                        <!-- tbalbiz souha : ajouter explanation here  -->
                        <div class="form-group">
                            <label for="edit-explanation">إضافة شرح للإجابة (اختياري):</label>
                            <textarea name="explanation" id="edit-explanation" class="form-control" rows="3" placeholder="سجل شرح الإجابة الصحيحة..."></textarea>
                            <small class="form-text text-muted">سيتم عرض هذا الشرح للطالب إذا أجاب بشكل خاطئ.</small>
                        </div>

                        <div class="form-group">
                            <label for="edit-question-type">نوع السؤال:</label>
                            <select id="edit-question-type" name="type" class="form-control" onchange="handleEditQuestionTypeChange(this)">
                                <option value="multiple">اختيار متعدد</option>
                                <option value="descriptive">صح/خطأ</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="edit-grade">🎯 درجة السؤال:</label>
                            <input type="number" name="grade" id="edit-grade" class="form-control" min="1" required>
                            <div class="invalid-feedback">يرجى إدخال درجة السؤال (رقم أكبر من 0).</div>
                        </div>

                        <div class="form-group">
                            <label for="edit-image">📸 صورة (اختياري):</label>
                            <input type="file" name="image" id="edit-image" class="form-control" accept="image/*" onchange="previewEditMedia('image')">
                            <div class="media-preview" id="edit-image-preview"></div>
                        </div>

                        <div class="form-group">
                            <label for="edit-video">🎥 فيديو (اختياري):</label>
                            <input type="file" name="video" id="edit-video" class="form-control" accept="video/*" onchange="previewEditMedia('video')">
                            <div class="media-preview" id="edit-video-preview"></div>
                        </div>

                        <div id="edit-multiple-choice-section">
                            <div class="answer-options" id="edit-answer-options">
                                <!-- Answers will be dynamically added here -->
                            </div>
                            <div class="invalid-feedback d-block answers-error" style="display: none;">يرجى إدخال إجابتين على الأقل.</div>
                            <div class="invalid-feedback d-block correct-answer-error" style="display: none;">يرجى اختيار الإجابة الصحيحة.</div>
                        </div>

                        <div id="edit-true-false-section" style="display: none;">
                            <div class="answer-options">
                                <div class="answer-option-row">
                                    <!-- <div class="answer-option-col">
                                        <div class="answer-option">
                                            <span>صحيح</span>
                                            <input type="hidden" name="answers[0]" value="صح">
                                            
                                                <input type="radio" name="correct_answer" value="0" class="correct-answer-radio" required>
                                                
                                            
                                        </div>
                                    </div>
                                    <div class="answer-option-col">
                                        <div class="answer-option">
                                            <span>خطأ</span>
                                            <input type="hidden" name="answers[1]" value="خطأ">
                                            
                                                <input type="radio" name="correct_answer" value="1" class="correct-answer-radio">
                                            
                                        </div>
                                    </div> --> 
                                </div>
                            </div>
                            <div class="invalid-feedback d-block correct-answer-error" style="display: none;">يرجى اختيار الإجابة الصحيحة.</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <button type="submit" form="edit-question-form" class="btn btn-primary">حفظ التعديلات</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Quiz Settings Modal -->
<div class="modal fade" id="editQuizModal" tabindex="-1" aria-labelledby="editQuizModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editQuizModalLabel">تعديل إعدادات الكويز</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                @if($quiz)
                <form id="edit-quiz-form" action="{{ route('quiz.update', ['quiz_id' => $quiz->id]) }}" method="POST" class="needs-validation" novalidate>
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="edit-quiz-title">عنوان الكويز:</label>
        <input type="text" name="title" id="edit-quiz-title" class="form-control" value="{{ $quiz->title }}" required>
        <div class="invalid-feedback">يرجى إدخال عنوان الكويز.</div>
    </div>
    <div class="form-group">
        <label for="edit-quiz-time">مدة الكويز (بالدقائق):</label>
        <input type="number" name="time_limit" id="edit-quiz-time" class="form-control" value="{{ $quiz->time }}" min="1" required>
        <div class="invalid-feedback">يرجى إدخال مدة الكويز.</div>
    </div>
    <div class="form-group">
        <label for="edit-correct-answers">عدد الإجابات الصحيحة المطلوبة:</label>
        <input type="number" name="correct_answers" id="edit-correct-answers" class="form-control" value="{{ session('correct_answers', 1) }}" min="1" max="3" required>
        <div class="invalid-feedback">يرجى إدخال عدد الإجابات الصحيحة (بين 1 و3).</div>
    </div>
    <div class="form-group">
        <label for="edit-quiz-pass-mark">🎯 درجة النجاح:</label>
        <input type="number" name="pass_mark" id="edit-quiz-pass-mark" class="form-control" value="{{ $quiz->pass_mark }}" min="0" max="100" step="1" required>
        <div class="invalid-feedback">يرجى إدخال درجة النجاح (0-100).</div>
    </div>
    <div class="form-group">
        <label for="edit-quiz-status">حالة الكويز:</label>
        <select name="status" id="edit-quiz-status" class="form-control" required>
            <option value="active" {{ $quiz->status == 'active' ? 'selected' : '' }}>نشط</option>
            <option value="inactive" {{ $quiz->status == 'inactive' ? 'selected' : '' }}>غير نشط</option>
        </select>
        <div class="invalid-feedback">يرجى اختيار حالة الكويز.</div>
    </div>
    <div class="form-group">
        <label for="edit-quiz-certificate">
            <input type="checkbox" name="certificate" id="edit-quiz-certificate" value="1" {{ $quiz->certificate ? 'checked' : '' }}>
            إصدار شهادة عند النجاح
        </label>
    </div>
</form>                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                <button type="submit" form="edit-quiz-form" class="btn btn-primary">حفظ التعديلات</button>
            </div>
        </div>
    </div>
</div>
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999">
  <div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body" id="toast-message">Success message</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    
    <script>
            window.quizRoutes = {
        generateFromPDF: '{{ route("quiz.generateFromPDF") }}'
    };
    window.assetPaths = {
        diviseurs: '{{ asset('images/diviseurs.png') ?: 'https://via.placeholder.com/24' }}',
        regle: '{{ asset('images/regle.png') ?: 'https://via.placeholder.com/24' }}'
    };
    
        if (data.success_message) {
    document.getElementById('toast-message').textContent = data.success_message;
    const toastEl = document.getElementById('successToast');
    const toast = new bootstrap.Toast(toastEl);
    toast.show();
}

fetch(`/quiz/${quizId}/question/${questionId}/update`, {
    method: 'POST',
    body: formData,
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
})
.then(response => response.json())
.then(data => {
    if (data.success_message) {
        document.getElementById('toast-message').textContent = data.success_message;
        const toastEl = document.getElementById('successToast');
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    } else if (data.error_message) {
        alert(data.error_message); // Ou bien afficher dans une autre toast d’erreur
    }
})
.catch(error => {
    console.error('Erreur lors de la mise à jour:', error);
    alert('حدث خطأ أثناء تعديل السؤال');
});




</script>
    <script src="{{ asset('enseignant/js/quetab.js') }}"></script>
    <script>
    let initialPageCount = 1;
    let quizId = null;
    let baseQuestionCount = 0;
    let formQuestionIndex = 0;
    let deletedQuestions = [];
    let quillEditors = {};

    @if($quiz)
        initialPageCount = {{ $quiz->questions->count() + 1 }};
        quizId = {{ $quiz->id }};
        baseQuestionCount = {{ $quiz->questions->count() }};
        formQuestionIndex = 0;
    @endif

    let pageCount = initialPageCount;

    @if($quiz)
        quillEditors[initialPageCount] = new Quill(`#question-editor-${initialPageCount}`, {
            theme: "snow",
            placeholder: "اكتب السؤال هنا...",
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['link', 'image', 'video', 'formula'],
                    [{ 'header': 1 }, { 'header': 2 }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'direction': 'rtl' }],
                    [{ 'size': ['small', false, 'large', 'huge'] }],
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'font': [] }],
                    [{ 'align': [] }],
                    ['clean']
                ]
            }
        });

        // Bind Quill editor changes to hidden input for the initial page
        quillEditors[initialPageCount].on('text-change', () => {
            const hiddenInput = document.getElementById(`question-text-${initialPageCount}`);
            hiddenInput.value = quillEditors[initialPageCount].root.innerHTML;
        });
    @endif

    // Set up CSRF token for AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Handle PDF file input change
    document.getElementById('pdfInput').addEventListener('change', function(event) {
        var fileName = event.target.files[0] ? event.target.files[0].name : "لم يتم اختيار ملف";
        document.getElementById('pdfFileName').textContent = fileName;
    });

    // Add a new question page
    function addNewPage(template = null) {
        pageCount++;
        formQuestionIndex++;

        console.log('Adding new page with formQuestionIndex:', formQuestionIndex);

        const newTab = document.createElement('li');
        newTab.classList.add('nav-item');
        newTab.id = `tab-${pageCount}`;
        newTab.innerHTML = `
            <a class="nav-link active" data-toggle="tab" href="#page-${pageCount}">
                السؤال ${pageCount} <i class="fas fa-times" onclick="deleteTab(${pageCount})"></i>
            </a>
        `;
        document.getElementById('question-tabs').appendChild(newTab);

        // Deactivate other tabs
        document.querySelectorAll('#question-tabs .nav-link').forEach(tab => {
            tab.classList.remove('active');
        });

        const newPage = document.createElement('div');
        newPage.classList.add('tab-pane', 'quiz-box', 'active');
        newPage.id = `page-${pageCount}`;

        let questionText = template ? template.questionText : '';
        let questionType = template ? template.type : 'multiple';
        let answers = template ? template.answers : [];
        let correctAnswerIndex = template ? template.correctAnswerIndex : null;
        let grade = template ? template.grade : 10;

        newPage.innerHTML = `
            <h2 class="text-center">السؤال ${pageCount} <i class="fas fa-pen"></i></h2>
            <div class="form-group">
                <label for="question-text-${pageCount}">نص السؤال:</label>
                <div id="question-editor-${pageCount}"></div>
                <input type="hidden" name="questions[${formQuestionIndex}][question_text]" id="question-text-${pageCount}" class="form-control" required>
                <div class="invalid-feedback">يرجى إدخال نص السؤال.</div>
            </div>
            <div class="form-group">
                <label for="question-type-${pageCount}">نوع السؤال:</label>
                <select id="question-type-${pageCount}" class="form-control" name="questions[${formQuestionIndex}][type]" onchange="handleQuestionTypeChange(this, ${pageCount})">
                    <option value="multiple" ${questionType === 'multiple' ? 'selected' : ''}>اختيار متعدد</option>
                    <option value="descriptive" ${questionType === 'descriptive' ? 'selected' : ''}>صح/خطأ</option>
                </select>
            </div>
            <div class="form-group">
                <label for="grade-${pageCount}">🎯 درجة السؤال:</label>
                <input type="number" name="questions[${formQuestionIndex}][grade]" id="grade-${pageCount}" class="form-control" value="${grade}" min="1" required>
                <div class="invalid-feedback">يرجى إدخال درجة السؤال (رقم أكبر من 0).</div>
            </div>
            <div class="form-group">
                <label for="image-${pageCount}">📸 صورة (اختياري):</label>
                <input type="file" name="questions[${formQuestionIndex}][image]" id="image-${pageCount}" class="form-control" accept="image/*" onchange="previewMedia('image', ${pageCount})">
                <div class="media-preview" id="image-preview-${pageCount}"></div>
            </div>
            <div class="form-group">
                <label for="video-${pageCount}">🎥 فيديو (اختياري):</label>
                <input type="file" name="questions[${formQuestionIndex}][video]" id="video-${pageCount}" class="form-control" accept="video/*" onchange="previewMedia('video', ${pageCount})">
                <div class="media-preview" id="video-preview-${pageCount}"></div>
            </div>
            <div id="multiple-choice-section-${pageCount}" style="${questionType === 'multiple' ? '' : 'display: none;'}">
                <div class="answer-options" id="answer-options-${pageCount}">
                    <div class="answer-option-row">
                        <div class="answer-option-col">
                            <div class="answer-option">
                                <img src="{{ asset('images/diviseurs.png') ?: 'https://via.placeholder.com/24' }}" alt="icon">
                                <input type="text" name="questions[${formQuestionIndex}][answers][0]" placeholder="إجابة 1" value="${answers[0] || ''}" class="form-control answer-input" data-index="0" required>
                                <label>
                                    <input type="radio" name="questions[${formQuestionIndex}][correct_answer]" value="0" class="correct-answer-radio" ${correctAnswerIndex == 0 ? 'checked' : ''} required>
                                    صحيح
                                </label>
                            </div>
                            <div class="invalid-feedback d-block" style="display: none;">يرجى إدخال الإجابة الأولى.</div>
                        </div>
                        <div class="answer-option-col">
                            <div class="answer-option">
                                <img src="{{ asset('images/regle.png') ?: 'https://via.placeholder.com/24' }}" alt="icon">
                                <input type="text" name="questions[${formQuestionIndex}][answers][1]" placeholder="إجابة 2" value="${answers[1] || ''}" class="form-control answer-input" data-index="1" required>
                                <label>
                                    <input type="radio" name="questions[${formQuestionIndex}][correct_answer]" value="1" class="correct-answer-radio" ${correctAnswerIndex == 1 ? 'checked' : ''}>
                                    صحيح
                                </label>
                            </div>
                            <div class="invalid-feedback d-block" style="display: none;">يرجى إدخال الإجابة الثانية.</div>
                        </div>
                    </div>
                    <div class="answer-option-row">
                        <div class="answer-option-col">
                            <div class="answer-option">
                                <img src="{{ asset('images/surligneur.png') ?: 'https://via.placeholder.com/24' }}" alt="icon">
                                <input type="text" name="questions[${formQuestionIndex}][answers][2]" placeholder="إجابة 3" value="${answers[2] || ''}" class="form-control answer-input" data-index="2">
                                <label>
                                    <input type="radio" name="questions[${formQuestionIndex}][correct_answer]" value="2" class="correct-answer-radio" ${correctAnswerIndex == 2 ? 'checked' : ''}>
                                    صحيح
                                </label>
                            </div>
                        </div>
                        <div class="answer-option-col">
                            <div class="answer-option">
                                <img src="{{ asset('images/trombone.png') ?: 'https://via.placeholder.com/24' }}" alt="icon">
                                <input type="text" name="questions[${formQuestionIndex}][answers][3]" placeholder="إجابة 4" value="${answers[3] || ''}" class="form-control answer-input" data-index="3">
                                <label>
                                    <input type="radio" name="questions[${formQuestionIndex}][correct_answer]" value="3" class="correct-answer-radio" ${correctAnswerIndex == 3 ? 'checked' : ''}>
                                    صحيح
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="invalid-feedback d-block answers-error" style="display: none;">يرجى إدخال إجابتين على الأقل.</div>
                <div class="invalid-feedback d-block correct-answer-error" style="display: none;">يرجى اختيار الإجابة الصحيحة.</div>
            </div>
            <div id="true-false-section-${pageCount}" style="${questionType === 'descriptive' ? '' : 'display: none;'}">
                <div class="answer-options">
                    <div class="answer-option-row">
                        <div class="answer-option-col">
                            <div class="answer-option">
                                <img src="{{ asset('images/diviseurs.png') ?: 'https://via.placeholder.com/24' }}" alt="icon">
                                <span>صحيح</span>
                                <input type="hidden" name="questions[${formQuestionIndex}][answers][0]" value="صح">
                                    <input type="radio" name="questions[${formQuestionIndex}][correct_answer]" value="0" class="correct-answer-radio" ${correctAnswerIndex == 0 ? 'checked' : ''} required>
                                
                            </div>
                        </div>
                        <div class="answer-option-col">
                            <div class="answer-option">
                                <img src="{{ asset('images/regle.png') ?: 'https://via.placeholder.com/24' }}" alt="icon">
                                <span>خطأ</span>
                                <input type="hidden" name="questions[${formQuestionIndex}][answers][1]" value="خطأ">
                                
                                    <input type="radio" name="questions[${formQuestionIndex}][correct_answer]" value="1" class="correct-answer-radio" ${correctAnswerIndex == 1 ? 'checked' : ''}>
                                    
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="invalid-feedback d-block correct-answer-error" style="display: none;">يرجى اختيار الإجابة الصحيحة.</div>
            </div>
            <div class="d-flex justify-content-between mt-3">
                <button type="button" class="btn btn-preview w-50 mr-2" onclick="previewQuestion(${pageCount})">👁 معاينة السؤال</button>
                <button type="button" class="btn btn-duplicate w-50" onclick="duplicateQuestion(${pageCount})"><i class="fas fa-copy"></i> تكرار السؤال</button>
            </div>
        `;

        // Deactivate other panes
        document.querySelectorAll('#pages-container .tab-pane').forEach(pane => {
            pane.classList.remove('active');
        });

        document.getElementById('pages-container').appendChild(newPage);

        // Initialize Quill editor for the new page
        quillEditors[pageCount] = new Quill(`#question-editor-${pageCount}`, {
            theme: 'snow',
            placeholder: 'اكتب السؤال هنا...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['link', 'image', 'video', 'formula'],
                    [{ 'header': 1 }, { 'header': 2 }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'direction': 'rtl' }],
                    [{ 'size': ['small', false, 'large', 'huge'] }],
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'font': [] }],
                    [{ 'align': [] }],
                    ['clean']
                ]
            }
        });

        if (template) {
            quillEditors[pageCount].root.innerHTML = questionText;
        }

        // Bind Quill editor changes to hidden input
        quillEditors[pageCount].on('text-change', () => {
            const hiddenInput = document.getElementById(`question-text-${pageCount}`);
            hiddenInput.value = quillEditors[pageCount].root.innerHTML;
        });

        updateProgress();
    }

    // Delete a question tab (not the actual question in the database)
    function deleteTab(pageNum) {
        if (document.querySelectorAll('#question-tabs .nav-item').length <= 1) {
            alert('لا يمكن حذف السؤال الأخير. يجب أن يحتوي الكويز على سؤال واحد على الأقل.');
            return;
        }

        const tab = document.getElementById(`tab-${pageNum}`);
        const pane = document.getElementById(`page-${pageNum}`);

        deletedQuestions.push({
            tab: tab.outerHTML,
            pane: pane.outerHTML,
            pageNum: pageNum,
            quillContent: quillEditors[pageNum] ? quillEditors[pageNum].root.innerHTML : null
        });

        document.getElementById('undoBtn').style.display = 'block';

        tab.remove();
        pane.remove();
        delete quillEditors[pageNum];

        // Activate the first tab if the deleted one was active
        if (document.querySelector(`#tab-${pageNum} .nav-link`)?.classList.contains('active')) {
            const firstTab = document.querySelector('#question-tabs .nav-link');
            if (firstTab) {
                firstTab.classList.add('active');
                const firstPaneId = firstTab.getAttribute('href').substring(1);
                document.getElementById(firstPaneId).classList.add('active');
            }
        }

        updateProgress();
    }

    // Undo deletion of a question tab
    function undoDelete() {
        if (deletedQuestions.length === 0) return;

        const lastDeleted = deletedQuestions.pop();
        const { tab, pane, pageNum, quillContent } = lastDeleted;

        const tabContainer = document.getElementById('question-tabs');
        const newTab = document.createElement('li');
        newTab.classList.add('nav-item');
        newTab.id = `tab-${pageNum}`;
        newTab.innerHTML = tab;
        tabContainer.appendChild(newTab);

        const pagesContainer = document.getElementById('pages-container');
        const newPane = document.createElement('div');
        newPane.classList.add('tab-pane', 'quiz-box', 'active');
        newPane.id = `page-${pageNum}`;
        newPane.innerHTML = pane;
        pagesContainer.appendChild(newPane);

        document.querySelectorAll('#question-tabs .nav-link').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('#pages-container .tab-pane').forEach(p => p.classList.remove('active'));
        newTab.querySelector('.nav-link').classList.add('active');
        newPane.classList.add('active');

        quillEditors[pageNum] = new Quill(`#question-editor-${pageNum}`, {
            theme: 'snow',
            placeholder: 'اكتب السؤال هنا...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['link', 'image', 'video', 'formula'],
                    [{ 'header': 1 }, { 'header': 2 }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'direction': 'rtl' }],
                    [{ 'size': ['small', false, 'large', 'huge'] }],
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'font': [] }],
                    [{ 'align': [] }],
                    ['clean']
                ]
            }
        });

        if (quillContent) {
            quillEditors[pageNum].root.innerHTML = quillContent;
        }

        quillEditors[pageNum].on('text-change', () => {
            const hiddenInput = document.getElementById(`question-text-${pageNum}`);
            hiddenInput.value = quillEditors[pageNum].root.innerHTML;
        });

        if (deletedQuestions.length === 0) {
            document.getElementById('undoBtn').style.display = 'none';
        }

        updateProgress();
    }

    // Duplicate a question
    function duplicateQuestion(pageNum) {
        const quillEditor = quillEditors[pageNum];
        const questionText = quillEditor ? quillEditor.root.innerHTML : document.querySelector(`#page-${pageNum} p strong:contains("نص السؤال:")`)?.nextSibling?.textContent;
        const questionType = document.getElementById(`question-type-${pageNum}`)?.value || document.querySelector(`#page-${pageNum} p strong:contains("النوع:")`)?.nextSibling?.textContent;
        const grade = document.getElementById(`grade-${pageNum}`)?.value || document.querySelector(`#page-${pageNum} p strong:contains("الدرجة:")`)?.nextSibling?.textContent;
        let answers = [];
        let correctAnswerIndex = null;

        if (questionType === 'multiple' || questionType === 'اختيار متعدد') {
            answers = Array.from(document.querySelectorAll(`#answer-options-${pageNum} .answer-input`)).map(input => input.value);
            correctAnswerIndex = document.querySelector(`input[name="questions[${pageNum - baseQuestionCount - 1}][correct_answer]"]:checked`)?.value;
            if (!answers.length) {
                const answersList = document.querySelectorAll(`#page-${pageNum} h4:contains("الإجابات:") ~ ul li`);
                answersList.forEach((li, index) => {
                    const answerText = li.textContent.replace('(صحيح)', '').trim();
                    answers.push(answerText);
                    if (li.textContent.includes('(صحيح)')) {
                        correctAnswerIndex = index;
                    }
                });
            }
        } else {
            answers = ['صح', 'خطأ'];
            correctAnswerIndex = document.querySelector(`input[name="questions[${pageNum - baseQuestionCount - 1}][correct_answer]"]:checked`)?.value;
            if (correctAnswerIndex === undefined) {
                const answersList = document.querySelectorAll(`#page-${pageNum} h4:contains("الإجابات:") ~ ul li`);
                answersList.forEach((li, index) => {
                    const answerText = li.textContent.replace('(صحيح)', '').trim();
                    answers.push(answerText);
                    if (li.textContent.includes('(صحيح)')) {
                        correctAnswerIndex = index;
                    }
                });
            }
        }

        const template = {
            questionText,
            type: questionType === 'اختيار متعدد' ? 'multiple' : 'descriptive',
            answers,
            correctAnswerIndex,
            grade
        };

        addNewPage(template);
    }

    // Apply a predefined question template
    function applyTemplate(type) {
        let template = {};
        if (type === 'multiple') {
            template = {
                questionText: '<p>ما هو عاصمة مصر؟</p>',
                type: 'multiple',
                answers: ['القاهرة', 'الإسكندرية', 'الجيزة', 'أسوان'],
                correctAnswerIndex: 0,
                grade: 10
            };
        } else if (type === 'descriptive') {
            template = {
                questionText: '<p>القاهرة هي عاصمة مصر.</p>',
                type: 'descriptive',
                answers: ['صح', 'خطأ'],
                correctAnswerIndex: 0,
                grade: 5
            };
        }
        addNewPage(template);
        $('#templateModal').modal('hide');
    }

    // Handle question type change (Multiple Choice or True/False)
    function handleQuestionTypeChange(selectElement, pageNum) {
        const multipleChoiceSection = document.getElementById(`multiple-choice-section-${pageNum}`);
        const trueFalseSection = document.getElementById(`true-false-section-${pageNum}`);
        const answerInputs = document.querySelectorAll(`#answer-options-${pageNum} .answer-input`);
        const correctAnswerRadios = document.querySelectorAll(`input[name="questions[${pageNum - baseQuestionCount - 1}][correct_answer]"]`);

        if (selectElement.value === 'multiple') {
            multipleChoiceSection.style.display = 'block';
            trueFalseSection.style.display = 'none';
            answerInputs.forEach(input => {
                if (input.dataset.index == 0 || input.dataset.index == 1) {
                    input.setAttribute('required', 'required');
                } else {
                    input.removeAttribute('required');
                }
            });
            correctAnswerRadios.forEach(radio => radio.setAttribute('required', 'required'));
        } else if (selectElement.value === 'descriptive') {
            multipleChoiceSection.style.display = 'none';
            trueFalseSection.style.display = 'block';
            answerInputs.forEach(input => input.removeAttribute('required'));
            correctAnswerRadios.forEach(radio => radio.setAttribute('required', 'required'));
        }
    }

    // Preview media (image or video) for a question
    function previewMedia(type, pageNum) {
        const input = document.getElementById(`${type}-${pageNum}`);
        const previewContainer = document.getElementById(`${type}-preview-${pageNum}`);
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const url = URL.createObjectURL(file);
            if (type === 'image') {
                previewContainer.innerHTML = `<img src="${url}" alt="Preview Image">`;
            } else if (type === 'video') {
                previewContainer.innerHTML = `<video src="${url}" controls></video>`;
            }
        } else {
            previewContainer.innerHTML = '';
        }
    }

    // Preview a question in a modal
    function previewQuestion(pageNum) {
        const quillEditor = quillEditors[pageNum];
        const questionText = quillEditor ? quillEditor.root.innerHTML : document.querySelector(`#page-${pageNum} p strong:contains("نص السؤال:")`)?.nextSibling?.textContent;
        const questionType = document.getElementById(`question-type-${pageNum}`)?.value || document.querySelector(`#page-${pageNum} p strong:contains("النوع:")`)?.nextSibling?.textContent;
        const grade = document.getElementById(`grade-${pageNum}`)?.value || document.querySelector(`#page-${pageNum} p strong:contains("الدرجة:")`)?.nextSibling?.textContent;
        const imageInput = document.getElementById(`image-${pageNum}`)?.files[0];
        const videoInput = document.getElementById(`video-${pageNum}`)?.files[0];
        const imageSrc = document.querySelector(`#page-${pageNum} p strong:contains("الصورة:")`)?.nextSibling?.querySelector('img')?.src;
        const videoSrc = document.querySelector(`#page-${pageNum} p strong:contains("الفيديو:")`)?.nextSibling?.querySelector('video')?.src;
        let answers = [];
        let correctAnswerIndex = document.querySelector(`input[name="questions[${pageNum - baseQuestionCount - 1}][correct_answer]"]:checked`)?.value;

        if (questionType === 'multiple' || questionType === 'اختيار متعدد') {
            answers = Array.from(document.querySelectorAll(`#answer-options-${pageNum} .answer-input`)).map(input => input.value);
            if (!answers.length) {
                const answersList = document.querySelectorAll(`#page-${pageNum} h4:contains("الإجابات:") ~ ul li`);
                answersList.forEach((li, index) => {
                    const answerText = li.textContent.replace('(صحيح)', '').trim();
                    answers.push(answerText);
                    if (li.textContent.includes('(صحيح)')) {
                        correctAnswerIndex = index;
                    }
                });
            }
        } else {
            answers = ['صح', 'خطأ'];
            if (correctAnswerIndex === undefined) {
                const answersList = document.querySelectorAll(`#page-${pageNum} h4:contains("الإجابات:") ~ ul li`);
                answersList.forEach((li, index) => {
                    const answerText = li.textContent.replace('(صحيح)', '').trim();
                    answers.push(answerText);
                    if (li.textContent.includes('(صحيح)')) {
                        correctAnswerIndex = index;
                    }
                });
            }
        }

        let previewHtml = `
            <h4>السؤال ${pageNum}</h4>
            <p><strong>نص السؤال:</strong> ${questionText}</p>
            <p><strong>النوع:</strong> ${questionType === 'multiple' || questionType === 'اختيار متعدد' ? 'اختيار متعدد' : 'صح/خطأ'}</p>
            <p><strong>الدرجة:</strong> ${grade}</p>
        `;

        if (imageInput) {
            const imageUrl = URL.createObjectURL(imageInput);
            previewHtml += `<p><strong>الصورة:</strong> <img src="${imageUrl}" alt="Question Image" style="max-width: 200px; border-radius: 10px;"></p>`;
        } else if (imageSrc) {
            previewHtml += `<p><strong>الصورة:</strong> <img src="${imageSrc}" alt="Question Image" style="max-width: 200px; border-radius: 10px;"></p>`;
        }

        if (videoInput) {
            const videoUrl = URL.createObjectURL(videoInput);
            previewHtml += `<p><strong>الفيديو:</strong> <video src="${videoUrl}" controls style="max-width: 200px; border-radius: 10px;"></video></p>`;
        } else if (videoSrc) {
            previewHtml += `<p><strong>الفيديو:</strong> <video src="${videoSrc}" controls style="max-width: 200px; border-radius: 10px;"></video></p>`;
        }

        previewHtml += `<h5>الإجابات:</h5><ul>`;
        answers.forEach((answer, index) => {
            previewHtml += `<li>${answer} ${index == correctAnswerIndex ? '<span style="color: #28a745;">(صحيح)</span>' : ''}</li>`;
        });
        previewHtml += `</ul>`;

        document.getElementById('preview-content').innerHTML = previewHtml;
        $('#questionPreviewModal').modal('show');
    }

    // Update the progress bar and question count
    function updateProgress() {
        const totalTabs = document.querySelectorAll('#question-tabs .nav-item').length;
        document.getElementById('question-count').textContent = totalTabs;
        const progress = (totalTabs / 10) * 100;
        document.getElementById('progress-bar').style.width = `${progress}%`;
    }

    // Form validation for the question form
    document.getElementById('question-form')?.addEventListener('submit', function(event) {
        let valid = true;

        const activeTab = document.querySelector('#question-tabs .nav-link.active');
        if (!activeTab) return;

        const pageNum = activeTab.getAttribute('href').substring(6);
        const quillEditor = quillEditors[pageNum];
        const questionText = quillEditor.root.innerHTML.trim();
        const hiddenInput = document.getElementById(`question-text-${pageNum}`);
        if (!questionText || questionText === '<p><br></p>') {
            hiddenInput.classList.add('is-invalid');
            valid = false;
        } else {
            hiddenInput.value = questionText;
            hiddenInput.classList.remove('is-invalid');
        }

        const gradeInput = document.getElementById(`grade-${pageNum}`);
        if (!gradeInput.value || gradeInput.value < 1) {
            gradeInput.classList.add('is-invalid');
            valid = false;
        } else {
            gradeInput.classList.remove('is-invalid');
        }

        const questionType = document.getElementById(`question-type-${pageNum}`).value;
        const answerInputs = document.querySelectorAll(`#answer-options-${pageNum} .answer-input`);
        const correctAnswerRadio = document.querySelector(`input[name="questions[${pageNum - baseQuestionCount - 1}][correct_answer]"]:checked`);
        const answersError = document.querySelector(`#multiple-choice-section-${pageNum} .answers-error`);
        const correctAnswerError = document.querySelector(`#multiple-choice-section-${pageNum} .correct-answer-error`) || document.querySelector(`#true-false-section-${pageNum} .correct-answer-error`);

        if (questionType === 'multiple') {
            let filledAnswers = 0;
            answerInputs.forEach((input, idx) => {
                const answerValue = input.value.trim();
                if (answerValue) {
                    filledAnswers++;
                    input.classList.remove('is-invalid');
                    input.nextElementSibling.style.display = 'none';
                } else {
                    if (idx < 2) {
                        input.classList.add('is-invalid');
                        input.nextElementSibling.style.display = 'block';
                    } else {
                        input.removeAttribute('name');
                    }
                }
            });

            if (filledAnswers < 2) {
                valid = false;
                answersError.style.display = 'block';
            } else {
                answersError.style.display = 'none';
            }

            if (!correctAnswerRadio) {
                valid = false;
                correctAnswerError.style.display = 'block';
            } else {
                correctAnswerError.style.display = 'none';
            }
        } else if (questionType === 'descriptive') {
            if (!correctAnswerRadio) {
                valid = false;
                correctAnswerError.style.display = 'block';
            } else {
                correctAnswerError.style.display = 'none';
            }
        }

        if (!valid) {
            event.preventDefault();
            event.stopPropagation();
        }

        this.classList.add('was-validated');
    });

    // Form validation for the quiz metadata form
    document.getElementById('quiz-metadata-form')?.addEventListener('submit', function(event) {
        let valid = true;

        const titleInput = document.getElementById('quiz-title');
        if (!titleInput.value.trim()) {
            titleInput.classList.add('is-invalid');
            valid = false;
        } else {
            titleInput.classList.remove('is-invalid');
        }

        const timeInput = document.getElementById('quiz-time');
        if (!timeInput.value || timeInput.value < 1) {
            timeInput.classList.add('is-invalid');
            valid = false;
        } else {
            timeInput.classList.remove('is-invalid');
        }

        const passMarkInput = document.getElementById('quiz-pass-mark');
        if (!passMarkInput.value || passMarkInput.value < 0 || passMarkInput.value > 100) {
            passMarkInput.classList.add('is-invalid');
            valid = false;
        } else {
            passMarkInput.classList.remove('is-invalid');
        }

        const statusInput = document.getElementById('quiz-status');
        if (!statusInput.value) {
            statusInput.classList.add('is-invalid');
            valid = false;
        } else {
            statusInput.classList.remove('is-invalid');
        }

        if (!valid) {
            event.preventDefault();
            event.stopPropagation();
        }

        this.classList.add('was-validated');
    });

    // Toggle dark mode
    document.addEventListener('DOMContentLoaded', function () {
    const toggleDarkModeBtn = document.getElementById('toggleDarkMode');

    // Check if dark mode is enabled in localStorage
    const isDarkMode = localStorage.getItem('darkMode') === 'enabled';
    if (isDarkMode) {
        document.body.classList.add('dark-mode');
        toggleDarkModeBtn.innerHTML = '<i class="fas fa-sun"></i> الوضع الفاتح';
    } else {
        toggleDarkModeBtn.innerHTML = '<i class="fas fa-moon"></i> الوضع الداكن';
    }

    // Toggle dark mode on button click
    toggleDarkModeBtn.addEventListener('click', function () {
        document.body.classList.toggle('dark-mode');
        const icon = this.querySelector('i');
        const isDarkModeNow = document.body.classList.contains('dark-mode');

        if (isDarkModeNow) {
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
            this.innerHTML = '<i class="fas fa-sun"></i> الوضع الفاتح';
            localStorage.setItem('darkMode', 'enabled'); // Save dark mode state
        } else {
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
            this.innerHTML = '<i class="fas fa-moon"></i> الوضع الداكن';
            localStorage.setItem('darkMode', 'disabled'); // Save light mode state
        }
    });
});
    // Handle background selection
    document.querySelectorAll('.background-option').forEach(option => {
        option.addEventListener('click', function() {
            const bgImage = this.getAttribute('data-bg');
            document.body.style.backgroundImage = `url(${bgImage})`;
            document.body.style.backgroundSize = 'cover';
            document.body.style.backgroundPosition = 'center';
            $('#backgroundModal').modal('hide');
        });
    });

    // Make tabs sortable
    $("#question-tabs").sortable({
        items: "> li",
        update: function(event, ui) {
            const tabs = document.querySelectorAll('#question-tabs .nav-item');
            tabs.forEach((tab, index) => {
                const pageNum = tab.id.split('-')[1];
                const newIndex = index + 1;
                tab.id = `tab-${newIndex}`;
                const link = tab.querySelector('.nav-link');
                link.setAttribute('href', `#page-${newIndex}`);
                link.innerHTML = `السؤال ${newIndex} <i class="fas fa-times" onclick="deleteTab(${newIndex})"></i>`;
                const pane = document.getElementById(`page-${pageNum}`);
                pane.id = `page-${newIndex}`;
                pane.querySelector('h2').textContent = `السؤال ${newIndex}`;
            });
        }
    });

    // Added: Edit Question Functionality
// Global Quill editor for the edit modal

// Initialize the edit modal's Quill editor
function initializeEditQuillEditor() {
    const editorElement = document.getElementById('edit-question-editor');
    if (!editorElement) {
        console.error('edit-question-editor element not found in the DOM');
        return false;
    }

    editQuillEditor = new Quill('#edit-question-editor', {
        theme: 'snow',
        placeholder: 'اكتب السؤال هنا...',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                ['link', 'image', 'video', 'formula'],
                [{ 'header': 1 }, { 'header': 2 }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'direction': 'rtl' }],
                [{ 'size': ['small', false, 'large', 'huge'] }],
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'font': [] }],
                [{ 'align': [] }],
                ['clean']
            ]
        }
    });

    editQuillEditor.on('text-change', () => {
        const hiddenInput = document.getElementById('edit-question-text');
        if (hiddenInput) {
            hiddenInput.value = editQuillEditor.root.innerHTML;
        }
    });

    return true;
}

// Fetch question data from the server
async function fetchQuestionData(quizId, questionId) {
    try {
        const response = await fetch(`/quiz/${quizId}/question/${questionId}/edit`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            credentials: 'same-origin', // Include session cookies
        });

        console.log('Fetch response status:', response);

        if (response.status === 401) {
            throw new Error('Unauthenticated: Please log in again.');
        }

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.error_message || `HTTP error! Status: ${response.status}`);
        }
        return await response.json();
    } catch (error) {
        console.error('Error fetching question data:', error);
        throw error;
    }
}

// Populate the edit modal with question data

function populateEditModal(quizId, questionId, question) {
    
     console.log('question data :',question);   
     console.log('Loaded question:', question.question);
    // Extract question details
    const questionText = question.question.question || '';
    const questionType = question.question.type || 'multiple';
    const grade = question.question.grade || 10;
   
    const explanation = question.question.explanation || '';
    const image = question.image ? `/storage/${question.question.image}` : null;
    const video = question.video ? `/storage/${question.question.video}` : null;
    const answers = question.answers || [];
    console.log('Answers:', answers);

    const correctAnswerIndex = answers.findIndex(answer => answer.correct == 1);

    console.log('Populating modal with data:', { questionText, questionType, grade, image, video, answers, correctAnswerIndex });

    // Set form values
    document.getElementById('edit-quiz-id').value = quizId;
    document.getElementById('edit-question-id').value = questionId;
    document.getElementById('edit-question-form').action = `/quiz/${quizId}/question/${questionId}/update`;

    document.getElementById('edit-question-type').value = questionType;
    document.getElementById('edit-grade').value = grade;
    document.getElementById('edit-explanation').value = explanation;

    let editQuillEditor = null;
    const editorElement = document.getElementById('edit-question-editor');
    editQuillEditor = new Quill('#edit-question-editor', {
        theme: 'snow',
        placeholder: 'اكتب السؤال هنا...',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                ['link', 'image', 'video', 'formula'],
                [{ 'header': 1 }, { 'header': 2 }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'direction': 'rtl' }],
                [{ 'size': ['small', false, 'large', 'huge'] }],
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'font': [] }],
                [{ 'align': [] }],
                ['clean']
            ]
        }
    });

    editQuillEditor.on('text-change', () => {
        const hiddenInput = document.getElementById('edit-question-text');
        if (hiddenInput) {
            hiddenInput.value = editQuillEditor.root.innerHTML;
        }
    });
    // Initialize Quill editor if not already initialized
    // if (!editQuillEditor) {
    //     if (!initializeEditQuillEditor()) {
    //         alert('فشل في تهيئة محرر النصوص. يرجى المحاولة مرة أخرى.');
    //         return;
    //     }
    // }
    //initializeEditQuillEditor();
    // Set question text in Quill editor
    editQuillEditor.root.innerHTML = questionText;
    document.getElementById('edit-question-text').value = questionText;

    // Set media previews
    const imagePreview = document.getElementById('edit-image-preview');
    const videoPreview = document.getElementById('edit-video-preview');
    imagePreview.innerHTML = image ? `<img src="${image}" alt="Question Image" style="max-width: 200px; border-radius: 10px;">` : '';
    videoPreview.innerHTML = video ? `<video src="${video}" controls style="max-width: 200px; border-radius: 10px;"></video>` : '';


    // Populate answers
    const answerOptions = document.getElementById('edit-answer-options');
    answerOptions.innerHTML = '';

    if (questionType === 'multiple') {
        answers.forEach((answer, index) => {
            addEditAnswerOption(answer.answer, index, correctAnswerIndex);
        });
        document.getElementById('edit-multiple-choice-section').style.display = 'block';
        document.getElementById('edit-true-false-section').style.display = 'none';
    } else {
        document.getElementById('edit-multiple-choice-section').style.display = 'none';
        document.getElementById('edit-true-false-section').style.display = 'block';
        document.querySelector('#edit-true-false-section input[value="0"]').checked = correctAnswerIndex == 0;
        document.querySelector('#edit-true-false-section input[value="1"]').checked = correctAnswerIndex == 1;
    }

    // Show the modal
    console.log('Modal populated, showing...');
    $('#editQuestionModal').modal('show');
}

// Main function to edit a question
async function editQuestion(questionId, pageNum) {
    console.log('editQuestion called with questionId:', questionId, 'pageNum:', pageNum, 'quizId:', quizId);

    try {
       
        // Fetch question data
        const data = await fetchQuestionData(quizId, questionId);
       
        // Check for error messages in the response
        if (data.error_message) {
            alert(data.error_message);
            return;
        }
        // Populate the modal with the fetched data
        populateEditModal(quizId, questionId, data);
    } catch (error) {
        console.error('Error in editQuestion:', error);

        if (error.message.includes('Unauthenticated')) {
            alert('الرجاء تسجيل الدخول مرة أخرى. تم انتهاء الجلسة.');
            window.location.href = '/login';
        } else {
            alert('فشل في تحميل بيانات السؤا   ل: ' + error.message);
        }
    }
}

// Add an answer option in the edit modal
function addEditAnswerOption(answer = '', index = 0, correctAnswerIndex = null) {
    const answerOptions = document.getElementById('edit-answer-options');
    const newOption = document.createElement('div');
    newOption.classList.add('answer-option-row');
    newOption.innerHTML = `
        <div class="answer-option-col">
            <div class="answer-option" data-answer-index="${index}">
                <img src="{{ asset('images/diviseurs.png') ?: 'https://via.placeholder.com/24' }}" alt="icon">
                <input type="text" name="answers[${index}]" placeholder="إجابة ${index + 1}" value="${answer}" class="form-control answer-input" data-index="${index}" ${index < 2 ? 'required' : ''}>
                <label>
                    <input type="radio" name="correct_answer" value="${index}" class="correct-answer-radio" ${correctAnswerIndex === index ? 'checked' : ''} ${index < 2 ? 'required' : ''}>
                    صحيح
                </label>
                <button type="button" class="btn btn-danger btn-sm remove-answer" onclick="removeEditAnswerOption(this)" ${index < 2 ? 'style="display: none;"' : ''}><i class="fas fa-trash"></i></button>
            </div>
            <div class="invalid-feedback d-block" style="display: none;">يرجى إدخال الإجابة ${index < 2 ? 'الأولى' : 'الثانية'}.</div>
        </div>
    `;
    answerOptions.appendChild(newOption);
}

// Remove an answer option in the edit modal
function removeEditAnswerOption(button) {
    const answerOption = button.closest('.answer-option-row');
    answerOption.remove();

    // Reindex answers
    const answerInputs = document.querySelectorAll('#edit-answer-options .answer-input');
    answerInputs.forEach((input, idx) => {
        input.name = `answers[${idx}]`;
        input.dataset.index = idx;
        input.placeholder = `إجابة ${idx + 1}`;
        input.nextElementSibling.querySelector('input').value = idx;
        input.nextElementSibling.nextElementSibling.style.display = idx < 2 ? 'none' : 'block';
        input.required = idx < 2;
    });
}

// Handle question type change in the edit modal
function handleEditQuestionTypeChange(selectElement) {
    const multipleChoiceSection = document.getElementById('edit-multiple-choice-section');
    const trueFalseSection = document.getElementById('edit-true-false-section');
    const answerInputs = document.querySelectorAll('#edit-answer-options .answer-input');
    const correctAnswerRadios = document.querySelectorAll('#edit-answer-options .correct-answer-radio');

    if (selectElement.value === 'multiple') {
        multipleChoiceSection.style.display = 'block';
        trueFalseSection.style.display = 'none';
        answerInputs.forEach((input, idx) => {
            if (idx < 2) {
                input.setAttribute('required', 'required');
            } else {
                input.removeAttribute('required');
            }
        });
        correctAnswerRadios.forEach(radio => radio.setAttribute('required', 'required'));
    } else if (selectElement.value === 'descriptive') {
        multipleChoiceSection.style.display = 'none';
        trueFalseSection.style.display = 'block';
        answerInputs.forEach(input => input.removeAttribute('required'));
        correctAnswerRadios.forEach(radio => radio.removeAttribute('required'));
    }
}

// Preview media in the edit modal

function previewEditMedia(type) {
    const input = document.getElementById(`edit-${type}`);
    const previewContainer = document.getElementById(`edit-${type}-preview`);
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const url = URL.createObjectURL(file);
        if (type === 'image') {
            previewContainer.innerHTML = `<img src="${url}" alt="Preview Image" style="max-width: 200px; border-radius: 10px;">`;
        } else if (type === 'video') {
            previewContainer.innerHTML = `<video src="${url}" controls style="max-width: 200px; border-radius: 10px;"></video>`;
        }
    } else {
        previewContainer.innerHTML = '';
    }
}



     //Added: Delete Question Functionality
    function deleteQuestion(questionId, pageNum) {
        if (!confirm('هل أنت متأكد من حذف هذا السؤال؟')) {
            return;
        }

        fetch(`/quiz/${quizId}/question/${questionId}/delete`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success_message) {
                alert(data.success_message);
                // Remove the question from the UI
                document.getElementById(`tab-${pageNum}`).remove();
                document.getElementById(`page-${pageNum}`).remove();
                // Update tabs and progress
                updateProgress();
                // Activate the first tab if the deleted one was active
                const firstTab = document.querySelector('#question-tabs .nav-link');
                if (firstTab) {
                    firstTab.classList.add('active');
                    const firstPaneId = firstTab.getAttribute('href').substring(1);
                    document.getElementById(firstPaneId).classList.add('active');
                }
            } else if (data.error_message) {
                alert(data.error_message);
            }
        })
        .catch(error => {
            console.error('Error deleting question:', error);
            alert('حدث خطأ أثناء حذف السؤال. يرجى المحاولة مرة أخرى.');
        });
    }

    // Added: Form validation for the edit question form
    document.addEventListener('DOMContentLoaded', function() {
    $('#editQuestionModal').on('shown.bs.modal', function() {
        const form = document.getElementById('edit-question-form');
        if (form) {
            form.removeEventListener('submit', handleFormSubmit);
            form.addEventListener('submit', handleFormSubmit);
            console.log('Event listener attached to edit-question-form');
        } else {
            console.error('edit-question-form not found in the DOM');
        }
    });
});

function handleFormSubmit(event) {
    event.preventDefault(); // Prevent default form submission
    console.log('Edit form submitted');

    let valid = true;

    // Validate question text
    const questionText = editQuillEditor.root.innerHTML.trim();
    const hiddenInput = document.getElementById('edit-question-text');
    if (!questionText || questionText === '<p><br></p>') {
        hiddenInput.classList.add('is-invalid');
        valid = false;
    } else {
        hiddenInput.value = questionText;
        hiddenInput.classList.remove('is-invalid');
    }

    // Validate grade
    const gradeInput = document.getElementById('edit-grade');
    if (!gradeInput.value || gradeInput.value < 1) {
        gradeInput.classList.add('is-invalid');
        valid = false;
    } else {
        gradeInput.classList.remove('is-invalid');
    }

    // Validate answers
    const questionType = document.getElementById('edit-question-type').value;
    const answerInputs = document.querySelectorAll('#edit-answer-options .answer-input');
    const correctAnswerRadio = document.querySelector('#edit-answer-options input[name="correct_answer"]:checked') || document.querySelector('#edit-true-false-section input[name="correct_answer"]:checked');
    const answersError = document.querySelector('#edit-multiple-choice-section .answers-error');
    const correctAnswerError = document.querySelector('#edit-multiple-choice-section .correct-answer-error') || document.querySelector('#edit-true-false-section .correct-answer-error');

    if (questionType === 'multiple') {
        let filledAnswers = 0;
        answerInputs.forEach((input, idx) => {
            const answerValue = input.value.trim();
            if (answerValue) {
                filledAnswers++;
                input.classList.remove('is-invalid');
                input.nextElementSibling.style.display = 'none';
            } else {
                if (idx < 2) {
                    input.classList.add('is-invalid');
                    input.nextElementSibling.style.display = 'block';
                } else {
                    input.removeAttribute('name');
                }
            }
        });

        if (filledAnswers < 2) {
            valid = false;
            answersError.style.display = 'block';
        } else {
            answersError.style.display = 'none';
        }

        if (!correctAnswerRadio) {
            valid = false;
            correctAnswerError.style.display = 'block';
        } else {
            correctAnswerError.style.display = 'none';
        }
    } else if (questionType === 'descriptive') {
        if (!correctAnswerRadio) {
            valid = false;
            correctAnswerError.style.display = 'block';
        } else {
            correctAnswerError.style.display = 'none';
        }
    }

    if (!valid) {
        console.log('Form validation failed');
        this.classList.add('was-validated');
        return;
    }

    // Log the form data being sent
    const formData = new FormData(this);
    console.log('Form data being sent:');
    for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }

    // Submit the form via AJAX
    fetch(this.action, {
        method: 'POST', // Laravel uses POST with _method=PUT
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: formData,
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success_message) {
            alert(data.success_message);
            $('#editQuestionModal').modal('hide');
            window.location.reload(); // Reload to reflect changes
        } else if (data.error_message) {
            alert(data.error_message);
        } else if (data.errors) {
            console.error('Validation errors:', data.errors);
            alert('فشل في تعديل السؤال بسبب أخطاء في التحقق. يرجى التحقق من الحقول.');
        }
    })
    .catch(error => {
        console.error('Error updating question:', error);
        alert('حدث خطأ أثناء تعديل السؤال. يرجى المحاولة مرة أخرى.');
    });
}
// Form validation and submission for the edit quiz form
document.addEventListener('DOMContentLoaded', function() {
    $('#editQuizModal').on('shown.bs.modal', function() {
        const form = document.getElementById('edit-quiz-form');
        if (form) {
            form.removeEventListener('submit', handleQuizFormSubmit);
            form.addEventListener('submit', handleQuizFormSubmit);
            console.log('Event listener attached to edit-quiz-form');
        } else {
            console.error('edit-quiz-form not found in the DOM');
        }
    });
});

function handleQuizFormSubmit(event) {
    event.preventDefault(); // Prevent default form submission
    console.log('Edit quiz form submitted');

    let valid = true;

    // Validate title
    const titleInput = document.getElementById('edit-quiz-title');
    if (!titleInput.value.trim()) {
        titleInput.classList.add('is-invalid');
        valid = false;
    } else {
        titleInput.classList.remove('is-invalid');
    }

    // Validate time limit
    const timeInput = document.getElementById('edit-quiz-time');
    if (!timeInput.value || timeInput.value < 1) {
        timeInput.classList.add('is-invalid');
        valid = false;
    } else {
        timeInput.classList.remove('is-invalid');
    }

    // Validate correct answers
    const correctAnswersInput = document.getElementById('edit-correct-answers');
    if (!correctAnswersInput.value || correctAnswersInput.value < 1 || correctAnswersInput.value > 3) {
        correctAnswersInput.classList.add('is-invalid');
        valid = false;
    } else {
        correctAnswersInput.classList.remove('is-invalid');
    }

    // Validate pass mark
    const passMarkInput = document.getElementById('edit-quiz-pass-mark');
    if (!passMarkInput.value || passMarkInput.value < 0 || passMarkInput.value > 100) {
        passMarkInput.classList.add('is-invalid');
        valid = false;
    } else {
        passMarkInput.classList.remove('is-invalid');
    }

    // Validate status
    const statusInput = document.getElementById('edit-quiz-status');
    if (!statusInput.value) {
        statusInput.classList.add('is-invalid');
        valid = false;
    } else {
        statusInput.classList.remove('is-invalid');
    }

    if (!valid) {
        console.log('Quiz form validation failed');
        this.classList.add('was-validated');
        return;
    }

    // Log the form data being sent
    const formData = new FormData(this);
    console.log('Quiz form data being sent:');
    for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }

    // Submit the form via AJAX
    fetch(this.action, {
        method: 'POST', // Laravel uses POST with _method=PUT
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: formData,
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success_message) {
            alert(data.success_message);
            $('#editQuizModal').modal('hide');
            window.location.reload(); // Reload to reflect changes
        } else if (data.error_message) {
            alert(data.error_message);
        } else if (data.errors) {
            console.error('Validation errors:', data.errors);
            alert('فشل في تعديل إعدادات الكويز بسبب أخطاء في التحقق. يرجى التحقق من الحقول.');
        }
    })
    .catch(error => {
        console.error('Error updating quiz:', error);
        alert('حدث خطأ أثناء تعديل إعدادات الكويز. يرجى المحاولة مرة أخرى.');
    });
    document.addEventListener('DOMContentLoaded', function () {
    const schoolLevelSelect = document.getElementById('school_level_id');
    const subjectSelect = document.getElementById('subject_id');
    const subjectOptions = subjectSelect.querySelectorAll('option:not([value=""])');

    function updateSubjects() {
        const selectedLevel = schoolLevelSelect.options[schoolLevelSelect.selectedIndex];
        const selectedSectionId = selectedLevel ? selectedLevel.getAttribute('data-section-id') : null;

        // Log for debugging
        console.log('Selected Section ID:', selectedSectionId);

        // Reset subjects dropdown
        subjectOptions.forEach(option => {
            option.style.display = 'none';
            option.removeAttribute('selected');
        });
        subjectSelect.value = ''; // Reset selection

        if (selectedSectionId) {
            // Show only subjects that match the selected school level's section_id
            subjectOptions.forEach(option => {
                const optionSectionId = option.getAttribute('data-section-id');
                console.log('Option Section ID:', optionSectionId, 'Option Value:', option.value);
                if (optionSectionId === selectedSectionId) {
                    option.style.display = 'block';
                }
            });
        }

        // Trigger validation
        subjectSelect.dispatchEvent(new Event('change'));
    }

    // Run on page load to handle old() values
    updateSubjects();

    // Run whenever the school level changes
    schoolLevelSelect.addEventListener('change', updateSubjects);
});
}

</script>

<script>
   
    $(document).ready(function() {
   
        function loadMaterials(levelId, subjectSelectId, selectedSubjectId = null) {

            if (levelId && !isNaN(levelId) && levelId > 0) {
                $.ajax({
                    url: '{{ route('quiz.getMaterials', ':levelId') }}'.replace(':levelId', levelId),
                    type: 'GET',
                    dataType: 'json',
                    beforeSend: function() {
                        $(subjectSelectId).html('<option value="">جارٍ التحميل...</option>');
                    },
                    success: function(data) {
                        $(subjectSelectId).empty().append('<option value="">-- اختر المادة --</option>');
                        if (data.length > 0) {
                            $.each(data, function(index, material) {
                                var isSelected = selectedSubjectId && material.id == selectedSubjectId ? 'selected' : '';
                                $(subjectSelectId).append(
                                    `<option value="${material.id}" data-section-id="${material.section_id}" ${isSelected}>${material.name}</option>`
                                );
                            });
                        } else {
                            $(subjectSelectId).append('<option value="">لا توجد مواد متاحة</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error, xhr.responseText);
                        $(subjectSelectId).empty().append('<option value="">خطأ في تحميل المواد</option>');
                        alert('حدث خطأ أثناء تحميل المواد');
                    }
                });
            } else {
                console.warn('Invalid or empty levelId:', levelId);
                $(subjectSelectId).empty().append('<option value="">-- اختر المادة --</option>');
            }
        }

        $('#school_level_id').on('change', function() {
            var levelId = $(this).val();
            console.log('Create form level changed:', levelId);
            loadMaterials(levelId, '#subject_id', null);
        });

        @if(old('school_level_id'))
            console.log('Initializing create form with old level:', {{ old('school_level_id') }});
            $('#school_level_id').val({{ old('school_level_id') }}).trigger('change');
        @endif
    });


</script>
</body>
</html>