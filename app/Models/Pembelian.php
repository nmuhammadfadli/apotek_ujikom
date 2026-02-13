<?php

namespace App\Models;
use App\Models\DetailPembelian;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    protected $table = 'pembelian';
    protected $primaryKey = 'nota';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['nota','tgl_nota','kd_supplier','diskon'];

    public function detail()
    {
        return $this->hasMany(DetailPembelian::class, 'nota', 'nota');
    }
    public function supplier(){ return $this->belongsTo(Supplier::class, 'kd_supplier', 'id'); }
}

