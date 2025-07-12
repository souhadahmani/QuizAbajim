@component('mail::layout')
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    @component('mail::panel')
        <h1 style="text-align: right; color: #003366; border-bottom: 2px solid #00bcd4; padding-bottom: 10px;">إعادة تعيين كلمة المرور</h1>
        
        <p style="text-align: right;">مرحباً!</p>
        
        <p style="text-align: right;">لقد تلقينا طلباً لإعادة تعيين كلمة المرور لحسابك.</p>
    @endcomponent

    @component('mail::button', ['url' => $actionUrl, 'color' => 'primary'])
        إعادة تعيين كلمة المرور
    @endcomponent

    <p style="text-align: right;">ينتهي صلاحية رابط إعادة التعيين هذا خلال 60 دقيقة.</p>
    
    <p style="text-align: right;">إذا لم تطلب إعادة تعيين كلمة المرور، فلا داعي لاتخاذ أي إجراء آخر.</p>

    @slot('footer')
        @component('mail::footer')
            <p style="text-align: right; direction: rtl;">
                مع التقدير،<br>
                فريق {{ config('app.name') }}
            </p>
        @endcomponent
    @endslot
@endcomponent