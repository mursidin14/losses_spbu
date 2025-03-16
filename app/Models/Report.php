<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $table = 'reports';

    protected $fillable = [
        'tanggal',
        'stok_awal',
        'no_tangki',
        'no_pnbp',
        'vol_sebelum_penerimaan',
        'vol_penerimaan_pnbp',
        'vol_penerimaan_aktual',
        'susut_tangki',
        'pengeluaran',
        'stok_teoritis',
        'stok_aktual',
        'susut_pengeluaran',
        'toleransi',
        'total_susut',
        'susut_harian',
        'susut_bulanan',
        'susut_tahunan',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($report) {
            $report->susut_tangki = $report->vol_penerimaan_pnbp - $report->vol_penerimaan_aktual;
            $despenserJumlah = $report->despenser()->sum('jumlah');
            if ($despenserJumlah > 0) {
                $report->pengeluaran = $despenserJumlah;
            }
            $report->stok_teoritis = $report->stok_awal + $report->vol_penerimaan_aktual - $report->pengeluaran;
            $report->susut_pengeluaran = $report->stok_teoritis - $report->stok_aktual;
            $report->total_susut = $report->susut_tangki + $report->susut_pengeluaran;
            $report->susut_harian = ($report->total_susut / $report->stok_teoritis) * 100;

            // Susut Bulanan
            $month = $report->tanggal->format('Y-m');
            $monthlyReports = Report::where('product_id', $report->product_id)
                ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$month])
                ->get();
            
           $monthlyTotalSusut = $monthlyReports->sum('total_susut');
           $monthlyTotalStok = $monthlyReports->sum('stok_teoritis');
           
           $report->susut_bulanan = ($monthlyTotalStok > 0)? ($monthlyTotalSusut/$monthlyTotalStok) * 100:0;
            
            // Susut Tahunan
            $year = $report->tanggal->format('Y');
            $yearlyReports = Report::where('product_id', $report->product_id)
                ->whereYear('tanggal', $year)
                ->get();
            
            $yearlyTotalSusut = $yearlyReports->sum('total_susut');
            $yearlyTotalStok = $yearlyReports->sum('stok_teoritis');
            
            $report->susut_tahunan = ($yearlyTotalStok > 0) ? ($yearlyTotalSusut / $yearlyTotalStok) * 100:0;
        });

        static::updating(function ($report) {
            $report->susut_tangki = $report->vol_penerimaan_pnbp - $report->vol_penerimaan_aktual;
            $despenserJumlah = $report->despenser()->sum('jumlah');
            if ($despenserJumlah > 0) {
                $report->pengeluaran = $despenserJumlah;
            }
            $report->stok_teoritis = $report->stok_awal + $report->vol_penerimaan_aktual - $report->pengeluaran;
            $report->susut_pengeluaran = $report->stok_teoritis - $report->stok_aktual;
            $report->total_susut = $report->susut_tangki + $report->susut_pengeluaran;

            // susut harian
            $report->susut_harian = ($report->total_susut / $report->stok_teoritis) * 100;

            // Susut Bulanan
            $month = $report->tanggal->format('Y-m');
            $monthlyReports = Report::where('product_id', $report->product_id)
                ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$month])
                ->get();

            $monthlyTotalSusut = $monthlyReports->sum('total_susut');
            $monthlyTotalStok = $monthlyReports->sum('stok_teoritis');

            $report->susut_bulanan = ($monthlyTotalStok > 0)
                ? ($monthlyTotalSusut / $monthlyTotalStok) * 100
                : 0;

        // Susut Tahunan
        $year = $report->tanggal->format('Y');
        $yearlyReports = Report::where('product_id', $report->product_id)
            ->whereYear('tanggal', $year)
            ->get();

        $yearlyTotalSusut = $yearlyReports->sum('total_susut');
        $yearlyTotalStok = $yearlyReports->sum('stok_teoritis');

        $report->susut_tahunan = ($yearlyTotalStok > 0)
            ? ($yearlyTotalSusut / $yearlyTotalStok) * 100
            : 0;
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke tabel teller
    public function despenser()
    {
        return $this->hasMany(Despenser::class, 'product_id', 'product_id');
    }

    protected $casts = [
        'tanggal' => 'date',
    ];
}
