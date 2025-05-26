<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proxy extends Model
{
    protected $fillable = [
        'membership_number', 'full_name', 'national_id', 'phone', 'city', 'address'
    ];
    public function client()
{
    return $this->belongsTo(Client::class);
}

}
