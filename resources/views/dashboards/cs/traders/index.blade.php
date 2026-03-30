@extends('dashboards.super-admin.traders.index', [
    'title' => 'التجار',
    'subtitle' => 'عرض ومراجعة ملفات التجار',
    'heading' => 'التجار',
    'searchPlaceholder' => 'ابحث بالاسم أو البريد',
    'emptyState' => 'لا يوجد تجار',
    'indexRoute' => 'dashboard.cs.traders.index',
    'showRoute' => 'dashboard.cs.traders.show',
])