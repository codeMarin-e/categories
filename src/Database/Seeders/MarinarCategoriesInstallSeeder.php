<?php
    namespace Marinar\Categories\Database\Seeders;

    use Illuminate\Database\Seeder;
    use Marinar\Categories\MarinarCategories;

    class MarinarCategoriesInstallSeeder extends Seeder {

        use \Marinar\Marinar\Traits\MarinarSeedersTrait;

        public static function configure() {
            static::$packageName = 'marinar_categories';
            static::$packageDir = MarinarCategories::getPackageMainDir();
        }

        public function run() {
            if(!in_array(env('APP_ENV'), ['dev', 'local'])) return;

            $this->autoInstall();

            $this->refComponents->info("Done!");
        }

    }
