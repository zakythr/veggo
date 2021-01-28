<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HariPengirimanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hari = [
            'senin', 'selasa', 'rabu', 'kamis', 'jumat',
            'sabtu', 'minggu'
        ];
        
        for($a=0;$a<sizeof($hari);$a++)
        {
            DB::table('hari_pengiriman')->insert([
                'tersedia' => 1,
                'nama_hari' => $hari[$a]
            ]);
        }

    }
}
