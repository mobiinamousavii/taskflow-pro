<?php

namespace Database\Seeders;

use App\Models\project_status;
use App\Models\workspace;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workspaces = workspace::all();
        if ($workspaces->isEmpty()) {
            $workspace = workspace::create([
                'name' => 'defualt',
                'description' => 'defualt',
                'owner_id' => 1,
            ]);
            $workspace = workspace::collect($workspace);
        }
        $defualtstatuses = [
            ['name' => 'backlog', 'position' => 0, 'is_completed' => false],
            ['name' => 'to do', 'position' => 1, 'is_completed' => false],
            ['name' => 'in progress', 'position' => 2, 'is_completed' => false],
            ['name' => 'review', 'position' => 3, 'is_completed' => false],
            ['name' => 'done', 'position' => 4, 'is_completed' => true],
        ];
        foreach ($workspaces as $workspace) {
            foreach ($defualtstatuses as $status) {
                project_status::firstOrCreate([
                    'workspace_id' => $workspace->id,
                    'name' => $status['name'],
                    'position' => $status['position'],
                    'is_completed' => $status['is_completed'],
                ]);
            }
        }
    }
}
