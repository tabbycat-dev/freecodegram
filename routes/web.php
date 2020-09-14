<?php

use App\Mail\NewUserWelcomeMail;
use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::post('/follow/{user}', 'FollowController@store' );


Route::get('/email', function(){
    return new NewUserWelcomeMail(); //use App\Mail\NewUserWelcomeMail;
}); //view markdown email

Route::get('/', 'PostController@index');
Route::get('/post/create', 'PostController@create');
Route::get('/post/{post}', 'PostController@show');
Route::post('/post', 'PostController@store');

Route::get('/profile/{user}', 'ProfileController@index')->name('profile.show');
Route::get('/profile/{user}/edit', 'ProfileController@edit')->name('profile.edit'); //this show edit form
Route::patch('/profile/{user}', 'ProfileController@update')->name('profile.update'); //do the process of updating action


