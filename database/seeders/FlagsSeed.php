<?php

namespace Database\Seeders;

use App\Constants\Flags;
use App\Models\Flag;
use Illuminate\Database\Seeder;

class FlagsSeed extends Seeder
{
    public function run(): void
    {
        $flags = Flags::cases();
        foreach ($flags as $flag) {
            Flag::create(['name' => $flag]);
        }
    }
}
