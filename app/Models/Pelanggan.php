<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';
    protected $primaryKey = 'kd_pelanggan';
    protected $fillable = ['nm_pelanggan','alamat','kota','telpon'];

    public function penjualans()
    {
        return $this->hasMany(Penjualan::class, 'kd_pelanggan', 'kd_pelanggan');
    }
}