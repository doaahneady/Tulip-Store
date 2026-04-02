

<?php $__env->startSection('body'); ?>
    <h2 class="main-title" style="font-family: 'El Messiri', Arial, sans-serif; color: #0f4f55; font-size: 28px; margin: 0 0 20px 0; text-align: center; font-weight: 600;">
        رمز التحقق الخاص بك
    </h2>

    <p class="text-content" style="color: #555; font-size: 16px; line-height: 1.6; text-align: center; margin: 0 0 25px 0;">
        مرحباً <strong style="color: #0f4f55;"><?php echo e($name); ?></strong>،
    </p>

    <p class="text-content" style="color: #666; font-size: 15px; line-height: 1.6; text-align: center; margin: 0 0 30px 0;">
        لقد تلقينا طلباً لإعادة تعيين كلمة المرور الخاصة بك. استخدم الرمز التالي لإكمال العملية:
    </p>

    <!-- Verification Code Box -->
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <div class="code-box" style="background: linear-gradient(135deg, #ff6f35 0%, #ff8c5a 100%); border-radius: 20px; padding: 25px 20px; display: inline-block; box-shadow: 0 10px 30px rgba(255, 111, 53, 0.3); max-width: 90%;">
                    <p class="verification-code" style="color: #ffffff; font-size: 42px; font-weight: 600; letter-spacing: 10px; margin: 0; font-family: 'El Messiri', sans-serif; word-break: break-all;">
                        <?php echo e($code); ?>

                    </p>
                </div>
            </td>
        </tr>
    </table>

    <p class="text-content" style="color: #666; font-size: 14px; line-height: 1.6; text-align: center; margin: 30px 0 0 0;">
        هذا الرمز صالح لمدة <strong style="color: #ff6f35;">10 دقائق</strong> فقط
    </p>

    <div style="background: #f8f9fa; border-right: 4px solid #ff6f35; padding: 15px; margin: 25px 0; border-radius: 10px;">
        <p class="text-content" style="color: #555; font-size: 13px; line-height: 1.6; margin: 0;">
            <strong style="color: #0f4f55;">ملاحظة هامة:</strong> إذا لم تطلب إعادة تعيين كلمة المرور، يرجى تجاهل هذه الرسالة. حسابك آمن ولن يتم إجراء أي تغييرات.
        </p>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('app_links'); ?>
    <?php
        $baseUrl = rtrim((string) config('app.url', ''), '/');
        $androidSrc = $baseUrl !== '' ? ($baseUrl.'/images/android.png') : url('/images/android.png');
        $iosSrc = $baseUrl !== '' ? ($baseUrl.'/images/ios.png') : url('/images/ios.png');
    ?>
    <div style="text-align: center; margin-top: 25px;">
        <a href="https://play.google.com/store/apps/details?id=com.tulip.vendor" style="margin-right: 15px;">
            <img src="<?php echo e($androidSrc); ?>" alt="تحميل من Google Play" style="width: 150px; max-width: 100%; height: auto;">
        </a>
        <a href="https://apps.apple.com/app/tulip-vendor/id123456789">
            <img src="<?php echo e($iosSrc); ?>" alt="تحميل من App Store" style="width: 150px; max-width: 100%; height: auto;">
        </a>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.layouts.tulip-email', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Tulip-Store\resources\views/emails/verification.blade.php ENDPATH**/ ?>