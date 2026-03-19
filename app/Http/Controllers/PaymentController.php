<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'payment_model' => 'required|in:Paypal,Stripe',
            'amount' => 'required|numeric|min:0',
        ]);

        $reservation = Reservation::findOrFail($validated['reservation_id']);

        if ($reservation->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'You are not allowed to pay for this reservation.',
            ], 403);
        }

        if ($reservation->status !== 'pending') {
            return response()->json([
                'message' => 'This reservation cannot be paid.',
            ], 422);
        }

        if (Carbon::now()->greaterThan($reservation->expires_at)) {
            $reservation->update([
                'status' => 'expired',
            ]);

            return response()->json([
                'message' => 'This reservation has expired.',
            ], 422);
        }

        if ((float) $validated['amount'] !== (float) $reservation->total_price) {
            return response()->json([
                'message' => 'The payment amount does not match the reservation total price.',
            ], 422);
        }

        $alreadyPaid = Payment::where('reservation_id', $reservation->id)
            ->where('payment_status', 'payed')
            ->exists();

        if ($alreadyPaid) {
            return response()->json([
                'message' => 'This reservation has already been paid.',
            ], 422);
        }

        $payment = DB::transaction(function () use ($validated, $reservation) {
            $payment = Payment::create([
                'payment_model' => $validated['payment_model'],
                'amount' => $validated['amount'],
                'payment_status' => 'payed',
                'reservation_id' => $reservation->id,
            ]);

            $reservation->update([
                'status' => 'paid',
            ]);

            return $payment;
        });

        return response()->json([
            'message' => 'Payment completed successfully.',
            'payment' => $payment,
            'reservation' => $reservation->fresh(),
        ], 201);
    }

    public function show(Payment $payment)
    {
        $payment->load('reservation');

        if (
            $payment->reservation &&
            $payment->reservation->user_id !== Auth::id() &&
            !Auth::user()->is_admin
        ) {
            return response()->json([
                'message' => 'Unauthorized.',
            ], 403);
        }

        return response()->json($payment, 200);
    }
}