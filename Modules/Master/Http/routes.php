<?php

Route::group(['middleware' => ['web', 'authUserDefine'], 'prefix' => 'master', 'namespace' => 'Modules\Master\Http\Controllers'], function()
{
    Route::get('/suppliers-master-maintenance',         'SuppliersMasterMaintenanceController@getDetail' )->name(isset(FUNCTION_CD['suppliers-master-maintenance']['view']) ? FUNCTION_CD['suppliers-master-maintenance']['view'] : '');
    Route::post('/suppliers-master-maintenance/save',   'SuppliersMasterMaintenanceController@postSave'  )->name(isset(FUNCTION_CD['suppliers-master-maintenance']['save']) ? FUNCTION_CD['suppliers-master-maintenance']['save'] : '');
    Route::post('/suppliers-master-maintenance/delete', 'SuppliersMasterMaintenanceController@postDelete')->name(isset(FUNCTION_CD['suppliers-master-maintenance']['delete']) ? FUNCTION_CD['suppliers-master-maintenance']['delete'] : '');
    Route::post('/suppliers-master-maintenance/refer',  'SuppliersMasterMaintenanceController@postRefer');
    
    Route::get('/suppliers-master-search',         'SuppliersMasterSearchController@getSearch' )->name(isset(FUNCTION_CD['suppliers-master-search']['view']) ? FUNCTION_CD['suppliers-master-search']['view'] : '');
    Route::post('/suppliers-master-search/search', 'SuppliersMasterSearchController@postSearch')->name(isset(FUNCTION_CD['suppliers-master-search']['search']) ? FUNCTION_CD['suppliers-master-search']['search'] : '');

    Route::get('/product-master-detail',         'ProductMasterDetailController@getDetail' )->name(isset(FUNCTION_CD['product-master-detail']['view']) ? FUNCTION_CD['product-master-detail']['view'] : '');
    Route::post('/product-master-detail/save',   'ProductMasterDetailController@postSave'  )->name(isset(FUNCTION_CD['product-master-detail']['save']) ? FUNCTION_CD['product-master-detail']['save'] : '');
    Route::post('/product-master-detail/delete', 'ProductMasterDetailController@postDelete')->name(isset(FUNCTION_CD['product-master-detail']['delete']) ? FUNCTION_CD['product-master-detail']['delete'] : '');
    Route::post('/product-master-detail/refer',  'ProductMasterDetailController@postRefer');

    Route::get('/product-master-search',         'ProductMasterSearchController@getSearch' )->name(isset(FUNCTION_CD['product-master-search']['view']) ? FUNCTION_CD['product-master-search']['view'] : '');
    Route::post('/product-master-search/search', 'ProductMasterSearchController@postSearch')->name(isset(FUNCTION_CD['product-master-search']['search']) ? FUNCTION_CD['product-master-search']['search'] : '');

    Route::get('/component-master-detail',                       'ComponentMasterDetailController@getDetail' )->name(isset(FUNCTION_CD['component-master-detail']['view']) ? FUNCTION_CD['component-master-detail']['view'] : '');
    Route::post('/component-master-detail/save',                 'ComponentMasterDetailController@postSave'  )->name(isset(FUNCTION_CD['component-master-detail']['save']) ? FUNCTION_CD['component-master-detail']['save'] : '');
    Route::post('/component-master-detail/delete',               'ComponentMasterDetailController@postDelete')->name(isset(FUNCTION_CD['component-master-detail']['delete']) ? FUNCTION_CD['component-master-detail']['delete'] : '');  
    Route::post('/component-master-detail/refer-part',           'ComponentMasterDetailController@postReferPart');
    Route::post('/component-master-detail/refer-purchase-price', 'ComponentMasterDetailController@postReferPurchasePrice');

    Route::get('/component-master-search',         'ComponentMasterSearchController@getSearch' )->name(isset(FUNCTION_CD['component-master-search']['view']) ? FUNCTION_CD['component-master-search']['view'] : '');
    Route::post('/component-master-search/search', 'ComponentMasterSearchController@postSearch')->name(isset(FUNCTION_CD['component-master-search']['search']) ? FUNCTION_CD['component-master-search']['search'] : '');

    Route::get('/component-list-detail',           'ComponentListDetailController@getDetail')->name(isset(FUNCTION_CD['component-list-detail']['view']) ? FUNCTION_CD['component-list-detail']['view'] : '');
    Route::post('/component-list-detail/save',     'ComponentListDetailController@postSave' )->name(isset(FUNCTION_CD['component-list-detail']['save']) ? FUNCTION_CD['component-list-detail']['save'] : '');
    Route::post('/component-list-detail/delete',   'ComponentListDetailController@deleteBom')->name(isset(FUNCTION_CD['component-list-detail']['delete']) ? FUNCTION_CD['component-list-detail']['delete'] : '');
    Route::get('/component-list-detail/refer-bom', 'ComponentListDetailController@postRefer');

    Route::get('/component-list-search',         'ComponentListSearchController@getSearch' )->name(isset(FUNCTION_CD['component-list-search']['view']) ? FUNCTION_CD['component-list-search']['view'] : '');
    Route::post('/component-list-search/search', 'ComponentListSearchController@postSearch')->name(isset(FUNCTION_CD['component-list-search']['search']) ? FUNCTION_CD['component-list-search']['search'] : '');
    Route::post('/component-list-search/upload', 'ComponentListSearchController@postUpload')->name(isset(FUNCTION_CD['component-list-search']['upload']) ? FUNCTION_CD['component-list-search']['upload'] : '');

    Route::get('/selling-unit-price-by-client-detail',         'SellingUnitPriceByClientDetailController@getDetail' )->name(isset(FUNCTION_CD['selling-unit-price-by-client-detail']['view']) ? FUNCTION_CD['selling-unit-price-by-client-detail']['view'] : '');
    Route::post('/selling-unit-price-by-client-detail/save',   'SellingUnitPriceByClientDetailController@postSave'  )->name(isset(FUNCTION_CD['selling-unit-price-by-client-detail']['save']) ? FUNCTION_CD['selling-unit-price-by-client-detail']['save'] : '');
    Route::post('/selling-unit-price-by-client-detail/delete', 'SellingUnitPriceByClientDetailController@postDelete')->name(isset(FUNCTION_CD['selling-unit-price-by-client-detail']['delete']) ? FUNCTION_CD['selling-unit-price-by-client-detail']['delete'] : '');
    Route::post('/selling-unit-price-by-client-detail/refer',  'SellingUnitPriceByClientDetailController@postRefer');

    Route::get('/selling-unit-price-by-client-search',         'SellingUnitPriceByClientSearchController@index'     )->name(isset(FUNCTION_CD['selling-unit-price-by-client-search']['view']) ? FUNCTION_CD['selling-unit-price-by-client-search']['view'] : '');
    Route::post('/selling-unit-price-by-client-search/search', 'SellingUnitPriceByClientSearchController@postSearch')->name(isset(FUNCTION_CD['selling-unit-price-by-client-search']['search']) ? FUNCTION_CD['selling-unit-price-by-client-search']['search'] : '');
	
	Route::get('/user-master-detail',         'UserMasterDetailController@getDetail' )->name(isset(FUNCTION_CD['user-master-detail']['view']) ? FUNCTION_CD['user-master-detail']['view'] : '');
    Route::post('/user-master-detail/save',   'UserMasterDetailController@postSave'  )->name(isset(FUNCTION_CD['user-master-detail']['save']) ? FUNCTION_CD['user-master-detail']['save'] : '');
    Route::post('/user-master-detail/delete', 'UserMasterDetailController@postDelete')->name(isset(FUNCTION_CD['user-master-detail']['delete']) ? FUNCTION_CD['user-master-detail']['delete'] : '');
    Route::post('/user-master-detail/refer',  'UserMasterDetailController@postRefer');

    Route::get('/user-master-search',          'UserMasterSearchController@index'     )->name(isset(FUNCTION_CD['user-master-search']['view']) ? FUNCTION_CD['user-master-search']['view'] : '');
    Route::post('/user-master-search/search',  'UserMasterSearchController@postSearch')->name(isset(FUNCTION_CD['user-master-search']['search']) ? FUNCTION_CD['user-master-search']['search'] : '');
});
