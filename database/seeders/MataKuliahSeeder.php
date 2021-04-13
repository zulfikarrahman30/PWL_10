<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mata = [
        	[
        		'nama_matkul'=>'Pemograman Berbasis Object',
        		'sks'=>3,
        		'jam'=>6,
        		'semester'=>4
        	],
        	[
        		'nama_matkul'=>'Pemograman Web Lanjut',
        		'sks'=>3,
        		'jam'=>6,
        		'semester'=>4
        	],
        	[
        		'nama_matkul'=>'Basis Data Lanjut',
        		'sks'=>3,
        		'jam'=>6,
        		'semester'=>4
        	],
        	[
        		'nama_matkul'=>'Praktikum Basis Data Lanjut',
        		'sks'=>3,
        		'jam'=>6,
        		'semester'=>4
        	],
        ];
        DB::table('matakuliah')->insert($mata);
    }
}
