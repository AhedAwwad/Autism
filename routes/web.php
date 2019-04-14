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

Route::get('/', function () {
    return view('welcome');
});

//home_page
Route::get('/index','controlpage@index');
//register_route
Route::get('/register','controlpage@register');
Route::post('/register1','controlpage@registerstore');
//login_route
Route::get('/login','controlpage@login');
Route::post('/login1','controlpage@loginstore');
Route::get('/login','controlpage@login');
//logout
Route::get('/logout','controlpage@logout');
//display Autism Children
Route::get('/display/{sp_id}','controlpage@display');
//chat
Route::get('/chat/{sp_id}','controlpage@chat');
Route::get('/delete/{ch_id}','controlpage@delete');

Route::get('/xx','controlpage@xx');
Route::get('/aa','controlpage@aa');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
