<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix'=>'inicio'],function (){
	Route::get('/index',['uses'=>'InicioController@index','as'=>'inicio.index']);
	Route::get('/list',['uses'=>'InicioController@list','as'=>'inicio.list']);
	Route::post('/create',['uses'=>'InicioController@create','as'=>'inicio.create']);
	Route::get('/return/{id}',['uses'=>'InicioController@returnview','as'=>'inicio.return']);
});

Auth::routes();
Route::get('/', 'HomeController@index')->name('home');
