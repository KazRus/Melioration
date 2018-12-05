@extends('admin.layout.layout')

@section('content')

    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-8 col-8 align-self-center">
                <h3 class="text-themecolor m-b-0 m-t-0" >
                    {{ $title }}
                </h3>
            </div>
            <div class="col-md-4 col-4 align-self-center text-right">
                <a href="/admin/blog" class="btn waves-effect waves-light btn-danger pull-right hidden-sm-down"> Назад</a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="card-block">
                        @if (isset($error))
                            <div class="alert alert-danger">
                                {{ $error }}
                            </div>
                        @endif
                        @if($row->blog_id > 0)
                            <form action="/admin/blog/{{$row->blog_id}}" method="POST">
                                <input type="hidden" name="_method" value="PUT">
                                @else
                                    <form action="/admin/blog" method="POST">
                                        @endif
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="blog_id" value="{{ $row->blog_id }}">
                                        <input type="hidden" class="image-name" id="blog_image" name="blog_image" value="{{ $row->blog_image }}"/>

                                        <div class="box-body">
                                            <div class="form-group">
                                                <label>Название</label>
                                                <input value="{{ $row->blog_name_ru }}" type="text" class="form-control" name="blog_name_ru" placeholder="">
                                            </div>
                                            <div class="form-group">
                                                <label>Краткое описание</label>
                                                <textarea name="blog_desc_ru" class="ckeditor form-control"><?=$row->blog_desc_ru?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Отображать на главной странице</label>
                                                <select name="is_show_main" data-placeholder="Выберите" class="form-control">
                                                    <option @if($row->is_show_main == 0) selected @endif value="0">Нет</option>
                                                    <option @if($row->is_show_main == 1) selected @endif value="1">Да</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Категория</label>
                                                <select name="category_id" data-placeholder="Выберите" class="form-control">
                                                    @foreach($categories as $item)
                                                        <option @if($item->category_id == $row->category_id) selected @endif value="{{$item->category_id}}">{{$item->category_name_ru}}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Дата</label>
                                                <input id="date-format" value="{{ $row->blog_date }}" type="text" class="form-control datetimepicker-input" name="blog_date" placeholder="">
                                            </div>
                                        </div>
                                        <div class="box-footer">
                                            <button type="submit" class="btn btn-primary">Сохранить</button>
                                        </div>
                                    </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-block">
                        <div class="box box-primary" style="padding: 30px; text-align: center">
                            <div style="padding: 20px; border: 1px solid #c2e2f0">
                                <img class="image-src" src="{{ $row->blog_image }}" style="width: 100%; "/>
                            </div>
                            <div style="background-color: #c2e2f0;height: 40px;margin: 0 auto;width: 2px;"></div>
                            <form id="image_form" enctype="multipart/form-data" method="post" class="image-form">
                                <i class="fa fa-plus"></i>
                                <input id="avatar-file" type="file" onchange="uploadImage()" name="image"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('js')


    <script>

        $('#date-format').bootstrapMaterialDatePicker({ format: 'DD.MM.YYYY HH:mm' });

    </script>

@endsection



