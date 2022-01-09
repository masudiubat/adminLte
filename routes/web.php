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


Route::get('/otp/verification/show', 'web\OtpVerificationController@show')->name('otp.verification.show');
Route::post('/otp/verification/send', 'web\OtpVerificationController@verify_otp')->name('otp.verification.send');
Route::get('/opt/code/resend', 'web\OtpVerificationController@resend_code')->name('otp.code.resend');
/**
 *  Researcher application related routes
 */
Route::get('/researcher/application/create', 'web\ResearcherApplicationController@create')->name('researcher.application.create');
Route::post('/researcher/application/store', 'web\ResearcherApplicationController@store')->name('researcher.application.store');

Route::group(['middleware' => ['auth', 'TwoFA']], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    /**
     *  User related routes
     */
    Route::get('/user', 'web\UserController@index')->name('user.index');
    Route::post('/user/store', 'web\UserController@store')->name('user.store');
    Route::get('/user/edit/{id}', 'web\UserController@edit')->name('user.edit');
    Route::post('/user/update/{id}', 'web\UserController@update')->name('user.update');
    Route::post('/user/password/change/{id}', 'web\UserController@change_user_password')->name('user.password.change');

    Route::get('/personal/profile/detail/{id}', 'web\PersonalProfileController@show')->name('personal.profile.detail');
    Route::post('/personal/profile/change/password/{id}', 'web\PersonalProfileController@change_password')->name('personal.profile.change.password');
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

    /**
     * Project Related Routes
     */
    Route::get('/scope', 'web\ScopeController@index')->name('scope')->middleware(['role:admin']);
    Route::post('/scope/store', 'web\ScopeController@store')->name('scope.store')->middleware(['role:admin']);
    Route::get('/scope/edit/{id}', 'web\ScopeController@edit')->name('scope.edit')->middleware(['role:admin']);
    Route::post('/scope/update/{id}', 'web\ScopeController@update')->name('scope.update')->middleware(['role:admin']);
    Route::delete('/scope/destroy/{id}', 'web\ScopeController@destroy')->name('scope.destroy')->middleware(['role:admin']);

    /**
     * Report Category related routes
     */
    Route::get('/report/category', 'web\ReportCategoryController@index')->name('report.category')->middleware(['role:admin']);
    Route::post('/report/category', 'web\ReportCategoryController@store')->name('report.category.store')->middleware(['role:admin']);
    Route::get('/report/category/show/{id}', 'web\ReportCategoryController@show')->name('report.category.show')->middleware(['role:admin']);
    Route::get('/report/category/edit/{id}', 'web\ReportCategoryController@edit')->name('report.category.edit')->middleware(['role:admin']);
    Route::post('/report/category/{id}', 'web\ReportCategoryController@update')->name('report.category.update')->middleware(['role:admin']);
    Route::delete('/report/category/{id}', 'web\ReportCategoryController@destroy')->name('report.category.destroy')->middleware(['role:admin']);
    /**
     * Project Related Routes
     */
    Route::get('/admin/project/index', 'web\AdminProjectController@index')->name('admin.project.index')->middleware(['role:admin']);
    Route::get('/admin/project/create', 'web\AdminProjectController@create')->name('admin.project.create')->middleware(['role:admin']);
    Route::post('/admin/project/store', 'web\AdminProjectController@store')->name('admin.project.store')->middleware(['role:admin']);
    Route::get('/admin/project/search/member/{id}', 'web\AdminProjectController@search_member')->name('admin.project.search.member')->middleware(['role:admin']);
    Route::get('/admin/project/current', 'web\AdminProjectController@current_project')->name('admin.project.current')->middleware(['role:admin']);
    Route::get('/admin/project/upcoming', 'web\AdminProjectController@upcoming_project')->name('admin.project.upcoming')->middleware(['role:admin']);
    Route::get('/admin/project/archieve', 'web\AdminProjectController@archieve_project')->name('admin.project.archieve')->middleware(['role:admin']);
    Route::get('/admin/project/show/{id}', 'web\AdminProjectController@show')->name('admin.project.show')->middleware(['role:admin']);
    Route::delete('/admin/project/destroy/{id}', 'web\AdminProjectController@destroy')->name('admin.project.destroy')->middleware(['role:admin']);

    /**
     *  Admin check report related routes
     */
    Route::get('/admin/report/create', 'web\AdminProjectReportController@create')->name('admin.report.create')->middleware(['role:admin|moderator']);
    Route::post('/admin/report/store', 'web\AdminProjectReportController@store')->name('admin.report.store')->middleware(['role:admin|moderator']);
    Route::get('/admin/report/current', 'web\AdminProjectReportController@index')->name('admin.report.current')->middleware(['role:admin|moderator']);
    Route::get('/admin/report/show/{id}', 'web\AdminProjectReportController@show')->name('admin.report.show')->middleware(['role:admin|moderator']);
    Route::get('/admin/report/edit/{id}', 'web\AdminProjectReportController@edit')->name('admin.report.edit')->middleware(['role:admin|moderator']);
    Route::post('/admin/report/update/{id}', 'web\AdminProjectReportController@update')->name('admin.report.update')->middleware(['role:admin|moderator']);
    Route::get('/admin/report/send/archive/{id}', 'web\AdminProjectReportController@report_send_archive')->name('admin.report.send.archive')->middleware(['role:admin|moderator']);
    Route::get('/admin/report/send/index/{id}', 'web\AdminProjectReportController@report_send_index')->name('admin.report.send.index')->middleware(['role:admin|moderator']);
    Route::get('/admin/report/archieve', 'web\AdminProjectReportController@archieve')->name('admin.report.archieve')->middleware(['role:admin|moderator']);
    /**
     * Researcher routes
     */
    Route::get('/client/project/index', 'web\ClientProjectController@index')->name('client.project.index')->middleware(['role:client']);
    Route::get('/client/project/create', 'web\ClientProjectController@create')->name('client.project.create')->middleware(['role:client']);
    Route::post('/client/project/store', 'web\ClientProjectController@store')->name('client.project.store')->middleware(['role:client']);
    Route::get('/client/project/search/member', 'web\ClientProjectController@search_member')->name('client.project.search.member')->middleware(['role:client']);
    Route::get('/client/project/show/{id}', 'web\ClientProjectController@show')->name('client.project.show')->middleware(['role:client']);
    /**
     * Researcher routes
     */
    Route::get('/researcher/project/all', 'web\ResearcherProjectController@index')->name('researcher.project.all')->middleware(['role:researcher']);
    Route::get('/researcher/project/show/{id}', 'web\ResearcherProjectController@show')->name('researcher.project.show')->middleware(['role:researcher']);
    Route::get('/researcher/project/current', 'web\ResearcherProjectController@current_project')->name('researcher.project.current')->middleware(['role:researcher']);
    Route::get('/researcher/project/upcoming', 'web\ResearcherProjectController@upcoming_project')->name('researcher.project.upcoming')->middleware(['role:researcher']);
    Route::get('/researcher/project/archieve', 'web\ResearcherProjectController@archieve_project')->name('researcher.project.archieve')->middleware(['role:researcher']);
    Route::get('/researcher/project/unapproved', 'web\ResearcherProjectController@unapproved_project')->name('researcher.project.unapproved')->middleware(['role:researcher']);

    /**
     * Researcher Report related routes
     */
    Route::get('/researcher/new/report', 'web\ResearcherReportController@create')->name('researcher.new.report')->middleware(['role:researcher|admin']);
    Route::get('/project/scopes/search/{id}', 'web\ResearcherReportController@search_scopes')->name('project.scopes.search')->middleware(['role:researcher|admin']);
    Route::post('/researcher/report/store', 'web\ResearcherReportController@store')->name('researcher.report.store')->middleware(['role:researcher|admin']);
    Route::post('/researcher/report/files/upload', 'web\ResearcherReportController@files_upload')->name('researcher.report.files.upload')->middleware(['role:researcher|admin']);
    Route::get('/temp/image/delete/{id}', 'web\ResearcherReportController@temp_image_delete')->name('temp.image.delete')->middleware(['role:admin|researcher']);
    Route::get('/researcher/all/reports', 'web\ResearcherReportController@index')->name('researcher.all.reports')->middleware(['role:researcher|admin']);
    Route::get('/researcher/report/show/{id}', 'web\ResearcherReportController@show')->name('researcher.report.show')->middleware(['role:researcher|admin']);
    Route::get('/researcher/report/edit/{id}', 'web\ResearcherReportController@edit')->name('researcher.report.edit')->middleware(['role:researcher']);
    Route::get('/researcher/report/pdf/download/{id}', 'web\ResearcherReportController@dwonlaod_pdf')->name('researcher.report.pdf.download')->middleware(['role:researcher|admin']);
});
