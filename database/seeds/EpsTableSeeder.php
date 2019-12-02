<?php

use Illuminate\Database\Seeder;

class EpsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('eps')->delete();
        DB::table('eps')->insert([
			[
				'id' => 1,
				'eps_code' => 'Nessuno/NOT',
				'eps_desc' => 'Nessuno/NOT',
			],
			[
				'id' => 2,
				'eps_code' => 'ACSI',
				'eps_desc' => 'ACSI',
			],
			[
				'id' => 3,
				'eps_code' => 'AICS',
				'eps_desc' => 'AICS',
			],
			[
				'id' => 4,
				'eps_code' => 'ASC',
				'eps_desc' => 'ASC',
			],
			[
				'id' => 5,
				'eps_code' => 'ASI',
				'eps_desc' => 'ASI',
			],
				[
				'id' => 6,
				'eps_code' => 'CSAIN',
				'eps_desc' => 'CSAIN',
			],
			[
				'id' => 7,
				'eps_code' => 'CSEN',
				'eps_desc' => 'CSEN',
			],
			[
				'id' => 8,
				'eps_code' => 'CSI',
				'eps_desc' => 'CSI',
			],
			[
				'id' => 9,
				'eps_code' => 'CUSI',
				'eps_desc' => 'CUSI',
			],
			[
				'id' => 10,
				'eps_code' => 'ENDAS',
				'eps_desc' => 'ENDAS',
			],
				[
				'id' => 11,
				'eps_code' => 'FIDS',
				'eps_desc' => 'FIDS',
			],
			[
				'id' => 12,
				'eps_code' => 'LIBERTAS',
				'eps_desc' => 'LIBERTAS',
			],
			[
				'id' => 13,
				'eps_code' => 'MSP',
				'eps_desc' => 'MSP',
			],
			[
				'id' => 14,
				'eps_code' => 'OPES',
				'eps_desc' => 'OPES',
			],
			[
				'id' => 15,
				'eps_code' => 'PGS',
				'eps_desc' => 'PGS',
			],
				[
				'id' => 16,
				'eps_code' => 'UISP',
				'eps_desc' => 'UISP',
			],
			[
				'id' => 17,
				'eps_code' => 'US ACLI',
				'eps_desc' => 'US ACLI',
			],
		]
		);
    }
}
