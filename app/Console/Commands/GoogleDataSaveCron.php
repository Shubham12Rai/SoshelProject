<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use App\Models\ServiceCities;
use App\Models\ServiceType;
use App\Helpers\ApiHelper;
use App\Models\ServiceGoogleDataSave;
use Illuminate\Support\Facades\Http;

class GoogleDataSaveCron extends Command
{
    public function __construct(ApiHelper $ApiHelper)
    {
        parent::__construct();
        $this->apiHelper = $ApiHelper;
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'googleData:savecron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // database call
        $serviceCitiesData = ServiceCities::all();
        $ServiceTypeData = ServiceType::all();
        \Log::info("start cron job! ");
        return 0;
        // foreach ($serviceCitiesData as $serviceCity) {
        //     foreach ($ServiceTypeData as $ServiceType) {
        //         $nextPage = null;
        //         do {
        //             // curl URL
        //             $bulkInsertData = [];
        //             $url = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?keyword=';
        //             $url .= $ServiceType['name'] . '&location=' . $serviceCity['latitude'] . '%2C';
        //             $url .= $serviceCity['longitude'] . '&radius=' . $serviceCity['radius'];
        //             $url .= '&key=' . env('GOOGLE_API_KEY');
        //             if ($nextPage) {
        //                 $url .= '&pagetoken=' . $nextPage;
        //             }
        //             // $url = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?keyword=restaurant&location=41.418155%2C-81.842722&radius=4000&key=AIzaSyBJUbxS6nSGpGrkIoUQTcnkRScLKMreIqg&pagetoken=AcJnMuF0G25dzIl2Lf2EVfSwPVgPOyjdMRLF7uA6zAJQK7N3U1ACgK7nVc06SctI_woFVHdboknDmY88Qq9tHejrvQdg3sJ9R_7Iq8KS8rjfBZdevTbQP0FUMOZq7f89MK7qyFmIWsBiENsUcczUiTwK4v6P81UUfc7fekeKWfsc5CMsmgo-f9JWVLR3xhj5EVFBi1muEZWbClWzYApOvYbOUCFFLedTpIc1hzwe3uD20OC-If6a8-iK5rWj0Dsdu23l4Rmc3tzHNmoN6Q4j5yP_rJYS6ys2WJDjQ_FSt2qzA81QNt9J6v70FXFfLu_baPHN6ncHwMRnLCH26UE0esXSWtA8o7x-1DdiTVR4_ZZ7tsubh4FUi_RhgnNnRyEHywsQalSbuyB4phd-v245nqSEpr43XNOBGxlY3dSONM_5q0eHwPym3fPdOVrZy5AW';
        //             $url = trim($url);
        //             \Log::info(" google url $url");

        //             $googleData = Http::get($url); //$this->apiHelper->curlFunctionForCron($url);
        //             $googleResult = $googleData['results'];
        //             foreach ($googleResult as $value) {
        //                 $checkDataExist = ServiceGoogleDataSave::where('place_id', $value['place_id'])
        //                     ->get();
        //                 if ($checkDataExist->count() == 0) {
        //                     $insertData = [
        //                         'place_name' => $value['name'],
        //                         'place_id' => $value['place_id'],
        //                         'vicinity' => $value['vicinity'],
        //                         'latitude' => $value['geometry']['location']['lat'],
        //                         'longitude' => $value['geometry']['location']['lng'],
        //                         'json_object' => json_encode($value),
        //                         'service_type_id' => $ServiceType['id'],
        //                         'service_city_id' => $serviceCity['id'],
        //                         'created_at' => now(), 
        //                         'updated_at' => now()
        //                     ];
        //                     $bulkInsertData[] = $insertData;
        //                 } else {
        //                     $updateData = [
        //                         'place_name' => $value['name'],
        //                         'vicinity' => $value['vicinity'],
        //                         'latitude' => $value['geometry']['location']['lat'],
        //                         'longitude' => $value['geometry']['location']['lng'],
        //                         'json_object' => json_encode($value),
        //                         'service_type_id' => $ServiceType['id'],
        //                         'service_city_id' => $serviceCity['id'],
        //                         'updated_at' => now()
        //                     ];
        //                     ServiceGoogleDataSave::where('place_id', $value['place_id'])->update($updateData);
        //                 }

        //             }
        //             ServiceGoogleDataSave::insert($bulkInsertData);
        //             if (isset($googleData['next_page_token']) && !empty($googleData['next_page_token'])) {
        //                 $nextPage = $googleData['next_page_token'];
        //                 sleep(2);
        //             } else {
        //                 $nextPage = null;
        //             }

        //         } while ($nextPage);

        //     }
        // }
        // \Log::info("end cron job ");
        // return 0;
    }
}
