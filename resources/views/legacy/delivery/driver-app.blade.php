<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تطبيق السائق - Tulip Store</title>
      <!-- fav icon -->
        <link rel="icon" type="image/png" href="/images/fav_icon-v1.png">
    <style>
        body { font-family:'El Messiri',sans-serif; background:#f5f7fb; color:#222; margin:0; }
        .container { max-width: 640px; margin: 0 auto; padding: 2rem; }
        .card { background: #fff; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.06); padding: 1.5rem; margin-bottom: 1rem; }
        .title { font-weight: 700; font-size: 1.25rem; margin-bottom: .5rem; }
        .field { margin-bottom: 1rem; }
        .field label { display:block; margin-bottom:.5rem; color:#555; font-weight:600; }
        .field input { width:100%; padding:.75rem 1rem; border:2px solid #e6e8ee; border-radius:12px; background:#fafbfe; }
        .actions { display:flex; gap:.75rem; flex-wrap: wrap; }
        .btn { border:none; border-radius:10px; padding:.75rem 1rem; cursor:pointer; font-weight:700; }
        .btn-primary { background:#2563eb; color:#fff; }
        .btn-secondary { background:#f59e0b; color:#fff; }
        .btn-danger { background:#ef4444; color:#fff; }
        .status { margin-top:1rem; padding:.75rem 1rem; border-radius:10px; background:#eef2ff; color:#1e3a8a; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="title">تحديث حالة التكليف</div>
        <div class="field">
            <label>رقم التكليف</label>
            <input type="number" id="assignmentId" placeholder="مثال: 123">
        </div>
        <div class="actions">
            <button class="btn btn-primary" onclick="updateStatus('pickup')">استلام</button>
            <button class="btn btn-secondary" onclick="updateStatus('in-transit')">قيد النقل</button>
            <button class="btn btn-primary" onclick="updateStatus('deliver')">تم التسليم</button>
            <button class="btn btn-danger" onclick="updateStatus('failed')">فشل التسليم</button>
        </div>
        <div id="statusBox" class="status" style="display:none"></div>
    </div>
</div>
<script>
async function updateStatus(action) {
    const id = document.getElementById('assignmentId').value;
    if (!id) { alert('يرجى إدخال رقم التكليف'); return; }
    try {
        const res = await fetch(`/api/delivery/assignments/${id}/${action}`, { method:'POST' });
        const data = await res.json();
        const box = document.getElementById('statusBox');
        box.style.display = 'block';
        box.textContent = data?.success ? 'تم تحديث الحالة بنجاح' : 'حدث خطأ';
    } catch (e) {}
}
</script>
</body>
</html>
