<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$router->get('hello', 'Site\SiteController@hello');

$router->group(['middleware' => 'auth|auth.locale'], function () use ($router) {

    // user
    $router->group(['prefix' => 'users', 'namespace' => 'User'], function () use ($router) {
        $router->get('/', 'UserController@index');
        $router->get('/profile', 'UserController@getInfo');
        $router->get('/{id}/checkpoints', 'UserController@getListCheckpoint');
    });

    // campaign
    $router->group(['prefix' => 'campaigns', 'namespace' => 'Campaign'], function () use ($router) {
        $router->get('/', 'CampaignController@all');
        $router->post('/', 'CampaignController@store');
        $router->get('/current', 'CampaignController@getCurrentCampaign');
        $router->delete('/{id}', 'CampaignController@delete');
        $router->get('/current', 'CampaignController@getCurrentCampaign');
        $router->put('/{id}', 'CampaignController@update');
        $router->get('/{id}', 'CampaignController@show');
        $router->post('/import', 'CampaignController@import');
    });

    // checkpoint
    $router->group(['prefix' => 'checkpoints', 'namespace' => 'Checkpoint'], function () use ($router) {
        $router->post('/', 'CheckPointController@store');
        $router->get('/', 'CheckPointController@index');
        $router->get('/my-checkpoint', 'CheckPointController@myCheckpoint');
        $router->get('/total', 'CheckPointController@total');
        $router->put('/manager/assign', 'CheckPointController@assignAssessor');
        $router->put('/{id}/employee/save', 'CheckPointController@saveFormByEmployee');
        $router->put('/{id}/employee/send', 'CheckPointController@sendFormByEmployee');
        $router->put('/{id}/assessor/save', 'CheckPointController@saveFormByAssessor');
        $router->put('/{id}/assessor/send', 'CheckPointController@sendFormByAssessor');
        $router->put('/{id}/assessor/reject', 'CheckPointController@rejectFormByAssessor');
        $router->put('/{id}/manager/save', 'CheckPointController@saveFormByManager');
        $router->put('/{id}/manager/approve', 'CheckPointController@approveFormByManager');
        $router->put('/{id}/manager/reject', 'CheckPointController@rejectFormByManager');
        $router->get('/{id}', 'CheckPointController@show');
    });

    $router->group(['prefix' => 'department', 'namespace' => 'Department'], function () use ($router) {
        $router->get('/', 'DepartmentController@index');
        $router->get('/assessor', 'DepartmentController@assessor');
    });

    $router->group(['prefix' => 'site', 'namespace' => 'Site'], function () use ($router) {
        $router->get('/menu', 'SiteController@menu');
    });

    $router->group(['prefix' => 'report', 'namespace' => 'Report'], function () use ($router) {
        $router->get('/', 'ReportController@index');
    });
});
