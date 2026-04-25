<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ClusterController as AdminClusterController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FormReportController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\GuestAuthController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\warnPDAM;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ROOT ROUTE — Force login dulu
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    
    $user = Auth::user();
    if ($user->isInstansi()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('dashboard');
})->name('home');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('guest')
    ->name('login.submit');

Route::post('/auth/guest', [GuestAuthController::class, 'login'])
    ->name('auth.guest');

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])
    ->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])
    ->name('auth.google.callback');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| WARGA DASHBOARD (untuk warga / guest)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user && $user->isInstansi()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('homepage');
    })->name('dashboard');
    
    // Rute Laporan Warga
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [App\Http\Controllers\ReportController::class, 'index'])->name('index'); // Tracking laporan
        Route::post('/', [App\Http\Controllers\ReportController::class, 'store'])->name('store'); // Submit dari form
        Route::patch('/{report}/cancel', [App\Http\Controllers\ReportController::class, 'cancel'])->name('cancel'); // Batalkan
        Route::patch('/{report}/resolve', [App\Http\Controllers\ReportController::class, 'resolve'])->name('resolve'); // Selesaikan
        Route::post('/extensions/{extension}/respond', [App\Http\Controllers\ReportController::class, 'respondExtension'])->name('extension.respond'); // Setujui/Tolak Extend
        Route::get('/{report}', [App\Http\Controllers\ReportController::class, 'show'])->name('show');
    });
});
// Route::middleware(['auth'])->group(function () {
//     Route::get('/dashboard', function () {
//         $user = auth()->user();
        
//         // Auto-redirect instansi ke admin panel
//         if ($user->isInstansi()) {
//             return redirect()->route('admin.dashboard');
//         }
        
//         // Warga / guest dashboard
//         return view('dashboard');
//     })->name('dashboard');
    
//     // Optional: route untuk lihat homepage setelah login (jika butuh)
//     Route::get('/homepage', function () {
//         return view('homepage');
//     })->name('homepage');
//     Route::get('/logout', [GoogleAuthController::class, 'logout'])->name('auth.logout');
// });

Route::get('/homepage', [HomepageController::class, 'homepage'])->name('homepage');
Route::get('/lapor', [FormReportController::class, 'create'])->name('reports.create');
Route::post('/lapor', [FormReportController::class, 'store'])->name('reports.store');

Route::get('/email', [warnPDAM::class, 'warnTechnician'])->name('email');

/*
|--------------------------------------------------------------------------
| ADMIN PANEL ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'instansi'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Reports
        Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/{report}', [AdminReportController::class, 'show'])->name('reports.show');
        Route::patch('/reports/{report}/acknowledge', [AdminReportController::class, 'acknowledge'])->name('reports.acknowledge');
        Route::patch('/reports/{report}/start', [AdminReportController::class, 'start'])->name('reports.start');
        Route::patch('/reports/{report}/complete', [AdminReportController::class, 'complete'])->name('reports.complete');
        Route::patch('/reports/{report}/dismiss', [AdminReportController::class, 'dismiss'])->name('reports.dismiss');
        Route::post('/reports/{report}/extension', [AdminReportController::class, 'requestExtension'])->name('reports.extension');
        
        // Clusters
        Route::get('/clusters', [AdminClusterController::class, 'index'])->name('clusters.index');
        Route::get('/clusters/{cluster}', [AdminClusterController::class, 'show'])->name('clusters.show');
        Route::post('/clusters/{cluster}/analyze', [AdminClusterController::class, 'analyze'])->name('clusters.analyze');
        Route::patch('/clusters/{cluster}/status', [AdminClusterController::class, 'updateStatus'])->name('clusters.status');
        
        // Notifications
        Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
        Route::patch('/notifications/{notification}/read', [AdminNotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::patch('/notifications/read-all', [AdminNotificationController::class, 'markAllAsRead'])->name('notifications.read.all');
    });
