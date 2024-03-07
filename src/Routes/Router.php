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
        Route::get('/debug/env', [EnvironmentDebugger::class, 'getEnvironment', false]);

        // Language routes
        Route::get('/language', [LanguageController::class, 'getMessages', false]);

        // User routes
        Route::get('/user', [UserController::class, 'getUserByField', true], [VerifyToken::class]);
        Route::post('/user/login', [UserController::class, 'userLogin', true]);
        Route::post('/user', [UserController::class, 'postNewUser', true], [VerifyToken::class]);
        Route::put('/user', [UserController::class, 'updateUser', true], [VerifyToken::class]);

        // Project routes
        Route::get('/project', [ProjectController::class, 'getUserProjects', true], [VerifyToken::class]);
        Route::get('/project/markdown', [ProjectController::class, 'getProjectMarkdown', true], [VerifyToken::class]);
        Route::post('/project', [ProjectController::class, 'postNewProject', true], [VerifyToken::class]);
        Route::put('/project', [ProjectController::class, 'updateProjectMd', true], [VerifyToken::class]);
        Route::delete('/project', [ProjectController::class, 'inactivateProject', true], [VerifyToken::class, ValidateDeletePermission::class]);

        Route::get('/invite/user', [EmailInviteController::class, 'searchUser', true], [VerifyToken::class]);
        Route::put('/invite/user', [EmailInviteController::class, 'setUserPermission', true], [VerifyToken::class, ValidateSharePermission::class]);
    }
}