<?php

echo "Testing Dashboard Views...\n\n";

$views = [
    'Super Admin' => 'resources/views/dashboards/super-admin/index.blade.php',
    'Finance' => 'resources/views/dashboards/finance/index.blade.php', 
    'HR' => 'resources/views/dashboards/hr/index.blade.php',
    'IT' => 'resources/views/dashboards/it/index.blade.php',
    'Supervisor' => 'resources/views/dashboards/supervisor/index.blade.php',
    'Vendor' => 'resources/views/dashboards/vendor/index.blade.php',
];

foreach ($views as $name => $path) {
    if (file_exists($path)) {
        echo "✓ {$name} Dashboard View EXISTS\n";
    } else {
        echo "✗ {$name} Dashboard View MISSING - Creating...\n";
        
        // Create the directory if it doesn't exist
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
            echo "  Created directory: {$dir}\n";
        }
        
        // Create a basic view file
        $viewContent = "@extends('dashboards.layouts.app', ['title' => '{$name} Dashboard'])

@section('content')
<div class=\"container-fluid\">
    <h1>{$name} Dashboard</h1>
    <p>Welcome to the {$name} dashboard. This is a placeholder view.</p>
    
    <div class=\"row\">
        <div class=\"col-md-12\">
            <div class=\"card\">
                <div class=\"card-header\">
                    <h3 class=\"card-title\">{$name} Overview</h3>
                </div>
                <div class=\"card-body\">
                    <p>Dashboard content will be implemented here.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection";
        
        file_put_contents($path, $viewContent);
        echo "  Created view file: {$path}\n";
    }
}

echo "\nAll dashboard views are now available!\n";
echo "\nYou can now access:\n";
echo "- Super Admin: /simple-admin-test\n";
echo "- View Status: /test-view-only\n";