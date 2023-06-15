<?php

use Illuminate\Support\Facades\Route;
use DmLogic\GooglePhotoSlideshow\Http\Controllers\ImageController;
use DmLogic\GooglePhotoSlideshow\Http\Controllers\SlideshowContoller;
use DmLogic\GooglePhotoSlideshow\Http\Controllers\GoogleOAuthController;

Route::get('/', function () {
    return view('photos::slideshow');
});

Route::controller(SlideshowContoller::class)->group(function () {
    Route::get('/photo/random', 'random');
    Route::get('/photo/{id}', 'single');
    Route::get('/album/{album}/photo/{photo?}', 'albumImage');
});

Route::get('/image/{path}', ImageController::class)
     ->where('path', '[A-Za-z_0-9-\/\.]+');
;

Route::get('/google-oauth/start', [GoogleOAuthController::class, 'start'])
     ->name('oauth.start');
Route::post('/google-oauth/request', [GoogleOAuthController::class, 'generateRequest'])
     ->name('oauth.generate');
Route::get('/google-oauth/handle', [GoogleOAuthController::class, 'handleRedirect'])
     ->name('oauth.handle');
