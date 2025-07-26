<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlockedPeriod extends Model
{
    use HasFactory;

    protected $table = 'blocked_periods';

    protected $fillable = [
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'message',
        'created_by',
    ];

    protected $dates = ['start_date', 'end_date'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getIsFullDayAttribute()
    {
        return is_null($this->start_time) && is_null($this->end_time);
    }

    public function getIsMultiDayAttribute()
    {
        return !is_null($this->end_date) && $this->start_date->lt($this->end_date);
    }


    public function scopeForDate($query, $date)
    {
        return $query->whereDate('start_date', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('end_date')->orWhereDate('end_date', '>=', $date);
            });
    }
}
