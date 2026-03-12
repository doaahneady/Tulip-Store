<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تسجيل تاجر - متجر توليب</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family:"El Messiri", sans-serif; min-height: 100vh; display: flex; background: #f8f9fa; }
        .container { max-width: 980px; width: 100%; margin: auto; padding: 2rem; }
        .card { background: white; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.08); overflow: hidden; }
        .header { padding: 2rem; background: linear-gradient(135deg, #4a148c 0%, #7b1fa2 100%); color: white; }
        .header h1 { font-family: 'El Messiri', sans-serif; font-size: 1.8rem; }
        .steps { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-top: 1rem; }
        .step { background: rgba(255,255,255,0.15); border-radius: 12px; padding: .75rem; display: flex; align-items: center; gap: .75rem; color: rgba(255,255,255,0.9); }
        .step.active { background: rgba(255,255,255,0.3); font-weight: 600; }
        .content { padding: 2rem; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; font-weight: 600; color: #333; margin-bottom: .5rem; font-size: .95rem; }
        .input, .file { width: 100%; padding: .875rem 1rem; border: 2px solid #e0e0e0; border-radius: 12px; background: #fafafa; transition: .2s; }
        .input:focus, .file:focus { outline: none; border-color: #7b1fa2; background: white; box-shadow: 0 0 0 4px rgba(123, 31, 162, 0.1); }
        .grid { display: grid; gap: 1rem; }
        .grid-2 { grid-template-columns: repeat(2, 1fr); }
        .actions { display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem; }
        .btn { padding: .875rem 1.25rem; border: none; border-radius: 12px; cursor: pointer; font-weight: 600; }
        .btn-primary { background: linear-gradient(135deg, #7b1fa2 0%, #9c27b0 100%); color: white; }
        .btn-outline { background: white; color: #7b1fa2; border: 2px solid #7b1fa2; }
        .nav { display: flex; gap: 1rem; margin-top: 1rem; }
        .notice { background: #efe; border: 1px solid #cfc; color: #060; padding: 1rem; border-radius: 10px; margin-bottom: 1rem; font-size: 0.9rem; display: none; }
        .error { background: #fee; border: 1px solid #fcc; color: #c00; padding: 1rem; border-radius: 10px; margin-bottom: 1rem; font-size: 0.9rem; display: none; }
        .links { margin-top: 1rem; display: flex; gap: 1rem; }
        .links a { color: #7b1fa2; text-decoration: none; }
        .links a:hover { text-decoration: underline; }
        @media (max-width: 768px) { .grid-2 { grid-template-columns: 1fr; } }
    </style>
    <script>
        function showStep(step) {
            document.querySelectorAll('.step-pane').forEach(p => p.style.display = 'none');
            document.getElementById('step-' + step).style.display = 'block';
            document.querySelectorAll('.step').forEach((el, idx) => {
                if ((idx + 1) === step) el.classList.add('active'); else el.classList.remove('active');
            });
            document.getElementById('prevBtn').style.visibility = step === 1 ? 'hidden' : 'visible';
            document.getElementById('nextBtn').style.display = step === 4 ? 'none' : 'inline-flex';
            document.getElementById('submitBtn').style.display = step === 4 ? 'inline-flex' : 'none';
        }
        function nextStep() {
            const current = parseInt(document.getElementById('currentStep').value);
            showStep(current + 1);
            document.getElementById('currentStep').value = current + 1;
        }
        function prevStep() {
            const current = parseInt(document.getElementById('currentStep').value);
            showStep(current - 1);
            document.getElementById('currentStep').value = current - 1;
        }
        window.addEventListener('DOMContentLoaded', () => showStep(1));
    </script>
    </head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1>تسجيل تاجر جديد</h1>
                <div class="steps">
                    <div class="step active"><i class="fas fa-user"></i><span>الحساب</span></div>
                    <div class="step"><i class="fas fa-building"></i><span>تفاصيل العمل</span></div>
                    <div class="step"><i class="fas fa-university"></i><span>المعلومات البنكية</span></div>
                    <div class="step"><i class="fas fa-file-upload"></i><span>المستندات</span></div>
                </div>
            </div>
            <div class="content">
                @if ($errors->any())
                    <div class="error">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $errors->first() }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="notice">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('trader.register') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="currentStep" value="1">
                    <div id="step-1" class="step-pane">
                        <div class="grid grid-2">
                            <div class="form-group">
                                <label>الاسم التجاري (بالإنجليزية)</label>
                                <input type="text" name="business_name_en" class="input" required>
                            </div>
                            <div class="form-group">
                                <label>الاسم التجاري (بالعربية)</label>
                                <input type="text" name="business_name_ar" class="input">
                            </div>
                            <div class="form-group">
                                <label>البريد الإلكتروني</label>
                                <input type="email" name="email" class="input" required>
                            </div>
                            <div class="form-group">
                                <label>الهاتف</label>
                                <input type="text" name="phone" class="input" required>
                            </div>
                            <div class="form-group">
                                <label>كلمة المرور</label>
                                <input type="password" name="password" class="input" required>
                            </div>
                            <div class="form-group">
                                <label>تأكيد كلمة المرور</label>
                                <input type="password" name="password_confirmation" class="input" required>
                            </div>
                        </div>
                    </div>
                    <div id="step-2" class="step-pane" style="display:none">
                        <div class="grid grid-2">
                            <div class="form-group">
                                <label>رقم السجل التجاري</label>
                                <input type="text" name="registration_number" class="input">
                            </div>
                            <div class="form-group">
                                <label>الرقم الضريبي</label>
                                <input type="text" name="tax_id" class="input">
                            </div>
                            <div class="form-group">
                                <label>اسم الشخص المسؤول</label>
                                <input type="text" name="contact_person" class="input" required>
                            </div>
                            <div class="form-group">
                                <label>عنوان العمل</label>
                                <input type="text" name="business_address" class="input" required>
                            </div>
                            <div class="form-group">
                                <label>شعار العمل</label>
                                <input type="file" name="business_logo" class="file" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div id="step-3" class="step-pane" style="display:none">
                        <div class="grid grid-2">
                            <div class="form-group">
                                <label>اسم البنك</label>
                                <input type="text" name="bank_name" class="input" required>
                            </div>
                            <div class="form-group">
                                <label>اسم صاحب الحساب</label>
                                <input type="text" name="account_holder" class="input" required>
                            </div>
                            <div class="form-group">
                                <label>رقم الحساب</label>
                                <input type="text" name="account_number" class="input" required>
                            </div>
                            <div class="form-group">
                                <label>IBAN</label>
                                <input type="text" name="iban" class="input">
                            </div>
                        </div>
                    </div>
                    <div id="step-4" class="step-pane" style="display:none">
                        <div class="grid grid-2">
                            <div class="form-group">
                                <label>رخصة العمل</label>
                                <input type="file" name="business_license" class="file" accept=".pdf,image/*">
                            </div>
                            <div class="form-group">
                                <label>الشهادة الضريبية</label>
                                <input type="file" name="tax_certificate" class="file" accept=".pdf,image/*">
                            </div>
                            <div class="form-group">
                                <label>هوية المالك</label>
                                <input type="file" name="owner_id_card" class="file" accept=".pdf,image/*">
                            </div>
                        </div>
                    </div>
                    <div class="actions">
                        <button type="button" id="prevBtn" class="btn btn-outline" onclick="prevStep()">السابق</button>
                        <div class="nav">
                            <a href="{{ route('trader.login.form') }}">لدي حساب؟ تسجيل الدخول</a>
                        </div>
                        <div>
                            <button type="button" id="nextBtn" class="btn btn-primary" onclick="nextStep()">التالي</button>
                            <button type="submit" id="submitBtn" class="btn btn-primary" style="display:none">إرسال التسجيل</button>
                            <button type="button" id="apiSubmitBtn" class="btn btn-outline" style="display:none">إرسال عبر الواجهة</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const apiBtn = document.getElementById('apiSubmitBtn');
    apiBtn.style.display = 'inline-flex';
    apiBtn.addEventListener('click', async () => {
        const form = document.querySelector('form[action="{{ route('trader.register') }}"]');
        const data = {
            business_name_en: form.querySelector('input[name="business_name_en"]').value,
            business_name_ar: form.querySelector('input[name="business_name_ar"]').value,
            email: form.querySelector('input[name="email"]').value,
            phone: form.querySelector('input[name="phone"]').value,
            password: form.querySelector('input[name="password"]').value,
            password_confirmation: form.querySelector('input[name="password_confirmation"]').value,
            registration_number: form.querySelector('input[name="registration_number"]').value,
            tax_id: form.querySelector('input[name="tax_id"]').value,
            contact_person: form.querySelector('input[name="contact_person"]').value,
            business_address: form.querySelector('input[name="business_address"]').value,
            bank_name: form.querySelector('input[name="bank_name"]').value,
            account_holder: form.querySelector('input[name="account_holder"]').value,
            account_number: form.querySelector('input[name="account_number"]').value,
            iban: form.querySelector('input[name="iban"]').value,
        };
        try {
            const res = await fetch('/api/trader/register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const json = await res.json();
            if (json?.success) {
                alert('تم إرسال التسجيل. سيتم إشعارك بعد المراجعة.');
                window.location = "{{ route('trader.login.form') }}";
            } else {
                alert('حدث خطأ أثناء الإرسال');
            }
        } catch (e) {}
    });
});
</script>

