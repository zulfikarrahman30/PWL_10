<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mahasiswa;

class kelas extends Model
{
    use HasFactory;
    protected $table='kelas';

    public function mahasiswas()
    {
        return $this ->hasMany(Mahasiswas::class);
    }
}
