<?php

namespace Ipeweb\RecapSheets\Routes;

use Ipeweb\RecapSheets\Controller\EmailInviteController;
use Ipeweb\RecapSheets\Controller\EnvironmentDebugger;
use Ipeweb\RecapSheets\Controller\LanguageController;
use Ipeweb\RecapSheets\Controller\ProjectController;
use Ipeweb\RecapSheets\Controller\UserController;
use Ipeweb\RecapSheets\Middleware\ValidateDeletePermission;
use Ipeweb\RecapSheets\Middleware\ValidateSharePermission;
use Ipeweb\RecapSheets\Middleware\VerifyToken;

class Router
{
    public static function setRoutes()
    {
        // Debug route
        Route::get('/debug/env', [EnvironmentDebugger::class, 'getEnvironment']);

        // Language routes
        Route::get('/language', [LanguageController::class, 'getMessages']);

        // User routes
        Route::get('/user', [UserController::class, 'getUserByField'], [VerifyToken::class]);
        Route::get('/user/authenticate', [UserController::class, 'reauthenticateUser'], [VerifyToken::class]);
        Route::post('/user/login', [UserController::class, 'userLogin']);
        Route::post('/user', [UserController::class, 'postNewUser'], [VerifyToken::class]);
        Route::put('/user', [UserController::class, 'updateUser'], [VerifyToken::class]);

        // Project routes
        Route::get('/project', [ProjectController::class, 'getUserProjects'], [VerifyToken::class]);
        Route::get('/project/markdown', [ProjectController::class, 'getProjectMarkdown'], [VerifyToken::class]);
        Route::post('/project', [ProjectController::class, 'postNewProject'], [VerifyToken::class]);
        Route::put('/project', [ProjectController::class, 'updateProjectMd'], [VerifyToken::class]);
        Route::delete('/project', [ProjectController::class, 'inactivateProject'], [VerifyToken::class, ValidateDeletePermission::class]);

        Route::get('/invite/user', [EmailInviteController::class, 'searchUser'], [VerifyToken::class]);
        Route::put('/invite/user', [EmailInviteController::class, 'setUserPermission'], [VerifyToken::class, ValidateSharePermission::class]);
    }
}
