<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mesin extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama',
        'type',
        'durasi',
        'status',
    ];
    public function order()
    {
        return $this->belongsTo(order::class);
    }
}
