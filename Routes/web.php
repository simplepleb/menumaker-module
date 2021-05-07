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

/*Route::prefix('admin/menumaker')->group(function() {
    Route::get('/', 'Backend\MenumakerController@index');
});*/

Route::group(['prefix' => '/api/menu/'], function () {
    Route::get('/{menu_name}', 'Modules\Menumaker\Http\Controllers\Backend\MenumakerController@api_index');
});


Route::group(['namespace' => '\Modules\Menumaker\Http\Controllers\Backend', 'as' => 'backend.', 'middleware' => ['web', 'auth', 'can:view_backend'], 'prefix' => 'admin'], function () {
    /*
    * These routes need view-backend permission
    * (good if you want to allow more than one group in the backend,
    * then limit the backend features by different roles or permissions)
    *
    * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
    */

    /*
     *
     *  Exchanges Routes
     *
     * ---------------------------------------------------------------------
     */
    $module_name = 'menumaker';
    $controller_name = 'MenumakerController';

    //Route::get($module_name .'/', $controller_name.'@index');
    Route::post($module_name . '/addcustommenu', array('as' => 'addcustommenu', 'uses' => 'MenumakerController@addcustommenu'));
    Route::post($module_name . '/deleteitemmenu', array('as' => 'deleteitemmenu', 'uses' => 'MenumakerController@deleteitemmenu'));
    Route::post($module_name . '/deletemenug', array('as' => 'deletemenug', 'uses' => 'MenumakerController@deletemenug'));
    Route::post($module_name . '/createnewmenu', array('as' => 'createnewmenu', 'uses' => 'MenumakerController@createnewmenu'));
    Route::post($module_name . '/generatemenucontrol', array('as' => 'generatemenucontrol', 'uses' => 'MenumakerController@generatemenucontrol'));
    Route::post($module_name . '/updateitem', array('as' => 'updateitem', 'uses' => 'MenumakerController@updateitem'));

    Route::resource("$module_name", "$controller_name");
});
