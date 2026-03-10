@extends('dashboards.layouts.app')

@section('content')
@php $title = 'Style Guide'; $subtitle = 'Dashboard Next UI: tokens, components, and usage'; @endphp

<div class="mb-6 flex flex-wrap items-center gap-3">
    <a href="{{ route('dashboard.admin.prototypes.wireframes') }}" class="btn btn-secondary">
        Wireframes
    </a>
    <a href="{{ route('dashboard.admin.prototypes.mockups') }}" class="btn btn-primary">
        Mockups
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Design Changes</div>
                <div class="card-subtitle">New design system for all dashboards</div>
            </div>
        </div>
        <div class="card-body">
            <div class="space-y-3 text-sm">
                <div>
                    Dashboard Next UI replaces the previous dashboard design files with a single, scoped design layer.
                </div>
                <ul class="list-disc pl-5 space-y-2">
                    <li>New dark, high-contrast palette with subtle gradients and glass surfaces.</li>
                    <li>Typography tuned for readability with consistent weights and spacing.</li>
                    <li>Unified components (nav, buttons, cards, tables, alerts, badges, collapsibles).</li>
                    <li>Motion uses small lifts and fades, disabled when reduced motion is preferred.</li>
                    <li>Layout remains identical for action buttons and navigation; only styling changed.</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Color Tokens</div>
                <div class="card-subtitle">CSS variables (dx-*)</div>
            </div>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-2 gap-4">
                <div class="rounded-lg border border-gray-200 overflow-hidden">
                    <div class="h-10" style="background: var(--dx-primary);"></div>
                    <div class="p-3 text-sm">
                        <div class="font-semibold">Primary</div>
                        <div class="text-gray-500">--dx-primary</div>
                    </div>
                </div>
                <div class="rounded-lg border border-gray-200 overflow-hidden">
                    <div class="h-10" style="background: var(--dx-primary-2);"></div>
                    <div class="p-3 text-sm">
                        <div class="font-semibold">Secondary</div>
                        <div class="text-gray-500">--dx-primary-2</div>
                    </div>
                </div>
                <div class="rounded-lg border border-gray-200 overflow-hidden">
                    <div class="h-10" style="background: rgba(255,255,255,0.08);"></div>
                    <div class="p-3 text-sm">
                        <div class="font-semibold">Surface</div>
                        <div class="text-gray-500">--dx-surface</div>
                    </div>
                </div>
                <div class="rounded-lg border border-gray-200 overflow-hidden">
                    <div class="h-10" style="background: var(--dx-accent);"></div>
                    <div class="p-3 text-sm">
                        <div class="font-semibold">Accent</div>
                        <div class="text-gray-500">--dx-accent</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Typography</div>
                <div class="card-subtitle">Hierarchy and readable defaults</div>
            </div>
        </div>
        <div class="card-body">
            <div class="space-y-3">
                <div>
                    <div class="text-2xl font-black">Heading / عنوان</div>
                    <div class="text-sm text-gray-600">Use strong weight and tight tracking for titles.</div>
                </div>
                <div>
                    <div class="text-base font-bold">Section / قسم</div>
                    <div class="text-sm text-gray-600">Use muted text for secondary descriptions.</div>
                </div>
                <div>
                    <div class="text-sm">Body / نص</div>
                    <div class="text-sm text-gray-600">Keep lines short and spacing consistent.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Buttons</div>
                <div class="card-subtitle">Use .btn variants for shared actions</div>
            </div>
        </div>
        <div class="card-body flex flex-wrap gap-3">
            <button type="button" class="btn btn-primary">Primary</button>
            <button type="button" class="btn btn-secondary">Secondary</button>
            <button type="button" class="btn btn-success">Success</button>
            <button type="button" class="btn btn-warning">Warning</button>
            <button type="button" class="btn btn-error">Danger</button>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Alerts</div>
                <div class="card-subtitle">Use .alert for status messages</div>
            </div>
        </div>
        <div class="card-body space-y-3">
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <div>Success message example.</div>
            </div>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <div>Error message example.</div>
            </div>
        </div>
    </div>

    <div class="card lg:col-span-2">
        <div class="card-header">
            <div>
                <div class="card-title">Tables</div>
                <div class="card-subtitle">Use .table-container + .table for consistent styling</div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Column</th>
                            <th>Value</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Example Row</td>
                            <td>123</td>
                            <td><span class="badge badge-success">OK</span></td>
                        </tr>
                        <tr>
                            <td>Example Row</td>
                            <td>456</td>
                            <td><span class="badge badge-warning">Review</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
