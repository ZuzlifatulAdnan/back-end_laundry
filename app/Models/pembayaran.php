<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class pembayaran extends Model
{
    use HasFactory;
    protected $fillable = [
        'no_pembayaran',
        'order_id',
        'metode_pembayaran',
        'jumlah_dibayar',
        'bukti_bayar',
        'status',
        
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function order()
    {
        return $this->belongsTo(order::class);
    }
}
