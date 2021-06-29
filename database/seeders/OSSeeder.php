<?php

namespace Database\Seeders;

use App\Models\OS;
use Illuminate\Database\Seeder;

class OSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OS::truncate();
        OS::insert(['id' => 1, 'name' => 'iOS'], ['id' => 2, 'name' => 'Android']);
    }
}
