<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CategoryController extends Controller {
    public function __construct() {
        if(!request()->route()) return;

        $this->db_table = Category::getModel()->getTable();
        $this->routeNamespace = Str::before(request()->route()->getName(), '.categories');
        View::composer('admin/categories/*', function($view)  {
            $viewData = [
                'route_namespace' => $this->routeNamespace,
            ];
            // @HOOK_VIEW_COMPOSERS
            $view->with($viewData);
        });
        // @HOOK_CONSTRUCT
    }

    public function index() {
        $viewData = [];
        $bldQry =  Category::where("{$this->db_table}.site_id", app()->make('Site')->id)->orderBy("{$this->db_table}.ord", 'ASC');
        $subBldQry = clone $bldQry;

        // @HOOK_INDEX_END

        $viewData['subBldQry'] = $subBldQry;
        $viewData['categories'] = $bldQry->where("{$this->db_table}.parent_id", 0)
            ->paginate(20)->appends( request()->query() );

        return view('admin/categories/categories', $viewData);
    }

    public function create() {
        $viewData = [];
        $viewData['subParentBldQry'] = Category::where("{$this->db_table}.site_id", app()->make('Site')->id)
            ->orderBy("{$this->db_table}.ord", 'ASC');
        $viewData['mainCategories'] = (clone $viewData['subParentBldQry'])->where('parent_id', 0)->get();

        // @HOOK_CREATE

        return view('admin/categories/category', $viewData);
    }

    public function edit(Category $chCategory) {
        $viewData = [];
        $viewData['subParentBldQry'] = Category::where("{$this->db_table}.site_id", app()->make('Site')->id)
            ->orderBy("{$this->db_table}.ord", 'ASC')
            ->where('id', '!=', $chCategory->id);
        $viewData['mainCategories'] = (clone $viewData['subParentBldQry'])->where('parent_id', 0)->get();

        // @HOOK_EDIT

        $viewData['chCategory'] = $chCategory;
        return view('admin/categories/category', $viewData);
    }

    public function store(CategoryRequest $request) {
        $validatedData = $request->validated();

        // @HOOK_STORE_VALIDATE

        $chCategory = Category::create( array_merge([
            'site_id' => app()->make('Site')->id,
        ], $validatedData));

        // @HOOK_STORE_INSTANCE

        $chCategory->setAVars($validatedData['add']);
        $chCategory->setUri($validatedData['uri']['slug'], $validatedData['uri']['pointable_type'], $validatedData['uri']['attributes']);

        // @HOOK_STORE_END
        event( 'category.submited', [$chCategory, $validatedData] );

        return redirect()->route($this->routeNamespace.'.categories.edit', $chCategory)
            ->with('message_success', trans('admin/categories/category.created'));
    }

    public function update(Category $chCategory, CategoryRequest $request) {
        $validatedData = $request->validated();

        // @HOOK_UPDATE_VALIDATE

        $chCategory->update( $validatedData );
        $chCategory->setAVars($validatedData['add']);
        $chCategory->setUri($validatedData['uri']['slug'], $validatedData['uri']['pointable_type'], $validatedData['uri']['attributes']);

        // @HOOK_UPDATE_END

        event( 'category.submited', [$chCategory, $validatedData] );
        if($request->has('action')) {
            return redirect()->route($this->routeNamespace.'.categories.index')
                ->with('message_success', trans('admin/categories/category.updated'));
        }
        return back()->with('message_success', trans('admin/categories/category.updated'));
    }

    public function move(Category $chCategory, $direction) {
        // @HOOK_MOVE

        $chCategory->orderMove($direction);

        // @HOOK_MOVE_END

        return back();
    }

    public function destroy(Category $chCategory, Request $request) {
        // @HOOK_DESTROY
        $chCategory->delete();

        // @HOOK_DESTROY_END
        if($request->redirect_to)
            return redirect()->to($request->redirect_to)
                ->with('message_danger', trans('admin/categories/category.deleted'));

        return redirect()->route($this->routeNamespace.'.categories.index')
            ->with('message_danger', trans('admin/categories/category.deleted'));
    }
}
