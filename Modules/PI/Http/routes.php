<?php

Route::group(['middleware' => ['web', 'authUserDefine'], 'prefix' => 'pi', 'namespace' => 'Modules\PI\Http\Controllers'], function()
{
	//pi detail
    Route::get('/pi-detail',                 'PiDetailController@getDetail'      )->name(isset(FUNCTION_CD['pi-detail']['view']) ? FUNCTION_CD['pi-detail']['view'] : '');
    Route::post('/pi-detail/save',           'PiDetailController@postSave'       )->name(isset(FUNCTION_CD['pi-detail']['save']) ? FUNCTION_CD['pi-detail']['save'] : '');
    Route::post('/pi-detail/delete',         'PiDetailController@deletePi'       )->name(isset(FUNCTION_CD['pi-detail']['delete']) ? FUNCTION_CD['pi-detail']['delete'] : '');
    Route::post('/pi-detail/approve',        'PiDetailController@approvePi'      )->name(isset(FUNCTION_CD['pi-detail']['approve']) ? FUNCTION_CD['pi-detail']['approve'] : '');
    Route::post('/pi-detail/approve-cancel', 'PiDetailController@cancelApprovePi')->name(isset(FUNCTION_CD['pi-detail']['approve-cancel']) ? FUNCTION_CD['pi-detail']['approve-cancel'] : '');
    Route::get('/download-csv',              'PiDetailController@piDownloadCSV'  )->name(isset(FUNCTION_CD['pi-detail']['download']) ? FUNCTION_CD['pi-detail']['download'] : '');
    Route::post('/import-csv',               'PiDetailController@piImportCSV'    )->name(isset(FUNCTION_CD['pi-detail']['upload']) ? FUNCTION_CD['pi-detail']['upload'] : '');
    Route::get('/print-detail',              'PiDetailController@postPrint');
    Route::get('/pi-detail/print',           'PiDetailController@postPrint');
    Route::get('/response-data',             'PiDetailController@responseData');
    //pi refer
    Route::get('/refer-suppliers',           'PiDetailController@referSuppliers');
    Route::get('/refer-product',             'PiDetailController@referProduct');
    Route::get('/refer-pi-detail',           'PiDetailController@referPiDetail');
    
    //pi search
    Route::get('/pi-search',          'PiSearchController@getList'      )->name(isset(FUNCTION_CD['pi-search']['view']) ? FUNCTION_CD['pi-search']['view'] : '');
    Route::post('/search',            'PiSearchController@postSearch'   )->name(isset(FUNCTION_CD['pi-search']['search']) ? FUNCTION_CD['pi-search']['search'] : '');
    Route::post('/pi-search/approve', 'PiSearchController@approvePiList')->name(isset(FUNCTION_CD['pi-search']['approve']) ? FUNCTION_CD['pi-search']['approve'] : '');
    Route::post('/pi-search/print',   'PiSearchController@printPiList'  )->name(isset(FUNCTION_CD['pi-search']['print']) ? FUNCTION_CD['pi-search']['print'] : '');
    
});
