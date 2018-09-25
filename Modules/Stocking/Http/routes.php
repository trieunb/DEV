<?php

Route::group(['middleware' => ['web', 'authUserDefine'], 'prefix' => 'stocking', 'namespace' => 'Modules\Stocking\Http\Controllers'], function()
{
    Route::get('/stocking-detail', 		   'StockingDetailController@getSearch' )->name(isset(FUNCTION_CD['stocking-detail']['view']) ? FUNCTION_CD['stocking-detail']['view'] : '');
    Route::post('/stocking-detail/search', 'StockingDetailController@postSearch')->name(isset(FUNCTION_CD['stocking-detail']['search']) ? FUNCTION_CD['stocking-detail']['search'] : '');
    Route::post('/stocking-detail/save',   'StockingDetailController@postSave'  )->name(isset(FUNCTION_CD['stocking-detail']['save']) ? FUNCTION_CD['stocking-detail']['save'] : '');

    Route::get('/check-outside-ordered-products', 'CheckOutsideOrderedProductsController@getSearch')->name(isset(FUNCTION_CD['check-outside-ordered-products']['view']) ? FUNCTION_CD['check-outside-ordered-products']['view'] : '');
    Route::get('/stocking-search',                   'StockingSearchController@getStockingSearch'	)->name(isset(FUNCTION_CD['stocking-search']['view']) ? FUNCTION_CD['stocking-search']['view'] : '');
    Route::post('/stocking-search',                  'StockingSearchController@postStockingSearch'   )->name(isset(FUNCTION_CD['stocking-search']['search']) ? FUNCTION_CD['stocking-search']['search'] : '');
    Route::get('/stocking-update', 'StockingUpdateController@getIndex' )->name(isset(FUNCTION_CD['stocking-update']['view']) ? FUNCTION_CD['stocking-update']['view'] : '');
    Route::post('/stocking-update/save', 'StockingUpdateController@postSave'  )->name(isset(FUNCTION_CD['stocking-update']['save']) ? FUNCTION_CD['stocking-update']['save'] : '');
    Route::post('/stocking-update/delete', 'StockingUpdateController@postDelete'  )->name(isset(FUNCTION_CD['stocking-update']['delete']) ? FUNCTION_CD['stocking-update']['delete'] : '');
});
