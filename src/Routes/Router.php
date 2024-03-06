<?php

namespace Ipeweb\RecapSheets\Routes;

use Ipeweb\RecapSheets\Controller\EmailInviteController;
use Ipeweb\RecapSheets\Controller\LanguageController;
use Ipeweb\RecapSheets\Controller\ProjectController;
use Ipeweb\RecapSheets\Controller\UserController;
use Ipeweb\RecapSheets\Middleware\VerifyToken;

class Router
{
    public static function setRoutes()
    {
        // Language routes
        Route::get('/language', [LanguageController::class, 'getMessages', 'none']);

        // User routes
        Route::get('/user', [UserController::class, 'getUserByField', 'encode_response'], [new VerifyToken]);
        Route::post('/user/login', [UserController::class, 'userLogin', 'encode_response']);
        Route::post('/user', [UserController::class, 'postNewUser', 'encode_response'], [new VerifyToken]);
        Route::put('/user', [UserController::class, 'updateUser', 'encode_response'], [new VerifyToken]);

        // Project routes
        Route::get('/project', [ProjectController::class, 'getUserProjects', 'encode_response'], [new VerifyToken]);
        Route::get('/project/markdown', [ProjectController::class, 'getProjectMarkdown', 'encode_response'], [new VerifyToken]);
        Route::post('/project', [ProjectController::class, 'postNewProject', 'encode_response'], [new VerifyToken]);
        Route::put('/project', [ProjectController::class, 'updateProjectMd', 'encode_response'], [new VerifyToken]);
        Route::delete('/project', [ProjectController::class, 'inactivateProject', 'encode_response'], [new VerifyToken]);

        Route::get('/invite/user', [EmailInviteController::class, 'searchUser', 'encode_response'], [new VerifyToken]);
        Route::put('/invite/user', [EmailInviteController::class, 'setUserPermission', 'encode_response'], [new VerifyToken]);
    }
}
