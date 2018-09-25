<?php

Route::group(['middleware' => ['web', 'authUserDefine'], 'prefix' => 'invoice', 'namespace' => 'Modules\Invoice\Http\Controllers'], function()
{
    Route::get('/invoice-detail', 				'InvoiceDetailController@getDetail'		)->name(isset(FUNCTION_CD['invoice-detail']['view']) ? FUNCTION_CD['invoice-detail']['view'] : '');
    Route::post('/invoice-detail/save', 		'InvoiceDetailController@postSave'		)->name(isset(FUNCTION_CD['invoice-detail']['save']) ? FUNCTION_CD['invoice-detail']['save'] : '');
    Route::post('/invoice-detail/delete', 		'InvoiceDetailController@deleteInvoice'	)->name(isset(FUNCTION_CD['invoice-detail']['delete']) ? FUNCTION_CD['invoice-detail']['delete'] : '');
    Route::get('/refer-fwd-detail',				'InvoiceDetailController@referFwdDetail');
    Route::get('/refer-invoice-detail',			'InvoiceDetailController@referInvoiceDetail');
    Route::post('/invoice-detail/check-deposit','InvoiceDetailController@checkDeposit');

    Route::get('/invoice-search', 'InvoiceSearchController@getSearch' )->name(isset(FUNCTION_CD['invoice-search']['view']) ? FUNCTION_CD['invoice-search']['view'] : '');
    Route::post('/invoice-search','InvoiceSearchController@postSearch')->name(isset(FUNCTION_CD['invoice-search']['search']) ? FUNCTION_CD['invoice-search']['search'] : '');

});
