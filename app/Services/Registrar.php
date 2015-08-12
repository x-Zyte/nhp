<?php namespace App\Services;

use App\Employee;
use App\User;
use Validator;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;

class Registrar implements RegistrarContract {

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
		return Validator::make($data, [
			//'name' => 'required|max:255',
			//'email' => 'required|email|max:255|unique:users',
			//'password' => 'required|confirmed|min:6',

            'firstname' => 'required|max:50',
            'lastname' => 'required|max:50',
            'phone' => 'max:20',
            'email' => 'required|email|max:100|unique:employees',
            'username' => 'required|max:50',
            'password' => 'required|confirmed|min:6',
            'branchid' => 'required_if:isadmin,0',
            'departmentid' => 'required_if:isadmin,0',
            'roleid' => 'required_if:isadmin,0',
            'teamid' => 'required_if:isadmin,0',
		],
        [
            'firstname.required' => 'The Firstname field is required.',
            'firstname.max' => 'The Firstname may not be greater than 50 characters.',
            'lastname.required' => 'The Lastname field is required.',
            'lastname.max' => 'The Lastname may not be greater than 50 characters.',
            'phone.max' => 'The Phone may not be greater than 20 characters.',
            'email.required' => 'The Email field is required.',
            'email.max' => 'The Email may not be greater than 100 characters.',
            'username.required' => 'The Username field is required.',
            'username.max' => 'The Username may not be greater than 50 characters.',
            'password.required' => 'The Password field is required.',
            'password.confirmed' => 'The Password confirmation does not match.',
            'password.min' => 'The Password must be at least 6 characters.',
            'branchid.required_if' => 'The Branch field is required when Admin is No.',
            'departmentid.required_if' => 'The Department field is required when Admin is No.',
            'roleid.required_if' => 'The Role field is required when Admin is No.',
            'teamid.required_if' => 'The Team field is required when Admin is No.',
        ]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	public function create(array $data)
	{
		return Employee::create([
			//'name' => $data['name'],
			//'email' => $data['email'],
			//'password' => bcrypt($data['password']),

            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => bcrypt($data['password']),
            'isadmin' => $data['isadmin'],
            'branchid' => $data['isadmin'] == 1 ? null : $data['branchid'],
            'departmentid' => $data['isadmin'] == 1 ? null : $data['departmentid'],
            'roleid' => $data['isadmin'] == 1 ? null : $data['roleid'],
            'teamid' => $data['isadmin'] == 1 ? null : $data['teamid'],
            'active' => $data['active'],
            'createdby' => null,//$data['createdby'],
            'createddate' => date('d/m/Y H:i:s'),
            'modifiedby' => null,//$data['modifiedby'],
            'modifieddate' => date('d/m/Y H:i:s'),
		]);
	}

}
