<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'App\Http\Controllers\HomeController@index');
Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');
Route::post('/filter', 'App\Http\Controllers\HomeController@search')->name('search_date');

Route::get('/config', 'App\Http\Controllers\ConfigController@index')->name('config');
Route::put('/config/update/{id}', 'App\Http\Controllers\ConfigController@update')->name('config.update');

Route::group(['namespace' => 'App\Http\Controllers\Profile'], function () {
	Route::get('/profile', 'ProfileController@index')->name('profile');
	Route::get('/edit/profile/{id}', 'ProfileController@editProfileLoad')->name('profile.edit');
	Route::post('/update/profile/{id}', 'ProfileController@updateAdminProfile')->name('profile.update');
	Route::get('/update/password/{id}', 'ProfileController@passwordUpdate')->name('update.password');
	Route::put('/profile/update/profile/{id}', 'ProfileController@updateProfile')->name('profile.update.profile');
	Route::put('/profile/update/password/{id}', 'ProfileController@updatePassword')->name('profile.update.password');
	Route::put('/profile/update/avatar/{id}', 'ProfileController@updateAvatar')->name('profile.update.avatar');
});

Route::group(['namespace' => 'App\Http\Controllers\Error'], function () {
	Route::get('/unauthorized', 'ErrorController@unauthorized')->name('unauthorized');
});

Route::group(['namespace' => 'App\Http\Controllers\User'], function () {
	//Users
	Route::get('/user', 'UserController@index')->name('user');
	Route::get('/user/create', 'UserController@create')->name('user.create');
	Route::post('/user/store', 'UserController@store')->name('user.store');
	Route::get('/user/edit/{id}', 'UserController@edit')->name('user.edit');
	Route::put('/user/update/{id}', 'UserController@update')->name('user.update');
	Route::get('/user/edit/password/{id}', 'UserController@editPassword')->name('user.edit.password');
	Route::put('/user/update/password/{id}', 'UserController@updatePassword')->name('user.update.password');
	Route::get('/user/show/{id}', 'UserController@show')->name('user.show');
	Route::get('/user/destroy/{id}', 'UserController@destroy')->name('user.destroy');
	// Roles
	Route::get('/role', 'RoleController@index')->name('role');
	Route::get('/role/create', 'RoleController@create')->name('role.create');
	Route::post('/role/store', 'RoleController@store')->name('role.store');
	Route::get('/role/edit/{id}', 'RoleController@edit')->name('role.edit');
	Route::put('/role/update/{id}', 'RoleController@update')->name('role.update');
	Route::get('/role/show/{id}', 'RoleController@show')->name('role.show');
	Route::get('/role/destroy/{id}', 'RoleController@destroy')->name('role.destroy');
});

Route::group(['namespace' => 'App\Http\Controllers\Auth'], function () {
	Route::get('/forget-password', 'ForgotPasswordController@forgetPasswordLoad')->name('forget.password');
	Route::post('/otp-verification', 'ForgotPasswordController@verifyOtp')->name('otp.verify');
	Route::post('/password-reset', 'ForgotPasswordController@resetPassword')->name('password.reset');
	Route::post('/password-update', 'ForgotPasswordController@passwordUpdate')->name('password.update');
});

Route::group(['namespace' => 'App\Http\Controllers\Dashboard'], function () {

	//User-Management
	Route::get('/user-management', 'UserManagementController@userManagementLoad')->name('user.management');
	Route::get('/view-user/{id}', 'UserManagementController@userViewData')->name('user.view');
	Route::get('/block-user/{id}', 'UserManagementController@blockUser')->name('user.block');
	Route::get('/filter', 'UserManagementController@filter')->name('user.filter');
	Route::get('/unblock-user/{id}', 'UserManagementController@unblockUser')->name('user.unblock');

	//Manage-Payment
	Route::get('/manage-payments', 'ManagePaymentController@managePaymentsLoad')->name('manage.payments');
	Route::get('/timePeriodFilter', 'ManagePaymentController@timePeriodFilter')->name('timeperiod.filter');
	Route::get('/searchFilter', 'ManagePaymentController@searchFilter')->name('search.filter');
	Route::post('/exportTransaction', 'ManagePaymentController@exportTransaction')->name('export.transaction');
	Route::get('/view-transaction/{id}', 'ManagePaymentController@viewTransaction')->name('view.transaction');

	//Event-Management
	Route::get('/event-management', 'EventManagementController@eventManagementLoad')->name('event.management');
	Route::get('/addview-event', 'EventManagementController@eventAddView')->name('event.addView');
	Route::post('/add-event', 'EventManagementController@eventAdd')->name('event.add');
	Route::get('/view-event/{id}', 'EventManagementController@eventView')->name('event.view');
	Route::get('/edit-event/{id}', 'EventManagementController@eventEdit')->name('event.edit');
	Route::post('/update-event/{id}', 'EventManagementController@eventUpdate')->name('event.update');
	Route::get('/delete-event/{id}', 'EventManagementController@eventDelete')->name('event.delete');
	Route::get('/filter-event', 'EventManagementController@eventFilter')->name('event.filter');

	//Notification-Management
	Route::get('/notification-management', 'NotificationManagementController@notificationManagementLoad')->name('notification.management');
	Route::get('/addView-notification', 'NotificationManagementController@notificationAddView')->name('notification.addView');
	Route::post('/add-notification', 'NotificationManagementController@notificationAdd')->name('notification.add');
	Route::get('/get-users/{selectedUserType}', 'NotificationManagementController@listingUser')->name('notification.listingUser');
	Route::get('/view-notification/{id}', 'NotificationManagementController@notificationView')->name('notification.view');
	Route::get('/delete-notification/{id}', 'NotificationManagementController@notificationDelete')->name('notification.delete');
	Route::get('/filter-notification', 'NotificationManagementController@notificationFilter')->name('notification.filter');

	//Static-Content-Management
	Route::get('/static-content-management', 'StaticContentManagementController@staticManagementLoad')->name('static.content.management');
	Route::post('/update-privacy-policy/{id}', 'StaticContentManagementController@privacyPolicyUpdate')->name('privacypolicy.update');
	Route::get('/view-term-condition', 'StaticContentManagementController@termConditionView')->name('termcondition.view');
	Route::post('/update-term-condition/{id}', 'StaticContentManagementController@termConditionUpdate')->name('termcondition.update');
	Route::get('/view-FAQ', 'StaticContentManagementController@faqView')->name('faq.view');
	Route::get('/Add-FAQ', 'StaticContentManagementController@faqAdd')->name('faq.add');
	Route::post('/update-FAQ', 'StaticContentManagementController@faqupdate')->name('faq.update');
	Route::get('/edit-FAQ/{id}', 'StaticContentManagementController@faqEdit')->name('faq.edit');
	Route::post('/edit-update-FAQ/{id}', 'StaticContentManagementController@faqEditUpdate')->name('faq.edit.update');
	Route::get('/delete-FAQ/{id}', 'StaticContentManagementController@faqDelete')->name('faq.delete');

	//Review-Feedback
	Route::get('/review-feedback', 'ReviewFeedbackController@reviewFeedbackLoad')->name('review.feedback');
	Route::get('/filter-feedback', 'ReviewFeedbackController@feedbackFilter')->name('feedback.filter');
});