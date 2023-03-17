<x-admin.main>
    <div class="container-fluid">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route("{$route_namespace}.home")}}"><i class="fa fa-home"></i></a>
            </li>
            <li class="breadcrumb-item active">@lang('admin/categories/categories.categories')</li>
        </ol>


        <div class="row">
            <div class="col-12">
                @can('create', App\Models\Cateogry::class)
                    <a href="{{ route("{$route_namespace}.categories.create") }}"
                       class="btn btn-sm btn-primary h5"
                       title="create">
                        <i class="fa fa-plus mr-1"></i>@lang('admin/categories/categories.create')
                    </a>
                @endcan

                {{-- @HOOK_ADDON_LINKS --}}

            </div>
        </div>

        <x-admin.box_messages />

        <div class="table-responsive rounded ">
            <table class="table table-sm">
                <thead class="thead-light">
                <tr class="">
                    <th scope="col" class="text-center">@lang('admin/categories/categories.id')</th>
                    {{-- @HOOK_AFTER_ID_TH --}}

                    <th scope="col" class="w-60">@lang('admin/categories/categories.name')</th>
                    {{-- @HOOK_AFTER_NAME_TH --}}

                    <th scope="col" class="text-center">@lang('admin/categories/categories.edit')</th>
                    {{-- @HOOK_AFTER_EDIT_TH --}}

                    <th colspan="2" scope="col" class="text-center">@lang('admin/categories/categories.move_th')</th>
                    {{-- @HOOK_AFTER_MOVE_TH --}}

                    <th scope="col" class="text-center">@lang('admin/categories/categories.remove')</th>
                </tr>
                </thead>
                <tbody>
                @if(count($categories))
                    @include('admin/categories/categories_list', [
                        'categories' => $categories,
                        'level' => 0,
                    ])
                @else
                    <tr>
                        <td colspan="4">@lang('admin/categories/categories.no_categories')</td>
                    </tr>
                @endif
                </tbody>
            </table>

            {{$categories->links('admin.paging')}}

        </div>
    </div>
</x-admin.main>
