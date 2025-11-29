<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ConfiguratorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| GLB Model File Route (for 3D Viewer)
|--------------------------------------------------------------------------
*/

Route::get('/storage/glb/{path}', function ($path) {
  // Try multiple paths
  $paths = [
    storage_path('app/public/' . $path),
    storage_path('app/' . $path),
    public_path('storage/' . $path),
  ];

  $fullPath = null;
  foreach ($paths as $p) {
    if (file_exists($p)) {
      $fullPath = $p;
      break;
    }
  }

  if (!$fullPath) {
    abort(404, "GLB file not found: {$path}");
  }

  return response()->file($fullPath, [
    'Content-Type' => 'model/gltf-binary',
    'Access-Control-Allow-Origin' => '*',
  ]);
})->where('path', '.*')->name('glb.serve');

/*
|--------------------------------------------------------------------------
| Image Proxy Route (for CORS bypass)
|--------------------------------------------------------------------------
*/

Route::get('/api/image-proxy', function () {
  $url = request()->query('url');

  if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
    abort(400, 'Invalid URL');
  }

  try {
    $client = new \GuzzleHttp\Client([
      'timeout' => 10,
      'verify' => false,
    ]);

    $response = $client->get($url);
    $contentType = $response->getHeaderLine('Content-Type') ?: 'image/jpeg';
    $body = $response->getBody()->getContents();

    return response($body, 200, [
      'Content-Type' => $contentType,
      'Access-Control-Allow-Origin' => '*',
      'Cache-Control' => 'public, max-age=86400',
    ]);
  } catch (\Exception $e) {
    abort(500, 'Failed to fetch image: ' . $e->getMessage());
  }
})->name('image.proxy');

/*
|--------------------------------------------------------------------------
| SEO Routes
|--------------------------------------------------------------------------
*/

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap-pages.xml', [SitemapController::class, 'pages'])->name('sitemap.pages');
Route::get('/sitemap-fabrics.xml', [SitemapController::class, 'fabrics'])->name('sitemap.fabrics');
Route::get('/sitemap-models.xml', [SitemapController::class, 'models'])->name('sitemap.models');

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Static Pages
Route::get('/ve-chung-toi', [PageController::class, 'about'])->name('about');
Route::get('/lien-he', [PageController::class, 'contact'])->name('contact');
Route::post('/lien-he', [PageController::class, 'submitContact'])->name('contact.submit');
Route::get('/chinh-sach-bao-hanh', [PageController::class, 'warranty'])->name('warranty');
Route::get('/huong-dan-chon-size', [PageController::class, 'sizeGuide'])->name('size-guide');
Route::get('/dieu-khoan-dich-vu', [PageController::class, 'terms'])->name('terms');
Route::get('/chinh-sach-bao-mat', [PageController::class, 'privacy'])->name('privacy');

// Fabric Collection
Route::get('/bo-suu-tap-vai', [PageController::class, 'fabrics'])->name('fabrics.index');
Route::get('/bo-suu-tap-vai/{category}', [PageController::class, 'fabricCategory'])->name('fabrics.category');

/*
|--------------------------------------------------------------------------
| Configurator Routes
|--------------------------------------------------------------------------
*/

Route::prefix('thiet-ke-vest')->name('configurator.')->group(function () {
  Route::get('/', [ConfiguratorController::class, 'index'])->name('index');
  Route::get('/share/{code}', [ConfiguratorController::class, 'share'])->name('share');
});

/*
|--------------------------------------------------------------------------
| Cart Routes
|--------------------------------------------------------------------------
*/

Route::prefix('gio-hang')->name('cart.')->group(function () {
  Route::get('/', [CartController::class, 'index'])->name('index');
});

/*
|--------------------------------------------------------------------------
| Checkout Routes
|--------------------------------------------------------------------------
*/

Route::prefix('thanh-toan')->name('checkout.')->group(function () {
  Route::get('/', [CheckoutController::class, 'index'])->name('index');
  Route::post('/', [CheckoutController::class, 'process'])->name('process');
  Route::get('/thanh-cong/{orderNumber}', [CheckoutController::class, 'success'])->name('success');
  Route::get('/callback/{gateway}', [CheckoutController::class, 'paymentCallback'])->name('callback');
});

/*
|--------------------------------------------------------------------------
| API Routes (for AJAX calls)
|--------------------------------------------------------------------------
*/

Route::prefix('api')->group(function () {
  // Configurator (Layered Images)
  Route::get('/configurator/layers', [ConfiguratorController::class, 'getLayers']);
  Route::post('/configurator/save', [ConfiguratorController::class, 'save']);

  // 3D Configurator (Modular Models)
  Route::prefix('3d-configurator')->group(function () {
    Route::get('/product-types', [\App\Http\Controllers\Api\ThreeDConfiguratorController::class, 'productTypes']);
    Route::get('/{productType}', [\App\Http\Controllers\Api\ThreeDConfiguratorController::class, 'getConfiguration']);
    Route::post('/{productType}/model-files', [\App\Http\Controllers\Api\ThreeDConfiguratorController::class, 'getModelFiles']);
    Route::post('/{productType}/update-option', [\App\Http\Controllers\Api\ThreeDConfiguratorController::class, 'updateOption']);
    Route::post('/{productType}/calculate-price', [\App\Http\Controllers\Api\ThreeDConfiguratorController::class, 'calculatePrice']);
    Route::post('/{productType}/save', [\App\Http\Controllers\Api\ThreeDConfiguratorController::class, 'saveConfiguration']);
    Route::get('/{productType}/fabrics', [\App\Http\Controllers\Api\ThreeDConfiguratorController::class, 'getFabrics']);
  });

  // Cart
  Route::get('/cart/count', [CartController::class, 'count']);
  Route::post('/cart/add', [CartController::class, 'add']);
  Route::patch('/cart/item/{item}', [CartController::class, 'update']);
  Route::delete('/cart/item/{item}', [CartController::class, 'remove']);
  Route::post('/cart/discount', [CartController::class, 'applyDiscount']);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
  // Orders
  Route::prefix('don-hang')->name('orders.')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('index');
    Route::get('/{order:order_number}', [OrderController::class, 'show'])->name('show');
  });

  // Profile
  Route::prefix('tai-khoan')->name('profile.')->group(function () {
    Route::get('/', [OrderController::class, 'profile'])->name('edit');
    Route::patch('/', [OrderController::class, 'updateProfile'])->name('update');
  });
});

/*
|--------------------------------------------------------------------------
| Authentication Routes (using Laravel Breeze or similar)
|--------------------------------------------------------------------------
*/

// If not using Laravel Breeze, add basic auth routes
Route::get('/dang-nhap', function () {
  return view('auth.login');
})->name('login');

Route::get('/dang-ky', function () {
  return view('auth.register');
})->name('register');

Route::post('/dang-xuat', function () {
  auth()->logout();
  request()->session()->invalidate();
  request()->session()->regenerateToken();
  return redirect('/');
})->name('logout')->middleware('auth');
