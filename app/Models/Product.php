<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = "products";

    protected $fillable = [
        'name'
    ];

    public function report()
    {
        return $this->hasMany(Report::class);
    }

    public function despenser()
    {
        return $this->hasMany(Despenser::class);
    }

    public function penerimaan()
    {
        return $this->hasMany(Penerimaan::class);
    }

    public function nozzle()
    {
        return $this->hasMany(Nozzle::class);
    }

    public function latestReport()
    {
        return $this->hasOne(Report::class)->latestOfMany('tanggal');
    }
}
