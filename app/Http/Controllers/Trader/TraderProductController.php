<?php

namespace App\Http\Controllers\Trader;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\DashboardNotification;
use App\Models\Employee;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CategoryAttributeDefinition;
use App\Models\Product;
use App\Models\Trader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TraderProductController extends Controller
{
    private function currentTrader(): Trader
    {
        $user = Auth::guard('trader')->user();
        abort_unless($user, 401);
        $trader = $user instanceof Trader
            ? $user
            : Trader::where('user_id', $user->id)->firstOrFail();
        abort_unless($trader->status === Trader::STATUS_APPROVED, 403);

        return $trader;
    }

    private function categories()
    {
        if (! Schema::hasTable('categories')) {
            return collect();
        }

        return Category::query()
            ->when(Schema::hasColumn('categories', 'market'), fn ($q) => $q->where('market', 'store'))
            ->orderBy('name')
            ->get();
    }

    public function index(Request $request)
    {
        $trader = $this->currentTrader();

        $products = Product::query()
            ->where('trader_id', $trader->id)
            ->when($request->search, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            })
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('trader.products.index', compact('trader', 'products'));
    }

    public function create()
    {
        $trader = $this->currentTrader();
        $categories = $this->categories();

        return view('trader.products.create', compact('trader', 'categories'));
    }

    public function categoryAttributes(\App\Models\Category $category)
    {
        $defs = CategoryAttributeDefinition::forCategory((int) $category->id);
        if ($defs->isEmpty()) {
            $name = mb_strtolower((string) ($category->name ?? ''));
            $fallback = [];
            if (str_contains($name, 'زه') || str_contains($name, 'ورد') || str_contains($name, 'bouquet')) {
                $fallback = [
                    ['name' => 'اللون الأساسي', 'type' => 'color', 'options' => ['#ff6b6b', '#ffd93d', '#2ec4b6'], 'is_required' => false],
                    ['name' => 'المناسبة', 'type' => 'select', 'options' => ['زواج', 'تخرج', 'عيد ميلاد', 'تهنئة'], 'is_required' => false],
                    ['name' => 'تاريخ التسليم', 'type' => 'date', 'options' => [], 'is_required' => false],
                    ['name' => 'ملاحظة البطاقة', 'type' => 'textarea', 'options' => [], 'is_required' => false],
                ];
            } elseif (str_contains($name, 'الكترون') || str_contains($name, 'electronics')) {
                $fallback = [
                    ['name' => 'العلامة التجارية', 'type' => 'text', 'options' => [], 'is_required' => false],
                    ['name' => 'الموديل', 'type' => 'text', 'options' => [], 'is_required' => false],
                    ['name' => 'الضمان (أشهر)', 'type' => 'number', 'options' => [], 'is_required' => false],
                    ['name' => 'اللون', 'type' => 'multiselect', 'options' => ['#000000', '#ffffff', '#f97316', '#1e3a8a'], 'is_required' => false],
                ];
            } elseif (str_contains($name, 'ملابس') || str_contains($name, 'ألبسة') || str_contains($name, 'clothes')) {
                $fallback = [
                    ['name' => 'المقاس', 'type' => 'select', 'options' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'], 'is_required' => true],
                    ['name' => 'اللون', 'type' => 'multiselect', 'options' => ['#111827', '#ef4444', '#10b981', '#3b82f6'], 'is_required' => false],
                    ['name' => 'الخامة', 'type' => 'text', 'options' => [], 'is_required' => false],
                    ['name' => 'الجنس', 'type' => 'radio', 'options' => ['رجالي', 'نسائي', 'أطفال'], 'is_required' => false],
                ];
            } else {
                $fallback = [
                    ['name' => 'العلامة التجارية', 'type' => 'text', 'options' => [], 'is_required' => false],
                    ['name' => 'المنشأ', 'type' => 'text', 'options' => [], 'is_required' => false],
                    ['name' => 'تاريخ الانتهاء', 'type' => 'date', 'options' => [], 'is_required' => false],
                ];
            }
            $defs = collect($fallback)->map(function ($d, $i) {
                return (object) [
                    'name' => $d['name'],
                    'type' => $d['type'],
                    'options' => $d['options'],
                    'is_required' => (bool) ($d['is_required'] ?? false),
                    'sort_order' => $i,
                ];
            });
        }

        return response()->json([
            'success' => true,
            'attributes' => $defs->map(function ($d) {
                return [
                    'name' => $d->name,
                    'type' => $d->type,
                    'options' => is_array($d->options) ? $d->options : [],
                    'is_required' => (bool) ($d->is_required ?? false),
                ];
            })->values()->all(),
        ]);
    }

    public function store(Request $request)
    {
        $trader = $this->currentTrader();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'details' => 'nullable|string',
            'category_id' => 'nullable|integer',
            'sku' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'track_inventory' => 'nullable|boolean',
            'stock_quantity' => 'nullable|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'custom_attributes' => 'nullable|array|max:200',
            'custom_attributes.*.id' => 'nullable|integer',
            'custom_attributes.*.uid' => 'nullable|string|max:40',
            'custom_attributes.*.name' => 'nullable|string|max:80',
            'custom_attributes.*.key' => 'nullable|string|max:80',
            'custom_attributes.*.type' => 'nullable|in:text,textarea,select,number,date,checkbox_group,radio_group,file,radio,multiselect,checkbox,color,dropdown',
            'custom_attributes.*.required' => 'nullable|boolean',
            'custom_attributes.*.options' => 'nullable|string|max:2000',
            'custom_attributes.*.value' => 'nullable',
            'custom_attributes.*.value.*' => 'nullable|string|max:255',
            'custom_attributes.*.value_existing' => 'nullable|string|max:2000',
            'custom_attributes.*.rules' => 'nullable|array',
            'custom_attributes.*.rules.min_length' => 'nullable|integer|min:0|max:10000',
            'custom_attributes.*.rules.max_length' => 'nullable|integer|min:0|max:10000',
            'custom_attributes.*.rules.min' => 'nullable|numeric',
            'custom_attributes.*.rules.max' => 'nullable|numeric',
            'custom_attributes.*.rules.allowed_file_types' => 'nullable|string|max:200',
            'custom_attributes.*.rules.max_file_size_kb' => 'nullable|integer|min:1|max:51200',
            'custom_attribute_files' => 'nullable|array',
            'custom_attribute_files.*' => 'nullable|file|max:51200',
        ]);

        $this->validateCustomAttributes($validated['custom_attributes'] ?? [], $request->file('custom_attribute_files') ?? []);
        $this->validateCategoryAttributes((int) ($validated['category_id'] ?? 0), $validated['custom_attributes'] ?? []);

        $slug = Str::slug($validated['name']);
        if ($slug === '') {
            $slug = 'product';
        }
        $baseSlug = $slug;
        $i = 0;
        while (Product::where('slug', $slug)->exists() && $i < 50) {
            $slug = $baseSlug.'-'.random_int(1000, 9999);
            $i++;
        }

        $sku = $validated['sku'] ?? null;
        if (! $sku) {
            $sku = 'TRD-'.$trader->id.'-'.Str::upper(Str::random(6));
        }

        $imagePath = null;
        if ($request->file('image')) {
            $imagePath = Storage::disk('public')->putFile('products/trader', $request->file('image'));
        }

        $imagePaths = [];
        if ($request->file('images')) {
            foreach ($request->file('images') as $img) {
                if ($img) {
                    $imagePaths[] = Storage::disk('public')->putFile('products/trader', $img);
                }
            }
        }

        $data = [
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'details' => $validated['details'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'trader_id' => $trader->id,
            'sku' => $sku,
            'price' => $validated['price'],
            'discount_price' => $validated['discount_price'] ?? null,
            'cost_price' => $validated['cost_price'] ?? null,
            'track_inventory' => (bool) ($validated['track_inventory'] ?? true),
            'stock_quantity' => (int) ($validated['stock_quantity'] ?? 0),
            'low_stock_threshold' => (int) ($validated['low_stock_threshold'] ?? 5),
            'status' => 'pending',
        ];
        if (Schema::hasColumn('products', 'market')) {
            $data['market'] = 'store';
        }
        if (Schema::hasColumn('products', 'is_trader_product')) {
            $data['is_trader_product'] = true;
        }
        if (Schema::hasColumn('products', 'is_active')) {
            $data['is_active'] = false;
        }
        if ($imagePath !== null && Schema::hasColumn('products', 'image')) {
            $data['image'] = $imagePath;
        }
        if ($imagePaths && Schema::hasColumn('products', 'images')) {
            $data['images'] = $imagePaths;
        }

        $product = Product::create($data);
        $this->syncCustomAttributes($product, $validated['custom_attributes'] ?? [], $request);
        $this->notifyCustomerSupportProductReview($product);

        return redirect()->route('trader.products.index')->with('success', 'Product submitted. Waiting for support approval.');
    }

    public function edit(Product $product)
    {
        $trader = $this->currentTrader();
        abort_unless((int) $product->trader_id === (int) $trader->id, 404);

        $categories = $this->categories();

        return view('trader.products.edit', compact('trader', 'product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $trader = $this->currentTrader();
        abort_unless((int) $product->trader_id === (int) $trader->id, 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'details' => 'nullable|string',
            'category_id' => 'nullable|integer',
            'sku' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'track_inventory' => 'nullable|boolean',
            'stock_quantity' => 'nullable|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'custom_attributes' => 'nullable|array|max:200',
            'custom_attributes.*.id' => 'nullable|integer',
            'custom_attributes.*.uid' => 'nullable|string|max:40',
            'custom_attributes.*.name' => 'nullable|string|max:80',
            'custom_attributes.*.key' => 'nullable|string|max:80',
            'custom_attributes.*.type' => 'nullable|in:text,textarea,select,number,date,checkbox_group,radio_group,file,radio,multiselect,checkbox,color,dropdown',
            'custom_attributes.*.required' => 'nullable|boolean',
            'custom_attributes.*.options' => 'nullable|string|max:2000',
            'custom_attributes.*.value' => 'nullable',
            'custom_attributes.*.value.*' => 'nullable|string|max:255',
            'custom_attributes.*.value_existing' => 'nullable|string|max:2000',
            'custom_attributes.*.rules' => 'nullable|array',
            'custom_attributes.*.rules.min_length' => 'nullable|integer|min:0|max:10000',
            'custom_attributes.*.rules.max_length' => 'nullable|integer|min:0|max:10000',
            'custom_attributes.*.rules.min' => 'nullable|numeric',
            'custom_attributes.*.rules.max' => 'nullable|numeric',
            'custom_attributes.*.rules.allowed_file_types' => 'nullable|string|max:200',
            'custom_attributes.*.rules.max_file_size_kb' => 'nullable|integer|min:1|max:51200',
            'custom_attribute_files' => 'nullable|array',
            'custom_attribute_files.*' => 'nullable|file|max:51200',
        ]);

        $this->validateCustomAttributes($validated['custom_attributes'] ?? [], $request->file('custom_attribute_files') ?? []);
        $this->validateCategoryAttributes((int) ($validated['category_id'] ?? 0), $validated['custom_attributes'] ?? []);

        $updates = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'details' => $validated['details'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'sku' => $validated['sku'] ?? $product->sku,
            'price' => $validated['price'],
            'discount_price' => $validated['discount_price'] ?? null,
            'cost_price' => $validated['cost_price'] ?? null,
            'track_inventory' => (bool) ($validated['track_inventory'] ?? $product->track_inventory),
            'stock_quantity' => (int) ($validated['stock_quantity'] ?? $product->stock_quantity),
            'low_stock_threshold' => (int) ($validated['low_stock_threshold'] ?? $product->low_stock_threshold),
        ];

        if ($request->file('image') && Schema::hasColumn('products', 'image')) {
            $updates['image'] = Storage::disk('public')->putFile('products/trader', $request->file('image'));
        }

        if ($request->file('images') && Schema::hasColumn('products', 'images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $img) {
                if ($img) {
                    $imagePaths[] = Storage::disk('public')->putFile('products/trader', $img);
                }
            }
            $updates['images'] = $imagePaths;
        }

        $requiresReview = in_array((string) ($product->status ?? ''), ['approved', 'active'], true)
            && (
                $product->getOriginal('name') !== $updates['name']
                || $product->getOriginal('description') !== $updates['description']
                || $product->getOriginal('details') !== $updates['details']
                || (string) $product->getOriginal('category_id') !== (string) $updates['category_id']
                || (string) $product->getOriginal('price') !== (string) $updates['price']
                || (string) $product->getOriginal('discount_price') !== (string) $updates['discount_price']
                || (string) $product->getOriginal('cost_price') !== (string) $updates['cost_price']
                || array_key_exists('image', $updates)
                || array_key_exists('images', $updates)
            );

        if ($requiresReview) {
            $updates['status'] = 'pending';
            if (Schema::hasColumn('products', 'is_active')) {
                $updates['is_active'] = false;
            }
            if (Schema::hasColumn('products', 'reviewed_by')) {
                $updates['reviewed_by'] = null;
            }
            if (Schema::hasColumn('products', 'reviewed_at')) {
                $updates['reviewed_at'] = null;
            }
            if (Schema::hasColumn('products', 'rejection_reason')) {
                $updates['rejection_reason'] = null;
            }
        }

        $product->update($updates);
        $this->syncCustomAttributes($product, $validated['custom_attributes'] ?? [], $request);
        if ($requiresReview) {
            $this->notifyCustomerSupportProductReview($product);
        }

        return redirect()->route('trader.products.index')->with('success', $requiresReview ? 'Changes submitted. Waiting for support approval.' : 'Product updated.');
    }

    private function notifyCustomerSupportProductReview(Product $product): void
    {
        if (! Schema::hasTable('dashboard_notifications') || ! Schema::hasTable('employees')) {
            return;
        }

        $csEmployees = Employee::query()
            ->when(Schema::hasColumn('employees', 'is_cs'), fn ($q) => $q->where('is_cs', true))
            ->when(Schema::hasColumn('employees', 'status'), fn ($q) => $q->where('status', 'active'))
            ->get(['id']);

        if ($csEmployees->isEmpty()) {
            return;
        }

        $actionUrl = null;
        try {
            $actionUrl = route('dashboard.cs.trader-products');
        } catch (\Throwable $e) {
            $actionUrl = '/dashboard/cs/trader-products';
        }

        foreach ($csEmployees as $emp) {
            DashboardNotification::create([
                'dashboard_type' => 'cs',
                'user_type' => Employee::class,
                'user_id' => $emp->id,
                'type' => 'trader_product_review',
                'title' => 'New trader product pending review',
                'message' => 'A trader submitted "'.$product->name.'" for approval.',
                'action_url' => $actionUrl,
                'icon' => 'fa-box',
                'color' => 'amber',
                'is_read' => false,
            ]);
        }
    }

    private function validateCustomAttributes(array $rows, array $files): void
    {
        $errors = [];
        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }
            $name = trim((string) ($row['name'] ?? ''));
            if ($name === '') {
                continue;
            }
            $type = strtolower((string) ($row['type'] ?? 'text'));
            $type = match ($type) {
                'dropdown' => 'select',
                'radio' => 'radio_group',
                'multiselect' => 'checkbox_group',
                default => $type,
            };
            $required = filter_var($row['required'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $rules = is_array($row['rules'] ?? null) ? $row['rules'] : [];

            $value = $row['value'] ?? null;
            $hasValue = ! is_null($value)
                && ! (is_string($value) && trim($value) === '')
                && ! (is_array($value) && count(array_filter($value, fn ($v) => trim((string) $v) !== '')) === 0);

            if ($type === 'file') {
                $uid = (string) ($row['uid'] ?? '');
                $existing = trim((string) ($row['value_existing'] ?? ''));
                $file = $uid !== '' ? ($files[$uid] ?? null) : null;
                if ($required && ! $file && $existing === '') {
                    $errors['custom_attributes'] = 'يرجى تعبئة الحقول الإضافية المطلوبة';
                    break;
                }
                if ($file) {
                    $maxKb = isset($rules['max_file_size_kb']) ? (int) $rules['max_file_size_kb'] : null;
                    if ($maxKb && (int) ceil($file->getSize() / 1024) > $maxKb) {
                        $errors['custom_attributes'] = 'يرجى الالتزام بقيود حجم الملف';
                        break;
                    }
                    $allowed = trim((string) ($rules['allowed_file_types'] ?? ''));
                    if ($allowed !== '') {
                        $allowedSet = array_values(array_filter(array_map(fn ($x) => ltrim(strtolower(trim((string) $x)), '.'), preg_split('/,/', $allowed) ?: [])));
                        $ext = strtolower($file->getClientOriginalExtension() ?: '');
                        if ($ext !== '' && $allowedSet && ! in_array($ext, $allowedSet, true)) {
                            $errors['custom_attributes'] = 'نوع ملف غير مسموح';
                            break;
                        }
                    }
                }
                continue;
            }

            $optionsRaw = (string) ($row['options'] ?? '');
            $options = array_values(array_filter(array_map(fn ($p) => trim((string) $p), preg_split('/\r?\n|,/', $optionsRaw) ?: []), fn ($v) => $v !== ''));

            if ($required && ! $hasValue) {
                $errors['custom_attributes'] = 'يرجى تعبئة الحقول الإضافية المطلوبة';
                break;
            }
            if (! $hasValue) {
                continue;
            }

            if (in_array($type, ['select', 'radio_group'], true)) {
                $v = is_array($value) ? '' : trim((string) $value);
                if ($v !== '' && $options && ! in_array($v, $options, true)) {
                    $errors['custom_attributes'] = 'قيمة غير صالحة ضمن الخيارات';
                    break;
                }
            }

            if ($type === 'checkbox_group') {
                $arr = is_array($value) ? $value : (is_string($value) ? preg_split('/\r?\n|,/', $value) : []);
                $arr = array_values(array_filter(array_map(fn ($v) => trim((string) $v), $arr), fn ($v) => $v !== ''));
                if ($required && count($arr) === 0) {
                    $errors['custom_attributes'] = 'يرجى تعبئة الحقول الإضافية المطلوبة';
                    break;
                }
                if ($options) {
                    foreach ($arr as $x) {
                        if (! in_array($x, $options, true)) {
                            $errors['custom_attributes'] = 'قيمة غير صالحة ضمن الخيارات';
                            break 2;
                        }
                    }
                }
            }

            if (in_array($type, ['text', 'textarea'], true) && is_string($value)) {
                $minLen = isset($rules['min_length']) ? (int) $rules['min_length'] : null;
                $maxLen = isset($rules['max_length']) ? (int) $rules['max_length'] : null;
                $len = mb_strlen($value);
                if ($minLen !== null && $minLen > 0 && $len < $minLen) {
                    $errors['custom_attributes'] = 'يرجى الالتزام بقيود طول النص';
                    break;
                }
                if ($maxLen !== null && $maxLen > 0 && $len > $maxLen) {
                    $errors['custom_attributes'] = 'يرجى الالتزام بقيود طول النص';
                    break;
                }
            }

            if ($type === 'number') {
                $n = is_array($value) ? null : (is_numeric($value) ? (float) $value : null);
                if ($n === null) {
                    $errors['custom_attributes'] = 'قيمة رقمية غير صحيحة';
                    break;
                }
                if (isset($rules['min']) && is_numeric($rules['min']) && $n < (float) $rules['min']) {
                    $errors['custom_attributes'] = 'قيمة أقل من الحد الأدنى';
                    break;
                }
                if (isset($rules['max']) && is_numeric($rules['max']) && $n > (float) $rules['max']) {
                    $errors['custom_attributes'] = 'قيمة أعلى من الحد الأقصى';
                    break;
                }
            }
        }

        if ($errors) {
            throw \Illuminate\Validation\ValidationException::withMessages($errors);
        }
    }

    private function syncCustomAttributes(Product $product, array $rows, Request $request): void
    {
        if (! Schema::hasTable('product_attributes')) {
            return;
        }
        if (
            ! Schema::hasColumn('product_attributes', 'is_custom')
            || ! Schema::hasColumn('product_attributes', 'type')
            || ! Schema::hasColumn('product_attributes', 'options')
        ) {
            return;
        }

        $files = $request->file('custom_attribute_files') ?? [];
        if (! is_array($files)) {
            $files = [];
        }

        $items = collect($rows)->map(fn ($r) => is_array($r) ? $r : [])->filter(function ($r) {
            return trim((string) ($r['name'] ?? '')) !== '';
        })->values()->take(200);

        $idsToKeep = [];
        foreach ($items as $idx => $row) {
            $name = trim((string) ($row['name'] ?? ''));
            $type = strtolower((string) ($row['type'] ?? 'text'));
            $type = match ($type) {
                'dropdown' => 'select',
                'radio' => 'radio_group',
                'multiselect' => 'checkbox_group',
                default => $type,
            };
            $key = trim((string) ($row['key'] ?? ''));
            if ($key === '') {
                $key = Str::slug($name);
            }
            $key = substr($key, 0, 80);
            $required = filter_var($row['required'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $rules = is_array($row['rules'] ?? null) ? $row['rules'] : [];
            $optionsRaw = (string) ($row['options'] ?? '');
            $options = array_values(array_filter(array_map(fn ($p) => trim((string) $p), preg_split('/\r?\n|,/', $optionsRaw) ?: []), fn ($v) => $v !== ''));
            $valueRaw = $row['value'] ?? null;

            $value = null;
            $valueText = null;
            $valueNumber = null;
            $valueDate = null;
            $valueJson = null;

            if ($type === 'checkbox') {
                $b = filter_var($valueRaw, FILTER_VALIDATE_BOOLEAN);
                $value = $b ? '1' : '0';
                $valueText = $value;
                $valueJson = ['checked' => $b];
            } elseif ($type === 'checkbox_group') {
                $arr = is_array($valueRaw) ? $valueRaw : (is_string($valueRaw) ? preg_split('/\r?\n|,/', $valueRaw) : []);
                $arr = array_values(array_filter(array_map(fn ($v) => trim((string) $v), $arr), fn ($v) => $v !== ''));
                $arr = array_values(array_unique($arr));
                $valueJson = $arr;
                $valueText = implode(', ', $arr);
                $value = json_encode($arr, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            } elseif ($type === 'file') {
                $uid = (string) ($row['uid'] ?? '');
                $file = $uid !== '' ? ($files[$uid] ?? null) : null;
                $existing = trim((string) ($row['value_existing'] ?? ''));
                if ($file) {
                    $path = Storage::disk('public')->putFile('products/trader/attributes', $file);
                    $valueText = $path;
                    $value = $path;
                    $valueJson = [
                        'path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'mime' => $file->getClientMimeType(),
                        'size' => $file->getSize(),
                    ];
                } elseif ($existing !== '') {
                    $valueText = $existing;
                    $value = $existing;
                } else {
                    $valueText = null;
                    $value = null;
                }
            } else {
                $value = is_array($valueRaw) ? '' : (string) ($valueRaw ?? '');
                $value = trim($value);
                if ($type === 'number' && $value !== '' && is_numeric($value)) {
                    $valueNumber = (float) $value;
                }
                if ($type === 'date' && $value !== '' && strtotime($value) !== false) {
                    $valueDate = date('Y-m-d', strtotime($value));
                    $value = $valueDate;
                }
                $valueText = $value === '' ? null : $value;
            }

            $payload = [
                'name' => $name,
                'attribute_key' => $key,
                'type' => $type,
                'value' => $value ?? '',
                'value_text' => $valueText,
                'value_number' => $valueNumber,
                'value_date' => $valueDate,
                'value_json' => $valueJson,
                'options' => in_array($type, ['select', 'radio_group', 'checkbox_group', 'color'], true) ? $options : [],
                'is_custom' => true,
                'sort_order' => $idx,
                'is_required' => $required,
                'rules' => $rules ?: null,
            ];

            $existing = null;
            if (! empty($row['id'])) {
                $existing = $product->attributes()->where('id', (int) $row['id'])->where('is_custom', true)->first();
            }

            if ($existing) {
                $existing->update($payload);
                $idsToKeep[] = (int) $existing->id;
            } else {
                $created = $product->attributes()->create($payload);
                $idsToKeep[] = (int) $created->id;
            }
        }

        $product->attributes()->where('is_custom', true)->whereNotIn('id', $idsToKeep ?: [0])->delete();
    }

    private function validateCategoryAttributes(int $categoryId, array $rows): void
    {
        if ($categoryId <= 0) {
            return;
        }
        $defs = CategoryAttributeDefinition::forCategory($categoryId);
        if ($defs->isEmpty()) {
            return;
        }

        $byName = collect($rows)->map(fn ($r) => is_array($r) ? $r : [])->filter(function ($r) {
            return trim((string) ($r['name'] ?? '')) !== '';
        })->keyBy(fn ($r) => trim((string) ($r['name'] ?? '')));

        $errors = [];
        foreach ($defs as $def) {
            $defName = (string) $def->name;
            $defType = (string) $def->type;
            $required = (bool) ($def->is_required ?? false);
            $opt = is_array($def->options) ? $def->options : [];

            $row = $byName->get($defName);
            $val = $row['value'] ?? null;
            $hasValue = ! is_null($val) && ! (is_string($val) && trim($val) === '') && ! (is_array($val) && count(array_filter($val, fn ($v) => trim((string) $v) !== '')) === 0);
            if ($required && ! $hasValue) {
                $errors['custom_attributes'] = 'يرجى تعبئة الحقول الإضافية المطلوبة';
                break;
            }

            if (! $hasValue) {
                continue;
            }

            if (in_array($defType, ['select', 'radio'], true)) {
                $v = is_array($val) ? null : (string) $val;
                if ($opt && $v !== null && ! in_array($v, $opt, true)) {
                    $errors['custom_attributes'] = 'قيمة غير صالحة ضمن الحقول الإضافية';
                    break;
                }
            }
            if ($defType === 'multiselect') {
                $arr = is_array($val) ? $val : (is_string($val) ? preg_split('/,/', $val) : []);
                $arr = array_values(array_filter(array_map(fn ($v) => trim((string) $v), $arr), fn ($v) => $v !== ''));
                if ($opt) {
                    foreach ($arr as $v) {
                        if (! in_array($v, $opt, true)) {
                            $errors['custom_attributes'] = 'قيمة غير صالحة ضمن الحقول الإضافية';
                            break 2;
                        }
                    }
                }
            }
        }

        if (! empty($errors)) {
            throw \Illuminate\Validation\ValidationException::withMessages($errors);
        }
    }

    public function destroy(Product $product)
    {
        $trader = $this->currentTrader();
        abort_unless((int) $product->trader_id === (int) $trader->id, 404);
        abort_unless(in_array((string) $product->status, ['pending', 'rejected'], true), 403);

        $product->delete();

        return back()->with('success', 'Product deleted.');
    }

    public function inventory(Request $request)
    {
        $trader = $this->currentTrader();

        $products = Product::query()
            ->where('trader_id', $trader->id)
            ->whereIn('status', ['approved', 'active'])
            ->when($request->search, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(30)
            ->withQueryString();

        return view('trader.inventory', compact('trader', 'products'));
    }

    public function updateInventory(Request $request, Product $product)
    {
        $trader = $this->currentTrader();
        abort_unless((int) $product->trader_id === (int) $trader->id, 404);
        abort_unless(in_array((string) $product->status, ['active', 'approved'], true), 403);

        $validated = $request->validate([
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'track_inventory' => 'nullable|boolean',
        ]);

        $product->update([
            'stock_quantity' => (int) $validated['stock_quantity'],
            'low_stock_threshold' => array_key_exists('low_stock_threshold', $validated)
                ? (int) ($validated['low_stock_threshold'] ?? 0)
                : (int) ($product->low_stock_threshold ?? 0),
            'track_inventory' => array_key_exists('track_inventory', $validated)
                ? (bool) $validated['track_inventory']
                : (bool) $product->track_inventory,
        ]);

        return back()->with('success', 'Inventory updated.');
    }

    public function sales(Request $request)
    {
        $trader = $this->currentTrader();

        $from = $request->date('from');
        $to = $request->date('to');
        $to = $to ? $to->endOfDay() : null;

        $orderItemQuery = OrderItem::query()
            ->whereHas('product', function ($q) use ($trader) {
                $q->where('trader_id', $trader->id);
            })
            ->whereHas('order', function ($q) {
                $q->whereIn('status', ['delivered', 'completed']);
            })
            ->when($from, fn ($q) => $q->where('created_at', '>=', $from))
            ->when($to, fn ($q) => $q->where('created_at', '<=', $to));

        $summary = [
            'revenue' => (float) (clone $orderItemQuery)->sum('total_price'),
            'units_sold' => (int) (clone $orderItemQuery)->sum('quantity'),
            'orders' => (int) (clone $orderItemQuery)->distinct()->count('order_id'),
        ];

        $topProducts = (clone $orderItemQuery)
            ->select('product_id', DB::raw('SUM(quantity) as units_sold'), DB::raw('SUM(total_price) as revenue'))
            ->groupBy('product_id')
            ->orderByDesc('revenue')
            ->limit(50)
            ->get()
            ->map(function ($row) {
                $product = Product::find($row->product_id);

                return [
                    'id' => (int) $row->product_id,
                    'name' => $product?->name,
                    'sku' => $product?->sku,
                    'units_sold' => (int) $row->units_sold,
                    'revenue' => (float) $row->revenue,
                ];
            });

        $orderIds = (clone $orderItemQuery)->distinct()->pluck('order_id');
        $recentOrders = Order::query()
            ->whereIn('id', $orderIds)
            ->with(['user'])
            ->latest()
            ->limit(30)
            ->get();

        return view('trader.sales', compact('trader', 'summary', 'topProducts', 'recentOrders', 'from', 'to'));
    }
}
