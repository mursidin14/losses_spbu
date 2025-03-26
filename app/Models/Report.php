<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $table = 'reports';

    protected $fillable = [
        'product_id',
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
        'susut_mingguan',
        'susut_bulanan',
        'susut_tahunan',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($report) {
            $report->susut_tangki = $report->vol_penerimaan_pnbp - $report->vol_penerimaan_aktual;
            $report->stok_teoritis = $report->stok_awal + $report->vol_penerimaan_aktual - $report->pengeluaran;
            $report->susut_pengeluaran = $report->stok_teoritis - $report->stok_aktual;
            $report->total_susut = $report->susut_tangki + $report->susut_pengeluaran;
            $report->susut_harian = ($report->susut_pengeluaran / $report->pengeluaran) * 1;

            // susut mingguan
            $startOfWeek = now()->startOfWeek();
            $endOfWeek = now()->endOfWeek();
            $weeklyReports = Report::where('product_id', $report->product_id)
                ->whereBetween("tanggal", [$startOfWeek, $endOfWeek])
                ->get();
            
           $weeklyTotalSusut = $weeklyReports->sum('susut_pengeluaran');
           $weeklyTotalStok = $weeklyReports->sum('pengeluaran');
           
           $report->susut_mingguan = ($weeklyTotalStok > 0)? ($weeklyTotalSusut/$weeklyTotalStok) * 1:0;

            // Susut Bulanan
            $month = $report->tanggal->format('Y-m');
            $monthlyReports = Report::where('product_id', $report->product_id)
                ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$month])
                ->get();
            
           $monthlyTotalSusut = $monthlyReports->sum('susut_pengeluaran');
           $monthlyTotalStok = $monthlyReports->sum('pengeluaran');
           
           $report->susut_bulanan = ($monthlyTotalStok > 0)? ($monthlyTotalSusut/$monthlyTotalStok) * 1:0;
            
            // Susut Tahunan
            $year = $report->tanggal->format('Y');
            $yearlyReports = Report::where('product_id', $report->product_id)
                ->whereYear('tanggal', $year)
                ->get();
            
            $yearlyTotalSusut = $yearlyReports->sum('susut_pengeluaran');
            $yearlyTotalStok = $yearlyReports->sum('pengeluaran');
            
            $report->susut_tahunan = ($yearlyTotalStok > 0) ? ($yearlyTotalSusut / $yearlyTotalStok) * 1:0;
        });

        static::updating(function ($report) {
            $report->susut_tangki = $report->vol_penerimaan_pnbp - $report->vol_penerimaan_aktual;
            $report->stok_teoritis = $report->stok_awal + $report->vol_penerimaan_aktual - $report->pengeluaran;
            $report->susut_pengeluaran = $report->stok_teoritis - $report->stok_aktual;
            $report->total_susut = $report->susut_tangki + $report->susut_pengeluaran;

            // susut harian
            $report->susut_harian = ($report->susut_pengeluaran / $report->pengeluaran) * 1;

            // susut mingguan
            $startOfWeek = now()->startOfWeek();
            $endOfWeek = now()->endOfWeek();
            $weeklyReports = Report::where('product_id', $report->product_id)
                ->whereBetween("tanggal", [$startOfWeek, $endOfWeek])
                ->get();

            $weeklyTotalSusut = $weeklyReports->sum('susut_pengeluaran');
            $weeklyTotalStok = $weeklyReports->sum('pengeluaran');
                
            $report->susut_mingguan = ($weeklyTotalStok > 0)? ($weeklyTotalSusut/$weeklyTotalStok) * 1:0;

            // Susut Bulanan
            $month = $report->tanggal->format('Y-m');
            $monthlyReports = Report::where('product_id', $report->product_id)
                ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$month])
                ->get();

            $monthlyTotalSusut = $monthlyReports->sum('susut_pengeluaran');
            $monthlyTotalStok = $monthlyReports->sum('pengeluaran');

            $report->susut_bulanan = ($monthlyTotalStok > 0)
                ? ($monthlyTotalSusut / $monthlyTotalStok) * 1
                : 0;

        // Susut Tahunan
        $year = $report->tanggal->format('Y');
        $yearlyReports = Report::where('product_id', $report->product_id)
            ->whereYear('tanggal', $year)
            ->get();

        $yearlyTotalSusut = $yearlyReports->sum('susut_pengeluaran');
        $yearlyTotalStok = $yearlyReports->sum('pengeluaran');

        $report->susut_tahunan = ($yearlyTotalStok > 0)
            ? ($yearlyTotalSusut / $yearlyTotalStok) * 1
            : 0;
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
