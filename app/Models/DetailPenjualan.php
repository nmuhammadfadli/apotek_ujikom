<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    protected $table = 'penjualan_detail';
    protected $fillable = ['nota','kd_obat','jumlah','harga_satuan'];
    public function obat(){ return $this->belongsTo(Obat::class, 'kd_obat', 'kd_obat'); }
}
