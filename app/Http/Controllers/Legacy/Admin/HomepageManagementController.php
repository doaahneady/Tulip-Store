<?php

namespace App\Http\Controllers\Legacy\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomepageManagementController extends Controller
{
    /**
     * Show homepage management page
     */
    public function index()
    {
        return view('admin.homepage');
    }

    /**
     * Get homepage sections configuration
     */
    public function getSections()
    {
        $sections = Cache::get('homepage_sections', $this->getDefaultSections());

        return response()->json([
            'success' => true,
            'sections' => $sections,
        ]);
    }

    public function getSlides()
    {
        $slides = Setting::get('homepage_slider_slides', null);

        $defaultSlides = [
            [
                'image' => '/images/footer.jpg',
                'title' => 'أرسل ابتسامتك أينما كنت',
                'subtitle' => 'تسوق معنا أفضل المنتجات والعروض',
            ],
            [
                'image' => '/images/logo-girl.jpg',
                'title' => 'هدايا توليب',
                'subtitle' => 'لحظات استثنائية تستحق هدايا مميزة',
            ],
            [
                'image' => '/images/white_orange_logo.png',
                'title' => 'وصل حديثاً',
                'subtitle' => 'اكتشف أحدث المنتجات في متجرنا',
            ],
        ];

        if (! is_array($slides)) {
            $slides = [];
        }

        $slides = array_values(array_filter($slides, fn ($s) => is_array($s)));

        if (count($slides) < 3) {
            for ($i = count($slides); $i < 3; $i++) {
                $slides[] = $defaultSlides[$i];
            }
            Setting::set('homepage_slider_slides', $slides, 'json', 'Home page slider slides');
        }

        if (empty($slides)) {
            $slides = $defaultSlides;
            Setting::set('homepage_slider_slides', $slides, 'json', 'Home page slider slides');
        }

        if (empty($slides)) {
            $slides = [
                [
                    'image' => '/images/footer.jpg',
                    'title' => 'أرسل ابتسامتك أينما كنت',
                    'subtitle' => 'تسوق معنا أفضل المنتجات والعروض',
                ],
                [
                    'image' => '/images/logo-girl.jpg',
                    'title' => 'هدايا توليب',
                    'subtitle' => 'لحظات استثنائية تستحق هدايا مميزة',
                ],
                [
                    'image' => '/images/white_orange_logo.png',
                    'title' => 'وصل حديثاً',
                    'subtitle' => 'اكتشف أحدث المنتجات في متجرنا',
                ],
            ];
            Setting::set('homepage_slider_slides', $slides, 'json', 'Home page slider slides');
        }

        return response()->json([
            'success' => true,
            'slides' => $slides,
        ]);
    }

    /**
     * Update homepage sections configuration
     */
    public function updateSections(Request $request)
    {
        $request->validate([
            'sections' => 'required|array',
            'sections.*.id' => 'required|string',
            'sections.*.visible' => 'required|boolean',
            'sections.*.order' => 'required|integer',
        ]);

        $sections = $request->input('sections');

        // Store in cache (or database for persistence)
        Cache::put('homepage_sections', $sections, now()->addYear());

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث إعدادات الصفحة الرئيسية',
            'sections' => $sections,
        ]);
    }

    /**
     * Toggle section visibility
     */
    public function toggleSection(Request $request, $sectionId)
    {
        $sections = Cache::get('homepage_sections', $this->getDefaultSections());

        foreach ($sections as &$section) {
            if ($section['id'] === $sectionId) {
                $section['visible'] = ! $section['visible'];
                break;
            }
        }

        Cache::put('homepage_sections', $sections, now()->addYear());

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة القسم',
            'sections' => $sections,
        ]);
    }

    /**
     * Update lightning deals settings
     */
    public function updateLightningDeals(Request $request)
    {
        $request->validate([
            'duration_hours' => 'nullable|integer|min:1|max:72',
            'discount_min' => 'nullable|integer|min:5|max:90',
            'discount_max' => 'nullable|integer|min:10|max:95',
            'product_ids' => 'nullable|array',
            'enabled' => 'nullable|boolean',
        ]);

        $settings = [
            'duration_hours' => $request->input('duration_hours', 24),
            'discount_min' => $request->input('discount_min', 20),
            'discount_max' => $request->input('discount_max', 50),
            'product_ids' => $request->input('product_ids', []),
            'enabled' => $request->input('enabled', true),
            'updated_at' => now()->toISOString(),
        ];

        Cache::put('lightning_deals_settings', $settings, now()->addYear());

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث إعدادات عروض البرق',
            'settings' => $settings,
        ]);
    }

    /**
     * Get lightning deals settings
     */
    public function getLightningDeals()
    {
        $settings = Cache::get('lightning_deals_settings', [
            'duration_hours' => 24,
            'discount_min' => 20,
            'discount_max' => 50,
            'product_ids' => [],
            'enabled' => true,
        ]);

        return response()->json([
            'success' => true,
            'settings' => $settings,
        ]);
    }

    /**
     * Reorder sections
     */
    public function reorderSections(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|string',
        ]);

        $newOrder = $request->input('order');
        $sections = Cache::get('homepage_sections', $this->getDefaultSections());

        // Reorder sections based on new order
        $reordered = [];
        foreach ($newOrder as $index => $sectionId) {
            foreach ($sections as $section) {
                if ($section['id'] === $sectionId) {
                    $section['order'] = $index;
                    $reordered[] = $section;
                    break;
                }
            }
        }

        Cache::put('homepage_sections', $reordered, now()->addYear());

        return response()->json([
            'success' => true,
            'message' => 'تم إعادة ترتيب الأقسام',
            'sections' => $reordered,
        ]);
    }

    /**
     * Get default sections configuration
     */
    private function getDefaultSections()
    {
        return [
            [
                'id' => 'hero',
                'name' => 'البانر الرئيسي',
                'name_en' => 'Hero Banner',
                'visible' => true,
                'order' => 0,
                'editable' => true,
            ],
            [
                'id' => 'lightning_deals',
                'name' => 'عروض البرق',
                'name_en' => 'Lightning Deals',
                'visible' => true,
                'order' => 1,
                'editable' => true,
            ],
            [
                'id' => 'categories',
                'name' => 'الفئات',
                'name_en' => 'Categories',
                'visible' => true,
                'order' => 2,
                'editable' => true,
            ],
            [
                'id' => 'products',
                'name' => 'المنتجات',
                'name_en' => 'Products',
                'visible' => true,
                'order' => 3,
                'editable' => true,
            ],
        ];
    }

    /**
     * Get featured products for a specific type
     */
    public function getFeaturedProducts(Request $request, $type)
    {
        $validTypes = ['featured', 'flash', 'new'];
        if (! in_array($type, $validTypes)) {
            return response()->json(['success' => false, 'message' => 'Invalid type'], 400);
        }

        $key = "homepage_{$type}_products";
        $productIds = Cache::get($key, []);

        return response()->json([
            'success' => true,
            'type' => $type,
            'product_ids' => $productIds,
        ]);
    }

    /**
     * Save featured products for a specific type
     */
    public function saveFeaturedProducts(Request $request, $type)
    {
        $validTypes = ['featured', 'flash', 'new'];
        if (! in_array($type, $validTypes)) {
            return response()->json(['success' => false, 'message' => 'Invalid type'], 400);
        }

        $productIds = $request->input('product_ids', []);

        // Convert to integers
        $productIds = array_map('intval', $productIds);

        $key = "homepage_{$type}_products";
        Cache::put($key, $productIds, now()->addYear());

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ المنتجات بنجاح',
            'type' => $type,
            'product_ids' => $productIds,
        ]);
    }

    /**
     * Get all featured products counts
     */
    public function getFeaturedCounts()
    {
        return response()->json([
            'success' => true,
            'counts' => [
                'featured' => count(Cache::get('homepage_featured_products', [])),
                'flash' => count(Cache::get('homepage_flash_products', [])),
                'new' => count(Cache::get('homepage_new_products', [])),
            ],
        ]);
    }

    /**
     * Get all packages
     */
    public function getPackages()
    {
        $packages = Cache::get('homepage_packages', $this->getDefaultPackages());

        return response()->json([
            'success' => true,
            'packages' => $packages,
        ]);
    }

    /**
     * Save packages
     */
    public function savePackages(Request $request)
    {
        $request->validate([
            'packages' => 'required|array',
        ]);

        Cache::put('homepage_packages', $request->input('packages'), now()->addYear());

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ الباقات بنجاح',
        ]);
    }

    /**
     * Add a new package
     */
    public function addPackage(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'product_ids' => 'nullable|array',
        ]);

        $packages = Cache::get('homepage_packages', $this->getDefaultPackages());

        $newPackage = [
            'id' => 'pkg_'.time(),
            'name' => $request->input('name'),
            'product_ids' => $request->input('product_ids', []),
            'visible' => true,
            'order' => count($packages),
        ];

        $packages[] = $newPackage;
        Cache::put('homepage_packages', $packages, now()->addYear());

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة الباقة بنجاح',
            'package' => $newPackage,
        ]);
    }

    /**
     * Update a package
     */
    public function updatePackage(Request $request, $packageId)
    {
        $packages = Cache::get('homepage_packages', $this->getDefaultPackages());

        foreach ($packages as &$package) {
            if ($package['id'] === $packageId) {
                if ($request->has('name')) {
                    $package['name'] = $request->input('name');
                }
                if ($request->has('product_ids')) {
                    // Convert to integers
                    $productIds = $request->input('product_ids', []);
                    $package['product_ids'] = array_map('intval', $productIds);
                }
                if ($request->has('visible')) {
                    $package['visible'] = $request->input('visible');
                }
                break;
            }
        }

        Cache::put('homepage_packages', $packages, now()->addYear());

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الباقة بنجاح',
        ]);
    }

    /**
     * Delete a package
     */
    public function deletePackage($packageId)
    {
        $packages = Cache::get('homepage_packages', $this->getDefaultPackages());
        $packages = array_filter($packages, fn ($p) => $p['id'] !== $packageId);
        $packages = array_values($packages);

        Cache::put('homepage_packages', $packages, now()->addYear());

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الباقة بنجاح',
        ]);
    }

    /**
     * Show package products page
     */
    public function showPackagePage($packageId)
    {
        $packages = Cache::get('homepage_packages', $this->getDefaultPackages());
        $package = collect($packages)->firstWhere('id', $packageId);

        if (! $package) {
            return redirect('/store');
        }

        return view('package-products', [
            'package' => $package,
            'packageId' => $packageId,
        ]);
    }

    /**
     * Get default packages
     */
    private function getDefaultPackages()
    {
        return [
            [
                'id' => 'pkg_luxury_gifts',
                'name' => 'هدايا فاخرة',
                'product_ids' => [],
                'visible' => true,
                'order' => 0,
            ],
            [
                'id' => 'pkg_jewelry',
                'name' => 'المجوهرات',
                'product_ids' => [],
                'visible' => true,
                'order' => 1,
            ],
            [
                'id' => 'pkg_special_offers',
                'name' => 'عروض خاصة',
                'product_ids' => [],
                'visible' => true,
                'order' => 2,
            ],
            [
                'id' => 'pkg_new_arrivals',
                'name' => 'وصل حديثاً',
                'product_ids' => [],
                'visible' => true,
                'order' => 3,
            ],
        ];
    }
}
