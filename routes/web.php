<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PaymentController;
use App\Models\{Appointment, Service, Barber};
use Illuminate\Http\Request;

use Carbon\Carbon;
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
Route::middleware('auth')->group(function () {
    Route::get('/history', function () {
        $list = Appointment::with(['service','barber','payment'])
                ->where('customer_id', auth()->id())
                ->orderByDesc('scheduled_at')
                ->get();
        return view('history', compact('list'));
    })->name('history');
});

Route::get('/booking', function () {
    return view('booking', [
        'services' => Service::all(),
        'barbers'  => Barber::all(),
    ]);
})->name('booking');

Route::post('/booking', function(Request $req){
    $data = $req->validate([
        'service_id' => 'required|exists:services,id',
        'barber_id'  => 'required|exists:barbers,id',
        'date'       => 'required|date',
        'time'       => 'required',
    ]);

    $scheduled = Carbon::parse($data['date'].' '.$data['time']);

    Appointment::create([
        'customer_id'  => auth()->id(),
        'barber_id'    => $data['barber_id'],
        'service_id'   => $data['service_id'],
        'scheduled_at' => $scheduled,
        'status'       => 'pending',
    ]);

    return redirect()->route('history')->with('ok','จองคิวเรียบร้อย');
})->name('booking.store');