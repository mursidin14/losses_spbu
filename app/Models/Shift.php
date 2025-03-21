<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;
    protected $table = "shifts";

    protected $fillable = [
        'name',
    ];

    public function despenser()
    {
        return $this->hasMany(Despenser::class);
    }

    public function nozzle()
    {
        return $this->hasMany(Nozzle::class);
    }
}
