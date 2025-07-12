<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>لوحة تحكم الطالب</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="shortcut icon" href="{{ asset('images/abajimLOGO.png') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link href="https://unpkg.com/tippy.js@6/dist/tippy.css" rel="stylesheet">
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        :root {
            --primary-color: #42a5f5;
            --secondary-color: #1e88e5;
            --success-color: #4caf50;
            --warning-color: #ff9800;
            --danger-color: #f44336;
            --text-color: #333;
            --light-bg: #e3f2fd;
            --card-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            --floral-accent: #ff8a80;
            --soft-bg: #f0faff;
        }

        body {
            background: linear-gradient(135deg, var(--soft-bg) 0%, var(--light-bg) 100%);
            font-family: 'Tajawal', sans-serif;
            color: var(--text-color);
            overflow-x: hidden;
        }

        .navbar-custom {
            background: linear-gradient(135deg, #1e88e5 0%, #42a5f5 100%);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 10px 30px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-custom .logo img {
            height: 45px;
            transition: transform 0.3s;
        }

        .navbar-custom .logo:hover img {
            transform: scale(1.05);
        }

        .notification-icon, .profile-icon {
            position: relative;
            cursor: pointer;
            margin-left: 20px;
            transition: all 0.3s;
        }

        .notification-icon i, .profile-icon i {
            color: white;
            font-size: 1.4rem;
            transition: transform 0.3s;
        }

        .notification-icon:hover i {
            transform: scale(1.1);
            color: #ffeb3b;
        }

        .profile-icon:hover i {
            transform: rotate(30deg);
            color: #a5d6a7;
        }

        .notification-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--floral-accent);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            animation: pulse 1.5s infinite;
        }

        .notification-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            width: 300px;
            max-height: 500px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            display: none;
            z-index: 1001;
            overflow: hidden;
            transform-origin: top right;
            animation: scaleIn 0.2s ease-out;
            border: 1px solid #e0e0e0;
        }

        .notification-icon:hover .notification-dropdown {
            display: block;
        }

        .notification-header {
            padding: 15px 20px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .notification-header .mark-all-read {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .notification-header .mark-all-read:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .notification-list {
            max-height: 400px;
            overflow-y: auto;
            padding: 0;
        }

        .notification-list::-webkit-scrollbar {
            width: 6px;
        }

        .notification-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .notification-list::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .notification-list::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .notification-item {
            padding: 15px 20px;
            border-bottom: 1px solid #f5f5f5;
            transition: all 0.3s;
            display: flex;
            align-items: flex-start;
        }

        .notification-item.unread {
            background-color: #f8fafd;
            border-left: 3px solid var(--primary-color);
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item:hover {
            background-color: #f5f9ff;
        }

        .notification-icon-small {
            margin-right: 15px;
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }

        .notification-icon-small.info {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        .notification-icon-small.success {
            background: linear-gradient(135deg, var(--success-color), #43a047);
        }

        .notification-icon-small.warning {
            background: linear-gradient(135deg, var(--warning-color), #fb8c00);
        }

        .notification-icon-small.error {
            background: linear-gradient(135deg, #ff7043, var(--danger-color));
        }

        .notification-content {
            flex-grow: 1;
        }

        .notification-title {
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }

        .notification-message {
            font-size: 0.9rem;
            color: #666;
            line-height: 1.4;
            margin-bottom: 8px;
        }

        .notification-time {
            font-size: 0.75rem;
            color: #999;
            display: flex;
            align-items: center;
        }

        .notification-time i {
            font-size: 0.7rem;
            margin-right: 5px;
        }

        .notification-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
        }

        .notification-action {
            font-size: 0.8rem;
            padding: 3px 10px;
            border-radius: 4px;
            margin-left: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .notification-action.view {
            background: #e3f2fd;
            color: var(--primary-color);
        }

        .notification-action.view:hover {
            background: #bbdefb;
        }

        .notification-action.dismiss {
            background: #ffebee;
            color: var(--danger-color);
        }

        .notification-action.dismiss:hover {
            background: #ffcdd2;
        }

        .no-notifications {
            padding: 30px 20px;
            text-align: center;
            color: #999;
        }

        .no-notifications i {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #e0e0e0;
        }

        .analytics-btn {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .analytics-btn i {
            font-size: 1rem;
        }

        .analytics-btn:hover {
            background: linear-gradient(90deg, var(--secondary-color), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            color: white;
            text-decoration: none;
        }

        .settings-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            width: 200px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            display: none;
            z-index: 1001;
            overflow: hidden;
        }

        .profile-icon:hover .settings-dropdown {
            display: block;
            animation: fadeIn 0.3s ease-out;
        }

        .settings-dropdown a {
            display: block;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s;
            border-bottom: 1px solid #f0f0f0;
        }

        .settings-dropdown a:hover {
            background: #f5f5f5;
            color: var(--primary-color);
            padding-right: 20px;
        }

        .settings-dropdown a:last-child {
            border-bottom: none;
            color: var(--danger-color);
        }

        .settings-dropdown a:last-child:hover {
            background: #ffebee;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 15px;
        }

        .section-title {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            font-size: 1.2rem;
            font-weight: 600;
            box-shadow: var(--card-shadow);
            border-right: 4px solid var(--floral-accent);
        }

        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            border: none;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .progress-container {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            margin-bottom: 25px;
        }

        .progress {
            height: 18px;
            border-radius: 8px;
            background-color: #e9ecef;
        }

        .progress-bar {
            background: linear-gradient(90deg, var(--success-color), #66bb6a);
            border-radius: 8px;
            transition: width 1s ease-in-out;
        }

        .quiz-card {
            perspective: 1000px;
            margin-bottom: 20px;
        }

        .quiz-card-flip {
            position: relative;
            width: 100%;
            height: 100px;
            transition: transform 0.6s;
            transform-style: preserve-3d;
        }

        .quiz-card.flipped .quiz-card-flip {
            transform: rotateY(180deg);
        }

        .quiz-card-front, .quiz-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            background: #fff;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 15px;
            display: flex;
            align-items: center;
        }

        .quiz-card-front {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .quiz-card-back {
            transform: rotateY(180deg);
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            flex-direction: column;
            justify-content: center;
            gap: 8px;
            text-align: center;
        }

        .quiz-info {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }

        .quiz-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .quiz-info:hover .quiz-icon {
            transform: scale(1.1);
        }

        .quiz-details h5 {
            margin: 0;
            font-size: 1.1rem;
        }

        .quiz-details-back p {
            margin: 5px 0;
            font-size: 0.95rem;
        }

        .filter-form {
            background: #fff;
            padding: 15px;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            margin-bottom: 25px;
            transition: max-height 0.3s ease-in-out;
            overflow: hidden;
        }

        .filter-form.collapsed {
            max-height: 60px;
        }

        .filter-form.expanded {
            max-height: 300px;
        }

        .select2-container--default .select2-selection--single {
            height: 40px;
            border: 2px solid #ddd;
            border-radius: 6px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px;
            padding-right: 15px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
        }

        .action-btn {
            padding: 8px 16px;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .action-btn.play {
            background: linear-gradient(90deg, var(--success-color), #66bb6a);
        }

        .action-btn.replay {
            background: linear-gradient(90deg, var(--warning-color), #ffb300);
        }

        .action-btn.disabled {
            background: #ccc !important;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .action-btn:hover:not(.disabled) {
            transform: scale(1.05);
            filter: brightness(1.1);
            color: white;
        }

        .stats-card {
            text-align: center;
            padding: 15px;
        }

        .stats-number {
            font-size: 2.2rem;
            font-weight: bold;
            color: var(--primary-color);
            margin: 8px 0;
        }

        .stats-label {
            color: #666;
            font-size: 0.9rem;
        }

        .motivational-quote {
            background: #fff;
            padding: 15px;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            text-align: center;
            margin-bottom: 25px;
            font-style: italic;
            color: #555;
            border-right: 4px solid var(--floral-accent);
            position: relative;
        }

        .motivational-quote::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(255, 138, 128, 0.1));
            border-radius: 12px;
            z-index: 0;
        }

        .motivational-quote * {
            position: relative;
            z-index: 1;
        }

        .roadmap-container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            margin-bottom: 25px;
            position: relative;
            overflow-x: auto;
            background: linear-gradient(180deg, #f5f5dc 0%, #e4d96f 100%);
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%"><path d="M0 200 Q100 150 200 180 Q300 210 400 150 Q500 90 600 120 Q700 150 800 80" fill="none" stroke="%238b4513" stroke-width="2"/></svg>');
            background-size: cover;
        }

        .roadmap {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            min-width: 600px;
            padding: 20px 0;
            height: 200px;
            margin: 0 auto;
        }

        .roadmap-path {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 200px;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%"><path d="M0 180 Q100 120 200 150 Q300 180 400 120 Q500 60 600 90 Q700 120 800 60" fill="none" stroke="%238b4513" stroke-width="4" stroke-dasharray="5,5" stroke-linecap="round"/></svg>') no-repeat center;
            z-index: 0;
        }

        .roadmap-start, .roadmap-end {
            position: absolute;
            z-index: 1;
        }

        .roadmap-start {
            right: 0;
            bottom: 20px;
            width: 50px;
            height: 50px;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="8" fill="%2399cc99"/><path fill="%238b4513" d="M12 6v12m-6-6h12"/></svg>') no-repeat center;
            background-size: 40px;
        }

        .roadmap-end {
            left: 0;
            top: 20px;
            width: 60px;
            height: 60px;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="%23d4a017" d="M4 8h16v10H4z"/><path fill="%23b8860b" d="M4 6h16v2H4zm0 12h16v2H4z"/><circle cx="12" cy="13" r="2" fill="%23ffeb3b"/></svg>') no-repeat center;
            background-size: 50px;
            animation: glow 2s infinite;
        }

        .roadmap-end-label {
            position: absolute;
            left: 70px;
            top: 20px;
            font-size: 0.9rem;
            color: #d4a017;
            font-weight: bold;
            text-shadow: 0 0 5px rgba(212, 160, 23, 0.7);
        }

        .roadmap-milestone {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: absolute;
            z-index: 1;
        }

        .roadmap-icon {
            width: 50px;
            height: 50px;
            margin-bottom: 10px;
            transition: transform 0.3s ease;
            animation: bounce 1.5s infinite;
        }

        .roadmap-milestone[data-landmark="treasure-chest"] .roadmap-icon {
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="%23d4a017" d="M4 8h16v8H4z"/><path fill="%23b8860b" d="M4 6h16v2H4zm0 10h16v2H4z"/><circle cx="12" cy="12" r="1" fill="%23ffeb3b"/></svg>') no-repeat center;
            background-size: 40px;
        }

        .roadmap-milestone[data-landmark="island"] .roadmap-icon {
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="8" fill="%2399cc99"/><path fill="%238b4513" d="M12 6v6m-3-3h6"/></svg>') no-repeat center;
            background-size: 40px;
        }

        .roadmap-milestone[data-landmark="future"] .roadmap-icon {
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="%23cccccc" d="M4 8h16v8H4z"/><path fill="%23b0b0b0" d="M4 6h16v2H4zm0 10h16v2H4z"/></svg>') no-repeat center;
            background-size: 40px;
        }

        .roadmap-milestone.completed .roadmap-icon {
            filter: brightness(1.2);
            animation: glow 1.5s infinite;
        }

        .roadmap-milestone:hover .roadmap-icon {
            transform: scale(1.1);
        }

        .roadmap-label {
            font-size: 0.9rem;
            text-align: center;
            color: #333;
            background: rgba(255, 255, 255, 0.9);
            padding: 5px 10px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .roadmap-date {
            font-size: 0.8rem;
            color: #666;
            background: rgba(255, 255, 255, 0.7);
            padding: 2px 8px;
            border-radius: 5px;
        }

        .roadmap-current {
            position: absolute;
            width: 40px;
            height: 40px;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="%238b4513" d="M4 8h16v8H4z"/><path fill="%23ffffff" d="M12 8v8m-4-4h8"/></svg>') no-repeat center;
            background-size: 30px;
            z-index: 2;
            animation: pulse 2s infinite;
        }

        .badges-container {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            margin-bottom: 25px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .badge-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 10px;
            transition: transform 0.3s;
        }

        .badge-item:hover {
            transform: scale(1.05);
        }

        .badge-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .badge-icon.bronze {
            background: linear-gradient(135deg, #cd7f32, #a97142);
        }

        .badge-icon.silver {
            background: linear-gradient(135deg, #c0c0c0, #a9a9a9);
        }

        .badge-icon.gold {
            background: linear-gradient(135deg, #ffd700, #e6b800);
        }

        .badge-icon.practice-star {
            background: linear-gradient(135deg, #ff4500, #ff6347);
        }

        .badge-icon.time-master {
            background: linear-gradient(135deg, #00ced1, #20b2aa);
        }

        .badge-icon.milestone {
            background: linear-gradient(135deg, #800080, #9932cc);
        }

        .badge-icon.streak {
            background: linear-gradient(135deg, #ff69b4, #ff1493);
        }

        .badge-item:hover .badge-icon {
            transform: scale(1.1);
        }

        .badge-name {
            margin-top: 10px;
            font-size: 1rem;
            color: var(--text-color);
            font-weight: 600;
        }

        .filter-toggle {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
            margin-bottom: 10px;
        }

        .filter-toggle:hover {
            background: var(--secondary-color);
        }

        .mascot-container {
            position: fixed;
            bottom: 15px;
            left: 15px;
            z-index: 1000;
        }

        .mascot {
            width: 70px;
            height: 70px;
            animation: bounce 1s infinite;
        }

        .mascot-message {
            position: absolute;
            top: -45px;
            left: 80px;
            background: var(--primary-color);
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease-in-out;
        }

        .pagination {
            margin-bottom: 0;
        }

        .page-link {
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            margin: 0 2px;
            border-radius: 4px;
        }

        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .page-link:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        @keyframes glow {
            0% { box-shadow: 0 0 5px rgba(212, 160, 23, 0.5); }
            50% { box-shadow: 0 0 15px rgba(212, 160, 23, 1); }
            100% { box-shadow: 0 0 5px rgba(212, 160, 23, 0.5); }
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        @keyframes scaleIn {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .dashboard-container {
                margin: 15px auto;
                padding: 0 10px;
            }

            .stats-number {
                font-size: 1.8rem;
            }

            .stats-label {
                font-size: 0.8rem;
            }

            .quiz-card-front {
                flex-direction: column;
                align-items: flex-start;
            }

            .quiz-actions {
                margin-top: 10px;
            }

            .roadmap {
                min-width: 500px;
                height: 180px;
            }

            .roadmap-icon {
                width: 40px;
                height: 40px;
                background-size: 30px;
            }

            .roadmap-label {
                font-size: 0.8rem;
                padding: 4px 8px;
            }

            .roadmap-start, .roadmap-end {
                width: 40px;
                height: 40px;
                background-size: 30px;
            }

            .roadmap-current {
                width: 30px;
                height: 30px;
                background-size: 20px;
            }

            .roadmap-end-label {
                font-size: 0.8rem;
                left: 50px;
                top: 15px;
            }

            .navbar-custom {
                padding: 10px 15px;
                
            }

            .notification-icon, .profile-icon {
                margin-left: 20px;
            }

            .notification-icon i, .profile-icon i {
                font-size: 1.2rem;
            }

            .notification-dropdown {
                width: 280px;
                right: -50px;
                left: auto;
            }

            .settings-dropdown {
                width: 160px;
                right: 0;
                left: auto;
            }

            .analytics-btn {
                padding: 6px 12px;
                font-size: 0.8rem;
            }

            .analytics-btn span {
                display: none;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center w-100">
                <!-- Logo -->
                <div class="logo-container">
                    <div class="logo">
                        <img src="{{ asset('images/abajim.png') }}" alt="Abajim" style="height: 50px;" onerror="this.src='/images/abajim.png';">
                    </div>
                </div>

                <!-- Navbar Actions -->
                <div class="d-flex align-items-center gap-3">
                    <!-- Notification Icon -->
                    <div class="notification-icon" id="notificationIcon" data-tippy-content="الإشعارات">
                        <i class="fas fa-bell fa-lg"></i>
                        <span class="notification-count" id="notificationCount">جديد</span>
                        <div class="notification-dropdown" id="notificationDropdown">
                            <div class="notification-header">
                                <h5>الإشعارات</h5>
                                <button class="mark-all-read" onclick="markAllAsRead()">تحديد الكل كمقروء</button>
                            </div>
                            <div class="notification-list" id="notificationList">
                                <!-- Initial content will be overwritten by JavaScript -->
                            </div>
                        </div>
                    </div>

                    <!-- BI Analytics Button -->
                    <a href="{{ route('student.performance') }}" class="btn analytics-btn" id="analyticsBtn" data-tippy-content="تحليلات الأداء">
                        <i class="fas fa-chart-bar"></i>
                        <span>تقارير حول أدائي</span>
                    </a>

                    <a href="{{ route('student.results') }}" class="btn analytics-btn" id="analyticsBtn" data-tippy-content="نتائجي">
                        <i class="fas fa-book"></i>
                        <span>استكشف النتائج الفارطة</span>
                    </a>


                    <!-- Account Settings Icon -->
                    <div class="profile-icon" id="settingsIcon" data-tippy-content="إعدادات الحساب">
                        <i class="fas fa-cog fa-lg"></i>
                        <div class="settings-dropdown" id="settingsDropdown">
                            <a href="{{ route('enseignant.edit') }}" class="dropdown-item text-dark">الإعدادات</a>
                            <a href="{{ route('logout') }}" class="dropdown-item text-dark" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> الخروج</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="dashboard-container">
        <!-- Badges Section -->
        <div class="badges-container">
            <div class="section-title">
                <i class="fas fa-trophy"></i> أوسمتك
            </div>
            @if ($badges->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-circle fa-2x text-muted mb-2"></i>
                    <p class="text-muted">لم تحصل على أوسمة بعد! أكمل المزيد من الاختبارات لتربح أوسمة!</p>
                </div>
            @else
                @foreach ($badges as $badge)
                    <div class="badge-item">
                        <div class="badge-icon {{ str_replace('_', '-', $badge->badge_type) }}">
                            @switch($badge->badge_type)
                                @case('StreakMaster')
                                    <img src="{{ asset('images/bronze-medal.svg') }}" alt="Bronze Badge" width="40" height="40">
                                    @break
                                @case('QuickLearner')
                                    <img src="{{ asset('images/silver-medal.svg') }}" alt="Silver Badge" width="40" height="40">
                                    @break
                                @case('PerfectScore')
                                    <img src="{{ asset('images/gold-medal.svg') }}" alt="Gold Badge" width="40" height="40">
                                    @break
                                @case('TimeMaster')
                                    <img src="{{ asset('images/hourglass.svg') }}" alt="Time Master Badge" width="40" height="40">
                                    @break
                                @case('FirstTry')
                                    <img src="{{ asset('images/star.svg') }}" alt="Milestone 10 Badge" width="40" height="40">
                                    @break
                            @endswitch
                        </div>
                        <div class="badge-name">{{ $badge->badge_name }}</div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stats-card">
                    <i class="fas fa-book-open fa-2x text-primary"></i>
                    <div class="stats-number">{{ $totalQuizzes ?? 0 }}</div>
                    <div class="stats-label">الاختبارات المتاحة</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card">
                    <i class="fas fa-check-circle fa-2x text-success"></i>
                    <div class="stats-number">{{ $completedQuizzes ?? 0 }}</div>
                    <div class="stats-label">الاختبارات المكتملة</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card">
                    <i class="fas fa-trophy fa-2x text-warning"></i>
                    <div class="stats-number">{{ round($progress ?? 0) }}%</div>
                    <div class="stats-label">معدل النجاح</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card">
                    <i class="fas fa-clock fa-2x text-info"></i>
                    <div class="stats-number">{{ $averageTime ?? '0' }}</div>
                    <div class="stats-label">متوسط وقت الإجابة</div>
                </div>
            </div>
        </div>

        <div class="progress-container">
            <h4 class="mb-3">تقدمك</h4>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: {{ $progress ?? 0 }}%;" 
                     aria-valuenow="{{ $progress ?? 0 }}" aria-valuemin="0" aria-valuemax="100">
                    {{ round($progress ?? 0) }}%
                </div>
            </div>
        </div>

        <div class="roadmap-container">
            <div class="section-title">
                <i class="fas fa-map"></i> مغامرة البحث عن الكنز
            </div>
            @if (!isset($results) || $results->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-circle fa-2x text-muted mb-2"></i>
                    <p class="text-muted">ابدأ مغامرة البحث عن الكنز الآن! أكمل اختبارًا لتكتشف الكنوز!</p>
                </div>
            @else
                <div class="roadmap">
                    <div class="roadmap-path"></div>
                    <div class="roadmap-start"></div>
                    <div class="roadmap-end"></div>
                    <div class="roadmap-end-label">ابحث عن الكنز لتحقق النجاح!</div>
                    @php
                        $results = $results->sortBy('created_at');
                        $completedCount = $results->count();
                        $futureMilestones = min(2, max(1, 5 - $completedCount));
                        $totalMilestones = $completedCount + $futureMilestones;
                        $positionStep = $totalMilestones > 1 ? 80 / ($totalMilestones - 1) : 0;
                        $baseVerticalPositions = [180, 120, 150, 90, 60];
                        $verticalPositions = [];
                        for ($i = 0; $i < $totalMilestones; $i++) {
                            $verticalPositions[] = $baseVerticalPositions[$i % count($baseVerticalPositions)];
                        }
                        $landmarks = ['treasure-chest', 'island', 'treasure-chest'];
                    @endphp
                    @foreach ($results as $index => $result)
                        <div class="roadmap-milestone {{ $result->score >= 50 ? 'completed' : '' }}" 
                             data-landmark="{{ $landmarks[$index % count($landmarks)] }}"
                             style="right: {{ 10 + $index * $positionStep }}%; bottom: {{ $verticalPositions[$index] }}px;">
                            <div class="roadmap-icon"></div>
                            <div class="roadmap-label">
                                {{ $result->quiz->title }}
                            </div>
                            <div class="roadmap-date">
                                {{ date('Y-m-d', strtotime($result->created_at)) }}
                            </div>
                        </div>
                    @endforeach
                    @for ($i = 0; $i < $futureMilestones; $i++)
                        @php $milestoneIndex = $completedCount + $i; @endphp
                        <div class="roadmap-milestone future" 
                             data-landmark="future"
                             style="right: {{ 10 + $milestoneIndex * $positionStep }}%; bottom: {{ $verticalPositions[$milestoneIndex] }}px;">
                            <div class="roadmap-icon"></div>
                            <div class="roadmap-label">
                                @if ($i == 0)
                                    الاختبار التالي!
                                @else
                                    تحدي قادم!
                                @endif
                            </div>
                            <div class="roadmap-date">
                                قريبًا
                            </div>
                        </div>
                    @endfor
                    @php
                        $currentPosition = $completedCount * $positionStep + 10;
                        $currentVerticalPosition = $completedCount < $totalMilestones ? $verticalPositions[$completedCount] : $verticalPositions[$totalMilestones - 1];
                        if ($completedCount == 0) {
                            $currentPosition = 10;
                            $currentVerticalPosition = 180;
                        }
                    @endphp
                    <div class="roadmap-current" 
                         style="right: {{ $currentPosition }}%; bottom: {{ $currentVerticalPosition }}px;"></div>
                </div>
            @endif
        </div>

        <div class="card">
            <div class="card-body">
                <div class="section-title">
                    <i class="fas fa-book-open"></i> الاختبارات المتاحة
                </div>
                <button id="filterToggle" class="filter-toggle">عرض الفلاتر</button>
                <form method="GET" action="{{ route('student.dashboard') }}" class="filter-form expanded" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="ابحث عن اختبار..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="level" id="school_level" class="form-select select2">
                                <option value="">اختر المستوى</option>
                                @foreach ($schoolLevels as $level)
                                    <option value="{{ $level->id }}" {{ request('level') == $level->id ? 'selected' : '' }}>
                                        {{ $level->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="subject" id="subject" class="form-select select2">
                                <option value="">اختر المادة</option>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ request('subject') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="type" id="type" class="form-control">
                                <option value="">اختر نوع الاختبار</option>
                                <option value="self_training" {{ request('type') == 'self_training' ? 'selected' : '' }}>تدريب</option>
                                <option value="evaluation" {{ request('type') == 'evaluation' ? 'selected' : '' }}>تحدي</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="difficulty" id="difficulty" class="form-control">
                                <option value="">اختر مستوى الصعوبة</option>
                                <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>سهل</option>
                                <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>متوسط</option>
                                <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>صعب</option>
                            </select>
                        </div>
        <!-- duration
    <div class="col-md-2">
        <select name="duration" class="form-select">
            <option value="">أي مدة</option>
            <option value="1" {{ request('duration') == '1' ? 'selected' : '' }}>1 دقيقة</option>
            <option value="5" {{ request('duration') == '5' ? 'selected' : '' }}>5 دقائق</option>
            <option value="10" {{ request('duration') == '10' ? 'selected' : '' }}>10 دقائق</option>
             <option value="15" {{ request('duration') == '15' ? 'selected' : '' }}>15 دقيقة</option>
            <option value="30" {{ request('duration') == '30' ? 'selected' : '' }}>30 دقيقة</option>
            <option value="60" {{ request('duration') == '60' ? 'selected' : '' }}>1 ساعة</option>
            <option value="120" {{ request('duration') == '120' ? 'selected' : '' }}>2 ساعة</option>
            <option value="custom" {{ request('duration') == 'custom' ? 'selected' : '' }}>مخصص</option>
        </select>
    </div>

    <div class="col-md-2 custom-duration-field" style="{{ request('duration') != 'custom' ? 'display:none;' : '' }}">
    <div class="input-group">
        <input type="number" name="min_duration" class="form-control" 
               min="1" value="{{ request('min_duration') }}" placeholder="الحد الأدنى">
        <span class="input-group-text">إلى</span>
        <input type="number" name="max_duration" class="form-control" 
               min="1" value="{{ request('max_duration') }}" placeholder="الحد الأقصى">
        <span class="input-group-text">دقيقة</span>
    </div>
</div>
    -->

<!-- total_mark 
    <div class="col-md-2">
    <select name="total_mark" class="form-select">
        <option value="">أي مجموع</option>
        <option value="20" {{ request('total_mark') == '20' ? 'selected' : '' }}>20 نقطة</option>
        <option value="50" {{ request('total_mark') == '50' ? 'selected' : '' }}>50 نقطة</option>
        <option value="100" {{ request('total_mark') == '100' ? 'selected' : '' }}>100 نقطة</option>
        <option value="custom" {{ request('total_mark') == 'custom' ? 'selected' : '' }}>مخصص</option>
    </select>
</div>
 -->

                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> بحث
                            </button>
                        </div>
                    </div>
                </form>

                <div class="row">
                    @forelse ($quizzes as $quiz)
                        <div class="col-md-6 mb-4">
                            <div class="quiz-card">
                                <div class="quiz-card-flip">
                                    <div class="quiz-card-front">
                                        <div class="quiz-info" onclick="flipCard(this)">
                                            <div class="quiz-icon">
                                                <i class="fas fa-book"></i>
                                            </div>
                                            <div class="quiz-details">
                                                <h5>{{ $quiz->title }}</h5>
                                                <p class="text-muted small">
                                                    <i class="fas fa-level-up-alt me-1"></i> 
                                                    {{ $quiz->difficulty_level == 'easy' ? 'سهل' : ($quiz->difficulty_level == 'medium' ? 'متوسط' : 'صعب') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="quiz-actions">
                                            @if ($quiz->results->isEmpty())
                                                <a href="{{ route('quiz.show', $quiz->id) }}" class="action-btn play">
                                                    <i class="fas fa-play"></i> ابدأ
                                                </a>
                                            @else
                                                @php
                                                    $lastResult = $quiz->results->sortByDesc('created_at')->first();
                                                    $canRetry = $quiz->type !== 'evaluation' || (now()->diffInHours($lastResult->created_at) >= 1);
                                                @endphp
                                                <a href="{{ $canRetry ? route('quiz.show', $quiz->id) : '#' }}"
                                                   class="action-btn {{ $canRetry ? 'replay' : 'disabled' }}"
                                                   @if (!$canRetry) title="يجب الانتظار ساعة واحدة قبل إعادة التقييم" @endif>
                                                    <i class="fas fa-redo"></i>  إعادة التحدي
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="quiz-card-back">
                                        <div class="quiz-details-back">
                                            <p><i class="fas fa-clock me-2"></i> {{ $quiz->time ?? 0 }} ثانية</p>
                                            <p><i class="fas fa-star me-2"></i> {{ $quiz->total_mark ?? 0 }} نقاط</p>
                                            <p><i class="fas fa-level-up-alt me-2"></i> 
                                                {{ $quiz->difficulty_level == 'easy' ? 'سهل' : ($quiz->difficulty_level == 'medium' ? 'متوسط' : 'صعب') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-4">
                                <i class="fas fa-exclamation-circle fa-3x text-muted mb-3"></i>
                                <p class="text-muted">لم يتم العثور على تحديات</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                @if(isset($quizzes) && $quizzes->hasPages())
                    <div class="pagination justify-content-center mt-3">
                        {{ $quizzes->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="mascot-container">
        <img src="{{ asset('images/book.png') }}" alt="Mascot" class="mascot">
        <div class="mascot-message">
            @if (session('quiz_completed'))
                أحسنت! لقد أكملت اختبارًا جديدًا!
            @else
                مرحبًا! جرب اختبارًا جديدًا اليوم!
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                dir: "rtl",
                width: '100%'
            });

            tippy('[data-tippy-content]', {
                placement: 'top',
                theme: 'light',
            });

            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-left",
                "timeOut": "5000",
                "rtl": true
            };

            @if (session('success'))
                toastr.success('{{ session('success') }}');
            @endif

            @if (session('error'))
                toastr.error('{{ session('error') }}');
            @endif

            $('.progress-bar').each(function() {
                $(this).css('width', $(this).attr('aria-valuenow') + '%');
            });

            $('#school_level').on('change', function() {
                const schoolLevelId = $(this).val();
                const subjectSelect = $('#subject');

                subjectSelect.empty().append('<option value="">اختر المادة</option>');
                if (schoolLevelId) {
                    subjectSelect.prop('disabled', true);
                    $.ajax({
                        url: '{{ route("subjects.bySchoolLevel") }}',
                        method: 'GET',
                        data: { school_level_id: schoolLevelId },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.subjects && response.subjects.length > 0) {
                                response.subjects.forEach(subject => {
                                    subjectSelect.append(
                                        `<option value="${subject.id}">${subject.name}</option>`
                                    );
                                });
                            }
                        },
                        error: function() {
                            toastr.error('حدث خطأ أثناء جلب المواد. يرجى المحاولة مرة أخرى.');
                        },
                        complete: function() {
                            subjectSelect.prop('disabled', false).trigger('change');
                        }
                    });
                }
            });

            if ($('#school_level').val()) {
                $('#school_level').trigger('change');
            }

            // Filter toggle functionality
            $('#filterToggle').on('click', function() {
                const filterForm = $('#filterForm');
                const isExpanded = filterForm.hasClass('expanded');
                filterForm.toggleClass('expanded collapsed');
                $(this).text(isExpanded ? 'عرض الفلاتر' : 'إخفاء الفلاتر');
            });

            initializeNotifications();
        });

        function flipCard(element) {
            const card = element.closest('.quiz-card');
            card.classList.toggle('flipped');
            setTimeout(() => card.classList.remove('flipped'), 2000); // Reset after 2 seconds
        }

        // Pusher Configuration
        var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            encrypted: true
        });

        var channel = pusher.subscribe('notifications.{{ auth()->id() }}');

        // Handle Pusher connection errors
        pusher.connection.bind('error', function(err) {
            console.error('Pusher connection error:', err);
        });

        channel.bind('notification.sent', function(data) {
            console.log('Received notification:', data);
            updateNotificationCount();
            toastr.success(data.message, data.title);

            // Prepend new notification if dropdown is visible
            if (notificationDropdownVisible) {
                prependNotification(data);
            }
        });

        let notificationDropdownVisible = false;

        function initializeNotifications() {
            updateNotificationCount();
            document.getElementById('notificationIcon').addEventListener('click', function(event) {
                event.stopPropagation();
                toggleNotificationDropdown();
            });

            document.addEventListener('click', function(event) {
                const notificationIcon = document.getElementById('notificationIcon');
                if (!notificationIcon.contains(event.target) && notificationDropdownVisible) {
                    toggleNotificationDropdown();
                }
            });

            setInterval(updateNotificationCount, 30000); // Update every 30 seconds
        }

        function updateNotificationCount() {
            fetch('{{ route('notifications.unreadCount') }}')
                .then(response => {
                    if (!response.ok) throw new Error('Failed to fetch unread count');
                    return response.json();
                })
                .then(data => {
                    const count = data.count;
                    const countElement = document.getElementById('notificationCount');
                    countElement.textContent = count;
                    countElement.style.display = count > 0 ? 'flex' : 'none';
                })
                .catch(error => console.error('Error updating notification count:', error));
        }

        function loadNotifications() {
            fetch('{{ route('notifications.json') }}')
                .then(response => {
                    if (!response.ok) throw new Error('Failed to fetch notifications');
                    return response.json();
                })
                .then(data => {
                    const notificationList = document.getElementById('notificationList');
                    notificationList.innerHTML = '';
                    if (data.error) {
                        notificationList.innerHTML = '<div class="no-notifications"><i class="fas fa-bell-slash"></i><p>فشل في تحميل الإشعارات</p></div>';
                        return;
                    }
                    if (data.notifications.length === 0) {
                        notificationList.innerHTML = '<div class="no-notifications"><i class="fas fa-bell-slash"></i><p>لا توجد إشعارات حالياً</p></div>';
                        return;
                    }
                    data.notifications.forEach(notification => {
                        appendNotification(notification);
                    });
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    const notificationList = document.getElementById('notificationList');
                    notificationList.innerHTML = '<div class="no-notifications"><i class="fas fa-bell-slash"></i><p>فشل في تحميل الإشعارات</p></div>';
                });
        }

        function prependNotification(notification) {
            const notificationList = document.getElementById('notificationList');
            const notificationItem = createNotificationElement(notification);
            notificationList.prepend(notificationItem);
        }

        function appendNotification(notification) {
            const notificationList = document.getElementById('notificationList');
            const notificationItem = createNotificationElement(notification);
            notificationList.appendChild(notificationItem);
        }

        function createNotificationElement(notification) {
            const item = document.createElement('div');
            item.className = `notification-item ${!notification.read ? 'unread' : ''}`;
            item.setAttribute('data-id', notification.id);

            const iconClass = notification.type === 'success' ? 'success' : 
                            notification.type === 'warning' ? 'warning' : 
                            'info';
            const icon = `<div class="notification-icon-small ${iconClass}">
                <i class="fas ${notification.type === 'success' ? 'fa-check-circle' : 
                              notification.type === 'warning' ? 'fa-exclamation-circle' : 
                              'fa-info-circle'}"></i>
            </div>`;

            const content = `
                <div class="notification-content">
                    <div class="notification-title">${notification.title}</div>
                    <div class="notification-message">${notification.message}</div>
                    <div class="notification-time">
                        <i class="fas fa-clock"></i> ${notification.created_at}
                    </div>
                </div>
            `;

            item.innerHTML = icon + content;
            return item;
        }

        function toggleNotificationDropdown() {
            const dropdown = document.getElementById('notificationDropdown');
            notificationDropdownVisible = !notificationDropdownVisible;
            dropdown.style.display = notificationDropdownVisible ? 'block' : 'none';
            if (notificationDropdownVisible) {
                loadNotifications();
            }
        }

        function markAsRead(id) {
            fetch(`/notifications/${id}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Failed to mark as read');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    updateNotificationCount();
                    loadNotifications();
                }
            })
            .catch(error => console.error('Error marking as read:', error));
        }

        function markAllAsRead() {
            fetch('/notifications/mark-all-as-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Failed to mark all as read');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    updateNotificationCount();
                    loadNotifications();
                }
            })
            .catch(error => console.error('Error marking all as read:', error));
        }

        function dismissNotification(id) {
            fetch(`/notifications/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Failed to dismiss notification');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    updateNotificationCount();
                    loadNotifications();
                }
            })
            .catch(error => console.error('Error dismissing notification:', error));
        }

        function viewNotification(id) {
            fetch(`/notifications/${id}/view`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Failed to view notification');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    markAsRead(id); // Mark as read on view
                    if (data.url) {
                        window.location.href = data.url;
                    }
                }
            })
            .catch(error => console.error('Error viewing notification:', error));
        }


        document.querySelector('select[name="duration"]').addEventListener('change', function() {
    const customField = document.querySelector('.custom-duration-field');
    if (this.value === 'custom') {
        customField.style.display = 'block';
    } else {
        customField.style.display = 'none';
    }
});
    </script>
</body>
</html>