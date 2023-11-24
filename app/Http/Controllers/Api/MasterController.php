<?php

namespace App\Http\Controllers\Api;

use App\Models\Politics;
use App\Models\Language;
use App\Models\Music;
use App\Models\Sport;
use App\Models\Pet;
use App\Models\IncomeLevel;
use App\Models\GoingOut;
use App\Models\JobRole;
use App\Models\CovidVaccineStatus;
use App\Models\DatingIntention;
use App\Models\EducationStatus;
use App\Models\Ethnicity;
use App\Models\FamilyPlans;
use App\Models\Plan;
use App\Models\RelationshipType;
use App\Models\Religious;
use App\Models\Sexuality;
use App\Models\Status;
use App\Models\Zodiac;
use App\Helpers\ApiHelper;
use App\Models\Report;
use App\Models\Feedback;
use App\Models\DeleteAccountFeedback;
use Illuminate\Http\Request;

class MasterController extends ApiHelper
{
    const OPEN_TO_ALL = 'Open to all';
    const PREFER_NOT_TO_SAY = 'Prefer not to say';
    const ORDER_BY_PREFER_NOT_TO_SAY = "CASE WHEN name = 'Prefer not to say' THEN 1 ELSE 0 END, name";
    const ORDER_BY_OTHER = "CASE WHEN name = 'Other' THEN 1 ELSE 0 END, name";
    /**
     * To get covid vaccine status data
     * @param req :[]
     * @param res : [Get all available covid vaccine status options]
     */
    public function getCovidVaccineStatus()
    {
        $covidVaccineData = CovidVaccineStatus::orderByRaw(self::ORDER_BY_PREFER_NOT_TO_SAY)->get();
        return $this->successRespond($covidVaccineData, 'ListOfOptions');
    }

    /**
     * To get dating intention data
     * @param req :[]
     * @param res : [Get all available dating intention options]
     */
    public function getDatingIntention()
    {
        $datingIntention = DatingIntention::orderByRaw("CASE
                            WHEN name = 'Prefer not to say' THEN 0
                            ELSE 1 END, name"
        )->get();

        // Replacing 'Prefer not to say' with 'Open to all' and set its id=0, Which is used for fliters
        foreach ($datingIntention as $status) {
            if ($status->name === self::PREFER_NOT_TO_SAY) {
                $status->id = 0;
                $status->name = self::OPEN_TO_ALL;
            }
        }

        return $this->successRespond($datingIntention, 'ListOfOptions');
    }

    /**
     * To get education status data
     * @param req :[]
     * @param res : [Get all available education status options]
     */
    public function getEducationStatus()
    {
        $educationStatus = EducationStatus::orderByRaw("CASE
                    WHEN name = 'Prefer not to say' THEN 0
                    WHEN name = 'Undergraduate' THEN 2
                    WHEN name = 'Postgraduate' THEN 3
                    ELSE 1 END, name"
        )->get();

        // Replacing 'Prefer not to say' with 'Open to all' and set its id=0, Which is used for fliters
        foreach ($educationStatus as $status) {
            if ($status->name === self::PREFER_NOT_TO_SAY) {
                $status->id = 0;
                $status->name = self::OPEN_TO_ALL;
            }
        }

        return $this->successRespond($educationStatus, 'ListOfOptions');
    }

    /**
     * To get ethnicity data
     * @param req :[]
     * @param res : [Get all available ethnicity options]
     */
    public function getEthnicity()
    {
        $ethnicity = Ethnicity::orderByRaw("CASE 
                    WHEN name = 'Other' THEN 0
                    ELSE 1 END, name"
        )->get();

        // Replacing 'Other' with 'Open to all' and set its id=0, Which is used for fliters
        foreach ($ethnicity as $status) {
            if ($status->name === 'Other') {
                $status->id = 0;
                $status->name = self::OPEN_TO_ALL;
            }
        }
        return $this->successRespond($ethnicity, 'ListOfOptions');
    }

    /**
     * To get family plan data
     * @param req :[]
     * @param res : [Get all available family plan options]
     */
    public function getFamilyPlans()
    {
        $familyPlans = FamilyPlans::orderByRaw("CASE WHEN name = 'Prefer not to say'
                    THEN 1 ELSE 0 END, name")->get();
        return $this->successRespond($familyPlans, 'ListOfOptions');
    }

    /**
     * To get goint out data
     * @param req :[]
     * @param res : [Get all available goint out options]
     */
    public function getGoingOut()
    {
        $goingOut = GoingOut::orderBy('name', 'asc')->get();
        return $this->successRespond($goingOut, 'ListOfOptions');
    }

    /**
     * To get language data
     * @param req :[]
     * @param res : [Get all available language options]
     */
    public function getlanguage()
    {
        $language = Language::orderByRaw(self::ORDER_BY_OTHER)->get();
        return $this->successRespond($language, 'ListOfOptions');
    }

    /**
     * To get music data
     * @param req :[]
     * @param res : [Get all available music options]
     */
    public function getMusics()
    {
        $music = Music::orderBy('name', 'asc')->get();
        return $this->successRespond($music, 'ListOfOptions');
    }

    /**
     * To get pets data
     * @param req :[]
     * @param res : [Get all available pets options]
     */
    public function getpets()
    {
        $pet = Pet::orderBy('name', 'asc')->get();
        return $this->successRespond($pet, 'ListOfOptions');
    }

    /**
     * To get plan data
     * @param req :[]
     * @param res : [Get all available plan options]
     */
    public function getPlan()
    {
        $plan = Plan::orderBy('name', 'asc')->get();
        return $this->successRespond($plan, 'ListOfOptions');
    }

    /**
     * To get relationship type data
     * @param req :[]
     * @param res : [Get all available relationship type options]
     */
    public function getRelationshipType()
    {
        $relationshipType = RelationshipType::orderByRaw("CASE WHEN name = 'Prefer not to say'
                    THEN 1 ELSE 0 END, name")->get();
        return $this->successRespond($relationshipType, 'ListOfOptions');
    }

    /**
     * To get religious data
     * @param req :[]
     * @param res : [Get all available religious options]
     */
    public function getReligious()
    {
        $religious = Religious::orderByRaw("CASE
        WHEN name = 'Prefer not to say' THEN 2
        WHEN name = 'Other' THEN 1
        ELSE 0
        END, name ASC")->get();
        return $this->successRespond($religious, 'ListOfOptions');
    }

    /**
     * To get sexuality data
     * @param req :[]
     * @param res : [Get all available sexuality options]
     */
    public function getSexuality()
    {
        $sexuality = Sexuality::orderByRaw("CASE
                    WHEN name = 'Other' THEN 0
                    WHEN name = 'Straight' THEN 1
                    ELSE 2 END, name"
        )->get();

        // Replacing 'Others' with 'Open to all' and set its id=0, Which is used for fliters
        foreach ($sexuality as $status) {
            if ($status->name === 'Other') {
                $status->id = 0;
                $status->name = self::OPEN_TO_ALL;
            }
        }
        return $this->successRespond($sexuality, 'ListOfOptions');
    }

    /**
     * To get sports data
     * @param req :[]
     * @param res : [Get all available sports options]
     */
    public function getSports()
    {
        $sport = Sport::orderBy('name', 'asc')->get();
        return $this->successRespond($sport, 'ListOfOptions');
    }

    /**
     * To get status data
     * @param req :[]
     * @param res : [Get all available status options]
     */
    public function getStatus()
    {
        $status = Status::all();
        return $this->successRespond($status, 'ListOfOptions');
    }

    /**
     * To get zodiac data
     * @param req :[]
     * @param res : [Get all available zodiac options]
     */
    public function getZodiac()
    {
        $zodiac = Zodiac::orderByRaw("CASE WHEN name = 'Prefer not to say'
                    THEN 0 ELSE 1 END, name")->get();

        // Replacing 'Prefer not to say' with 'Open to all' and set its id=0, Which is used for fliters
        foreach ($zodiac as $status) {
            if ($status->name === self::PREFER_NOT_TO_SAY) {
                $status->id = 0;
                $status->name = self::OPEN_TO_ALL;
            }
        }
        return $this->successRespond($zodiac, 'ListOfOptions');
    }

    /**
     * To get politics data
     * @param req :[]
     * @param res : [Get all available politics options]
     */
    public function getPolitics()
    {
        $politics = Politics::orderByRaw("CASE
                        WHEN name = 'Others' THEN 0
                        ELSE 1 END, name"
        )->get();

        // Replacing 'Others' with 'Open to all' and set its id=0, Which is used for fliters
        foreach ($politics as $status) {
            if ($status->name === 'Others') {
                $status->id = 0;
                $status->name = self::OPEN_TO_ALL;
            }
        }
        return $this->successRespond($politics, 'ListOfOptions');
    }

    /**
     * To get job role data
     * @param req :[]
     * @param res : [Get all available job role options]
     */
    public function getJobRole()
    {
        $jobRole = JobRole::orderByRaw("CASE
                    WHEN name = 'Others' THEN 0
                    ELSE 1 END, name"
        )->get();

        // Replacing 'Others' with 'Open to all' and set its id=0, Which is used for fliters
        foreach ($jobRole as $status) {
            if ($status->name === 'Others') {
                $status->id = 0;
                $status->name = self::OPEN_TO_ALL;
            }
        }
        return $this->successRespond($jobRole, 'ListOfOptions');
    }

    /**
     * To get income level data
     * @param req :[]
     * @param res : [Get all available income level options]
     */
    public function getIncomeLevel()
    {
        $incomeLevel = IncomeLevel::all();

        $incomeRange = [];
        foreach ($incomeLevel as $data) {

            if ($data['min_income'] === '1,000,000') {
                $incomeRange[] = [
                    'id' => $data->id,
                    'income' => $data->min_income . "+",
                    'status' => $data->status,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at
                ];
                break;
            }

            $incomeRange[] = [
                'id' => $data->id,
                'income' => $data->min_income . " - " . $data->max_income,
                'status' => $data->status,
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at
            ];
        }

        return $this->successRespond($incomeRange, 'ListOfOptions');
    }

    /**
     * To get report data
     * @param req :[]
     * @param res : [Get all available report options]
     */
    public function getReport()
    {
        $report = Report::orderByRaw(self::ORDER_BY_OTHER)->get();
        return $this->successRespond($report, 'ListOfOptions');
    }

    /**
     * To get feedback data
     * @param req :[]
     * @param res : [Get all available feedback options]
     */
    public function getfeedback()
    {
        $feedback = Feedback::orderBy('name', 'asc')->get();
        return $this->successRespond($feedback, 'ListOfOptions');
    }

    /**
     * To get interest data
     * @param req :[]
     * @param res : [Get all available interest options]
     */
    public function getInterest()
    {
        $sport = Sport::orderBy('name', 'asc')->get();
        $pet = Pet::orderBy('name', 'asc')->get();
        $music = Music::orderBy('name', 'asc')->get();
        $goingOut = GoingOut::orderBy('name', 'asc')->get();

        $response = [
            'sport' => $sport,
            'pet' => $pet,
            'music' => $music,
            'goingOut' => $goingOut
        ];

        return $this->successRespond($response, 'ListOfOptions');

    }

    public function getDeleteFeedback()
    {
        $feedback = DeleteAccountFeedback::orderByRaw("CASE
                    WHEN name = 'Other' THEN 1
                    ELSE 0 END, name ASC")
        ->get();
        return $this->successRespond($feedback, 'ListOfOptions');
    }

    /**
     * To get all master table data
     * @param req :[]
     * @param res : [Get all available all master table options]
     */
    public function getAllMasterTableData()
    {
        $covidVaccineData = CovidVaccineStatus::orderByRaw(self::ORDER_BY_PREFER_NOT_TO_SAY)->get();

        $datingIntention = DatingIntention::orderByRaw("CASE
                            WHEN name = 'Prefer not to say' THEN 1
                            ELSE 0 END, name"
        )->get();

        $educationStatus = EducationStatus::orderByRaw("CASE
                            WHEN name = 'Undergraduate' THEN 1
                            WHEN name = 'Postgraduate' THEN 2
                            WHEN name = 'Prefer not to say' THEN 3
                            ELSE 0 END, name"
        )->get();

        $ethnicity = Ethnicity::orderByRaw("CASE
                            WHEN name = 'Other' THEN 1
                            ELSE 0 END, name"
        )->get();

        $familyPlans = FamilyPlans::orderByRaw(self::ORDER_BY_PREFER_NOT_TO_SAY)->get();

        $language = Language::orderByRaw(self::ORDER_BY_OTHER)->get();

        $relationshipType = RelationshipType::orderByRaw(self::ORDER_BY_PREFER_NOT_TO_SAY)->get();

        $religious = Religious::orderByRaw("CASE WHEN name = 'Prefer not to say' THEN 2
                            WHEN name = 'Other' THEN 1 ELSE 0 END, name ASC")->get();

        $sexuality = Sexuality::orderByRaw("CASE
                            WHEN name = 'Straight' THEN 0
                            WHEN name = 'Other' THEN 2
                            ELSE 1 END, name"
        )->get();

        $zodiac = Zodiac::orderByRaw("CASE WHEN name = 'Prefer not to say'
                  THEN 1 ELSE 0 END, name")->get();

        $politics = Politics::orderByRaw("CASE
                            WHEN name = 'Others' THEN 1
                            ELSE 0 END, name"
        )->get();
        $jobRole = JobRole::orderByRaw("CASE
                            WHEN name = 'Others' THEN 1
                            ELSE 0 END, name"
        )->get();

        $incomeLevel = IncomeLevel::all();

        $incomeRange = [];
        foreach ($incomeLevel as $data) {

            if ($data['min_income'] === '1,000,000') {
                $incomeRange[] = [
                    'id' => $data->id,
                    'income' => $data->min_income . "+",
                    'status' => $data->status,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at
                ];
                break;
            }

            $incomeRange[] = [
                'id' => $data->id,
                'income' => $data->min_income . " - " . $data->max_income,
                'status' => $data->status,
                'created_at' => $data->created_at,
                'updated_at' => $data->updated_at
            ];
        }

        $response = [
            'covid_vaccine_data' => $covidVaccineData,
            'dating_intention' => $datingIntention,
            'education_status' => $educationStatus,
            'ethnicity' => $ethnicity,
            'family_plans' => $familyPlans,
            'language' => $language,
            'relationship_type' => $relationshipType,
            'religious' => $religious,
            'sexuality' => $sexuality,
            'zodiac' => $zodiac,
            'politics' => $politics,
            'jobRole' => $jobRole,
            'incomeRange' => $incomeRange,
        ];

        return $this->successRespond($response, 'ListOfOptions');
    }

    public function dataEncryption(Request $request)
    {
        $response = $this->encryptString($request->data);
        return response ($response);
    }
}