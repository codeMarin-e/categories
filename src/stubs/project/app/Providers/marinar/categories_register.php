<?php

use App\Models\Category;
use App\Policies\CategoryPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

Route::model('chCategory', Category::class);
Gate::policy(Category::class, CategoryPolicy::class);

