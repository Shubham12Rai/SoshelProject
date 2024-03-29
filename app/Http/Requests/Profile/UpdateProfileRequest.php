<?php 

namespace App\Http\Requests\Profile; 

use App\Http\Requests\Request; 

class UpdateProfileRequest extends Request 
{ 
	public function authorize() 
	{ 
		return true; 
	} 

	public function messages() 
	{ 
		return [ 
			'email.unique'=>'E-mail already registered in the system.', 
		]; 
	} 

	public function rules() 
	{ 
		return [ 
            'name' => 'required|string|min:4|max:255',
            'email' => 'required|email|unique:admins,email,'.$this->id
		]; 
	} 
}