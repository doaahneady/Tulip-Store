// Accounting Dashboard Interactive Features

document.addEventListener('DOMContentLoaded', function() {
    
    // ==================== Export Functions ====================
    window.exportToPDF = function(reportType) {
        const url = `/accounting/export?report_type=${reportType}&format=pdf`;
        window.open(url, '_blank');
        showNotification('جاري تحضير ملف PDF...', 'info');
    };

    window.exportToExcel = function(reportType) {
        const url = `/accounting/export?report_type=${reportType}&format=excel`;
        window.location.href = url;
        showNotification('جاري تحضير ملف Excel...', 'info');
    };

    // ==================== Print Functions ====================
    window.printReport = function() {
        window.print();
    };

    // ==================== Filter Functions ====================
    window.applyFilters = function(formId) {
        const form = document.getElementById(formId);
        if (form) {
            form.submit();
        }
    };

    window.resetFilters = function(formId) {
        const form = document.getElementById(formId);
        if (form) {
            form.reset();
            form.submit();
        }
    };

    // ==================== Account Management ====================
    window.addNewAccount = function() {
        showModal('addAccountModal');
    };

    window.editAccount = function(accountId) {
        fetch(`/accounting/accounts/${accountId}`)
            .then(response => response.json())
            .then(data => {
                // Populate edit form
                document.getElementById('edit_account_id').value = data.id;
                document.getElementById('edit_account_name').value = data.account_name;
                document.getElementById('edit_description').value = data.description;
                showModal('editAccountModal');
            })
            .catch(error => {
                showNotification('حدث خطأ في تحميل بيانات الحساب', 'error');
            });
    };

    window.toggleAccountStatus = function(accountId) {
        if (confirm('هل أنت متأكد من تغيير حالة الحساب؟')) {
            fetch(`/accounting/accounts/${accountId}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('تم تحديث حالة الحساب بنجاح', 'success');
                    setTimeout(() => location.reload(), 1000);
                }
            })
            .catch(error => {
                showNotification('حدث خطأ في تحديث الحساب', 'error');
            });
        }
    };

    // ==================== Journal Entry Management ====================
    window.createJournalEntry = function() {
        window.location.href = '/accounting/journal-entries/create';
    };

    window.postJournalEntry = function(entryId) {
        if (confirm('هل أنت متأكد من ترحيل هذا القيد؟ لا يمكن التراجع عن هذا الإجراء.')) {
            fetch(`/accounting/journal-entries/${entryId}/post`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('تم ترحيل القيد بنجاح', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(data.message || 'حدث خطأ في ترحيل القيد', 'error');
                }
            })
            .catch(error => {
                showNotification('حدث خطأ في ترحيل القيد', 'error');
            });
        }
    };

    window.reverseJournalEntry = function(entryId) {
        if (confirm('هل أنت متأكد من عكس هذا القيد؟')) {
            fetch(`/accounting/journal-entries/${entryId}/reverse`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(`تم إنشاء قيد العكس: ${data.reversal_number}`, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification(data.message || 'حدث خطأ في عكس القيد', 'error');
                }
            })
            .catch(error => {
                showNotification('حدث خطأ في عكس القيد', 'error');
            });
        }
    };

    window.deleteJournalEntry = function(entryId) {
        if (confirm('هل أنت متأكد من حذف هذا القيد؟')) {
            fetch(`/accounting/journal-entries/${entryId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('تم حذف القيد بنجاح', 'success');
                    setTimeout(() => location.reload(), 1000);
                }
            })
            .catch(error => {
                showNotification('حدث خطأ في حذف القيد', 'error');
            });
        }
    };

    // ==================== Quick Entry Templates ====================
    window.useQuickEntry = function(template) {
        const amount = prompt('أدخل المبلغ:');
        if (amount && !isNaN(amount)) {
            const description = prompt('أدخل الوصف (اختياري):') || '';
            
            fetch('/accounting/quick-entry', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ template, amount, description })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Populate journal entry form with template data
                    window.location.href = `/accounting/journal-entries/create?template=${template}&amount=${amount}&description=${encodeURIComponent(description)}`;
                }
            })
            .catch(error => {
                showNotification('حدث خطأ في تحميل القالب', 'error');
            });
        }
    };

    // ==================== Calculator Functions ====================
    window.openCalculator = function(type) {
        window.open(`/accounting/calculators/${type}`, '_blank', 'width=800,height=600');
    };

    // ==================== Invoice Management ====================
    window.viewInvoice = function(orderId) {
        window.open(`/order/${orderId}/invoice`, '_blank');
    };

    window.downloadInvoice = function(orderId) {
        window.location.href = `/order/${orderId}/invoice/download`;
        showNotification('جاري تحميل الفاتورة...', 'info');
    };

    window.sendInvoiceEmail = function(orderId) {
        if (confirm('هل تريد إرسال الفاتورة بالبريد الإلكتروني؟')) {
            fetch(`/accounting/invoices/${orderId}/send-email`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('تم إرسال الفاتورة بنجاح', 'success');
                }
            })
            .catch(error => {
                showNotification('حدث خطأ في إرسال الفاتورة', 'error');
            });
        }
    };

    // ==================== Receivables & Payables ====================
    window.viewCustomerStatement = function(customerId) {
        window.open(`/accounting/receivables/customer/${customerId}`, '_blank');
    };

    window.viewSupplierStatement = function(supplierId) {
        window.open(`/accounting/payables/supplier/${supplierId}`, '_blank');
    };

    window.recordPayment = function(type, id) {
        showModal(`${type}PaymentModal`);
        document.getElementById(`${type}_id`).value = id;
    };

    // ==================== Payroll Functions ====================
    window.processPayroll = function(employeeId) {
        if (confirm('هل تريد معالجة راتب هذا الموظف؟')) {
            fetch(`/accounting/payroll/${employeeId}/process`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('تم معالجة الراتب بنجاح', 'success');
                    setTimeout(() => location.reload(), 1000);
                }
            })
            .catch(error => {
                showNotification('حدث خطأ في معالجة الراتب', 'error');
            });
        }
    };

    window.viewPayslip = function(employeeId, month) {
        window.open(`/accounting/payroll/${employeeId}/payslip?month=${month}`, '_blank');
    };

    window.addEmployee = function() {
        showModal('addEmployeeModal');
    };

    // ==================== Fixed Assets ====================
    window.addAsset = function() {
        showModal('addAssetModal');
    };

    window.calculateDepreciation = function(assetId) {
        fetch(`/accounting/fixed-assets/${assetId}/depreciation`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('تم حساب الاستهلاك بنجاح', 'success');
                setTimeout(() => location.reload(), 1000);
            }
        })
        .catch(error => {
            showNotification('حدث خطأ في حساب الاستهلاك', 'error');
        });
    };

    // ==================== Settings ====================
    window.saveSettings = function() {
        const form = document.getElementById('settingsForm');
        if (form) {
            const formData = new FormData(form);
            
            fetch('/accounting/settings/save', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('تم حفظ الإعدادات بنجاح', 'success');
                }
            })
            .catch(error => {
                showNotification('حدث خطأ في حفظ الإعدادات', 'error');
            });
        }
    };

    window.createBackup = function() {
        if (confirm('هل تريد إنشاء نسخة احتياطية الآن؟')) {
            showNotification('جاري إنشاء النسخة الاحتياطية...', 'info');
            
            fetch('/accounting/settings/backup', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('تم إنشاء النسخة الاحتياطية بنجاح', 'success');
                }
            })
            .catch(error => {
                showNotification('حدث خطأ في إنشاء النسخة الاحتياطية', 'error');
            });
        }
    };

    window.viewAuditLog = function() {
        window.location.href = '/accounting/settings/audit-log';
    };

    // ==================== Modal Functions ====================
    window.showModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'flex';
        } else {
            // Create a simple modal if it doesn't exist
            alert('هذه الميزة قيد التطوير');
        }
    };

    window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'none';
        }
    };

    // ==================== Notification System ====================
    window.showNotification = function(message, type = 'info') {
        // Remove existing notifications
        const existing = document.querySelector('.notification-toast');
        if (existing) {
            existing.remove();
        }

        // Create notification
        const notification = document.createElement('div');
        notification.className = `notification-toast notification-${type}`;
        notification.innerHTML = `
            <div style="display: flex; align-items: center; gap: 1rem;">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}" style="font-size: 1.5rem;"></i>
                <span>${message}</span>
            </div>
        `;
        
        // Add styles
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: ${type === 'success' ? '#d1fae5' : type === 'error' ? '#fee2e2' : '#dbeafe'};
            color: ${type === 'success' ? '#065f46' : type === 'error' ? '#991b1b' : '#1e40af'};
            padding: 1rem 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10000;
            font-weight: 600;
            animation: slideDown 0.3s ease-out;
        `;

        document.body.appendChild(notification);

        // Auto remove after 3 seconds
        setTimeout(() => {
            notification.style.animation = 'slideUp 0.3s ease-out';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    };

    // ==================== Date Range Picker ====================
    window.updateDateRange = function(range) {
        const today = new Date();
        let startDate, endDate;

        switch(range) {
            case 'today':
                startDate = endDate = today.toISOString().split('T')[0];
                break;
            case 'week':
                startDate = new Date(today.setDate(today.getDate() - 7)).toISOString().split('T')[0];
                endDate = new Date().toISOString().split('T')[0];
                break;
            case 'month':
                startDate = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
                endDate = new Date().toISOString().split('T')[0];
                break;
            case 'year':
                startDate = new Date(today.getFullYear(), 0, 1).toISOString().split('T')[0];
                endDate = new Date().toISOString().split('T')[0];
                break;
        }

        document.getElementById('from_date').value = startDate;
        document.getElementById('to_date').value = endDate;
    };

    // ==================== Search Functions ====================
    window.searchAccounts = function() {
        const searchTerm = document.getElementById('accountSearch').value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    };

    // ==================== Bulk Actions ====================
    window.selectAll = function(checkbox) {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(cb => cb.checked = checkbox.checked);
    };

    window.bulkAction = function(action) {
        const selected = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
        
        if (selected.length === 0) {
            showNotification('الرجاء تحديد عنصر واحد على الأقل', 'error');
            return;
        }

        if (confirm(`هل تريد تطبيق "${action}" على ${selected.length} عنصر؟`)) {
            fetch(`/accounting/bulk-action`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ action, ids: selected })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('تم تنفيذ العملية بنجاح', 'success');
                    setTimeout(() => location.reload(), 1000);
                }
            })
            .catch(error => {
                showNotification('حدث خطأ في تنفيذ العملية', 'error');
            });
        }
    };

    // Add animation styles
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideDown {
            from { transform: translate(-50%, -100%); opacity: 0; }
            to { transform: translate(-50%, 0); opacity: 1; }
        }
        @keyframes slideUp {
            from { transform: translate(-50%, 0); opacity: 1; }
            to { transform: translate(-50%, -100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
});
