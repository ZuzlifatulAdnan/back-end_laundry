<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;
    protected $fillable = [
        'no_order',
        'user_id',
        'service_type',
        'mesin_id',
        'tanggal_order',
        'jam_order',
        'durasi',
        'koin',
        'berat',
        'detergent',
        'catatan',
        'tanggal_ambil',
        'status',
        'total_biaya',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function mesin()
    {
        return $this->belongsTo(mesin::class);
    }
    public function pembayaran()
    {
        return $this->hasOne(pembayaran::class);
    }
}
