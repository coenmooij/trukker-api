<?php

Route::prefix('authentication')->group(
    function () {
        Route::post('/login', 'AuthenticationController@login');
        Route::post('/register', 'AuthenticationController@register');
        Route::post('/registerClient', 'AuthenticationController@registerClient');
        Route::post('/resetPassword', 'AuthenticationController@resetPassword');
        Route::get('/logout', 'AuthenticationController@logout')->middleware('auth');
    }
);

Route::middleware('auth')->group(
    function () {
        Route::get('/jobProfiles', 'JobProfileController@getAll');
        Route::post('/jobProfiles', 'JobProfileController@post');
        Route::get('/jobProfiles/{id}', 'JobProfileController@get');

        Route::get('/jobProfiles/{id}/shifts', 'ShiftController@getAll');
        Route::get('/shifts/{id}', 'ShiftController@get');
    }
);
