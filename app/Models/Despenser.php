<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Despenser extends Model
{
    use HasFactory;

    protected $table = 'despensers';

    protected $fillable = [
        'product_id',
        'nozzle',
        'meter_awal',
        'meter_akhir',
        'jumlah',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($teller) {
            $teller->jumlah = $teller->meter_akhir - $teller->meter_awal;
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
