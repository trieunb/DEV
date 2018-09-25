<?php

Route::group(['middleware' => ['web', 'authUserDefine'], 'prefix' => 'component-order', 'namespace' => 'Modules\ComponentOrder\Http\Controllers'], function() {
    Route::get('/order-detail',                   'OrderDetailController@getOrder'          )->name(isset(FUNCTION_CD['order-detail']['view']) ? FUNCTION_CD['order-detail']['view'] : '');
    Route::get('/order-search',                   'OrderSearchController@getOrderSearch'	)->name(isset(FUNCTION_CD['order-search']['view']) ? FUNCTION_CD['order-search']['view'] : '');
    Route::post('/order-search',                  'OrderSearchController@postOrderSearch'   )->name(isset(FUNCTION_CD['order-search']['search']) ? FUNCTION_CD['order-search']['search'] : '');
    Route::post('/approved',                      'OrderSearchController@postApproved'      )->name(isset(FUNCTION_CD['order-search']['approved']) ? FUNCTION_CD['order-search']['approved'] : '');
    
    Route::post('/order-detail/refer-parts-order','OrderDetailController@postReferPartsOrder');
    Route::post('/order-detail/refer-component',  'OrderDetailController@postReferComponent');
    Route::post('/order-detail/refer-supplier',   'OrderDetailController@postReferSupplier');
    Route::post('/order-detail/refer-tax',        'OrderDetailController@postReferTax');
    Route::post('/order-detail/approved',         'OrderDetailController@postApproved'      )->name(isset(FUNCTION_CD['order-detail']['approved']) ? FUNCTION_CD['order-detail']['approved'] : '');
    Route::post('/order-detail/cancel-approved',  'OrderDetailController@postCancelApproved')->name(isset(FUNCTION_CD['order-detail']['cancel-approved']) ? FUNCTION_CD['order-detail']['cancel-approved'] : '');
    Route::post('/order-detail/save-order-detail','OrderDetailController@postSaveOrderDetail')->name(isset(FUNCTION_CD['order-detail']['save']) ? FUNCTION_CD['order-detail']['save'] : '');
    Route::post('/order-detail/delete-order',  	  'OrderDetailController@postDeleteOrderDetail')->name(isset(FUNCTION_CD['order-detail']['delete']) ? FUNCTION_CD['order-detail']['delete'] : '');
    
});
