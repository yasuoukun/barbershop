<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request, Appointment $appointment)
    {
        // ต้องล็อกอินก่อน (ถ้ายังไม่ได้ทำ role แอดมิน/ช่าง ให้ใช้เช็กง่ายๆก่อน)
        if (!auth()->check()) {
            abort(403, 'Unauthorized');
        }

        $data = $request->validate([
            'method' => 'required|in:cash,transfer',
            'amount' => 'required|integer|min:0',
            'slip'   => 'nullable|image|max:4096', // รองรับรูป 4MB
        ]);

        // เก็บไฟล์ไปที่ storage/app/public/payments
        $path = null;
        if ($request->hasFile('slip')) {
            $path = $request->file('slip')->store('payments', 'public');
        }

        // ถ้ามี payment อยู่แล้ว → update, ถ้ายังไม่มี → create
        $payment = $appointment->payment;
        if ($payment) {
            $payment->update([
                'method'   => $data['method'],
                'amount'   => $data['amount'],
                'paid_at'  => now(),
                'slip_path'=> $path ?? $payment->slip_path,
            ]);
        } else {
            $payment = $appointment->payment()->create([
                'method'   => $data['method'],
                'amount'   => $data['amount'],
                'paid_at'  => now(),
                'slip_path'=> $path,
            ]);
        }

        return back()->with('ok', 'บันทึกการชำระเงินแล้ว');
    }
}
