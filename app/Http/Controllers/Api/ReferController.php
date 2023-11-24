<?php 

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReferController extends ApiHelper 
{ 
    public function listing() 
    { 
		$user=Auth::user();
		$data['list']=User::where('referred_by',$user->refer_code)->get();
		$data['count']=count($data['list']);
		return $this->successRespond($data, 'Success');
    }	
}


