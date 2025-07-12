@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8" dir="rtl">
    <div class="bg-white rounded-lg shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-gray-800 text-right">الإشعارات
        <span id="notificationCount" class="ml-2 bg-blue-500 text-white rounded-full w-5 h-5 flex items-center justify-center" style="display: none;">0</span>
    </h2>
    @if($notifications->count() > 0)
        <button onclick="markAllAsRead()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-200">
            تحديد الكل كمقروء
        </button>
    @endif
</div>

        @if($notifications->count() > 0)
            <div class="space-y-4" id="notificationList">
                @foreach($notifications as $notification)
                    <div class="bg-gray-50 p-4 rounded-lg {{ ($notification->read_at ? 'opacity-75' : 'border-r-4 border-blue-500') }}" data-id="{{ $notification->id }}">
                        <div class="flex justify-between items-start">
                            <div class="flex-grow text-right">
                                <h3 class="text-lg font-medium text-gray-900">
                                    {{ $notification->data['title'] ?? $notification->title }}
                                </h3>
                                <p class="text-gray-600 mt-1">
                                    {{ $notification->data['message'] ?? $notification->message }}
                                </p>
                                <div class="text-sm text-gray-500 mt-2">
                                    {{ $notification->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <div class="flex space-x-2 space-x-reverse">
                                @if(!$notification->read_at)
                                    <button onclick="markAsRead('{{ $notification->id }}')" class="text-blue-500 hover:text-blue-600">
                                        <i class="fas fa-check"></i>
                                    </button>
                                @endif
                                <button onclick="deleteNotification('{{ $notification->id }}')" class="text-red-500 hover:text-red-600">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <div class="text-gray-400 text-lg">
                    لا توجد إشعارات بعد
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
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

        initializeNotifications();
    });

    var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        encrypted: true
    });

    var channel = pusher.subscribe('notifications.{{ auth()->id() }}');

    channel.bind('notification.sent', function(data) {
        console.log('Received notification:', data);
        updateNotificationCount();
        toastr.success(data.message, data.title);
        prependNotification(data);
    });

    let notificationDropdownVisible = false;

    function initializeNotifications() {
        updateNotificationCount();
        setInterval(updateNotificationCount, 60000);
    }

    function updateNotificationCount() {
        fetch('{{ route('notifications.unreadCount') }}')
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Error fetching unread count:', data.error);
                    return;
                }
                const count = data.count || 0;
                const countElement = document.getElementById('notificationCount');
                if (countElement) {
                    countElement.textContent = count;
                    countElement.style.display = count > 0 ? 'flex' : 'none';
                }
            })
            .catch(error => console.error('Error fetching unread count:', error));
    }

    function prependNotification(notification) {
        const notificationList = document.getElementById('notificationList');
        if (!notificationList) return;

        const notificationItem = document.createElement('div');
        notificationItem.className = `bg-gray-50 p-4 rounded-lg ${!notification.read ? 'border-r-4 border-blue-500' : 'opacity-75'}`;
        notificationItem.setAttribute('data-id', notification.id);
        notificationItem.innerHTML = `
            <div class="flex justify-between items-start">
                <div class="flex-grow text-right">
                    <h3 class="text-lg font-medium text-gray-900">${notification.title}</h3>
                    <p class="text-gray-600 mt-1">${notification.message}</p>
                    <div class="text-sm text-gray-500 mt-2">${notification.created_at}</div>
                </div>
                <div class="flex space-x-2 space-x-reverse">
                    ${!notification.read ? `
                        <button onclick="markAsRead('${notification.id}')" class="text-blue-500 hover:text-blue-600">
                            <i class="fas fa-check"></i>
                        </button>
                    ` : ''}
                    <button onclick="deleteNotification('${notification.id}')" class="text-red-500 hover:text-red-600">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        notificationList.prepend(notificationItem);
    }

    function markAsRead(id) {
        fetch(`/notifications/${id}/mark-as-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateNotificationCount();
                const item = document.querySelector(`[data-id="${id}"]`);
                if (item) {
                    item.classList.remove('border-r-4', 'border-blue-500');
                    item.classList.add('opacity-75');
                    const readButton = item.querySelector('.text-blue-500');
                    if (readButton) readButton.remove();
                }
            } else {
                toastr.error('فشل في تحديد الإشعار كمقروء');
            }
        })
        .catch(error => {
            console.error('Error marking as read:', error);
            toastr.error('فشل في تحديد الإشعار كمقروء');
        });
    }

    function markAllAsRead() {
        fetch('/notifications/mark-all-as-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateNotificationCount();
                const items = document.querySelectorAll('#notificationList .notification-item');
                items.forEach(item => {
                    item.classList.remove('border-r-4', 'border-blue-500');
                    item.classList.add('opacity-75');
                    const readButton = item.querySelector('.text-blue-500');
                    if (readButton) readButton.remove();
                });
            } else {
                toastr.error('فشل في تحديد جميع الإشعارات كمقروءة');
            }
        })
        .catch(error => {
            console.error('Error marking all as read:', error);
            toastr.error('فشل في تحديد جميع الإشعارات كمقروءة');
        });
    }

    function deleteNotification(id) {
        if (!confirm('هل أنت متأكد من حذف هذا الإشعار؟')) return;

        fetch(`/notifications/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateNotificationCount();
                const item = document.querySelector(`[data-id="${id}"]`);
                if (item) item.remove();
                toastr.success('تم حذف الإشعار بنجاح');
            } else {
                toastr.error('فشل في حذف الإشعار');
            }
        })
        .catch(error => {
            console.error('Error deleting notification:', error);
            toastr.error('فشل في حذف الإشعار');
        });
    }
</script>
@endpush
@endsection