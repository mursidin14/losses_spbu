<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = "products";

    protected $fillable = [
        'name',
        'slug'
    ];

    public function report()
    {
        return $this->hasMany(Report::class);
    }

    public function despenser()
    {
        return $this->hasMany(Despenser::class);
    }
}
