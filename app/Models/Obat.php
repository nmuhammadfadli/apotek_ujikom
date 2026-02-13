<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table = 'obat';
    protected $primaryKey = 'kd_obat';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'nm_obat','jenis','satuan','harga_beli', 'harga_jual','stok','kd_supplier'
    ];

     public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'kd_supplier', 'id');
    }
}

