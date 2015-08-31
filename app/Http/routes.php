<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

//Employee
Route::get('employee', 'EmployeeController@index');
Route::get('employee/read', 'EmployeeController@read');
Route::post('employee/update', 'EmployeeController@update');
Route::post('employee/check_username', 'EmployeeController@check_username');
Route::post('employee/check_email', 'EmployeeController@check_email');

//Branch
Route::get('branch', 'Settings\BranchController@index');
Route::get('branch/read', 'Settings\BranchController@read');
Route::post('branch/update', 'Settings\BranchController@update');

//Department
Route::get('department', 'Settings\DepartmentController@index');
Route::get('department/read', 'Settings\DepartmentController@read');
Route::post('department/update', 'Settings\DepartmentController@update');

//Team
Route::get('team', 'Settings\TeamController@index');
Route::get('team/read', 'Settings\TeamController@read');
Route::post('team/update', 'Settings\TeamController@update');

//Cartype
Route::get('cartype', 'Settings\CarTypeController@index');
Route::get('cartype/read', 'Settings\CarTypeController@read');
Route::post('cartype/update', 'Settings\CarTypeController@update');

//Carmodel
Route::get('carmodel', 'Settings\CarModelController@index');
Route::get('carmodel/read', 'Settings\CarModelController@read');
Route::post('carmodel/update', 'Settings\CarModelController@update');

//Carsubmodel
Route::get('carsubmodel', 'Settings\CarSubModelController@index');
Route::get('carsubmodel/read', 'Settings\CarSubModelController@read');
Route::post('carsubmodel/update', 'Settings\CarSubModelController@update');

//Bank
Route::get('bank', 'Settings\BankController@index');
Route::get('bank/read', 'Settings\BankController@read');
Route::post('bank/update', 'Settings\BankController@update');

//InsuranceCompany
Route::get('insurancecompany', 'Settings\InsuranceCompanyController@index');
Route::get('insurancecompany/read', 'Settings\InsuranceCompanyController@read');
Route::post('insurancecompany/update', 'Settings\InsuranceCompanyController@update');

//Customer
Route::get('customer', 'CustomerController@index');
Route::get('customer/read', 'CustomerController@read');
Route::post('customer/update', 'CustomerController@update');

//CustomerExpectation
Route::get('customerexpectation/read', 'CustomerExpectationController@read');
Route::post('customerexpectation/update', 'CustomerExpectationController@update');

//Car
Route::get('car', 'CarController@index');
Route::get('car/read', 'CarController@read');
Route::post('car/update', 'CarController@update');

//Pricelist
Route::get('pricelist', 'Settings\PricelistController@index');
Route::get('pricelist/read', 'Settings\PricelistController@read');
Route::post('pricelist/update', 'Settings\PricelistController@update');

Route::get('/', 'HomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
