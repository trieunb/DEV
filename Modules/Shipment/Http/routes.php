<?php

Route::group(['middleware' => ['web', 'authUserDefine'], 'prefix' => 'shipment', 'namespace' => 'Modules\Shipment\Http\Controllers'], function()
{
    //Shipment detail
    Route::get('/shipment-detail',                  'ShipmentDetailController@getDetail'               )->name(isset(FUNCTION_CD['shipment-detail']['view']) ? FUNCTION_CD['shipment-detail']['view'] : '');
    Route::post('/shipment-detail/save-shipment',   'ShipmentDetailController@postSaveShipmentDetail'  )->name(isset(FUNCTION_CD['shipment-detail']['save']) ? FUNCTION_CD['shipment-detail']['save'] : '');
    Route::post('/shipment-detail/delete-shipment', 'ShipmentDetailController@postDeleteShipmentDetail')->name(isset(FUNCTION_CD['shipment-detail']['delete']) ? FUNCTION_CD['shipment-detail']['delete'] : '');
    Route::post('/shipment-detail/approve',         'ShipmentDetailController@approveShipment'         )->name(isset(FUNCTION_CD['shipment-detail']['approve']) ? FUNCTION_CD['shipment-detail']['approve'] : '');
    Route::post('/shipment-detail/approve-cancel',  'ShipmentDetailController@cancelApproveShipment'   )->name(isset(FUNCTION_CD['shipment-detail']['approve-cancel']) ? FUNCTION_CD['shipment-detail']['approve-cancel'] : '');
    Route::post('/shipment-detail/refer-receive',   'ShipmentDetailController@postReferReceive');
    Route::post('/shipment-detail/refer-pi-no',     'ShipmentDetailController@postReferPiNo');
    Route::post('/shipment-detail/refer-shipment',  'ShipmentDetailController@postReferShipment');

    //Provisional Shipment Detail
    Route::get('/provisional-shipment-detail',                  'ProvisionalShipmentDetailController@getDetail'               )->name(isset(FUNCTION_CD['provisional-shipment-detail']['view']) ? FUNCTION_CD['provisional-shipment-detail']['view'] : '');
    Route::post('/provisional-shipment-detail/save-shipment',   'ProvisionalShipmentDetailController@postSaveShipmentDetail'  )->name(isset(FUNCTION_CD['provisional-shipment-detail']['save']) ? FUNCTION_CD['provisional-shipment-detail']['save'] : '');
    Route::post('/provisional-shipment-detail/delete-shipment', 'ProvisionalShipmentDetailController@postDeleteShipmentDetail')->name(isset(FUNCTION_CD['provisional-shipment-detail']['delete']) ? FUNCTION_CD['provisional-shipment-detail']['delete'] : '');
    Route::post('/provisional-shipment-detail/print',           'ProvisionalShipmentDetailController@postPrint');
    Route::post('/provisional-shipment-detail/control-other',   'ProvisionalShipmentDetailController@postControlOther');
    Route::post('/provisional-shipment-detail/refer-receive',   'ProvisionalShipmentDetailController@postReferReceive');
    Route::post('/provisional-shipment-detail/refer-pi-no',     'ProvisionalShipmentDetailController@postReferPiNo');
    Route::post('/provisional-shipment-detail/refer-shipment',  'ProvisionalShipmentDetailController@postReferShipment');
    
    //Shipment Search
    Route::get('/shipment-search',          'ShipmentSearchController@getSearch'  )->name(isset(FUNCTION_CD['shipment-search']['view']) ? FUNCTION_CD['shipment-search']['view'] : '');
    Route::post('/shipment-search/search',  'ShipmentSearchController@postSearch' )->name(isset(FUNCTION_CD['shipment-search']['search']) ? FUNCTION_CD['shipment-search']['search'] : '');
    Route::post('/shipment-search/approve', 'ShipmentSearchController@postApprove')->name(isset(FUNCTION_CD['shipment-search']['approve']) ? FUNCTION_CD['shipment-search']['approve'] : '');

    //Provisional shipment search
    Route::get('/provisional-shipment-search',         'ProvisionalShipmentSearchController@getSearch' )->name(isset(FUNCTION_CD['provisional-shipment-search']['view']) ? FUNCTION_CD['provisional-shipment-search']['view'] : '');
    Route::post('/provisional-shipment-search/search', 'ProvisionalShipmentSearchController@postSearch')->name(isset(FUNCTION_CD['provisional-shipment-search']['search']) ? FUNCTION_CD['provisional-shipment-search']['search'] : '');

});
