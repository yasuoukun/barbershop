<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PaymentController;
use App\Models\{Appointment, Service, Barber};
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    // … route อื่นๆ ของคุณ …

    // บันทึกการชำระเงิน (แนบสลิปได้)
    Route::post('/appointments/{appointment}/pay', [PaymentController::class, 'store'])
        ->name('payment.store');
});

Route::middleware('auth')->group(function () {

    // ประวัติการจองของลูกค้าที่ล็อกอินอยู่
    Route::get('/history', function () {
        $list = Appointment::with(['service','barber','payment'])
                ->where('customer_id', auth()->id())
                ->orderByDesc('scheduled_at')
                ->get();
        return view('history', compact('list'));
    })->name('history');

});
