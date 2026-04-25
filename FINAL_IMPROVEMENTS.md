# Final Admin Mart Improvements

## Changes Made

### 1. ✅ Removed Tips Section from Low Stock Page
**File:** `resources/views/dashboards/super-admin/mart-low-stock.blade.php`

**What was removed:**
- Blue info box with inventory management tips
- 4 bullet points with advice

**Result:** Cleaner, more focused page showing only the low stock products table.

---

### 2. ✅ Added Bulk Toggle Button for Inventory Tracking
**Feature:** One-click button to enable/disable inventory tracking for ALL products

**Location:** Main mart dashboard (`http://127.0.0.1:8000/dashboard/admin/mart`)

**Button Position:** Next to "المنتجات" heading, above the products table

**How it works:**
1. Click the "تفعيل الكل" button
2. Confirmation dialog appears:
   - Click "موافق" (OK) = Enable tracking for all products
   - Click "إلغاء" (Cancel) = Disable tracking for all products
3. Updates all mart products in database
4. Shows success message with count
5. Page reloads to show updated states

**Visual:**
```
┌─────────────────────────────────────────────────────────────┐
│ المنتجات  [🔘 تفعيل الكل]                                  │
│                                                             │
│ [Search] [Filters] [Add Product]                           │
└─────────────────────────────────────────────────────────────┘
```

---

## Files Modified

### 1. `resources/views/dashboards/super-admin/mart-low-stock.blade.php`
- Removed tips section (lines with blue info box)

### 2. `resources/views/dashboards/super-admin/mart.blade.php`
- Added bulk toggle button in header
- Added `bulkToggleInventory()` JavaScript function

### 3. `app/Http/Controllers/Dashboard/SuperAdminController.php`
- Added `bulkToggleInventoryTracking()` method

### 4. `routes/dashboard.php`
- Added bulk toggle route

---

## New Controller Method

```php
public function bulkToggleInventoryTracking(Request $request)
{
    abort_unless(Schema::hasTable('products'), 404);

    $trackInventory = $request->input('track_inventory', false);

    // Update all mart products
    $query = Product::query();
    
    if (Schema::hasColumn('products', 'market')) {
        $query->where('market', 'mart');
    }

    $updatedCount = $query->update([
        'track_inventory' => $trackInventory,
    ]);

    Cache::forget('mart:navigation:v1');

    $action = $trackInventory ? 'تفعيل' : 'تعطيل';
    $message = "تم {$action} تتبع المخزون لـ {$updatedCount} منتج";

    return response()->json([
        'success' => true,
        'message' => $message,
        'track_inventory' => $trackInventory,
        'updated_count' => $updatedCount,
    ]);
}
```

---

## New Route

```php
Route::post('/products/bulk-toggle-inventory', [SuperAdminController::class, 'bulkToggleInventoryTracking'])
    ->name('products.bulk-toggle-inventory');
```

**Full URL:** `POST /dashboard/admin/mart/products/bulk-toggle-inventory`

---

## JavaScript Function

```javascript
async function bulkToggleInventory() {
    const bulkBtn = document.getElementById('bulkToggleInventoryBtn');
    const bulkText = document.getElementById('bulkToggleText');
    
    // Ask user what action to take
    const action = confirm('اختر الإجراء:\n\nموافق = تفعيل تتبع المخزون لجميع المنتجات\nإلغاء = تعطيل تتبع المخزون لجميع المنتجات');
    const enableTracking = action; // true = enable, false = disable
    
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrf = csrfMeta ? csrfMeta.getAttribute('content') : '';
    
    try {
        bulkBtn.disabled = true;
        bulkBtn.style.opacity = '0.5';
        bulkText.textContent = 'جاري التحديث...';
        
        const response = await fetch('/dashboard/admin/mart/products/bulk-toggle-inventory', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify({ track_inventory: enableTracking })
        });
        
        if (response.ok) {
            const data = await response.json();
            
            // Show success message
            if (window.showToast) {
                window.showToast(data.message || 'تم تحديث جميع المنتجات بنجاح');
            } else {
                alert(data.message || 'تم تحديث جميع المنتجات بنجاح');
            }
            
            // Reload page to show updated states
            setTimeout(() => window.location.reload(), 1000);
        } else {
            throw new Error('Failed to bulk update');
        }
    } catch (error) {
        console.error('Error bulk toggling inventory:', error);
        alert('حدث خطأ أثناء التحديث الجماعي');
        bulkText.textContent = 'تفعيل الكل';
    } finally {
        bulkBtn.disabled = false;
        bulkBtn.style.opacity = '1';
    }
}
```

---

## Testing Instructions

### Test 1: Low Stock Page (Tips Removed)
1. Go to `http://127.0.0.1:8000/dashboard/admin/mart/low-stock`
2. ✅ Verify no blue tips box appears
3. ✅ Only table and actions are visible

### Test 2: Bulk Toggle - Enable All
1. Go to `http://127.0.0.1:8000/dashboard/admin/mart`
2. Click "تفعيل الكل" button
3. In confirmation dialog, click "موافق" (OK)
4. ✅ See loading state: "جاري التحديث..."
5. ✅ See success message: "تم تفعيل تتبع المخزون لـ X منتج"
6. ✅ Page reloads
7. ✅ All toggle buttons show green "مفعل"

### Test 3: Bulk Toggle - Disable All
1. Go to `http://127.0.0.1:8000/dashboard/admin/mart`
2. Click "تفعيل الكل" button
3. In confirmation dialog, click "إلغاء" (Cancel)
4. ✅ See loading state: "جاري التحديث..."
5. ✅ See success message: "تم تعطيل تتبع المخزون لـ X منتج"
6. ✅ Page reloads
7. ✅ All toggle buttons show gray "معطل"

---

## Visual Guide

### Before (Low Stock Page with Tips):
```
┌─────────────────────────────────────────────────────────────┐
│ Low Stock Products Table                                    │
│ [Product 1] [Product 2] [Product 3]                         │
├─────────────────────────────────────────────────────────────┤
│ 💡 نصائح لإدارة المخزون:                                   │
│ • قم بتحديث المخزون بانتظام                                │
│ • اضبط حد المخزون المنخفض                                  │
│ • فعّل تتبع المخزون للمنتجات المهمة                        │
│ • راجع هذه الصفحة يومياً                                   │
└─────────────────────────────────────────────────────────────┘
```

### After (Low Stock Page - Clean):
```
┌─────────────────────────────────────────────────────────────┐
│ Low Stock Products Table                                    │
│ [Product 1] [Product 2] [Product 3]                         │
└─────────────────────────────────────────────────────────────┘
```

### Bulk Toggle Button:
```
┌─────────────────────────────────────────────────────────────┐
│ المنتجات  [🔘 تفعيل الكل]  ← NEW BUTTON!                   │
│                                                             │
│ [Search] [Category Filter] [Add Product]                   │
├─────────────────────────────────────────────────────────────┤
│ Product Table with individual toggle buttons               │
└─────────────────────────────────────────────────────────────┘
```

### Confirmation Dialog:
```
┌─────────────────────────────────────────────────────────────┐
│ اختر الإجراء:                                              │
│                                                             │
│ موافق = تفعيل تتبع المخزون لجميع المنتجات                 │
│ إلغاء = تعطيل تتبع المخزون لجميع المنتجات                 │
│                                                             │
│                    [إلغاء]  [موافق]                        │
└─────────────────────────────────────────────────────────────┘
```

---

## Summary

✅ **Removed:** Tips section from low stock page  
✅ **Added:** Bulk toggle button for inventory tracking  
✅ **Updates:** All mart products at once  
✅ **User-friendly:** Clear confirmation dialog  
✅ **Feedback:** Success message with count  

**Total Changes:** 4 files modified  
**New Routes:** 1  
**New Methods:** 1  
**New JavaScript Functions:** 1  

---

**Status:** ✅ Complete & Ready to Test  
**Date:** April 22, 2026
