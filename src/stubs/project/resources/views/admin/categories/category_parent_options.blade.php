@foreach($mainCategories as $category)
    @if(isset($chCategory) && $chCategory->id == $category->id) @continue @endif
    <option value="{{$category->id}}"
            @if($sParentId == $category->id)selected='selected'@endif
    >{!! str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level) !!}{{$category->aVar('name')}}</option>
    @php $subCategories = $category->childrenQry($subParentBldQry)->get(); @endphp
    @includeWhen( count($subCategories), 'admin/categories/category_parent_options', [
        'mainCategories' => $subCategories,
        'sParentId' => $sParentId,
        'level' => $level+1
    ])
@endforeach
