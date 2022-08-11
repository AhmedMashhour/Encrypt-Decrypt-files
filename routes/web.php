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
    return view('encrypt');
})->name('home');
Route::post('/encrypt-file','EncryptController@encryptFile')->name('encrypt.encrypt-file');
Route::post('/decrypt-file','DecryptController@decryptFile')->name('decrypt.decrypt-file');
