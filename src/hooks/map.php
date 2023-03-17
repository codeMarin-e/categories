<?php
return [
    implode(DIRECTORY_SEPARATOR, [ base_path(), 'app', 'Models', 'Uri.php']) => [
        "// @HOOK_URIABLE_CLASSES" => "\t\t\App\Models\Category::class => 'admin/categories/category.uri_type', \n",
    ],
    implode(DIRECTORY_SEPARATOR, [ base_path(), 'resources', 'views', 'components', 'admin', 'box_sidebar.blade.php']) => [
        "{{--  @HOOK_ADMIN_SIDEBAR  --}}" => "\t<x-admin.sidebar.categories_option />\n",
    ],
    implode(DIRECTORY_SEPARATOR, [ base_path(), 'config', 'marinar.php']) => [
        "// @HOOK_MARINAR_CONFIG_ADDONS" => "\t\t\\Marinar\\Categories\\MarinarCategories::class, \n"
    ]
];
