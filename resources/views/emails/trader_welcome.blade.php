@extends('emails.layouts.tulip-email')

@section('body')
    <h2 class="main-title" style="font-family: 'El Messiri', Arial, sans-serif; color: #0f4f55; font-size: 28px; margin: 0 0 20px 0; text-align: center; font-weight: 600;">
        تهانينا! تم تفعيل حسابك كتاجر في Tulip Store
    </h2>

    <p class="text-content" style="color: #555; font-size: 16px; line-height: 1.6; text-align: center; margin: 0 0 15px 0;">
        مرحباً <strong style="color: #0f4f55;">{{ $traderName }}</strong>،
    </p>

    <p class="text-content" style="color: #666; font-size: 15px; line-height: 1.6; text-align: center; margin: 0 0 20px 0;">
        يسعدنا إعلامك بأن فريق الإدارة قد قام بمراجعة طلبك والموافقة على حساب التاجر الخاص بك. يمكنك الآن البدء في إدارة متجرك، إضافة منتجاتك، ومتابعة مبيعاتك.
    </p>

    <div style="text-align: center; margin: 20px 0;">
        <a href="{{ url('/trader/login') }}"
           style="display: inline-block; background: #0f4f55; color: #ffffff; text-decoration: none; padding: 12px 18px; border-radius: 14px; font-weight: 600;">
            الدخول إلى لوحة البائع
        </a>
    </div>

    <p class="text-content" style="color: #666; font-size: 14px; line-height: 1.6; text-align: center; margin: 15px 0 0 0;">
        نتمنى لك رحلة مبيعات ناجحة ومثمرة معنا!
        <br>
        شكراً لاختيارك منصتنا،<br>
        فريق عمل {{ config('app.name') }}
    </p>
@endsection

@section('app_links')
    @php
        $baseUrl = rtrim((string) config('app.url', ''), '/');
        $androidSrc = $baseUrl !== '' ? ($baseUrl.'/images/android.png') : url('/images/android.png');
        $iosSrc = $baseUrl !== '' ? ($baseUrl.'/images/ios.png') : url('/images/ios.png');
    @endphp
    <div style="text-align: center; margin-top: 25px;">
        <a href="#" style="margin-right: 15px;">
            <img src="{{ $androidSrc }}" alt="تحميل من Google Play" style="width: 150px; max-width: 100%; height: auto;">
        </a>
        <a href="#">
            <img src="{{ $iosSrc }}" alt="تحميل من App Store" style="width: 150px; max-width: 99%; height: auto;">
        </a>
    </div>
@endsection