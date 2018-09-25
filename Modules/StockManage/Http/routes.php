<?php

Route::group(['middleware' => ['web', 'authUserDefine'], 'prefix' => 'stock-manage', 'namespace' => 'Modules\StockManage\Http\Controllers'], function()
{
    Route::get('/input-output-detail', 					   'InputOutputDetailController@getDetail')->name(isset(FUNCTION_CD['input-output-detail']['view']) ? FUNCTION_CD['input-output-detail']['view'] : '');
    Route::post('/input-output-detail/save', 			   'InputOutputDetailController@postSave' )->name(isset(FUNCTION_CD['input-output-detail']['save']) ? FUNCTION_CD['input-output-detail']['save'] : '');
    Route::post('/input-output-detail/check-serial-exist', 'InputOutputDetailController@postSerialExist');
    Route::get('/input-output-detail/item', 			   'InputOutputDetailController@referItemSerial');
    Route::get('/input-output-detail/refer-in-out', 	   'InputOutputDetailController@referInOut');

    Route::get('/input-output-search',  'InputOutputSearchController@getSearch' )->name(isset(FUNCTION_CD['input-output-search']['view']) ? FUNCTION_CD['input-output-search']['view'] : '');
    Route::post('/input-output/search', 'InputOutputSearchController@postSearch')->name(isset(FUNCTION_CD['input-output-search']['search']) ? FUNCTION_CD['input-output-search']['search'] : '');
    Route::post('/input-output-search/upload', 'InputOutputSearchController@postUpload')->name(isset(FUNCTION_CD['input-output-search']['upload']) ? FUNCTION_CD['input-output-search']['upload'] : '');

    Route::get('/stock-search', 		'StockSearchController@index'	  )->name(isset(FUNCTION_CD['stock-search']['view']) ? FUNCTION_CD['stock-search']['view'] : '');
    Route::post('/stock-search/search', 'StockSearchController@postSearch')->name(isset(FUNCTION_CD['stock-search']['search']) ? FUNCTION_CD['stock-search']['search'] : '');

});
