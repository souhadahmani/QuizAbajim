@extends('enseignant.layout.layout')

@section('content')
<div class="container-fluid" style="position: relative; min-height: 100vh; background: linear-gradient(135deg, #e1f5fe 0%, #b3e5fc 100%);">

    <!-- Chalkboard Background -->
    <style>
        body::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, rgba(2, 136, 209, 0.2), rgba(255, 255, 255, 0.5)),
                        url('https://www.transparenttextures.com/patterns/chalkboard.png');
            opacity: 0.7;
            z-index: 0;
        }

        .main-container {
            position: relative;
            z-index: 1;
            font-family: 'Patrick Hand', cursive;
        }

        .page-header {
            background: rgba(255, 255, 255, 0.95);
            border: 3px solid #0288d1;
            border-radius: 15px;
            margin-bottom: 20px;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .breadcrumb {
            background: none;
            padding: 0;
            margin: 0;
        }

        .breadcrumb-item {
            color: #0277bd;
            font-size: 1.2rem;
        }

        .breadcrumb-item.active {
            color: #ef5350;
        }

        .app-actions li a {
            color: #0288d1;
            font-size: 1.5rem;
        }

        .app-actions li a:hover {
            color: #ffca28;
        }

        .table-container {
            background: rgba(255, 255, 255, 0.95);
            border: 3px solid #0288d1;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }

        .t-header {
            color: #0288d1;
            font-size: 1.8rem;
            text-shadow: 1px 1px #b3e5fc;
            text-align: center;
            margin-bottom: 15px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }

        .custom-table th, .custom-table td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #b3e5fc;
        }

        .custom-table th {
            background: #0288d1;
            color: #fff;
            text-shadow: 1px 1px #0277bd;
        }

        .btn-primary {
            background: #0288d1;
            border: none;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1.2rem;
            text-align: center;
            display: inline-block;
            width: auto;
            margin-right: 10px;
            text-decoration: none;
        }

        .btn-outline-danger {
            background: #ef5350;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-outline-success {
            background: #66bb6a;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-danger {
            background: #ef5350;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .alert-success {
            background: #66bb6a;
            color: #fff;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
        }

        .alert-success .close {
            color: #fff;
            opacity: 1;
        }

        .no-quizzes {
            text-align: center;
            color: #ef5350;
            font-size: 1.5rem;
            padding: 20px;
        }
    </style>

    <!-- Main container start -->
    <div class="main-container">
        <!-- Page header start -->
        <div class="page-header">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">الصفحات</li>
                <li class="breadcrumb-item active">الاختبارات</li>
            </ol>
            <ul class="app-actions">
                <li>
                    <a href="#" id="reportrange">
                        <span class="range-text"></span>
                        <i class="icon-chevron-down"></i>
                    </a>
                </li>
                <li>
                    <a href="#" data-toggle="tooltip" data-placement="top" title="طباعة">
                        <i class="icon-print"></i>
                    </a>
                </li>
                <li>
                    <a href="#" data-toggle="tooltip" data-placement="top" title="تحميل CSV">
                        <i class="icon-cloud_download"></i>
                    </a>
                </li>
            </ul>
        </div>

        <div style="margin: 20px 0;">
            <a href="{{ url('/add-edit-quiz') }}" class="btn btn-primary">
                إضافة اختبار
            </a>
        </div>

        @if(Session::has('success_message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>نجاح: </strong> {{ Session::get('success_message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="إغلاق">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
        @endif

        <div class="content-wrapper">
            <!-- Row start -->
            <div class="row gutters">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="table-container">
                        <div class="t-header">قائمة الاختبارات</div>
                        <div class="table-responsive">
                            <table id="scrollVertical" class="table custom-table">
                                <thead>
                                    <tr>
                                        <th>عنوان</th>
                                        <th>وقت</th>
                                        <th>chapter_id</th>
                                        <th>total_mark</th>
                                        <th>niveau</th>
                                        <th>matiere</th>
                                        <th>حالة</th>
                                        <th>تم إنشاؤها في</th>
                                        <th>فعل</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($allquiz) && !$allquiz->isEmpty())
                                        @foreach ($allquiz as $quiz)
                                            <tr>
                                                <td>{{ $quiz['title'] }}</td>
                                                <td>{{ $quiz['time'] }}</td>
                                                <td>{{ $quiz['chapter_id'] ?? 'N/A' }}</td>
                                                <td>{{ $quiz['total_mark'] }}</td>
                                                <td>{{ $quiz['niveau'] }}</td>
                                                <td>{{ $quiz['matiere'] ?? 'N/A' }}</td>
                                                <td>
                                                    @if($quiz['status'] === "active")
                                                        <a class="updateQuizStatus" id="quiz-{{ $quiz['id'] }}" quiz_id="{{ $quiz['id'] }}"
                                                           href="javascript:void(0)">
                                                            <span class="btn btn-outline-danger">إلغاء</span>
                                                        </a>
                                                    @else
                                                        <a class="updateQuizStatus" id="quiz-{{ $quiz['id'] }}" quiz_id="{{ $quiz['id'] }}"
                                                           href="javascript:void(0)">
                                                            <span class="btn btn-outline-success">تفعيل</span>
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>{{ date('Y-m-d h:i:s', strtotime($quiz['created_at'])) }}</td>
                                                <td>
                                                    <a href="{{ url('add-edit-quiz/' . $quiz['id']) }}">
                                                        <span class="btn btn-primary">تعديل</span>
                                                    </a>
                                                    <a href="javascript:void(0)" module="quiz" moduleid="{{ $quiz['id'] }}" title="quiz" class="confirmDelete">
                                                        <span class="btn btn-danger">حذف</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="9" class="no-quizzes">لا يوجد اختبارات حالياً!</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection