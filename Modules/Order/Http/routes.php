<?php

Route::group(['middleware' => ['web', 'authUserDefine'], 'prefix' => 'order', 'namespace' => 'Modules\Order\Http\Controllers'], function()
{
    Route::get('/order-confirm', 		 'OrderConfirmController@getSearch'			)->name(isset(FUNCTION_CD['order-confirm']['view']) ? FUNCTION_CD['order-confirm']['view'] : '');
    Route::post('/order-confirm/search', 'OrderConfirmController@postSearch'		)->name(isset(FUNCTION_CD['order-confirm']['search']) ? FUNCTION_CD['order-confirm']['search'] : '');
    Route::post('/order-confirm/save',   'OrderConfirmController@savePiOrderConfirm')->name(isset(FUNCTION_CD['order-confirm']['save']) ? FUNCTION_CD['order-confirm']['save'] : '');
});
