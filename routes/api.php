<?php

Route::resource('categories', 'Categories\CategoryController');
Route::resource('products', 'Products\ProductController');
Route::resource('addresses', 'Address\AddressController');
Route::resource('countries', 'Country\CountryController');
Route::get('addresses/{address}/shipping', 'Address\ShippingController@action');

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', 'Auth\RegisterController@register');
    Route::post('login', 'Auth\LoginController@login');
    Route::get('me', 'Auth\MeController@action');

});

Route::resource('carts', 'Cart\CartController', [
    'parameters' => [
        'carts' => 'productVariation'
        ]
]);
