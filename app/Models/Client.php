<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'membership_number', 'full_name', 'national_id', 'phone', 'city', 'address'
    ];

    public function factories(): HasMany
    {
        return $this->hasMany(Factory::class);
    }

    public function proxy()
    {
        return $this->hasOne(Proxy::class);
    }

    public function licenses(): HasMany
    {
        return $this->hasMany(License::class);
    }

    public function getActiveLicenseAttribute()
    {
        $now = now();
        return $this->licenses()
            ->where('end_date', '>=', $now)
            ->orderBy('end_date', 'desc')
            ->first();
    }

    /**
     * الحصول على الترخيص الساري للعميل
     */
    public function active_license()
    {
        return $this->hasOne(License::class)
            ->where('end_date', '>', now())
            ->orderBy('end_date', 'desc');
    }
}
