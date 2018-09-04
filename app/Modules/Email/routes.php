<?php
$namespace = 'App\Modules\Email\Controllers';

Route::group(['module'=>'Email', 'namespace' => $namespace], function() {
	Route::get('auto',['uses'=>'CrawlerController@getEmail']);
	Route::get('auto_gmail',['uses'=>'CrawlerController@getGmail']);
	Route::get('auto_trap',['uses'=>'CrawlerController@getTrap']);
});

Route::group(['module'=>'Email', 'namespace' => $namespace,'middleware' =>'web'], function() {
	
	Route::group(['prefix' => 'admin','middleware'=>'auth'], function() {

	    Route::get('/',['as'=>'dashboar','uses'=>'EmailController@home']);	

		/*Route::get('getgmail',['as'=>'getgmail','uses'=>'EmailController@getGmail']);*/
		Route::get('editorigin.html/{id}',['as'=>'geteditorigin','uses'=>'EmailController@getEditOrigin']);
		Route::post('editorigin.html/{id}',['as'=>'posteditorigin','uses'=>'EmailController@postEditOrigin']);
		Route::get('addorigin.html',['as'=>'getaddorigin','uses'=>'EmailController@getAddOrigin']);
		Route::post('addorigin.html',['as'=>'postaddorigin','uses'=>'EmailController@postAddOrigin']);
		Route::get('getmailimap',['as'=>'getmailimap','uses'=>'EmailController@getMailImap']);
		Route::get('listemail.html', ['as' => 'indexEmail', 'uses' => 'EmailController@index']);
		Route::get('detailemail.html/{id}', ['as' => 'detailemail', 'uses' => 'EmailController@detailEmail']);
		Route::get('listreport.html', ['as' => 'indexreport', 'uses' => 'EmailController@indexreport']);
		Route::get('statistic.html', ['as' => 'indexstatistic', 'uses' => 'EmailController@indexstatistic']);
		Route::post('statistic.html', ['as' => 'postStatistic', 'uses' => 'EmailController@postStatistic']);
		Route::get('listorigin.html', ['as' => 'indexorigin', 'uses' => 'EmailController@indexorigin']);
		Route::get('listtype.html', ['as' => 'indextype', 'uses' => 'EmailController@indextype']);
		Route::get('listsender.html', ['as' => 'indexsender', 'uses' => 'EmailController@indexsender']);
		Route::get('listip.html', ['as' => 'indexip', 'uses' => 'EmailController@indexip']);
		Route::get('listdomain.html', ['as' => 'indexdomain', 'uses' => 'EmailController@indexdomain']);
		Route::get('addemail.html', ['as' => 'addemail', 'uses' => 'EmailController@getAdd']);
		Route::post('addemail', ['as' => 'postaddemail', 'uses' => 'EmailController@postAdd']);
		Route::get('editemail.html/{id}', ['as' => 'editemail', 'uses' => 'EmailController@getEdit'])->where('id', '[0-9]+');
		Route::post('editemail.html/{id}', ['as' => 'editemail', 'uses' => 'EmailController@postEdit'])->where('id', '[0-9]+');
		Route::get('dellemail.html/{id}', ['as' => 'dellemail', 'uses' => 'EmailController@dellEmail'])->where('id', '[0-9]+');
		Route::get('setpublicmail/{id}', ['as' => 'publicmail', 'uses' => 'EmailController@setPublicMail'])->where('id', '[0-9]+');

		//Type
		Route::get('edittype.html/{id}',['as'=>'edittype','uses'=>'EmailController@getEditType']);
		Route::post('edittype.html/{id}',['as'=>'edittype','uses'=>'EmailController@postEditType']);
		Route::get('addtype.html',['as'=>'addtype','uses'=>'EmailController@getAddType']);
		Route::post('addtype.html',['as'=>'addtype','uses'=>'EmailController@postAddType']);
		Route::get('delltype.html/{id}',['as'=>'delltype','uses'=>'EmailController@dellType'])->where('id', '[0-9]+');
		Route::get('setpublictype.html/{id}',['as'=>'setpublictype','uses'=>'EmailController@setPublicType'])->where('id', '[0-9]+');
		//endtype
		
		//csv
		Route::get('csvchoose',['as'=>'csvchoose','uses'=>'EmailController@chooseCsv']);
		Route::post('csvread',['as'=>'csvread','uses'=>'EmailController@importCsv']);
		//ecel export
		Route::get('exportxls/{model}', ['as'=>'exportxls','uses'=>'EmailController@exportxls']);
	});

});
