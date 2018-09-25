<?php
Route::group(['prefix' => '/', 'middleware' => ['web'], 'namespace' => 'Modules\SystemManagement\Http\Controllers'], function () {
	Route::post('/login/do-login','LoginController@postDoLogin');
    Route::get('/login/success','LoginController@getSuccess');
    Route::get('/login/logout','LoginController@getLogout');
});

Route::group(['middleware' => ['web', 'authUserDefine'], 'prefix' => 'system-management', 'namespace' => 'Modules\SystemManagement\Http\Controllers'], function()
{
    Route::get('/library-master-search',         'LibraryMasterSearchController@getSearch' )->name(isset(FUNCTION_CD['library-master-search']['view']) ? FUNCTION_CD['library-master-search']['view'] : '');
    Route::post('/library-master-search/search', 'LibraryMasterSearchController@postSearch')->name(isset(FUNCTION_CD['library-master-search']['search']) ? FUNCTION_CD['library-master-search']['search'] : '');
   	
   	Route::get('/library-master',                       'LibraryMasterController@getDetail'   )->name(isset(FUNCTION_CD['library-master']['view']) ? FUNCTION_CD['library-master']['view'] : '');
    Route::post('/library-master/save',                 'LibraryMasterController@postSave'    )->name(isset(FUNCTION_CD['library-master']['save']) ? FUNCTION_CD['library-master']['save'] : '');
    Route::post('/library-master/refer-library-detail', 'LibraryMasterController@referLibrary');
    Route::post('/library-master/delete',               'LibraryMasterController@postDelete');

    Route::get('/authority',         'AuthorityController@getAuthority')->name(isset(FUNCTION_CD['authority']['view']) ? FUNCTION_CD['authority']['view'] : '');
    Route::post('/authority/search', 'AuthorityController@postSearch'  )->name(isset(FUNCTION_CD['authority']['search']) ? FUNCTION_CD['authority']['search'] : '');
    Route::post('/authority/save',   'AuthorityController@postSave'    )->name(isset(FUNCTION_CD['authority']['save']) ? FUNCTION_CD['authority']['save'] : '');
    Route::get('/authority/export',  'AuthorityController@getExport'   )->name(isset(FUNCTION_CD['authority']['output']) ? FUNCTION_CD['authority']['output'] : '');
    Route::post('/authority/upload', 'AuthorityController@postUpload'  )->name(isset(FUNCTION_CD['authority']['upload']) ? FUNCTION_CD['authority']['upload'] : '');
});
