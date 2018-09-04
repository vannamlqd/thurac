<?php
$namespace = 'App\Modules\User\Controllers';
Route::group(['module'=>'User', 'namespace' => $namespace,'middleware' =>'web'], function() {
	Route::get('login',['as'=>'login','uses'=>'UserController@login']);	
	Route::post('login',['as'=>'login','uses'=>'UserController@postlogin']);	
	Route::get('logout',['as'=>'logout','uses'=>'UserController@logout']);
	Route::group(['prefix' => 'admin','middleware'=>'auth'], function() {
		    Route::get('listUser.html',['as'=>'listUser','uses'=>'UserController@listUser']);
		    Route::get('addUser.html',['as'=>'addUser','uses'=>'UserController@addUser']);
		    Route::post('addUser.html',['as'=>'addUser','uses'=>'UserController@postadduser']);
		    Route::get('delUser/{id}',['as'=>'delUser','uses'=>'UserController@delUser']);
		});	

});
