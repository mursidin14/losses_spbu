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
        'shift_id',
        'tanggal',
        'waktu_masuk',
        'waktu_selesai',
        'stok_awal',
        'name',
        'nozzle',
        'jumlah',
        'stok_teoritis',
        'stok_akhir',
        'susut_despenser',
        'susut_harian',
        'susut_bulanan',
        'susut_tahunan',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($despenser) {
            $nozzles = Nozzle::where('product_id', $despenser->product_id)
                ->where('shift_id', $despenser->shift_id)
                ->whereDate('tanggal', $despenser->tanggal)
                ->get();
        
            $despenser->name = $nozzles->pluck('name');
            $despenser->nozzle = $nozzles->pluck('nozzle');
            $despenser->waktu_masuk = $nozzles->pluck('waktu_masuk');
            $despenser->waktu_selesai = $nozzles->pluck('waktu_selesai');
        });

        static::creating(function ($despenser) {

            $nozzles = Nozzle::where('product_id', $despenser->product_id)
                ->where('shift_id', $despenser->shift_id)
                ->whereDate('tanggal', $despenser->tanggal)
                ->get();

            $despenser->jumlah = $nozzles->sum('jumlah');
            $despenser->stok_teoritis = $despenser->stok_awal - $despenser->jumlah;
            $despenser->susut_despenser = $despenser->stok_teoritis - $despenser->stok_akhir;
            $despenser->susut_harian = ($despenser->susut_despenser / $despenser->stok_awal) * 100;

            // Susut Bulanan
            $month = $despenser->tanggal->format('Y-m');
            $monthlyReports = Despenser::where('product_id', $despenser->product_id)
                ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$month])
                ->get();
            
           $monthlyTotalSusut = $monthlyReports->sum('susut_harian');
           $monthlyTotalStok = $monthlyReports->sum('stok_teoritis');
           
           $despenser->susut_bulanan = ($monthlyTotalStok > 0)? ($monthlyTotalSusut/$monthlyTotalStok) * 100:0;
            
            // Susut Tahunan
            $year = $despenser->tanggal->format('Y');
            $yearlyReports = Despenser::where('product_id', $despenser->product_id)
                ->whereYear('tanggal', $year)
                ->get();
            
            $yearlyTotalSusut = $yearlyReports->sum('susut_harian');
            $yearlyTotalStok = $yearlyReports->sum('stok_teoritis');
            
            $despenser->susut_tahunan = ($yearlyTotalStok > 0) ? ($yearlyTotalSusut / $yearlyTotalStok) * 100:0;
        });

        static::updating(function ($despenser) {

            $nozzles = Nozzle::where('product_id', $despenser->product_id)
                ->where('shift_id', $despenser->shift_id)
                ->whereDate('tanggal', $despenser->tanggal)
                ->get();

            $despenser->jumlah = $nozzles->sum('jumlah');
            $despenser->stok_teoritis = $despenser->stok_awal - $despenser->jumlah;
            $despenser->susut_despenser = $despenser->stok_teoritis - $despenser->stok_akhir;
            $despenser->susut_harian = ($despenser->susut_despenser / $despenser->stok_awal) * 100;

            // Susut Bulanan
            $month = $despenser->tanggal->format('Y-m');
            $monthlyReports = Despenser::where('product_id', $despenser->product_id)
                ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$month])
                ->get();
            
           $monthlyTotalSusut = $monthlyReports->sum('susut_harian');
           $monthlyTotalStok = $monthlyReports->sum('stok_teoritis');
           
           $despenser->susut_bulanan = ($monthlyTotalStok > 0)? ($monthlyTotalSusut/$monthlyTotalStok) * 100:0;
            
            // Susut Tahunan
            $year = $despenser->tanggal->format('Y');
            $yearlyReports = Despenser::where('product_id', $despenser->product_id)
                ->whereYear('tanggal', $year)
                ->get();
            
            $yearlyTotalSusut = $yearlyReports->sum('susut_harian');
            $yearlyTotalStok = $yearlyReports->sum('stok_teoritis');
            
            $despenser->susut_tahunan = ($yearlyTotalStok > 0) ? ($yearlyTotalSusut / $yearlyTotalStok) * 100:0;
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    protected $casts = [
        'tanggal' => 'date',
        'name' => 'array',
        'nozzle' => 'array',
    ];
}
