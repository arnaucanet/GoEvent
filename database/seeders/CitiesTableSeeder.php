<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CitiesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('cities')->delete();
        
        \DB::table('cities')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Madrid',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Barcelona',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Valencia',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Sevilla',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Granada',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Málaga',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Córdoba',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Bilbao',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Zaragoza',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'San Sebastián',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Toledo',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Santiago de Compostela',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'Salamanca',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'Palma de Mallorca',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'Alicante',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'Cádiz',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'Segovia',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'Ibiza',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'Tenerife',
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'Las Palmas de Gran Canaria',
            ),
        ));
        
        
    }
}