@extends('index.layout.body')

@section('meta-tags')

    <title>Главная страница</title>
    <meta name="description" content="Desc">
    <meta name="keywords" content="keywords">

@endsection


@section('content')

    @include('index.layout.header')

    @include('index.index.slider')

    @include('index.index.about')

    @include('index.index.services')

    @include('index.index.gallery')

    @include('index.index.employees')

    @include('index.index.review')

    @include('index.index.news')

@endsection


