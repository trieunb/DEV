<?php

Route::group(['middleware' => ['web', 'authUserDefine'], 'prefix' => 'oversea-document', 'namespace' => 'Modules\OverseaDocument\Http\Controllers'], function()
{
    Route::get('/packing-list', 		'PackingListController@getSearch' )->name(isset(FUNCTION_CD['packing-list']['view']) ? FUNCTION_CD['packing-list']['view'] : '');
    Route::post('/packing-list/search', 'PackingListController@postSearch')->name(isset(FUNCTION_CD['packing-list']['search']) ? FUNCTION_CD['packing-list']['search'] : '');
});
