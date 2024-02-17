<?php

namespace Ipeweb\IpeSheets\Routes;

use Ipeweb\IpeSheets\Controller\LanguageController;
use Ipeweb\IpeSheets\Controller\ProjectController;
use Ipeweb\IpeSheets\Controller\UserController;

class Router
{
    public static function setRoutes()
    {
        // Language routes
        Route::get('/language/', [LanguageController::class, 'getMessages', 'none']);

        // User routes
        Route::get('/user/', [UserController::class, 'getUserByField', 'encode_response']);
        Route::post('/user/login/', [UserController::class, 'userLogin', 'encode_response']);
        Route::post('/user/', [UserController::class, 'postNewUser', 'encode_response']);
        Route::put('/user/', [UserController::class, 'updateUser', 'encode_response']);

        // Project routes
        Route::get('/project/', [ProjectController::class, 'getUserProjects', 'encode_response']);
        Route::post('/project/', [ProjectController::class, 'postNewProject', 'encode_response']);
    }
}
