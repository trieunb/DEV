<?php

Route::group(['middleware' => ['web', 'authUserDefine'], 'prefix' => 'manufactureinstruction', 'namespace' => 'Modules\ManufactureInstruction\Http\Controllers'], function()
{
    // Route::get('/', 'ManufactureInstructionController@index');

	// ANS810 - DungNN
    // Internal-order-search
    Route::get('/internalorder-search',         'InternalOrderSearchController@getSearch' )->name(isset(FUNCTION_CD['internalorder-search']['view']) ? FUNCTION_CD['internalorder-search']['view'] : '');
    Route::post('/internalorder-search/search', 'InternalOrderSearchController@postSearch')->name(isset(FUNCTION_CD['internalorder-search']['search']) ? FUNCTION_CD['internalorder-search']['search'] : '');   
    Route::post('/internalorder-search/print',  'InternalOrderSearchController@postPrint' ); 

    // Internal-order-detail
    Route::get('/internalorder-detail',                       'InternalOrderDetailController@getDetail' )->name(isset(FUNCTION_CD['internalorder-detail']['view']) ? FUNCTION_CD['internalorder-detail']['view'] : '');
    Route::post('/internalorder-detail/save',                 'InternalOrderDetailController@postSave'  )->name(isset(FUNCTION_CD['internalorder-detail']['save']) ? FUNCTION_CD['internalorder-detail']['save'] : '');
    Route::post('/internalorder-detail/delete',               'InternalOrderDetailController@postDelete')->name(isset(FUNCTION_CD['internalorder-detail']['delete']) ? FUNCTION_CD['internalorder-detail']['delete'] : '');
    Route::post('/internalorder-detail/refer-product',        'InternalOrderDetailController@postReferProduct');
    Route::post('/internalorder-detail/refer-internal-order', 'InternalOrderDetailController@postReferInternalOrder');
    Route::post('/internalorder-detail/print',                'InternalOrderDetailController@postPrint');

	// Manufacturing-instruction-report
	Route::get('/manufacturing-instruction-report',         'ManufacturingInstructionReportController@getSearch' )->name(isset(FUNCTION_CD['manufacturing-instruction-report']['view']) ? FUNCTION_CD['manufacturing-instruction-report']['view'] : '');
    Route::post('/manufacturing-instruction-report/search', 'ManufacturingInstructionReportController@postSearch')->name(isset(FUNCTION_CD['manufacturing-instruction-report']['search']) ? FUNCTION_CD['manufacturing-instruction-report']['search'] : '');   
    Route::post('/manufacturing-instruction-report/print',  'ManufacturingInstructionReportController@postPrint' );


    // Manufacturing-instruction-search
	Route::get('/manufacturing-instruction-search',                            'ManufacturingInstructionSearchController@getSearch'                 )->name(isset(FUNCTION_CD['manufacturing-instruction-search']['view']) ? FUNCTION_CD['manufacturing-instruction-search']['view'] : '');
    Route::post('/manufacturing-instruction-search/search',                    'ManufacturingInstructionSearchController@postSearch'                )->name(isset(FUNCTION_CD['manufacturing-instruction-search']['search']) ? FUNCTION_CD['manufacturing-instruction-search']['search'] : '');
    Route::post('/manufacturing-instruction-search/create-goods-issue-source', 'ManufacturingInstructionSearchController@postCreateGoodsIssueSoucre')->name(isset(FUNCTION_CD['manufacturing-instruction-search']['save']) ? FUNCTION_CD['manufacturing-instruction-search']['save'] : ''); 
});
