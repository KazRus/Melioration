<?php


/******* Admin page *******/
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => 'web'
], function() {

    Route::any('/login', 'AuthController@login');
    Route::get('/logout', 'AuthController@logout');

    Route::post('category/is_show', 'CategoryController@changeIsShow');
    Route::resource('category', 'CategoryController');

    Route::post('review/is_show', 'ReviewController@changeIsShow');
    Route::resource('review', 'ReviewController');

    Route::get('product/image/crop/show', 'ProductController@getCropImage');
    Route::get('product/image/crop', 'ProductController@saveCropImage');
    Route::get('product/category', 'ProductController@getCategoryByParent');
    Route::post('product/is_show', 'ProductController@changeIsShow');
    Route::resource('product', 'ProductController');

    Route::post('brand/is_show', 'BrandController@changeIsShow');
    Route::resource('brand', 'BrandController');

    Route::post('news/is_show', 'NewsController@changeIsShow');
    Route::resource('news', 'NewsController');

    Route::post('blog/is_show', 'BlogController@changeIsShow');
    Route::resource('blog', 'BlogController');
    
    Route::post('menu/is_show', 'MenuController@changeIsShow');
    Route::resource('menu', 'MenuController');

    Route::get('user/reset/{id}', 'UsersController@resetPassword');
    Route::post('user/is_show', 'UsersController@changeIsBan');
    Route::resource('user', 'UsersController');
    Route::any('password', 'UsersController@password');

    Route::get('index', 'IndexController@index');
});


/******* Main page *******/
Route::group([
    'middleware' => 'web'
], function() {
    Route::post('image/upload', 'ImageController@uploadImage');
    Route::get('media/{file_name}', 'ImageController@getImage')->where('file_name', '.*');
});


/******* Index *******/
Route::group([
    'middleware' => 'web',
    'namespace' => 'Index',
], function() {

    Route::get('/', 'IndexController@index');
    Route::get('contact', 'IndexController@showContact');
    Route::get('news', 'IndexController@showNews');
    Route::get('news-detail', 'IndexController@showNewsDetail');
    Route::get('gallery', 'IndexController@showGallery');
    Route::get('services', 'IndexController@showServices');
    Route::get('about', 'IndexController@showAbout');

});