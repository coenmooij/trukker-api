<?php

Route::prefix('authentication')->group(
    function () {
        Route::post('/register', 'AuthenticationController@register');
        Route::post('/login', 'AuthenticationController@login');
        Route::get('/logout', 'AuthenticationController@logout');
        Route::post('/forgotPassword', 'AuthenticationController@forgotPassword');
        Route::post('/resetPassword', 'AuthenticationController@resetPassword');
    }
);
