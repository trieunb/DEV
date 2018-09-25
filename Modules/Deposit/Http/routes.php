<?php

Route::group(['middleware' => ['web', 'authUserDefine'], 'prefix' => 'deposit', 'namespace' => 'Modules\Deposit\Http\Controllers'], function()
{
    Route::get('/deposit-detail',                     'DepositDetailController@getDepositDetail')->name(isset(FUNCTION_CD['deposit-detail']['view']) ? FUNCTION_CD['deposit-detail']['view'] : '');
    Route::post('/deposit-detail/save',               'DepositDetailController@postSave'        )->name(isset(FUNCTION_CD['deposit-detail']['save']) ? FUNCTION_CD['deposit-detail']['save'] : '');
    Route::post('/deposit-detail/delete',             'DepositDetailController@postDelete'      )->name(isset(FUNCTION_CD['deposit-detail']['delete']) ? FUNCTION_CD['deposit-detail']['delete'] : '');
    Route::post('/deposit-detail/refer-deposit',      'DepositDetailController@postReferDeposit');
    Route::post('/deposit-detail/refer-rcv',          'DepositDetailController@postReferRcv');
    Route::post('/deposit-detail/refer-invoice',      'DepositDetailController@postReferInvoice');
    Route::post('/deposit-detail/refer-client',       'DepositDetailController@postReferClient');
    Route::post('/deposit-detail/validate-total-amt', 'DepositDetailController@postValidateTotalAmt');

    Route::get('/deposit-search', 'DepositSearchController@getDepositSearch')->name(isset(FUNCTION_CD['deposit-search']['view']) ? FUNCTION_CD['deposit-search']['view'] : '');
    Route::post('/search',        'DepositSearchController@postSearch'      )->name(isset(FUNCTION_CD['deposit-search']['search']) ? FUNCTION_CD['deposit-search']['search'] : '');
});
