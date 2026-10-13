<?php

namespace Database\Seeders;

use App\Models\role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'owner', ' lable' => 'مدیر'],
            ['name' => 'admin', ' lable' => 'ادمین'],
            ['name' => 'member', ' lable' => 'عضو '],
            ['name' => 'viewer', ' lable' => 'ناظر'],
        ];
        foreach($roles as $role){
            role::firstOrCreate(['name'=>$role['name']] , $role);
        }
    }
}
