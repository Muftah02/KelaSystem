<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Factory extends Model
{
    protected $fillable = [
        'client_id', 'name', 'machine_type', 'reservation_type',
        'factory_area', 'specified_ton_quantity', 'map_location'
    ];

    protected $casts = [
        'factory_area' => 'decimal:2',
        'specified_ton_quantity' => 'decimal:2'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function getGoogleMapsUrlAttribute()
    {
        return $this->map_location;
    }

    public function licenses(): HasMany
    {
        return $this->hasMany(License::class);
    }
}
