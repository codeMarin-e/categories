<?php
    namespace Marinar\Categories;

    use Marinar\Categories\Database\Seeders\MarinarCategoriesInstallSeeder;

    class MarinarCategories {

        public static function getPackageMainDir() {
            return __DIR__;
        }

        public static function injects() {
            return MarinarCategoriesInstallSeeder::class;
        }
    }
