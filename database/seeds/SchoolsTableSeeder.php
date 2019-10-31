<?php

use Illuminate\Database\Seeder;

class SchoolsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       Schema::disableForeignKeyConstraints();
        DB::table('schools')->delete();
        Schema::enableForeignKeyConstraints();
        DB::table('schools')->insert([
            'id' => 1,
            'user_id' => 1,
            'eps' => 'zxcv1234',
			'name' => 'Scuola Admin',
            'phone' => '32432432432',
			'email'	=> 'jeoncsetest@gmail.com',
			'password'	=> '12345678',
			'city'	=> 'milano',
			'place'	=> 'milano',
			'address'	=> 'milano',
        ]
		);
    }
}
