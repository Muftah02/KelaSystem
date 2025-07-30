<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'total_spent',
        'orders_count',
        'last_order_date',
        'notes',
        'is_active',
        'customer_group'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'total_spent' => 'decimal:2',
        'orders_count' => 'integer',
        'last_order_date' => 'datetime',
        'is_active' => 'boolean'
    ];

    // العلاقات
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function recentOrders($limit = 5)
    {
        return $this->hasMany(Order::class)->latest()->limit($limit);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByGroup($query, $group)
    {
        return $query->where('customer_group', $group);
    }

    public function scopeVip($query)
    {
        return $query->where('customer_group', 'vip');
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getFullAddressAttribute()
    {
        $address = collect([
            $this->address_line_1,
            $this->address_line_2,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country
        ])->filter()->implode(', ');

        return $address ?: null;
    }

    public function getAverageOrderValueAttribute()
    {
        return $this->orders_count > 0 ? $this->total_spent / $this->orders_count : 0;
    }

    // Methods
    public function updateTotals()
    {
        $orders = $this->orders()->where('status', '!=', 'cancelled');
        
        $this->total_spent = $orders->sum('total_amount');
        $this->orders_count = $orders->count();
        $this->last_order_date = $orders->latest()->first()?->created_at;
        
        // تحديث مجموعة العميل تلقائياً
        if ($this->total_spent >= 5000) {
            $this->customer_group = 'vip';
        } elseif ($this->total_spent >= 2000) {
            $this->customer_group = 'wholesale';
        } else {
            $this->customer_group = 'regular';
        }
        
        return $this->save();
    }
}