<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تهيئة ملف المعلم - أباجيم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #00bcd4;
            --secondary-color: #0097a7;
            --accent-color: #ff6f61;
            --light-bg: #f8fcff;
            --dark-text: #333;
            --light-text: #6c757d;
            --border-radius: 12px;
            --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        
        .teacher-profile-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .profile-header {
            text-align: center;
            margin-bottom: 30px;
            color: var(--primary-color);
        }
        
        .profile-card {
            background: #fff;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .alert-danger {
            border-radius: var(--border-radius);
            margin-bottom: 25px;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark-text);
            margin-bottom: 8px;
            display: block;
        }
        
        .input-field {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #dee2e6;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        
        .input-field:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 8px rgba(0, 188, 212, 0.2);
            outline: none;
        }
        
        .text-red-500 {
            color: #dc3545;
            font-size: 0.9rem;
            margin-top: -15px;
            margin-bottom: 15px;
            display: block;
        }
        
        .materials-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .materials-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        
        .material-item {
            display: flex;
            align-items: center;
            background: #f5f5f5;
            padding: 12px 15px;
            border-radius: var(--border-radius);
        }
        
        .material-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary-color);
            margin-left: 10px;
        }
        
        .material-item label {
            cursor: pointer;
            color: var(--dark-text);
        }
        
        .btn-submit {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: var(--border-radius);
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: block;
            width: 100%;
            margin-top: 30px;
        }
        
        .btn-submit:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .no-materials {
            color: var(--light-text);
            text-align: center;
            padding: 20px;
            background: #f9f9f9;
            border-radius: var(--border-radius);
        }
        
        @media (max-width: 768px) {
            .teacher-profile-container {
                padding: 15px;
            }
            
            .profile-card {
                padding: 20px;
            }
            
            .materials-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="teacher-profile-container">
        <div class="profile-header">
            <h1><i class="fas fa-user-edit"></i> تهيئة ملفك الشخصي</h1>
        </div>
        
        <div class="profile-card">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('teacher.profile.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="level_id" class="form-label">
                        <i class="fas fa-layer-group"></i> اختر المستوى
                    </label>
                    <select name="level_id" id="level_id" class="input-field" required>
                        <option value="">اختر المستوى</option>
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}" {{ old('level_id') == $level->id ? 'selected' : '' }}>
                                {{ $level->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('level_id')
                        <div class="text-red-500">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="materials-section">
                    <h2><i class="fas fa-book-open"></i> تفعيل المواد</h2>
                    <div id="materials-container">
                        <!-- Materials will be loaded here via AJAX -->
                        <div class="no-materials">الرجاء اختيار مستوى أولاً</div>
                    </div>
                </div>
                
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> حفظ التغييرات
                </button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#level_id').on('change', function() {
                var levelId = $(this).val();

                if (levelId) {
                    $.ajax({
                        url: '/getmaterialbylevel/' + levelId,
                        type: 'GET',
                        dataType: 'json',
                        beforeSend: function() {
                            $('#materials-container').html('<div class="no-materials"><i class="fas fa-spinner fa-spin"></i> جاري تحميل المواد...</div>');
                        },
                        success: function(data) {
                            if (data.length > 0) {
                                let materialsHtml = '<div class="materials-container">';
                                $.each(data, function(index, material) {
                                    materialsHtml += `
                                        <div class="material-item">
                                            <input type="checkbox" name="matiere_ids[]" value="${material.id}" id="material_${material.id}">
                                            <label for="material_${material.id}">${material.name}</label>
                                        </div>
                                    `;
                                });
                                materialsHtml += '</div>';
                                $('#materials-container').html(materialsHtml);
                            } else {
                                $('#materials-container').html('<div class="no-materials">لا توجد مواد متاحة لهذا المستوى</div>');
                            }
                        },
                        error: function() {
                            $('#materials-container').html('<div class="no-materials text-red-500">حدث خطأ أثناء تحميل المواد</div>');
                        }
                    });
                } else {
                    $('#materials-container').html('<div class="no-materials">الرجاء اختيار مستوى أولاً</div>');
                }
            });
        });
    </script>
</body>
</html>