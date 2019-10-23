<?php

use Illuminate\Database\Seeder;

class DisciplinesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		Schema::disableForeignKeyConstraints();
        DB::table('disciplines')->delete();
        Schema::enableForeignKeyConstraints();
        DB::table('disciplines')->insert([[
            'id' => 1,
            'discipline_name' => 'SALSA',
            'discipline_desc' => 'portoricana, Cubana, solo salsa shine, duo salsa show,  duo salsa shine, solo salsa show, rueda',
			'created_at' => '2019-10-23 19:51:38',
            'updated_at' => '2019-10-23 19:51:38'
        ],
		[
            'id' => 2,
            'discipline_name' => 'BACHATA',
            'discipline_desc' => 'BACHATA',
			'created_at' => '2019-10-23 19:51:38',
            'updated_at' => '2019-10-23 19:51:38'
        ],
		[
            'id' => 3,
            'discipline_name' => 'KIZOMBA',
            'discipline_desc' => 'KIZOMBA',
			'created_at' => '2019-10-23 19:51:38',
            'updated_at' => '2019-10-23 19:51:38'
        ],
		[
            'id' => 4,
            'discipline_name' => 'MERENGUE',
            'discipline_desc' => 'MERENGUE',
			'created_at' => '2019-10-23 19:51:38',
            'updated_at' => '2019-10-23 19:51:38'
        ],
		[
            'id' => 5,
            'discipline_name' => 'LISCIO UNIFICATO',
            'discipline_desc' => 'Valzer Viennese, Mazurca, Polka',
			'created_at' => '2019-10-23 19:51:38',
            'updated_at' => '2019-10-23 19:51:38'
        ],
		[
            'id' => 6,
            'discipline_name' => 'BALLO DA SALA',
            'discipline_desc' => 'Valzer Lento, Tango, Fox Trot',
			'created_at' => '2019-10-23 19:51:38',
            'updated_at' => '2019-10-23 19:51:38'
        ],
		[
            'id' => 7,
            'discipline_name' => 'DANZE STANDARD',
            'discipline_desc' => 'valzer inglese, tango, valzer viennese, slowfox, quick step',
			'created_at' => '2019-10-23 19:51:38',
            'updated_at' => '2019-10-23 19:51:38'
        ],
		[
            'id' => 8,
            'discipline_name' => 'DANZE LATINO AMERICANE',
            'discipline_desc' => 'samba, cha cha cha, rumba, paso doble, jive',
			'created_at' => '2019-10-23 19:51:38',
            'updated_at' => '2019-10-23 19:51:38'
        ],
		[
            'id' => 9,
            'discipline_name' => 'DANZE ARGENTINE',
            'discipline_desc' => 'Tango Argentino, Vals, Milonga',
			'created_at' => '2019-10-23 19:51:38',
            'updated_at' => '2019-10-23 19:51:38'
        ],
		[
            'id' => 10,
            'discipline_name' => 'URBAN DANCE',
            'discipline_desc' => 'electric boogie, break dance, disco dance, disco acrobatica',
			'created_at' => '2019-10-23 19:51:38',
            'updated_at' => '2019-10-23 19:51:38'
        ],
		[
            'id' => 11,
            'discipline_name' => 'DANZE JAZZ',
            'discipline_desc' => 'jive jazz, rock & roll, rock acrobatico, boogie woogie, swing, mix blues, lindy hop',
			'created_at' => '2019-10-23 19:51:38',
            'updated_at' => '2019-10-23 19:51:38'
        ],
		[
            'id' => 12,
            'discipline_name' => 'MODERN',
            'discipline_desc' => 'contemporanea, modern jazz, lyrical jazz',
			'created_at' => '2019-10-23 19:51:38',
            'updated_at' => '2019-10-23 19:51:38'
        ],
		[
            'id' => 13,
            'discipline_name' => 'DANZE STREET',
            'discipline_desc' => 'Hip Hop, Break Dance, Dance Hall',
			'created_at' => '2019-10-23 19:51:38',
            'updated_at' => '2019-10-23 19:51:38'
        ],
		[
            'id' => 14,
            'discipline_name' => 'COREOGRAFICHE ',
            'discipline_desc' => 'Show Dance, VIDEO dance, Musical, CARIBBEAN SHOW DANCE',
			'created_at' => '2019-10-23 19:51:38',
            'updated_at' => '2019-10-23 19:51:38'
        ]]
		);
    }
}
