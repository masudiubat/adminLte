<?php

use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/console-create', function () {
    //$role = Role::create(['name' => 'super admin']);
    //$permission = Permission::create(['name' => 'user list view']);
    //$role = Role::findOrFail(1);
    //$permission = Permission::findOrFail(1);
    //$role->givePermissionTo($permission);
    //$user = User::findOrFail(1);
    //$user->givePermissionTo('create new role');
    //$userRoles = $user->permissions;
    //$user->assignRole($role);
    /*
    if ($permission) {
        echo "Successfully create permission";
    } else {
        echo "Error occure";
    } */
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/user', 'web\UserController@index')->name('user.index');
Route::post('/user/store', 'web\UserController@store')->name('user.store');
Route::get('/user/edit/{id}', 'web\UserController@edit')->name('user.edit');
Route::post('/user/update/{id}', 'web\UserController@update')->name('user.update');
Route::post('/user/password/change/{id}', 'web\UserController@change_user_password')->name('user.password.change');

/**
 *  User role and priviliges management
 
Route::group(['middleware' => ['role:super-admin|admin', 'auth']], function () {
});
 */
Route::get('/role', 'web\RoleController@index')->name('role');
Route::post('/role/store', 'web\RoleController@store')->name('role.store');
Route::post('/role/privilege/store', 'web\RoleController@privilege_store')->name('role.privilege.store');
Route::get('/check/role/permissions/{id}', 'web\RoleController@check_permissions')->name('check.role.permissions');
Route::get('/check/user/direct/permission/{id}', 'web\RoleController@check_direct_permission')->name('check.user.direct.permission');

/**
 * Activity log related routes
 */
Route::get('/activity/log', 'web\ActivityLogController@index')->name('activity.log');

/**
 *  Skill related all routes
 */
Route::get('/researcher/skill', 'web\ResearcherSkillController@index')->name('researcher.skill');
Route::post('/researcher/skill/store', 'web\ResearcherSkillController@store')->name('researcher.skill.store');
Route::get('/researcher/skill/edit/{id}', 'web\ResearcherSkillController@edit')->name('researcher.skill.edit');
Route::post('/researcher/skill/update/{id}', 'web\ResearcherSkillController@update')->name('researcher.skill.update');
Route::delete('/researcher/skill/destroy/{id}', 'web\ResearcherSkillController@destroy')->name('researcher.skill.destroy');

/**
 * Company reladed routes
 */
Route::get('/user/company', 'web\UserCompanyController@index')->name('user.company');
