@extends('emails.layouts.tulip-email')

@section('body')
    <h2 class="main-title" style="font-family: 'El Messiri', Arial, sans-serif; color: #0f4f55; font-size: 28px; margin: 0 0 20px 0; text-align: center; font-weight: 600;">
        رمز التحقق من حساب التاجر
    </h2>

    <p class="text-content" style="color: #555; font-size: 16px; line-height: 1.6; text-align: center; margin: 0 0 15px 0;">
        مرحباً بك في <strong style="color: #0f4f55;">Tulip Store</strong>!
    </p>

    <p class="text-content" style="color: #666; font-size: 15px; line-height: 1.6; text-align: center; margin: 0 0 25px 0;">
        لقد شارفت على إكمال عملية التسجيل كتاجر في منصتنا. يرجى استخدام الرمز التالي لتأكيد بريدك الإلكتروني:
    </p>

    <!-- OTP Code Box -->
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <div class="code-box" style="background: linear-gradient(135deg, #ff6f35 0%, #ff8c5a 100%); border-radius: 20px; padding: 25px 20px; display: inline-block; box-shadow: 0 10px 30px rgba(255, 111, 53, 0.3); max-width: 90%;">
                    <p class="verification-code" style="color: #ffffff; font-size: 42px; font-weight: 600; letter-spacing: 10px; margin: 0; font-family: 'El Messiri', sans-serif; word-break: break-all;">
                        {{ $otp }}
                    </p>
                </div>
            </td>
        </tr>
    </table>

    <p class="text-content" style="color: #666; font-size: 14px; line-height: 1.6; text-align: center; margin: 25px 0 0 0;">
        هذا الرمز صالح لمدة <strong style="color: #ff6f35;">10 دقائق</strong> فقط. إذا لم تكن أنت من بدأ عملية التسجيل، فيرجى تجاهل هذا البريد.
    </p>

    <p class="text-content" style="color: #666; font-size: 14px; line-height: 1.6; text-align: center; margin: 25px 0 0 0;">
        شكراً لانضمامك إلينا،<br>
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
