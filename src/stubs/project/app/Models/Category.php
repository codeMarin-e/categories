<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \App\Traits\MacroableModel;
use App\Traits\Orderable;
use App\Traits\AddVariable;
use App\Traits\Uriable;

class Category extends Model
{

    protected $fillable = ['site_id', 'parent_id', 'active'];

    use MacroableModel;
    use AddVariable;

    // @HOOK_TRAITS

    //URIABLE
    use Uriable;
    public function defaultUri($language = null, $site_id = null, $prepareLevel = null) { //just for default
        $prepareLevel = $prepareLevel?? config('marinar_categories.prepare_levels');
        if($prepareLevel && $this->parent_id) {
            $this->loadMissing('parent');
            return $this->parent->getUriSlug(prepareLevel: $prepareLevel-1).'/'.$this->id;
        }
        return 'categories/'.$this->id;
    }

    public function prepareSlug($slug, $prepareLevel = null) {
        $prepareLevel = $prepareLevel?? config('marinar_categories.prepare_levels');
        if($prepareLevel && $this->parent_id) {
            $this->loadMissing('parent');
            return $this->parent->getUriSlug(prepareLevel: $prepareLevel-1).'/'.$slug;
        }
        return $slug;
    }
    //END URIABLE

    //ORDERABLE
    use Orderable;
    public function orderableQryBld($qryBld = null) {
        $qryBld = $qryBld? clone $qryBld : $this;
        return $qryBld->where([
            [ 'parent_id', $this->parent_id ],
            [ 'site_id', $this->site_id ],
        ]);
    }
    //END ORDERABLE

    protected static function boot() {
        parent::boot();
        static::updating( static::class.'@onUpdating_parent');
        static::updated( static::class.'@onUpdated_parent');
        static::deleting( static::class.'@onDeleting_categories' );

        // @HOOK_BOOT
    }

    public function onUpdating_parent($model) {
        if (!$model->isDirty('parent_id')) return;
        $model->ord = static::freeOrd($model->orderableQryBld());
    }

    public function onUpdated_parent($model) {
        if(!$model->isDirty('parent_id'))
            return;
        $model->parent_id = $model->getOriginal('parent_id');
        $model->ord = $model->getOriginal('ord');
        $model->onDeleted_orderable($model);
    }

    public function onDeleting_categories($model) {
        $model->loadMissing('children');
        foreach($model->children as $category) {
            $category->delete();
        }
    }

    public function getParents() {
        $return = array();
        $this->loadMissing('parent.parent');
        if( !$this->parent ) return $return;
        $return[ $this->parent->id ] = $this->parent;
        return array_merge($return, $this->parent->getParents());
    }

    public function getLevel() {
        return count($this->getParents());
    }

    public function parent() {
        return $this->belongsTo(static::class, 'parent_id', 'id');
    }
    public function children() {
        return $this->hasMany( static::class, 'parent_id', 'id')->orderBy('ord');
    }

    public function childrenQry($bldQry = null) {
        if(is_null($bldQry)) return $this->children();
        return (clone $bldQry)->where('parent_id', $this->id);
    }
}
