<?php

Route::prefix('authentication')->group(
    function () {
        Route::post('/login', 'AuthenticationController@login');
        Route::get('/logout', 'AuthenticationController@logout');
        Route::post('/register', 'AuthenticationController@register');
        Route::post('/registerClient', 'AuthenticationController@registerClient');
        Route::post('/resetPassword', 'AuthenticationController@resetPassword');
    }
);
