<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المستخدمين - أباجم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f0faff 0%, #e3f2fd 100%);
            font-family: 'Tajawal', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow-x: hidden;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            background: #2c3e50;
            padding-top: 20px;
            transition: all 0.3s;
            z-index: 1000;
        }
        .sidebar .nav-link {
            color: #fff;
            padding: 10px 20px;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
        }
        .sidebar .nav-link:hover {
            background: #34495e;
            color: #42a5f5;
        }
        .sidebar .nav-link i {
            margin-left: 10px;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s;
        }
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-250px);
            }
            .content {
                margin-left: 0;
            }
            .sidebar.active {
                transform: translateX(0);
            }
        }
        .navbar-custom {
            background-color: #2c3e50;
            color: white;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 10px 20px;
        }
        .navbar-custom .navbar-brand {
            color: white;
            font-weight: bold;
            font-size: 1.5rem;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .card-header {
            background: linear-gradient(90deg, #42a5f5, #1e88e5);
            color: white;
            border-radius: 10px 10px 0 0;
            font-weight: 600;
        }
        .user-list-table {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
        }
        .user-list-table th {
            background: #42a5f5;
            color: white;
            padding: 15px;
        }
        .user-list-table td {
            padding: 12px;
            vertical-align: middle;
        }
        .btn-delete {
            background-color: #e74c3c;
            border: none;
            color: white;
            padding: 5px 15px;
            border-radius: 5px;
            transition: all 0.3s;
        }
        .btn-delete:hover {
            background-color: #c0392b;
            transform: scale(1.05);
        }
        .search-bar {
            margin-bottom: 20px;
            width: 100%;
            max-width: 400px;
        }
        .profile-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .upload-photo-btn {
            background: #42a5f5;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9rem;
            cursor: pointer;
        }
        .upload-photo-btn:hover {
            background: #1e88e5;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="text-center mb-3">
            <img src="{{ Auth::user()->avatar ?? 'https://via.placeholder.com/100' }}" alt="Admin Photo" class="profile-photo">
            <form action="{{ route('uploadPhoto') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="avatar" id="avatar" style="display: none;" onchange="this.form.submit()">
                <label for="avatar" class="upload-photo-btn">تغيير الصورة</label>
            </form>
            <h4 class="text-white mt-2">{{ Auth::user()->full_name ?? 'إدارة أباجم' }}</h4>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> لوحة التحكم</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('users') }}"><i class="fas fa-users"></i> إدارة المستخدمين</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="content">
        <nav class="navbar navbar-expand-lg navbar-custom mb-4">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">إدارة المستخدمين - أباجم</a>
            </div>
        </nav>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- User List Section -->
        <div class="card user-list-table">
            <div class="card-header">
                <h5 class="mb-0">قائمة المستخدمين</h5>
                <div class="search-bar mt-2">
                    <input type="text" class="form-control" id="searchUser" placeholder="ابحث عن مستخدم..." onkeyup="searchUsers()">
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>المعرف</th>
                            <th>الاسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>الإجراء</th>
                        </tr>
                    </thead>
                    <tbody id="userTableBody">
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->full_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <!-- Update Button -->
                                    <button type="button" class="btn btn-primary btn-sm me-2" onclick="openUpdateModal({{ $user->id }}, '{{ addslashes($user->full_name) }}', '{{ $user->email }}')">
                                        <i class="fas fa-edit me-1"></i> تحديث
                                    </button>
                                    
                                    <!-- Delete Button -->
                                    <form action="{{ route('deleteUser', $user->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-delete btn-sm" onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                                            <i class="fas fa-trash-alt me-1"></i> حذف
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">لا يوجد مستخدمون لعرضهم.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
                <!-- Pagination Links -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $users->links() }}
                </div>
            </div>
        </div>

        <!-- Single Update User Modal -->
        <div class="modal fade" id="updateUserModal" tabindex="-1" aria-labelledby="updateUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateUserModalLabel">تحديث المستخدم</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="updateUserForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="updateFullName" class="form-label">الاسم الكامل</label>
                                <input type="text" class="form-control" id="updateFullName" name="full_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="updateEmail" class="form-label">البريد الإلكتروني</label>
                                <input type="email" class="form-control" id="updateEmail" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="updatePassword" class="form-label">كلمة المرور الجديدة (اختيارية)</label>
                                <input type="password" class="form-control" id="updatePassword" name="password" placeholder="اتركها فارغة إذا لم ترد تغييرها">
                            </div>
                            <div class="mb-3">
                                <label for="updatePasswordConfirmation" class="form-label">تأكيد كلمة المرور</label>
                                <input type="password" class="form-control" id="updatePasswordConfirmation" name="password_confirmation" placeholder="تأكيد كلمة المرور الجديدة">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-primary" id="updateSubmitBtn">
                                <span class="btn-text">تحديث المستخدم</span>
                                <span class="btn-loading d-none">
                                    <i class="fas fa-spinner fa-spin me-1"></i> جاري التحديث...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <style>
        .btn-delete {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        /* Custom pagination styling */
        .pagination {
            margin-bottom: 0;
        }

        .page-link {
            color: #007bff;
            background-color: #fff;
            border: 1px solid #dee2e6;
            padding: 0.375rem 0.75rem;
            margin-left: -1px;
            line-height: 1.25;
            text-decoration: none;
        }

        .page-link:hover {
            z-index: 2;
            color: #0056b3;
            text-decoration: none;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }

        .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }

        .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            background-color: #fff;
            border-color: #dee2e6;
        }
        </style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function openUpdateModal(userId, fullName, email) {
    const form = document.getElementById('updateUserForm');
    
    // Properly construct the URL using Laravel's route helper
    form.action = `/admin/users/${userId}/update`;
    
    // Fill form fields
    document.getElementById('updateFullName').value = fullName;
    document.getElementById('updateEmail').value = email;
    
    // Clear password fields
    document.getElementById('updatePassword').value = '';
    document.getElementById('updatePasswordConfirmation').value = '';
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('updateUserModal'));
    modal.show();
}

// Handle form submission with loading state
document.getElementById('updateUserForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('updateSubmitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoading = submitBtn.querySelector('.btn-loading');
    
    // Show loading state
    btnText.classList.add('d-none');
    btnLoading.classList.remove('d-none');
    submitBtn.disabled = true;
    
    // Basic validation
    const password = document.getElementById('updatePassword').value;
    const passwordConfirmation = document.getElementById('updatePasswordConfirmation').value;
    
    if (password && password !== passwordConfirmation) {
        e.preventDefault();
        alert('كلمات المرور غير متطابقة');
        
        // Reset button state
        btnText.classList.remove('d-none');
        btnLoading.classList.add('d-none');
        submitBtn.disabled = false;
        return;
    }
    
    if (password && password.length < 8) {
        e.preventDefault();
        alert('كلمة المرور يجب أن تكون 8 أحرف على الأقل');
        
        // Reset button state
        btnText.classList.remove('d-none');
        btnLoading.classList.add('d-none');
        submitBtn.disabled = false;
        return;
    }
});

// Reset form when modal is closed
document.getElementById('updateUserModal').addEventListener('hidden.bs.modal', function () {
    const form = document.getElementById('updateUserForm');
    form.reset();
    
    // Reset button state
    const submitBtn = document.getElementById('updateSubmitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoading = submitBtn.querySelector('.btn-loading');
    
    btnText.classList.remove('d-none');
    btnLoading.classList.add('d-none');
    submitBtn.disabled = false;
});

function searchUsers() {
    const input = document.getElementById('searchUser');
    const filter = input.value.toUpperCase();
    const tbody = document.getElementById('userTableBody');
    const rows = tbody.getElementsByTagName('tr');

    for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let found = false;
        
        // Skip empty row
        if (cells.length < 4) continue;
        
        for (let j = 0; j < cells.length - 1; j++) { // -1 to exclude action column
            if (cells[j] && cells[j].textContent.toUpperCase().indexOf(filter) > -1) {
                found = true;
                break;
            }
        }
        
        rows[i].style.display = found ? '' : 'none';
    }
}
</script>
    </body>
</html>