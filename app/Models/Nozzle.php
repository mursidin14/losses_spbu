<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nozzle extends Model
{
    use HasFactory;
    protected $table = 'nozzles';

    protected $fillable = [
        'product_id',
        'shift_id',
        'tanggal',
        'name',
        'nozzle',
        'meter_awal',
        'meter_akhir',
        'waktu_masuk',
        'waktu_selesai',
        'jumlah',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($nozzle) {
            $nozzle->jumlah = $nozzle->meter_akhir - $nozzle->meter_awal;
        });
        static::updating(function ($nozzle) {
            $nozzle->jumlah = $nozzle->meter_akhir - $nozzle->meter_awal;
        });

    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
