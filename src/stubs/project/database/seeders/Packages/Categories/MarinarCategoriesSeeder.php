<?php
namespace Database\Seeders\Packages\Categories;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class MarinarCategoriesSeeder extends Seeder {

    public function run() {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::upsert([
            ['guard_name' => 'admin', 'name' => 'categories.view'],
            ['guard_name' => 'admin', 'name' => 'category.system'],
            ['guard_name' => 'admin', 'name' => 'category.create'],
            ['guard_name' => 'admin', 'name' => 'category.update'],
            ['guard_name' => 'admin', 'name' => 'category.delete'],
        ], ['guard_name','name']);
    }
}
