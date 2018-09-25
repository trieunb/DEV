<?php

Route::group(['middleware' => ['web', 'authUserDefine'], 'prefix' => 'purchase-request', 'namespace' => 'Modules\PurchaseRequest\Http\Controllers'], function()
{
	//router detail
    Route::get('/purchase-request-detail',          'PurchaseRequestDetailController@getDetail'             )->name(isset(FUNCTION_CD['purchase-request-detail']['view']) ? FUNCTION_CD['purchase-request-detail']['view'] : '');
    Route::post('/purchase-request-detail/save',    'PurchaseRequestDetailController@postSave'              )->name(isset(FUNCTION_CD['purchase-request-detail']['save']) ? FUNCTION_CD['purchase-request-detail']['save'] : '');
    Route::post('/purchase-request-detail/delete',  'PurchaseRequestDetailController@deletePurchaseRequest' )->name(isset(FUNCTION_CD['purchase-request-detail']['delete']) ? FUNCTION_CD['purchase-request-detail']['delete'] : '');
    Route::post('/purchase-request-detail/approve', 'PurchaseRequestDetailController@approvePurchaseRequest')->name(isset(FUNCTION_CD['purchase-request-detail']['approve']) ? FUNCTION_CD['purchase-request-detail']['approve'] : '');
    Route::get('/refer-purchase-request-detail',    'PurchaseRequestDetailController@referPurchaseRequest');
    Route::get('/refer-parts',                      'PurchaseRequestDetailController@referParts');
    Route::get('/refer-purchase-request',           'PurchaseRequestDetailController@referPurchaseRequest');
    //router search
    Route::get('/purchase-request-search',          'PurchaseRequestSearchController@getSearch'  )->name(isset(FUNCTION_CD['purchase-request-search']['view']) ? FUNCTION_CD['purchase-request-search']['view'] : '');
    Route::post('/purchase-request-search/search',  'PurchaseRequestSearchController@postSearch' )->name(isset(FUNCTION_CD['purchase-request-search']['search']) ? FUNCTION_CD['purchase-request-search']['search'] : '');
    Route::post('/purchase-request-search/approve', 'PurchaseRequestSearchController@postApprove')->name(isset(FUNCTION_CD['purchase-request-search']['approve']) ? FUNCTION_CD['purchase-request-search']['approve'] : '');
    Route::post('/purchase-request-detail/refer-supplier',  'PurchaseRequestDetailController@postReferSupplier' )->name(isset(FUNCTION_CD['purchase-request-detail']['refer-supplier']) ? FUNCTION_CD['purchase-request-detail']['refer-supplier'] : '');
});
