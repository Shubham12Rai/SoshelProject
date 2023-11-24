<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('App\Http\Controllers\Api')->name('api/')->group(static function () {
    Route::post('/login', 'LoginController@login');
    Route::post('/verify_otp', 'LoginController@verifyOtp');
    Route::post('/resend_otp', 'LoginController@resendOtp');
    Route::post('/terms_condition', 'LoginController@termsAndCondition');
    Route::post('/privacy_policy', 'LoginController@privacyPolicy');
    Route::post('/social_login', 'SocialLoginController@socialLogin');
    Route::post('/get_insta_token', 'SocialLoginController@getInstaToken');

    Route::get('/covid_vaccine', 'MasterController@getCovidVaccineStatus');
    Route::get('/dating_intention', 'MasterController@getDatingIntention');
    Route::get('/education_status', 'MasterController@getEducationStatus');
    Route::get('/ethnicity', 'MasterController@getEthnicity');
    Route::get('/family_plans', 'MasterController@getFamilyPlans');
    Route::get('/going_out', 'MasterController@getGoingOut');
    Route::get('/language', 'MasterController@getlanguage');
    Route::get('/music', 'MasterController@getMusics');
    Route::get('/pets', 'MasterController@getpets');
    Route::get('/plan', 'MasterController@getPlan');
    Route::get('/relationship_type', 'MasterController@getRelationshipType');
    Route::get('/religious', 'MasterController@getReligious');
    Route::get('/sexuality', 'MasterController@getSexuality');
    Route::get('/sports', 'MasterController@getSports');
    Route::get('/status', 'MasterController@getStatus');
    Route::get('/zodiac', 'MasterController@getZodiac');
    Route::get('/politics', 'MasterController@getPolitics');
    Route::get('/job_role', 'MasterController@getJobRole');
    Route::get('/income_level', 'MasterController@getIncomeLevel');
    Route::get('/report', 'MasterController@getReport');
    Route::get('/feedback', 'MasterController@getFeedback');
    Route::get('/interest', 'MasterController@getInterest');
    Route::get('/master_table_data', 'MasterController@getAllMasterTableData');
    Route::get('/get_delete_feedback', 'MasterController@getDeleteFeedback');
});

Route::middleware(['api', 'auth:api', 'block'])->namespace('App\Http\Controllers\Api')->group(function () {
    Route::post('/user_profile', 'UserProfileController@userProfile');
    Route::post('/profile_detail', 'UserProfileController@profileDetail');
    Route::delete('/image_delete', 'UserProfileController@imageDelete');
    Route::post('/photo_upload', 'UserProfileController@photoUpload');
    Route::post('/profile_photo_url', 'UserProfileController@profilePhotoUrl');
    Route::post('/email_recovery', 'LoginController@emailRecovery');
    Route::post('/location', 'LocationController@locationDetail');
    // Route::get('/home', 'HomeController@getNearestLocations');
    Route::post('/like_dislike', 'HomeController@likeAndDislike');
    Route::get('/about_profile', 'HomeController@aboutProfile');
    Route::post('/user_exist', 'HomeController@userAlreadyExist');
    Route::get('/exist_status', 'HomeController@getUserExistStatus');
    Route::get('/refer_listing', 'ReferController@listing');
    Route::post('/filter', 'FilterController@filter');
    Route::post('/user_likes', 'LikeDislikeController@generalLike');
    Route::post('/liked_user_update', 'LikeDislikeController@likedUserIdUpdate');
    // Route::post('/match', 'MatchController@createMatch');
    Route::get('/match_record', 'MatchController@getMatchAndRecentlyActive');
    Route::get('/match_user_data', 'MatchController@getMatchUserProfileData');
    Route::post('/remove_rematch', 'MatchController@removeRematch');
    Route::post('/save_event_filters', 'EventsController@saveEventsFilters');
    Route::post('/get_cards', 'EventsController@getCards');
    Route::post('/save_fcm_token', 'NotificationController@updateToken');
    Route::post('/reject_event', 'EventsController@rejectEvent');
    Route::get('/toggle_event_incognito', 'EventsController@toggleIncognito');
    Route::post('/we_met', 'ChatScreenController@weMet');
    Route::post('/block', 'ChatScreenController@userBlock');
    Route::post('/report', 'ChatScreenController@report');
    Route::post('/drink_request', 'ChatScreenController@sendDrinkRequest');
    Route::get('/get_drink_request', 'ChatScreenController@getDrinkRequest');
    Route::post('/accept_decline_request', 'ChatScreenController@acceptAndDeclineDrinkRequest');
    Route::post('/event_feedback', 'EventsController@feedback');
    Route::get('/feedback_popup', 'EventsController@feedbackPopup');
    Route::post('/event_like', 'EventsController@like');
    Route::post('/event_match', 'EventsController@match');
    Route::post('/event_dismatch', 'EventsController@dismatch');
    Route::post('/user_event_like', 'EventsController@eventLike');
    Route::get('/get_notifications', 'NotificationController@getNotificationData');
    Route::post('/notification_status_update', 'NotificationController@isReadNotification');
    Route::delete('/delete_notification', 'NotificationController@deleteNotification');
    Route::post('/show_users_on_map', 'MapController@showUsersOnMap');
    Route::post('/add_email', 'SettingsController@addMail');
    Route::post('/verify_email', 'SettingsController@verifyEmail');
    Route::get('/profile_data', 'ProfileScreenController@getProfileData');
    Route::post('/nearby_venues_save', 'VenueBasedController@saveVenueDetails');
    Route::get('/nearby_venues_dropdown', 'VenueBasedController@fetchNearByVenues');
    Route::post('/save_venue_filters', 'VenueBasedController@saveFilters');
    Route::get('/get_cards_inside_venue', 'VenueBasedController@getCards');
    Route::post('/like_user_inside_venue', 'VenueBasedController@likeUser');
    Route::get('/get_match_inside_venue', 'VenueBasedController@getVenueMatchData');
    Route::get('/like_data_inside_venue', 'VenueBasedController@venueBasedUserLike');
    Route::post('/venue_base_like_update', 'VenueBasedController@venueBasedUserLikeIsReadUpdate');
    Route::delete('/venue_base_move_out', 'VenueBasedController@venueBasedMoveOut');
    Route::get('/venue_base_checkin_status', 'VenueBasedController@getVenueBasedCheckinStatus');
    Route::post('/venue_base_unmatch', 'VenueBasedController@venueBaseUnmatch');
    Route::post('/venue_base_feedback', 'VenueBasedController@venueBaseFeedback');
    Route::post('/venue_base_we_met', 'VenueBasedController@venueBaseWeMet');
    Route::get('/venue_base_match_user_profile', 'VenueBasedController@getVenueMatchUserData');
    Route::get('/venue_base_notification_data', 'VenueBasedController@getVenueNotificationData');
    Route::post('/venue_notification_status_update', 'VenueBasedController@isReadVenueNotification');
    Route::delete('/delete_venue_notification', 'VenueBasedController@deleteVenueNotification');
    Route::get('/get_venue_base_we_met', 'VenueBasedController@venueBaseGetWeMet');
    Route::post('/save_venue_drink_request', 'VenueBasedController@venueBaseSendDrinkRequest');
    Route::get('/get_venue_drink_request', 'VenueBasedController@getVenueDrinkRequest');
    Route::post('/accept_decline_venue_drink_request', 'VenueBasedController@acceptAndDeclineVenueDrinkRequest');
    Route::post('/venue_base_send_flash_message', 'VenueBasedController@venueBaseSendFlashMessage');
    Route::get('/get_venue_flash_message', 'VenueBasedController@getVenueFlashMessage');
    Route::get('/get_venue_flash_message_data', 'VenueBasedController@getVenueflashMessageData');
    Route::post('/accept_decline_venue_message_request', 'VenueBasedController@acceptAndDeclineVenueMessageRequest');
    Route::post('/update_image', 'ProfileScreenController@updateOrUpload');
    Route::post('/update_interest_detail', 'ProfileScreenController@updateInterestAndDetail');
    Route::post('/logout', 'LoginController@logout');
    Route::post('/save_message', 'ChatScreenController@saveMessage');
    Route::get('/get_message', 'ChatScreenController@getMessage');
    Route::get('/flash_message_data', 'ChatScreenController@flashMessageData');
    Route::post('/accept_decline_message', 'ChatScreenController@acceptAndDeclineMessageRequest');
    Route::get('/polor_nearby_venues', 'PolorMapController@getPolorMapData');
    Route::post('/add_Email', 'SettingsController@addEmail');
    Route::post('/verify_email', 'SettingsController@verifyEmail');
    Route::post('/notification_toggle', 'SettingsController@updateNotificationToggle');
    Route::get('/get_setting_data', 'SettingsController@getSettingData');
    Route::post('/delete_account', 'SettingsController@deleteAccount');
    Route::post('/contact_us', 'SettingsController@contactUs');
    Route::get('/get_event_list', 'EventsController@getEventList');
    Route::post('/event_join_not_interested', 'EventsController@eventJoinStatus');
    Route::get('/required_field_status', 'UserProfileController@requiredFieldStatus');    
    Route::get('/data_encryption', 'MasterController@dataEncryption');    
});

// This is fallback route it should be at the end of the page
Route::get('/{page}',['App\Http\Controllers\Api\PageController','__invoke']);  //->where('page','terms-and-conditions|faq|privacy-policy')
