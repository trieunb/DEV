<?php

Route::group(['middleware' => ['web', 'authUserDefine'], 'prefix' => 'shifting', 'namespace' => 'Modules\Shifting\Http\Controllers'], function()
{
    // added by DaoNX - 20180327
    Route::get('/shifting-request-detail',          'ShiftingRequestDetailController@getDetail'               )->name(isset(FUNCTION_CD['shifting-request-detail']['view']) ? FUNCTION_CD['shifting-request-detail']['view'] : '');
    Route::post('/shifting-detail/save-shifting',   'ShiftingRequestDetailController@postSaveShiftingDetail'  )->name(isset(FUNCTION_CD['shifting-request-detail']['save']) ? FUNCTION_CD['shifting-request-detail']['save'] : '');    
    Route::post('/shifting-detail/delete-shifting', 'ShiftingRequestDetailController@postDeleteShiftingDetail')->name(isset(FUNCTION_CD['shifting-request-detail']['delete']) ? FUNCTION_CD['shifting-request-detail']['delete'] : '');
    Route::post('/shifting-detail/approve',         'ShiftingRequestDetailController@approveShifting'         )->name(isset(FUNCTION_CD['shifting-request-detail']['approve']) ? FUNCTION_CD['shifting-request-detail']['approve'] : '');
    Route::post('/shifting-detail/refer-move',      'ShiftingRequestDetailController@postReferMove');
    Route::post('/shifting-detail/refer-item',      'ShiftingRequestDetailController@postReferItem');
    Route::post('/shifting-detail/refer-manufacture', 'ShiftingRequestDetailController@postReferManufacture');
    // DungNN add new route
    Route::get('/shifting-request-search',           'ShiftingRequestSearchController@getSearch'   )->name(isset(FUNCTION_CD['shifting-request-search']['view']) ? FUNCTION_CD['shifting-request-search']['view'] : '');
    Route::post('/shifting-request-search/search',   'ShiftingRequestSearchController@postSearch'  )->name(isset(FUNCTION_CD['shifting-request-search']['search']) ? FUNCTION_CD['shifting-request-search']['search'] : '');
    Route::post('/shifting-request-search/approved', 'ShiftingRequestSearchController@postApproved')->name(isset(FUNCTION_CD['shifting-request-search']['approve']) ? FUNCTION_CD['shifting-request-search']['approve'] : '');

});
