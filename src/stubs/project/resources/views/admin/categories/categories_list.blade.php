@php
    $levelColoring = [
        'name' => [ 'primary', 'warning', 'dark' ]
    ];
    $levelColoring['ord'] = $levelColoring['name'];
@endphp

@pushonceOnReady('below_js_on_ready')
<script>
    function toggleChilds($parent, visible) {
        if (visible) {
            $('tr[data-parent="' + $parent.attr('data-id') + '"]').each(function (index, el) {
                var $el = $(el);
                toggleChilds($el, parseInt($el.attr('data-show')));
                $el.show();
            });
            return;
        }

        $('tr[data-parent="' + $parent.attr('data-id') + '"]').each(function (index, el) {
            var $el = $(el);
            toggleChilds($el, 0);
            $el.hide();
        });
    }

    $(document).on('click', '.js_childs_toggle', function (e) {
        e.preventDefault();
        var $this = $(this);
        var $thistr = $this.parents('tr').first();
        visible = parseInt($thistr.attr('data-show')) ? 0 : 1; //reverse;
        toggleChilds($thistr, visible);
        $thistr.attr('data-show', visible);
        $this.html(visible ?
            '<i class="fa fa-angle-down"></i>' : '<i class="fa fa-angle-up"></i>'
        );
    });
</script>
@endpushonceOnReady

@foreach($categories as $category)
    @php
        $categoryEditUri = route("{$route_namespace}.categories.edit", $category);
        $subCategories = $category->childrenQry($subBldQry)->get();
    @endphp
    @if($loop->first)
        @php $prevCategory = $category->getPrevious(); @endphp
    @endif
    @if($loop->last)
        @php $nextCategory = $category->getNext(); @endphp
    @endif
    <tr data-id="{{$category->id}}"
        data-parent="{{$category->parent_id}}"
        data-show="1">
        <td scope="row" class="text-center align-middle"><a href="{{ $categoryEditUri }}"
                                                            title="@lang('admin/categories/categories.edit')"
            >{{ $category->id }}</a></td>
        {{-- @HOOK_AFTER_ID --}}

        <td class="w-60 align-middle">
            {!! str_repeat('<i class="fa fa-arrow-right text-success mr-2"></i>', $level) !!}
            <a href="{{ $categoryEditUri }}"
               title="@lang('admin/categories/categories.edit')"
               class=" @if($category->active) text-{{$levelColoring['name'][$level%count($levelColoring['name'])]}} @else text-danger @endif"
            >{{ \Illuminate\Support\Str::words($category->aVar('name'), 12,'....') }}</a>
            @if(count($subCategories))
                <a href="#"
                   class="js_childs_toggle text-dark"
                   data-show="1"
                   data-id="{{$category->id}}"><i class="fa fa-angle-down"></i></a>
            @endif
        </td>
        {{-- @HOOK_AFTER_NAME --}}

        {{--    EDIT    --}}
        <td class="text-center">
            <a class="btn btn-link text-success"
               href="{{ $categoryEditUri }}"
               title="@lang('admin/categories/categories.edit')"><i class="fa fa-edit"></i></a></td>
        {{-- @HOOK_AFTER_EDIT--}}

        {{--    MOVE DOWN    --}}
        <td class="text-center">
            @if($authUser->can('update', $category) && (!$loop->last || $nextCategory))
                <a class="btn btn-link text-{{$levelColoring['ord'][$level%count($levelColoring['ord'])]}}"
                   href="{{route("{$route_namespace}.categories.move", [$category, 'down'])}}"
                   title="@lang('admin/categories/categories.move_down')"><i class="fa fa-arrow-down"></i></a>
            @endif
        </td>

        {{--    MOVE UP   --}}
        <td class="text-center">
            @if($authUser->can('update', $category) && (!$loop->first || $prevCategory))
                <a class="btn btn-link text-{{$levelColoring['ord'][$level%count($levelColoring['ord'])]}}"
                   href="{{route("{$route_namespace}.categories.move", [$category,'up'])}}"
                   title="@lang('admin/categories/categories.move_up')"><i class="fa fa-arrow-up"></i></a>
            @endif
        </td>

        {{-- @HOOK_AFTER_MOVE --}}

        {{--    DELETE    --}}
        <td class="text-center">
            @can('delete', $category)
                <form action="{{ route("{$route_namespace}.categories.destroy", $category->id) }}"
                      method="POST"
                      id="delete[{{$category->id}}]">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-link text-danger"
                            title="@lang('admin/categories/categories.remove')"
                            onclick="if(confirm('@lang("admin/categories/categories.remove_ask")')) document.querySelector( '#delete\\[{{$category->id}}\\] ').submit() "
                            type="button"><i class="fa fa-trash"></i></button>
                </form>
            @endcan
        </td>
    </tr>
    @includeWhen(count($subCategories), 'admin/categories/categories_list', [
        'categories' => $subCategories,
        'level' => $level+1
    ])
@endforeach
