<?php
// Set environments
$_ENV['APP_ENV'] = 'production';
$_ENV['APP_DEBUG'] = 'false';
$_SERVER['APP_ENV'] = 'production';
$_SERVER['APP_DEBUG'] = 'false';

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

// Bootstrap Laravel console kernel to register all paths and views
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Render the form-builder.index Blade view
    $html = view('form-builder.index')->render();

    // Replace absolute URLs with relative paths for static build compatibility
    $html = str_replace('http://localhost:5173/', '', $html);
    $html = str_replace('http://localhost:5173', '', $html);
    $html = str_replace('http://127.0.0.1:8000/', '', $html);
    $html = str_replace('http://127.0.0.1:8000', '', $html);

    // Save the output HTML
    file_put_contents(__DIR__.'/public/index.html', $html);
    echo "Success: Blade view successfully compiled to public/index.html with relative paths\n";
} catch (\Exception $e) {
    echo "Error compiling blade view: " . $e->getMessage() . "\n";
    exit(1);
}
