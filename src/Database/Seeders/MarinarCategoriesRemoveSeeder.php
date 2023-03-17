<?php
    namespace Marinar\Categories\Database\Seeders;

    use App\Models\Category;
    use Illuminate\Database\Seeder;
    use Marinar\Categories\MarinarCategories;
    use Spatie\Permission\Models\Permission;

    class MarinarCategoriesRemoveSeeder extends Seeder {

        use \Marinar\Marinar\Traits\MarinarSeedersTrait;

        public static function configure() {
            static::$packageName = 'marinar_categories';
            static::$packageDir = MarinarCategories::getPackageMainDir();
        }

        public function run() {
            if(!in_array(env('APP_ENV'), ['dev', 'local'])) return;

            $this->autoRemove();

            $this->refComponents->info("Done!");
        }

        public function clearMe() {
            $this->refComponents->task("Clear DB", function() {
                foreach(Category::get() as $category) {
                    $category->delete();
                }
                Permission::whereIn('name', [
                    'categories.view',
                    'category.system',
                    'category.create',
                    'category.update',
                    'category.delete',
                ])
                ->where('guard_name', 'admin')
                ->delete();
                app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
                return true;
            });
        }
    }
