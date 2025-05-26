<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class License extends Model
{
    protected $fillable = [
        'client_id',
        'factory_id',
        'commercial_record',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class);
    }

    public function getStatusAttribute(): string
    {
        $now = Carbon::now();
        $startDate = Carbon::parse($this->start_date);
        $endDate = Carbon::parse($this->end_date);
        
        // التحقق من صحة التواريخ
        if (!$startDate || !$endDate) {
            return 'غير صالح';
        }

        // حساب الأيام المتبقية حتى انتهاء الترخيص
        $daysUntilExpiry = $now->diffInDays($endDate, false);

        // إذا كان تاريخ اليوم بعد تاريخ النهاية
        if ($now->gt($endDate)) {
            return 'منتهي';
        }
        
        // إذا كان باقي على انتهاء الترخيص أقل من 10 أيام
        if ($daysUntilExpiry <= 10) {
            return 'قريب الانتهاء';
        }
        
        // إذا كان الترخيص ساري
        return 'ساري';
    }
} 