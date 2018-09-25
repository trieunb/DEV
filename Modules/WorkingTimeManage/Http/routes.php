<?php

Route::group(['middleware' => ['web', 'authUserDefine'], 'prefix' => 'working-time-manage', 'namespace' => 'Modules\WorkingTimeManage\Http\Controllers'], function()
{
    Route::get('/working-time-detail', 		   'WorkingTimeDetailController@getDetail' )->name(isset(FUNCTION_CD['working-time-detail']['view']) ? FUNCTION_CD['working-time-detail']['view'] : '');
    Route::post('/working-time-detail/save',   'WorkingTimeDetailController@postSave'  )->name(isset(FUNCTION_CD['working-time-detail']['save']) ? FUNCTION_CD['working-time-detail']['save'] : '');
    Route::post('/working-time-detail/delete', 'WorkingTimeDetailController@postDelete')->name(isset(FUNCTION_CD['working-time-detail']['delete']) ? FUNCTION_CD['working-time-detail']['delete'] : '');
    Route::post('/working-time-detail/refer',  'WorkingTimeDetailController@postRefer');

    Route::get('/working-time-search', 		   'WorkingTimeSearchController@index'     )->name(isset(FUNCTION_CD['working-time-search']['view']) ? FUNCTION_CD['working-time-search']['view'] : '');
    Route::post('/working-time-search/search', 'WorkingTimeSearchController@postSearch')->name(isset(FUNCTION_CD['working-time-search']['search']) ? FUNCTION_CD['working-time-search']['search'] : '');
});
