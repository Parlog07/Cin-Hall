<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationFactory> */
    use HasFactory;

    protected $fillable = ['status', 'expires_at', 'total_price', 'session_id', 'user_id'];

    public function seats()
    {
        return $this->belongsToMany(Seat::class, 'reservation_seat', 'reservation_id', 'seat_id')
            ->withTimestamps();
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function ticket()
    {
        return $this->hasOne(Ticket::class);
    }
}
