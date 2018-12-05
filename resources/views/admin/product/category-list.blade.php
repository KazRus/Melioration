
@if($type == 'parent')

    <select class="form-control select2" onchange="getCategoryListByParent(this,'child')">

        <option value="">Выберите</option>
        @foreach($categories as $key => $item)

            <option value="{{$item['category_id']}}" >{{$item['category_name_ru']}}</option>

        @endforeach

    </select>


@else

    <select class="form-control select2" name="category_id" @if(count($categories) == 0) style="display: none" @endif>

        <option value="{{$category_id}}"></option>
        @foreach($categories as $key => $item)

            <option value="{{$item['category_id']}}" >{{$item['category_name_ru']}}</option>

        @endforeach

    </select>

@endif

