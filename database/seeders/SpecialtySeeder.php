<?php

namespace Database\Seeders;

use App\Enums\SpecialtyEnum;
use App\Models\Specialty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach (SpecialtyEnum::cases() as $specialty) {
            Specialty::updateOrCreate(
                ['name' => $specialty->code()],
                ['name' => $specialty->code()]
            );
        }
    }
}
