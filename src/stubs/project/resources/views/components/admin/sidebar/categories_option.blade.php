@if($authUser->can('view', \App\Models\Category::class))
    {{--   CATEGORIES --}}
    <li class="nav-item @if(request()->route()->named("{$whereIam}.categories.*")) active @endif">
        <a class="nav-link " href="{{route("{$whereIam}.categories.index")}}">
            <i class="fa fa-fw fa-list mr-1"></i>
            <span>@lang("admin/categories/categories.sidebar")</span>
        </a>
    </li>
@endif
