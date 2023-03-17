<?php
	return [
		'install' => [
            'php artisan db:seed --class="\Marinar\Categories\Database\Seeders\MarinarCategoriesInstallSeeder"',
		],
		'remove' => [
            'php artisan db:seed --class="\Marinar\Categories\Database\Seeders\MarinarCategoriesRemoveSeeder"',
        ]
	];
