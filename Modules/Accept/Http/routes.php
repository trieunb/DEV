<?php

Route::group(['middleware' => ['web', 'authUserDefine'], 'prefix' => 'accept', 'namespace' => 'Modules\Accept\Http\Controllers'], function()
{
    Route::get('/accept-detail',                 'AcceptDetailController@getDetail'          )->name(isset(FUNCTION_CD['accept-detail']['view']) ? FUNCTION_CD['accept-detail']['view'] : '');
    Route::post('/accept-detail/save',           'AcceptDetailController@postSave'           )->name(isset(FUNCTION_CD['accept-detail']['save']) ? FUNCTION_CD['accept-detail']['save'] : '');
    Route::post('/accept-detail/delete',         'AcceptDetailController@deleteAccept'       )->name(isset(FUNCTION_CD['accept-detail']['delete']) ? FUNCTION_CD['accept-detail']['delete'] : '');
    Route::post('/accept-detail/approve',        'AcceptDetailController@approveAccept'      )->name(isset(FUNCTION_CD['accept-detail']['approve']) ? FUNCTION_CD['accept-detail']['approve'] : '');
    Route::post('/accept-detail/approve-cancel', 'AcceptDetailController@cancelApproveAccept')->name(isset(FUNCTION_CD['accept-detail']['approve-cancel']) ? FUNCTION_CD['accept-detail']['approve-cancel'] : '');
    Route::post('/accept-detail/cancel-order',   'AcceptDetailController@cancelOrderAccept'  )->name(isset(FUNCTION_CD['accept-detail']['cancel-order']) ? FUNCTION_CD['accept-detail']['cancel-order'] : '');
    Route::get('/print-detail',                  'AcceptDetailController@postPrint');
    Route::get('/refer-suppliers',               'AcceptDetailController@referSuppliers');
    Route::get('/refer-accept-detail',           'AcceptDetailController@referAcceptDetail');
    Route::get('/refer-product',                 'AcceptDetailController@referProduct');

    Route::get('/accept-search',          'AcceptSearchController@getSearch'        )->name(isset(FUNCTION_CD['accept-search']['view']) ? FUNCTION_CD['accept-search']['view'] : '');
    Route::post('/search',                'AcceptSearchController@postSearch'       )->name(isset(FUNCTION_CD['accept-search']['search']) ? FUNCTION_CD['accept-search']['search'] : '');
    Route::post('/accept-search/approve', 'AcceptSearchController@approveAcceptList')->name(isset(FUNCTION_CD['accept-search']['approve']) ? FUNCTION_CD['accept-search']['approve'] : '');
});
