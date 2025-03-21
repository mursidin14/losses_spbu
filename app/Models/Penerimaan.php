<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penerimaan extends Model
{
    use HasFactory;

    protected $table = 'penerimaans';
    protected $fillable = [
        'product_id',
        'tanggal',
        'no_tangki',
        'no_pnbp',
        'vol_sebelum_penerimaan',
        'vol_penerimaan_pnbp',
        'vol_penerimaan_aktual',
        'susut_tangki',
        'susut_harian',
        'susut_bulanan',
        'susut_tahunan',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($penerimaan) {
            $penerimaan->susut_tangki = $penerimaan->vol_penerimaan_pnbp - $penerimaan->vol_penerimaan_aktual;
            $penerimaan->susut_harian = ($penerimaan->susut_tangki / $penerimaan->vol_penerimaan_pnbp) * 100;

            // Susut Bulanan
            $month = $penerimaan->tanggal->format('Y-m');
            $monthlyReports = Report::where('product_id', $penerimaan->product_id)
                ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$month])
                ->get();
            
           $monthlyTotalSusut = $monthlyReports->sum('susut_harian');
           $monthlyTotalStok = $monthlyReports->sum('vol_penerimaan_pnbp');
           
           $penerimaan->susut_bulanan = ($monthlyTotalStok > 0)? ($monthlyTotalSusut/$monthlyTotalStok) * 100:0;
            
            // Susut Tahunan
            $year = $penerimaan->tanggal->format('Y');
            $yearlyReports = Report::where('product_id', $penerimaan->product_id)
                ->whereYear('tanggal', $year)
                ->get();
            
            $yearlyTotalSusut = $yearlyReports->sum('susut_harian');
            $yearlyTotalStok = $yearlyReports->sum('vol_penerimaan_pnbp');
            
            $penerimaan->susut_tahunan = ($yearlyTotalStok > 0) ? ($yearlyTotalSusut / $yearlyTotalStok) * 100:0;
        });

        static::updating(function ($penerimaan) {
            $penerimaan->susut_tangki = $penerimaan->vol_penerimaan_pnbp - $penerimaan->vol_penerimaan_aktual;
            $penerimaan->susut_harian = ($penerimaan->susut_tangki / $penerimaan->vol_penerimaan_pnbp) * 100;

            // Susut Bulanan
            $month = $penerimaan->tanggal->format('Y-m');
            $monthlyReports = Report::where('product_id', $penerimaan->product_id)
                ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$month])
                ->get();
            
           $monthlyTotalSusut = $monthlyReports->sum('susut_harian');
           $monthlyTotalStok = $monthlyReports->sum('vol_penerimaan_pnbp');
           
           $penerimaan->susut_bulanan = ($monthlyTotalStok > 0)? ($monthlyTotalSusut/$monthlyTotalStok) * 100:0;
            
            // Susut Tahunan
            $year = $penerimaan->tanggal->format('Y');
            $yearlyReports = Report::where('product_id', $penerimaan->product_id)
                ->whereYear('tanggal', $year)
                ->get();
            
            $yearlyTotalSusut = $yearlyReports->sum('susut_harian');
            $yearlyTotalStok = $yearlyReports->sum('vol_penerimaan_pnbp');
            
            $penerimaan->susut_tahunan = ($yearlyTotalStok > 0) ? ($yearlyTotalSusut / $yearlyTotalStok) * 100:0;
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected $casts = [
        'tanggal' => 'date',
    ];
}
