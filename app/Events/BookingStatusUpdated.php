<?php

namespace App\Events;

use App\Models\Booking;
use App\Models\BookingStatusHistory;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Booking $booking,
        public BookingStatusHistory $history,
    ) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('users.' . $this->booking->user_id)];
    }

    public function broadcastAs(): string
    {
        return 'booking.status.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'booking_id' => $this->booking->id,
            'kode_booking' => $this->booking->kode_booking,
            'status' => $this->booking->status_pengerjaan,
            'status_pembayaran' => $this->booking->status_pembayaran,
            'keterangan' => $this->history->keterangan,
            'updated_at' => $this->booking->fresh()->updated_at?->toIso8601String(),
        ];
    }
}
