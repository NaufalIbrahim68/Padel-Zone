<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';

    /**
     * Statuses that block a time slot from being booked.
     */
    const BLOCKING_STATUSES = [self::STATUS_PENDING, self::STATUS_CONFIRMED];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'court_id',
        'booking_date',
        'start_time',
        'end_time',
        'total_price',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'booking_date' => 'date',
        'total_price' => 'decimal:2',
    ];

    /**
     * Get the user that owns the booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the court that is booked.
     */
    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class);
    }

    /**
     * Check if the booking can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_CONFIRMED]);
    }

    /**
     * Check if the booking is active (blocking a slot).
     */
    public function isActive(): bool
    {
        return in_array($this->status, self::BLOCKING_STATUSES);
    }

    /**
     * Get formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp' . number_format($this->total_price, 0, ',', '.');
    }

    /**
     * Get formatted time range.
     */
    public function getTimeRangeAttribute(): string
    {
        return substr($this->start_time, 0, 5) . ' - ' . substr($this->end_time, 0, 5);
    }

    /**
     * Scope for active bookings (pending or confirmed).
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', self::BLOCKING_STATUSES);
    }

    /**
     * Scope for bookings on a given date.
     */
    public function scopeForDate($query, string $date)
    {
        return $query->where('booking_date', $date);
    }

    /**
     * Scope for bookings for a given court.
     */
    public function scopeForCourt($query, int $courtId)
    {
        return $query->where('court_id', $courtId);
    }
}
