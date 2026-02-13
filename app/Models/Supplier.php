<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    //protected $table = 'supplier';
    protected $fillable = ['nm_supplier','alamat','kota','telpon'];

    public function obats()
    {
        return $this->hasMany(Obat::class, 'kd_supplier', 'id');
    }
}