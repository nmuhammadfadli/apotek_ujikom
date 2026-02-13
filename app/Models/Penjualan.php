<?php
namespace App\Models;
use App\Models\DetailPenjualan;
use App\Models\Pelanggan;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualan';
    protected $primaryKey = 'nota';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['nota','tgl_nota','kd_pelanggan','diskon'];

    public function detail()
    {
        return $this->hasMany(DetailPenjualan::class, 'nota', 'nota');
    }
    public function pelanggan(){ return $this->belongsTo(Pelanggan::class, 'kd_pelanggan', 'kd_pelanggan'); }
}