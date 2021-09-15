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
    return redirect()->route('login');
});

Route::get('/console-create', function () {
    //$role = Role::create(['name' => 'super admin']);
    //$permission = Permission::create(['name' => 'Show user list']);
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

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

/**
 *  Researcher application related routes
 */
Route::get('/researcher/application/create', 'web\ResearcherApplicationController@create')->name('researcher.application.create');
Route::post('/researcher/application/store', 'web\ResearcherApplicationController@store')->name('researcher.application.store');

Route::group(['middleware' => ['auth']], function () {
    /**
     *  User related routes
     */
    Route::get('/user', 'web\UserController@index')->name('user.index');
    Route::post('/user/store', 'web\UserController@store')->name('user.store');
    Route::get('/user/edit/{id}', 'web\UserController@edit')->name('user.edit');
    Route::post('/user/update/{id}', 'web\UserController@update')->name('user.update');
    Route::post('/user/password/change/{id}', 'web\UserController@change_user_password')->name('user.password.change');

    Route::get('/personal/profile/detail/{id}', 'web\PersonalProfileController@show')->name('personal.profile.detail');
    /**
     *  Roles and Permissions related routes
     */
    Route::get('/role', 'web\RoleController@index')->name('role');
    Route::post('/role/store', 'web\RoleController@store')->name('role.store');
    Route::post('/role/privilege/store', 'web\RoleController@privilege_store')->name('role.privilege.store');
    Route::get('/check/role/permissions/{id}', 'web\RoleController@check_permissions')->name('check.role.permissions');
    Route::get('/check/user/direct/permission/{id}', 'web\RoleController@check_direct_permission')->name('check.user.direct.permission');
    Route::get('/change/user/role/{id}', 'web\RoleController@change_user_role')->name('change.user.role');
    Route::post('/change/user/role/store/{id}', 'web\RoleController@change_role_store')->name('change.user.role.store');

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
     * Organization reladed routes
     */
    Route::get('/organization', 'web\OrganizationController@index')->name('organization');
    Route::post('/organization/store', 'web\OrganizationController@store')->name('organization.store');
    Route::delete('/organization/destroy/{id}', 'web\OrganizationController@destroy')->name('organization.destroy');
    Route::get('/organization/edit/{id}', 'web\OrganizationController@edit')->name('organization.edit');
    Route::post('/organization/update/{id}', 'web\OrganizationController@update')->name('organization.update');


    /**
     * Social media relaed routes
     */
    Route::get('/social/media', 'web\SocialMediaController@index')->name('social.media');
    Route::post('/social/media/store', 'web\SocialMediaController@store')->name('social.media.store');
    Route::get('/social/media/edit/{id}', 'web\SocialMediaController@edit')->name('social.media.edit');
    Route::post('/social/media/update/{id}', 'web\SocialMediaController@update')->name('social.media.update');
    Route::delete('/social/media/destroy/{id}', 'web\SocialMediaController@destroy')->name('social.media.destroy');

    /**
     * Assign user to organization related routes
     */
    Route::get('/assign/organization/member', 'web\OrganizationMemberController@index')->name('assign.organization.member');
    Route::post('/assign/organization/member/store', 'web\OrganizationMemberController@store')->name('assign.organization.member.store');
    Route::get('/assign/organization/member/edit/{id}', 'web\OrganizationMemberController@edit')->name('assign.organization.member.edit');
    Route::post('/assign/organization/member/update/{id}', 'web\OrganizationMemberController@update')->name('assign.organization.member.update');
    Route::delete('/assign/organization/member/destroy/{id}', 'web\OrganizationMemberController@destroy')->name('assign.organization.member.destroy');

    Route::get('/admin/project', 'web\ProjectController@index')->name('admin.project')->middleware(['role:admin']);
    Route::get('/admin/project/create', 'web\ProjectController@create')->name('admin.project.create')->middleware(['role:admin']);
    Route::get('/organization/project/', 'web\OrganizationProjectController@index')->name('organization.project')->middleware(['role:admin|client']);
    Route::get('/organization/project/create', 'web\OrganizationProjectController@create')->name('organization.project.create')->middleware(['role:admin|client']);
});
