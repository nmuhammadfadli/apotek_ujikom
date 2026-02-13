<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DetailPembelian extends Model
{
    protected $table = 'pembelian_detail';
    protected $fillable = ['nota','kd_obat','jumlah','harga_satuan'];
    public function obat(){ return $this->belongsTo(Obat::class, 'kd_obat', 'kd_obat'); }
}