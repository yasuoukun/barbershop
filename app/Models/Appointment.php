<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'customer_id','barber_id','service_id','scheduled_at','status','notes'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function customer() { return $this->belongsTo(User::class,'customer_id'); }
    public function barber()   { return $this->belongsTo(Barber::class); }
    public function service()  { return $this->belongsTo(Service::class); }
    public function payment()  { return $this->hasOne(Payment::class); }
}
