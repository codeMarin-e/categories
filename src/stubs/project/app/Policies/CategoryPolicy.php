<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function before(User $user, $ability) {
        // @HOOK_POLICY_BEFORE
        if($user->hasRole('Super Admin', 'admin') )
            return true;
    }

    public function view(User $user) {
        // @HOOK_POLICY_VIEW
        return $user->hasPermissionTo('categories.view', request()->whereIam());
    }

    public function create(User $user) {
        // @HOOK_POLICY_CREATE
        return $user->hasPermissionTo('category.create', request()->whereIam());
    }

    public function update(User $user, Category $chCategory) {
        // @HOOK_POLICY_UPDATE
        if( !$user->hasPermissionTo('category.update', request()->whereIam()) )
            return false;
        return true;
    }

    public function delete(User $user, Category $chCategory) {
        // @HOOK_POLICY_DELETE
        if( !$user->hasPermissionTo('category.delete', request()->whereIam()) )
            return false;
        return true;
    }

    // @HOOK_POLICY_END
}
