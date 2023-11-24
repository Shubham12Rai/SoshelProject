<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use App\Models\Role;
use App\Models\Permission;
use Cache;
use Twilio\Rest\Client;
use Exception;

class User extends Authenticatable implements JWTSubject
{

    use Notifiable;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'height_in_inch',
        'height_in_feet',
        'ethnicity_id',
        'sexuality_id',
        'dating_intention_id',
        'education_status_id',
        'school_college_name',
        'relationship_type_id',
        'covid_vaccine_id',
        'religious_id',
        'family_plan_id',
        'zodiac_sign_id',
        'politics_id',
        'pronouns',
        'bio',
        'mobile_number',
        'otp',
        'otp_expire_time',
        'email_id',
        'google_id',
        'apple_id',
        'full_name',
        'terms_condition',
        'privacy_policy',
        'latitude',
        'longitude',
        'exist_status',
        'job_role_id',
        'other_job_option',
        'specific_work_area',
        'income_level_id',
        'email_otp',
        'email_otp_verified_status',
        'email_otp_expire_time'

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    protected $hidden = [

        'password',
        'remember_token',

    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasPermission(Permission $permission)
    {
        return $this->hasAnyRoles($permission->roles);
    }

    public function hasAnyRoles($roles)
    {
        if (is_array($roles) || is_object($roles)) {
            return (bool)$roles->intersect($this->roles)->count();
        }
        return $this->roles->contains('name', $roles);
    }

    public function isOnline()
    {
        return Cache::has('user-is-online-' . $this->id);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */

    public function getJWTCustomClaims()
    {
        return [];
    }

    // Twilio
    public function sendSMS($receiverNumber)
    {
        $message = 'Login OTP is ' . $this->otp;

        if (!str_starts_with($receiverNumber, '+')) {
            $countryCode = '+91';
            $receiverNumber = $countryCode . $receiverNumber;
        }

        try {
            $accountId = env("TWILIO_SID");
            $authToken = env("TWILIO_TOKEN");
            $twilioNumber = env("TWILIO_FROM");

            $client = new Client($accountId, $authToken);
            $client->messages->create($receiverNumber, [
                'from' => $twilioNumber,
                'body' => $message
            ]);

            info('SMS Sent Successfully!');

        } catch (Exception $e) {
            info("Error: " . $e->getMessage());
        }
    }

    public function user_musics()
    {
        return $this->hasMany(UserMusic::class)->with('music');
    }

    public function user_sports()
    {
        return $this->hasMany(UserSport::class)->with('sport');
    }

    public function user_language_spoken()
    {
        return $this->hasMany(UserLanguageSpoken::class)->with('language');
    }

    public function user_pets()
    {
        return $this->hasMany(UserPet::class)->with('pet');
    }

    public function user_going_out()
    {
        return $this->hasMany(UserGoingOut::class)->with('going_out');
    }

    public function ethnicity()
    {
        return $this->belongsTo(Ethnicity::class, 'ethnicity_id');
    }

    public function job_role()
    {
        return $this->belongsTo(JobRole::class, 'job_role_id');
    }

    public function sexuality()
    {
        return $this->belongsTo(Sexuality::class, 'sexuality_id');
    }

    public function datingIntention()
    {
        return $this->belongsTo(DatingIntention::class, 'dating_intention_id');
    }

    public function educationStatus()
    {
        return $this->belongsTo(EducationStatus::class, 'education_status_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function relationshipType()
    {
        return $this->belongsTo(RelationshipType::class, 'relationship_type_id');
    }

    public function covidVaccine()
    {
        return $this->belongsTo(CovidVaccineStatus::class, 'covid_vaccine_id');
    }

    public function familyPlan()
    {
        return $this->belongsTo(FamilyPlans::class, 'family_plan_id');
    }

    public function zodiacSign()
    {
        return $this->belongsTo(Zodiac::class, 'zodiac_sign_id');
    }

    public function politics()
    {
        return $this->belongsTo(Politics::class, 'politics_id');
    }

    public function religious()
    {
        return $this->belongsTo(Religious::class, 'religious_id');
    }

    public function profileImage()
    {
        return $this->hasMany(ProfileImage::class, 'user_id');
    }
	
	public function currentSubcription()
	{
	   return $this->hasOne(Subscription::class,'user_id')->latest();
	   // order by by how ever you need it ordered to get the latest
	}

    public function user_income_level()
    {
        return $this->belongsTo(IncomeLevel::class, 'income_level_id');
    }

    public function venue_based_user()
    {
        return $this->hasMany(UsersInsideVenues::class, 'id');
    }

    public function venue_based_user_like()
    {
        return $this->hasMany(UserLikeInsideVenues::class, 'id');
    }

    public function notification()
    {
        return $this->hasMany(Notification::class);
    }

    public function fcmToken()
    {
        return $this->hasMany(FcmToken::class);
    }

    public function reportedUsersFrom()
    {
        return $this->hasMany(ReportedUser::class, 'from');
    }
    
    
    public function reportedUsersTo()
    {
        return $this->hasMany(ReportedUser::class, 'to');
    }

    public function blockUsersFrom()
    {
        return $this->hasMany(BlockedUser::class, 'from');
    }
    
    
    public function blockUsersTo()
    {
        return $this->hasMany(BlockedUser::class, 'to');
    }

    public function chatSender()
    {
        return $this->hasMany(Chat::class, 'sender_id');
    }

    public function chatReciever()
    {
        return $this->hasMany(Chat::class, 'reciever_id');
    }

    public function drinkRecieverUser()
    {
        return $this->hasMany(DrinkRequest::class, 'receiver_user_id');
    }

    public function drinkSenderUser()
    {
        return $this->hasMany(DrinkRequest::class, 'sender_user_id');
    }

    public function likedFrom()
    {
        return $this->hasMany(Like::class, 'user_id');
    }

    public function likedTo()
    {
        return $this->hasMany(Like::class, 'liked_user_id');
    }

    public function matchRecieverUser()
    {
        return $this->hasMany(Matchs::class, 'receiver_user_id');
    }

    public function matchSenderUser()
    {
        return $this->hasMany(Matchs::class, 'sender_user_id');
    }

    public function UserLikeInsideVenuesFrom()
    {
        return $this->hasMany(UserLikeInsideVenues::class, 'from');
    }
    
    
    public function UserLikeInsideVenuesTo()
    {
        return $this->hasMany(UserLikeInsideVenues::class, 'to');
    }

    public function venueDrinkRecieverUser()
    {
        return $this->hasMany(VenueDrinkRequest::class, 'receiver_user_id');
    }

    public function venueDrinkSenderUser()
    {
        return $this->hasMany(VenueDrinkRequest::class, 'sender_user_id');
    }

    public function venueFlashSender()
    {
        return $this->hasMany(VenueFlashMessage::class, 'sender_id');
    }

    public function venueFlashReciever()
    {
        return $this->hasMany(VenueFlashMessage::class, 'reciever_id');
    }

    public function venueNotification()
    {
        return $this->hasMany(VenueNotification::class);
    }

    public function checkInUserInsideVenue()
    {
        return $this->hasMany(UsersInsideVenues::class);
    }

    public function venue_feedback()
    {
        return $this->hasMany(VenueFeedback::class, 'user_id');
    }
}
