<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الإعدادات - أباجيم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f8f9fa;
            padding-top: 70px;
        }
        
        /* Navbar Styles */
        .navbar-custom {
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 10px 20px;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-icons {
            display: flex;
            align-items: center;
        }

        .nav-icons .back-arrow {
            color: #00bcd4;
            font-size: 1.5rem;
            margin-left: 30px;
            cursor: pointer;
            transition: transform 0.3s ease, color 0.3s ease;
        }

        .nav-icons .back-arrow:hover {
            color: #0288d1;
            transform: scale(1.2);
        }

        .nav-icons .fa-bell {
            color: #00bcd4;
            font-size: 1.2rem;
            margin-left: 30px;
            cursor: pointer;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo img {
            max-height: 50px;
            width: auto;
            object-fit: contain;
        }

        .logout-btn {
            background: none;
            border: none;
            color: #ff6f61;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 5px;
            transition: transform 0.3s ease, background 0.3s ease, color 0.3s ease;
        }

        .logout-btn:hover {
            color: #e65b50;
            background: #e1f5fe;
            border-radius: 50%;
            transform: scale(1.2);
        }

        .logout-btn:focus {
            outline: none;
        }

        /* Page Layout */
        .page-header {
            padding: 20px 0 10px;
        }

        .breadcrumb {
            background: none;
            padding: 0;
            margin: 0;
            font-size: 1rem;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
            color: #6c757d;
        }

        .breadcrumb-item.active {
            color: #00bcd4;
            font-weight: 500;
        }

        .content-wrapper {
            padding: 20px;
            margin-top: 20px;
            background: #f8fcff;
            border-radius: 10px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }

        /* Profile Card */
        .profile-card {
            background: #fff;
            padding: 20px;
        }

        .profile-card .user-avatar img {
            width: 130px;
            height: 130px;
            border: 5px solid #00bcd4;
            border-radius: 50%;
            transition: transform 0.3s ease;
        }

        .profile-card .user-avatar img:hover {
            transform: scale(1.05);
        }

        .profile-card .user-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: #333;
            margin-top: 15px;
        }

        .profile-card .user-email {
            font-size: 0.95rem;
            color: #6c757d;
        }

        .setting-links {
            margin-top: 20px;
        }

        .setting-links a {
            display: flex;
            align-items: center;
            padding: 12px;
            color: #00bcd4;
            text-decoration: none;
            font-size: 1.1rem;
            border-radius: 8px;
            transition: background 0.3s ease, color 0.3s ease;
            margin-bottom: 8px;
        }

        .setting-links a:hover {
            background: #e1f5fe;
            color: #0288d1;
        }

        .setting-links i {
            margin-right: 12px;
            font-size: 1.2rem;
        }

        /* Settings Card */
        .settings-card .card-header {
            background: #fff;
            border-bottom: 1px solid #f0f0f0;
            padding: 20px;
        }

        .settings-card .card-title {
            font-size: 1.6rem;
            color: #00bcd4;
            font-weight: 700;
            margin: 0;
        }

        .settings-card .card-body {
            padding: 30px;
        }

        .alert {
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .form-group {
            margin-bottom: 1.8rem;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.6rem;
            display: block;
            font-size: 1rem;
        }

        .form-control, .form-control-file, select.input-field {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 12px;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus, .form-control-file:focus, select.input-field:focus {
            border-color: #00bcd4;
            box-shadow: 0 0 8px rgba(0, 188, 212, 0.2);
            outline: none;
        }

        .form-control[readonly] {
            background: #f8f9fa;
            color: #6c757d;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .btn-secondary {
            background: #6c757d;
            color: #fff;
            border: none;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .btn-primary {
            background: #00bcd4;
            color: #fff;
            border: none;
        }

        .btn-primary:hover {
            background: #0097a7;
            transform: translateY(-2px);
        }

        .img-thumbnail {
            border: 2px solid #dee2e6;
            border-radius: 8px;
            margin-top: 10px;
        }

        /* Actor-Specific Sections */
        .actor-section {
            margin-top: 30px;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .actor-section h2 {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        /* Teacher Specific Styles */
        .teacher-section {
            border-left: 4px solid #00bcd4;
        }

        .teacher-section h2 {
            color: #00bcd4;
        }

        .materials-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .materials-container div {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1rem;
            color: #333;
        }

        .materials-container input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #00bcd4;
        }

        .materials-container label {
            cursor: pointer;
        }

        /* Student Specific Styles */
        .student-section {
            border-left: 4px solid #4caf50;
        }

        .student-section h2 {
            color: #4caf50;
        }

        .student-info-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .student-info-item {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
        }

        .student-info-label {
            font-weight: 600;
            color: #4caf50;
            margin-bottom: 5px;
        }

        .student-info-value {
            color: #333;
        }

        .student-materials {
            margin-top: 20px;
        }

        .student-materials h3 {
            font-size: 1.2rem;
            color: #4caf50;
            margin-bottom: 15px;
        }

        .student-materials .materials-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .student-materials .material-badge {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 8px 15px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .text-red-500 {
            color: #dc3545;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .col-md-6 {
                width: 100%;
            }
            .card.profile-card {
                margin-bottom: 20px;
            }
            .logo {
                gap: 10px;
            }
            .logout-btn {
                font-size: 1.3rem;
            }
            .nav-icons .back-arrow {
                font-size: 1.3rem;
            }
            .nav-icons .fa-bell {
                font-size: 1.1rem;
            }
            .settings-card .card-body {
                padding: 20px;
            }
            .form-control, .form-control-file, select.input-field {
                padding: 10px;
            }
            .btn {
                padding: 10px 20px;
            }
            .student-info-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar-custom">
        <div class="nav-icons">
            <i class="fas fa-arrow-left back-arrow" onclick="window.history.back()" title="رجوع"></i>
            <i class="fas fa-bell"></i>
        </div>
        <div class="logo">
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn" title="تسجيل الخروج" onclick="return confirm('هل أنت متأكد من تسجيل الخروج؟')">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
            <img src="{{ asset('images/abajim.png') }}" alt="Abajim Logo">
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid">
        <div class="main-container">
            <!-- Page header -->
            <div class="page-header">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">الإعدادات</li>
                </ol>
            </div>

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <div class="row gutters">
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12">
                        <div class="card profile-card">
                            <div class="card-body text-center">
                                <div class="user-profile">
                                    <div class="user-avatar">
                                        <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('images/photo.png') }}" alt="User Photo" class="img-fluid" />
                                    </div>
                                    <h5 class="user-name">{{ Auth::user()->full_name }}</h5>
                                    <h6 class="user-email text-muted">{{ Auth::user()->email }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12">
                        <div class="card settings-card">
                            <div class="card-header">
                                <h4 class="card-title">تحديث المعلومات الشخصية</h4>
                                @if(Session::has('success_message'))
                                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                        {{ Session::get('success_message') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                @endif
                                @if ($errors->any())
                                    <div class="alert alert-danger mt-3">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <form action="{{ route('update.profile') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="fullName" class="form-label">الاسم الكامل</label>
                                                <input type="text" class="form-control" id="fullName" name="fullName" value="{{ Auth::user()->full_name }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="eMail" class="form-label">البريد الإلكتروني</label>
                                                <input type="email" class="form-control" id="eMail" name="eMail" value="{{ Auth::user()->email }}" readonly>
                                            </div>
                            <!-- tbalbiz souha : adding password njarbou -->
                                            <div class="form-group">
                                                <label for="pass" class="form-label">كلمة العبور</label>
                                                <input type="password" class="form-control" id="pass" name="pass" value="{{ Auth::user()->password }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="phone" class="form-label">رقم الهاتف</label>
                                                <input type="text" class="form-control" id="phone" name="phone" value="{{ Auth::user()->mobile }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="addRess" class="form-label">العنوان</label>
                                                <input type="text" class="form-control" id="addRess" name="addRess" value="{{ Auth::user()->address }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="role_name" class="form-label">المنصب/الدور</label>
                                                <input type="text" class="form-control" id="role_name" name="role_name" value="{{ Auth::user()->role_name }}" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="avatar" class="form-label">الصورة الشخصية</label>
                                                <input type="file" class="form-control-file" id="avatar" name="avatar" accept="image/*">
                                                @if(Auth::user()->avatar)
                                                    <small class="d-block mt-2">الصورة الحالية: <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Current Avatar" class="img-thumbnail" style="max-width: 100px; max-height: 100px;"></small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12 text-right mt-4">
                                            <button type="button" class="btn btn-secondary">إلغاء</button>
                                            <button type="submit" class="btn btn-primary ml-2">موافق</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Teacher-Specific Section: Update Level and Materials -->
                        @if(Auth::user()->role_name === 'teacher' || Auth::user()->role_name === 'enseignant')
                        <div class="actor-section teacher-section">
                            <h2>إعداد المستوى والمواد التي تُدرّس</h2>
                            <form action="{{ route('teacher.profile.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="level_id" class="form-label">اختر المستوى</label>
                                    <select name="level_id" id="level_id" class="input-field" required>
                                        <option value="">اختر المستوى</option>
                                        @foreach($levels as $level)
                                            <option value="{{ $level->id }}" {{ old('level_id', Auth::user()->materials->first()->section->level->id ?? '') == $level->id ? 'selected' : '' }}>
                                                {{ $level->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('level_id')
                                        <div class="text-red-500">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Materials Container -->
                                <h2>تفعيل المواد</h2>
                                <div id="materials-container" class="materials-container">
                                    @if(Auth::user()->materials->isNotEmpty())
                                        @foreach(Auth::user()->materials as $material)
                                            <div>
                                                <input type="checkbox" name="matiere_ids[]" value="{{ $material->id }}" id="material_{{ $material->id }}" checked>
                                                <label for="material_{{ $material->id }}">{{ $material->name }}</label>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                @error('matiere_ids')
                                    <div class="text-red-500">{{ $message }}</div>
                                @enderror

                                <div class="text-right mt-4">
                                    <button type="submit" class="btn btn-primary">موافق</button>
                                </div>
                            </form>
                        </div>
                        @endif

                        <!-- Student-Specific Section: Display Student Information -->
                        @if(Auth::user()->role_name === 'student' || Auth::user()->role_name === 'élève')
                        <div class="actor-section student-section">
                            <h2>معلومات الطالب</h2>
                            <div class="student-info-container">
                                <div class="student-info-item">
                                    <div class="student-info-label">المستوى الحالي</div>
                                    <div class="student-info-value">{{ Auth::user()->level->name ?? 'غير محدد' }}</div>
                                </div>
                                <div class="student-info-item">
                                    <div class="student-info-label">القسم</div>
                                    <div class="student-info-value">{{ Auth::user()->section->name ?? 'غير محدد' }}</div>
                                </div>
                                <div class="student-info-item">
                                    <div class="student-info-label">رقم التسجيل</div>
                                    <div class="student-info-value">{{ Auth::user()->registration_number ?? 'غير محدد' }}</div>
                                </div>
                                <div class="student-info-item">
                                    <div class="student-info-label">تاريخ التسجيل</div>
                                    <div class="student-info-value">{{ Auth::user()->created_at->format('Y-m-d') }}</div>
                                </div>
                            </div>
                            
                            @if(Auth::user()->level)
                            <div class="student-materials">
                                <h3>المواد المسجلة</h3>
                                <div class="materials-container">
                                    @foreach(Auth::user()->level->materials as $material)
                                        <div class="material-badge">
                                            <i class="fas fa-book"></i>
                                            <span>{{ $material->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            console.log('Document ready'); // Debug log

            // Handle level change for teachers
            $('#level_id').on('change', function() {
                var levelId = $(this).val();
                console.log('Level changed to:', levelId); // Debug log

                // Clear previous materials
                $('#materials-container').empty();

                if (levelId) {
                    // Show loading spinner
                    $('#loading-spinner').show();

                    // Add CSRF token to the request
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: '/getmaterialbylevel/' + levelId,
                        type: 'GET',
                        dataType: 'json',
                        timeout: 10000, // 10 second timeout
                        success: function(data) {
                            console.log('AJAX Success:', data); // Debug log
                            $('#loading-spinner').hide();

                            if (data && data.length > 0) {
                                $.each(data, function(index, material) {
                                    var materialHtml = `
                                        <div>
                                            <input type="checkbox" name="matiere_ids[]" value="${material.id}" id="material_${material.id}">
                                            <label for="material_${material.id}">${material.name}</label>
                                        </div>
                                    `;
                                    $('#materials-container').append(materialHtml);
                                });
                            } else {
                                $('#materials-container').append('<p class="text-muted">لا توجد مواد لهذا المستوى</p>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', xhr.responseText, status, error); // Debug log
                            $('#loading-spinner').hide();
                            
                            var errorMessage = 'حدث خطأ أثناء تحميل المواد';
                            if (xhr.status === 404) {
                                errorMessage = 'الرابط غير موجود - تأكد من إعداد الطريق بشكل صحيح';
                            } else if (xhr.status === 500) {
                                errorMessage = 'خطأ في الخادم - تأكد من وجود البيانات في قاعدة البيانات';
                            }
                            
                            $('#materials-container').html(`<p class="text-red-500">${errorMessage}</p>`);
                        }
                    });
                } else {
                    $('#materials-container').empty();
                    $('#loading-spinner').hide();
                }
            });

            // Trigger change on page load to populate materials if a level is already selected
            var initialLevelId = $('#level_id').val();
            if (initialLevelId) {
                console.log('Triggering change for initial level:', initialLevelId); // Debug log
                $('#level_id').trigger('change');
            }

            // Close alert messages
            $('.alert .close').on('click', function() {
                $(this).parent().fadeOut();
            });
        });
    </script>
</body>
</html>