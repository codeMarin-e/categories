@pushonce('below_templates')
@if(isset($chCategory) && $authUser->can('delete', $chCategory))
    <form action="{{ route("{$route_namespace}.categories.destroy", $chCategory) }}"
          method="POST"
          id="delete[{{$chCategory->id}}]">
        @csrf
        @method('DELETE')
    </form>
@endif
@endpushonce

<x-admin.main>
    @php $inputBag = 'category'; @endphp
    <div class="container-fluid">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route("{$route_namespace}.home")}}"><i class="fa fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route("{$route_namespace}.categories.index") }}">@lang('admin/categories/categories.categories')</a></li>
            <li class="breadcrumb-item active">@isset($chCategory){{ $chCategory->id }}@else @lang('admin/categories/category.create') @endisset</li>
        </ol>

        <div class="card">
            <div class="card-body">
                <form action="@isset($chCategory){{ route("{$route_namespace}.categories.update", $chCategory->id) }}@else{{ route("{$route_namespace}.categories.store") }}@endisset"
                      method="POST"
                      autocomplete="off"
                      enctype="multipart/form-data">
                    @csrf
                    @isset($chCategory)@method('PATCH')@endisset

                    <x-admin.box_messages />

                    <x-admin.box_errors :inputBag="$inputBag" />

                    @php
                        $sParentId = old("{$inputBag}.parent_id", (isset($chCategory)? $chCategory->parent_id : 0));
                    @endphp
                    <div class="form-group row">
                        <label for="{{$inputBag}}[parent_id]"
                               class="col-sm-2 col-form-label">@lang('admin/categories/category.parent_id'):</label>
                        <div class="col-sm-4">
                            <select class="form-control"
                                    id="{{$inputBag}}[parent_id]"
                                    name="{{$inputBag}}[parent_id]">
                                <option value="0"
                                        @if(!$sParentId)selected="selected"@endif
                                >@lang('admin/categories/category.parent_id_none')</option>
                                @includeWhen(count($mainCategories), 'admin/categories/category_parent_options', [
                                    'mainCategories' => $mainCategories,
                                    'sParentId' => $sParentId,
                                    'level' => 0
                                ])
                            </select>
                        </div>
                    </div>
                    {{-- @HOOK_AFTER_PARENT --}}

                    <div class="form-group row">
                        <label for="{{$inputBag}}[name]"
                               class="col-sm-2 col-form-label"
                        >@lang('admin/categories/category.name'):</label>
                        <div class="col-sm-10">
                            <input type="text"
                                   name="{{$inputBag}}[add][name]"
                                   id="{{$inputBag}}[add][name]"
                                   value="{{ old("{$inputBag}.add.name", (isset($chCategory)? $chCategory->aVar('name') : '')) }}"
                                   class="form-control @if($errors->$inputBag->has('add.name')) is-invalid @endif"
                            />
                        </div>
                    </div>

                    {{-- @HOOK_AFTER_NAME --}}

                    <div class="form-group row">
                        <label for="{{$inputBag}}[add][description]"
                               class="col-sm-2 col-form-label @if($errors->$inputBag->has('add.description')) text-danger @endif"
                        >@lang('admin/categories/category.description'):</label>
                        <div class="col-sm-10">
                            <x-admin.editor
                                :inputName="$inputBag.'[add][description]'"
                                :otherClasses="[ 'form-controll', ]"
                            >{{old("{$inputBag}.add.content", (isset($chCategory)? $chCategory->aVar('description') : ''))}}</x-admin.editor>
                        </div>
                    </div>
                    {{-- @HOOK_AFTER_DESCRIPTION --}}

                    <x-admin.uriable
                        :inputBag="$inputBag"
                        :uriable="$chCategory?? null"
                        :defaultUri="isset($chCategory)? $chCategory->defaultUri() : 'categories/[id]'"
                    />

                    {{-- @HOOK_AFTER_URI --}}

                    <div class="form-group row form-check">
                        <div class="col-lg-6">
                            <input type="checkbox"
                                   value="1"
                                   id="{{$inputBag}}[active]"
                                   name="{{$inputBag}}[active]"
                                   class="form-check-input @if($errors->$inputBag->has('active'))is-invalid @endif"
                                   @if(old("{$inputBag}.active") || (is_null(old("{$inputBag}.active")) && isset($chCategory) && $chCategory->active ))checked="checked"@endif
                            />
                            <label class="form-check-label"
                                   for="{{$inputBag}}[active]">@lang('admin/categories/category.active')</label>
                        </div>
                    </div>

                    {{-- @HOOK_AFTER_CHECKBOXES --}}

                    <div class="form-group row">
                        @isset($chCategory)
                            @can('update', $chCategory)
                                <button class='btn btn-success mr-2'
                                        type='submit'
                                        name='action'>@lang('admin/categories/category.save')
                                </button>

                                <button class='btn btn-primary mr-2'
                                        type='submit'
                                        name='update'>@lang('admin/categories/category.update')</button>
                            @endcan

                            @can('delete', $chCategory)
                                <button class='btn btn-danger mr-2'
                                        type='button'
                                        onclick="if(confirm('@lang("admin/categories/category.delete_ask")')) document.querySelector( '#delete\\[{{$chCategory->id}}\\] ').submit() "
                                        name='delete'>@lang('admin/categories/category.delete')</button>
                            @endcan
                        @else
                            @can('create', App\Models\Category::class)
                                <button class='btn btn-success mr-2'
                                        type='submit'
                                        name='create'>@lang('admin/categories/category.create')</button>
                            @endcan
                        @endisset

                        {{-- @HOOK_AFTER_BUTTONS --}}

                        <a class='btn btn-warning'
                           href="{{ route("{$route_namespace}.categories.index") }}"
                        >@lang('admin/categories/category.cancel')</a>
                    </div>

                    {{-- @HOOK_ADDON_BUTTONS --}}
                </form>
            </div>
        </div>

        {{-- @HOOK_ADDONS --}}
    </div>
</x-admin.main>
