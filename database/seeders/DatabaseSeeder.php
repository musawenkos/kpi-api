<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enum\UserRole;
use App\Models\Kpi;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $exec = User::factory()->create([
            'name' => 'Eve Executive', 'email' => 'exec@opms.test',
            'role' => UserRole::Executive, 'job_title' => 'Executive',
        ]);

        $section = User::factory()->create([
            'name' => 'Sam Section', 'email' => 'section@opms.test',
            'role' => UserRole::SectionLeader, 'leader_id' => $exec->id,
        ]);

        $techs = User::factory(3)->create([
            'role' => UserRole::Technical, 'leader_id' => $section->id,
        ]);

        $techs->first()->update(['email' => 'tech@opms.test']);

        foreach ($techs as $tech) {
            Kpi::factory(4)->create([
                'assigned_to' => $tech->id,
                'assigned_by' => $section->id,
            ]);
        }
    }
}
