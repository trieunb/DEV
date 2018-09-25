<?php

Route::group(['middleware' => ['web', 'authUserDefine'], 'prefix' => 'popup', 'namespace' => 'Modules\Popup\Http\Controllers'], function()
{
    /*Search Lecturer*/
    Route::get('/search/city', 'CitySearchController@getSearchCity' )->name(isset(FUNCTION_CD['popup-city-search']['view']) ? FUNCTION_CD['popup-city-search']['view'] : '');
    Route::post('/search/city','CitySearchController@postSearchCity')->name(isset(FUNCTION_CD['popup-city-search']['search']) ? FUNCTION_CD['popup-city-search']['search'] : '');

    Route::get('/search/country', 'CountrySearchController@getSearchCountry' )->name(isset(FUNCTION_CD['popup-country-search']['view']) ? FUNCTION_CD['popup-country-search']['view'] : '');
    Route::post('/search/country','CountrySearchController@postSearchCountry')->name(isset(FUNCTION_CD['popup-country-search']['search']) ? FUNCTION_CD['popup-country-search']['search'] : '');

    Route::get('/search/user', 		   'UserSearchController@getIndex'  )->name(isset(FUNCTION_CD['popup-user-search']['view']) ? FUNCTION_CD['popup-user-search']['view'] : '');
    Route::post('/search/user-search', 'UserSearchController@postSearch')->name(isset(FUNCTION_CD['popup-user-search']['search']) ? FUNCTION_CD['popup-user-search']['search'] : '');

	Route::get('/search/pi',  'PiSearchController@getList'   )->name(isset(FUNCTION_CD['popup-pi-search']['view']) ? FUNCTION_CD['popup-pi-search']['view'] : '');
	Route::post('/pi/search', 'PiSearchController@postSearch')->name(isset(FUNCTION_CD['popup-pi-search']['search']) ? FUNCTION_CD['popup-pi-search']['search'] : '');

	Route::get('/search/suppliers',  'SuppliersMasterSearchController@getSearch' )->name(isset(FUNCTION_CD['popup-suppliers-master-search']['view']) ? FUNCTION_CD['popup-suppliers-master-search']['view'] : '');
	Route::post('/search/suppliers', 'SuppliersMasterSearchController@postSearch')->name(isset(FUNCTION_CD['popup-suppliers-master-search']['search']) ? FUNCTION_CD['popup-suppliers-master-search']['search'] : '');

	Route::get('/search/product', 		  'ProductMasterSearchController@getSearch' )->name(isset(FUNCTION_CD['popup-product-master-search']['view']) ? FUNCTION_CD['popup-product-master-search']['view'] : '');
	Route::post('/search/product-search', 'ProductMasterSearchController@postSearch')->name(isset(FUNCTION_CD['popup-product-master-search']['search']) ? FUNCTION_CD['popup-product-master-search']['search'] : '');

	Route::get('/search/shipment', 	'ShipmentSearchController@getSearch' )->name(isset(FUNCTION_CD['popup-shipment-search']['view']) ? FUNCTION_CD['popup-shipment-search']['view'] : '');
	Route::post('/search/shipment', 'ShipmentSearchController@postSearch')->name(isset(FUNCTION_CD['popup-shipment-search']['search']) ? FUNCTION_CD['popup-shipment-search']['search'] : '');

	Route::get('/search/provisional-shipment', 		   'ProvisionalShipmentSearchController@getSearch' )->name(isset(FUNCTION_CD['popup-provisional-shipment-search']['view']) ? FUNCTION_CD['popup-provisional-shipment-search']['view'] : '');
	Route::post('/provisional-shipment-search/search', 'ProvisionalShipmentSearchController@postSearch')->name(isset(FUNCTION_CD['popup-provisional-shipment-search']['search']) ? FUNCTION_CD['popup-provisional-shipment-search']['search'] : '');

    Route::get('/search/accept',  'AcceptSearchController@getSearch' )->name(isset(FUNCTION_CD['popup-accept-search']['view']) ? FUNCTION_CD['popup-accept-search']['view'] : '');
	Route::post('/search/accept', 'AcceptSearchController@postSearch')->name(isset(FUNCTION_CD['popup-accept-search']['search']) ? FUNCTION_CD['popup-accept-search']['search'] : '');
	
	Route::get('/search/deposit', 		  'DepositSearchController@index'	  )->name(isset(FUNCTION_CD['popup-deposit-search']['view']) ? FUNCTION_CD['popup-deposit-search']['view'] : '');
	Route::post('/search/deposit-search', 'DepositSearchController@postSearch')->name(isset(FUNCTION_CD['popup-deposit-search']['search']) ? FUNCTION_CD['popup-deposit-search']['search'] : '');

	Route::get('/search/component',			'ComponentMasterSearchController@getSearch' )->name(isset(FUNCTION_CD['popup-component-master-search']['view']) ? FUNCTION_CD['popup-component-master-search']['view'] : '');
	Route::post('/search/component-search', 'ComponentMasterSearchController@postSearch')->name(isset(FUNCTION_CD['popup-component-master-search']['search']) ? FUNCTION_CD['popup-component-master-search']['search'] : '');

	Route::get('/search/componentproduct', 			'ComponentProductSearchController@getSearch' )->name(isset(FUNCTION_CD['popup-component-product-search']['view']) ? FUNCTION_CD['popup-component-product-search']['view'] : '');
	Route::post('/component-product-detail/search', 'ComponentProductSearchController@postSearch')->name(isset(FUNCTION_CD['popup-component-product-search']['search']) ? FUNCTION_CD['popup-component-product-search']['search'] : '');

	Route::get('/search/warehouse', 'WarehouseSearchController@getSearchWarehouse' )->name(isset(FUNCTION_CD['popup-warehouse-search']['view']) ? FUNCTION_CD['popup-warehouse-search']['view'] : '');
    Route::post('/search/warehouse','WarehouseSearchController@postSearchWarehouse')->name(isset(FUNCTION_CD['popup-warehouse-search']['search']) ? FUNCTION_CD['popup-warehouse-search']['search'] : '');

	Route::get('/search/shifting',  'ShiftingRequestSearchController@getSearch' )->name(isset(FUNCTION_CD['popup-shifting-request-search']['view']) ? FUNCTION_CD['popup-shifting-request-search']['view'] : '');
	Route::post('/search/shifting', 'ShiftingRequestSearchController@postSearch')->name(isset(FUNCTION_CD['popup-shifting-request-search']['search']) ? FUNCTION_CD['popup-shifting-request-search']['search'] : '');

	Route::get('/search/internalorder', 		'InternalOrderSearchController@getSearch' )->name(isset(FUNCTION_CD['popup-internalorder-search']['view']) ? FUNCTION_CD['popup-internalorder-search']['view'] : '');
	Route::post('/search/internalorder/search', 'InternalOrderSearchController@postSearch')->name(isset(FUNCTION_CD['popup-internalorder-search']['search']) ? FUNCTION_CD['popup-internalorder-search']['search'] : '');

	Route::get('/search/order', 'OrderSearchController@getOrderSearch')->name(isset(FUNCTION_CD['popup-order-search']['view']) ? FUNCTION_CD['popup-order-search']['view'] : '');
	Route::post('/search/order', 'OrderSearchController@postOrderSearch')->name(isset(FUNCTION_CD['popup-order-search']['search']) ? FUNCTION_CD['popup-order-search']['search'] : '');
	
	//ANS806 - Trieunb - Purchase Request
	Route::get('/search/purchaserequest',  'PurchaseRequestSearchController@getSearch' )->name(isset(FUNCTION_CD['popup-purchaserequest-request-search']['view']) ? FUNCTION_CD['popup-purchaserequest-request-search']['view'] : '');
	Route::post('/search/purchaserequest', 'PurchaseRequestSearchController@postSearch')->name(isset(FUNCTION_CD['popup-purchaserequest-request-search']['search']) ? FUNCTION_CD['popup-purchaserequest-request-search']['search'] : '');

	Route::get('/search/inputoutput',   'InputOutputSearchController@getSearch' )->name(isset(FUNCTION_CD['popup-input-output-search']['view']) ? FUNCTION_CD['popup-input-output-search']['view'] : '');
	Route::post('/search/input-output', 'InputOutputSearchController@postSearch')->name(isset(FUNCTION_CD['popup-input-output-search']['search']) ? FUNCTION_CD['popup-input-output-search']['search'] : '');

	Route::get('/search/stockmanage', 'StockManageSearchController@getSearch')->name(isset(FUNCTION_CD['popup-stock-manage-search']['view']) ? FUNCTION_CD['popup-stock-manage-search']['view'] : '');

	Route::get('/search/workingtime', 		  'WorkingTimeSearchController@getSearch' )->name(isset(FUNCTION_CD['popup-working-time-search']['view']) ? FUNCTION_CD['popup-working-time-search']['view'] : '');
	Route::post('/search/workingtime-search', 'WorkingTimeSearchController@postSearch')->name(isset(FUNCTION_CD['popup-working-time-search']['search']) ? FUNCTION_CD['popup-working-time-search']['search'] : '');
	// ANS806 - Trieunb - Input Output

	// ANS806 - Trieunb - shipment serial
	Route::get('/search/shipmentserial', 		 'ShipmentSerialSearchController@getSearch'		 )->name(isset(FUNCTION_CD['popup-shipment-serial-search']['view']) ? FUNCTION_CD['popup-shipment-serial-search']['view'] : '');
	Route::post('/search/shipment-serial', 		 'ShipmentSerialSearchController@postSearch'	 )->name(isset(FUNCTION_CD['popup-shipment-serial-search']['search']) ? FUNCTION_CD['popup-shipment-serial-search']['search'] : '');
	Route::post('/search/shipment-serial/index', 'ShipmentSerialSearchController@postSearchIndex');

	Route::get('/search/cartonitemset', 'CartonItemSetController@getCartonItemSet')->name(isset(FUNCTION_CD['popup-carton-item-set']['view']) ? FUNCTION_CD['popup-carton-item-set']['view'] : '');
	
	Route::get('/search/manufacturinginstruction',  	  'ManufacturingInstructionSearchController@getSearch')->name(isset(FUNCTION_CD['popup-manufacturing-instruction-search']['view']) ? FUNCTION_CD['popup-manufacturing-instruction-search']['view'] : '');
	Route::post('/search/manufacturinginstruction-search', 'ManufacturingInstructionSearchController@postSearch')->name(isset(FUNCTION_CD['popup-manufacturing-instruction-search']['search']) ? FUNCTION_CD['popup-manufacturing-instruction-search']['search'] : '');

	Route::get('/search/check-list', 'CheckListController@getSearch')->name(isset(FUNCTION_CD['popup-check-list']['view']) ? FUNCTION_CD['popup-check-list']['view'] : '');

    Route::get('/search/invoice',  'InvoiceSearchController@getSearch' )->name(isset(FUNCTION_CD['popup-invoice-search']['view']) ? FUNCTION_CD['popup-invoice-search']['view'] : '');
	Route::post('/search/invoice', 'InvoiceSearchController@postSearch')->name(isset(FUNCTION_CD['popup-invoice-search']['search']) ? FUNCTION_CD['popup-invoice-search']['search'] : '');

	Route::get('/search/shiftingserial',				  'ShiftingSerialSearchController@getSearch'		)->name(isset(FUNCTION_CD['popup-shifting-serial-search']['view']) ? FUNCTION_CD['popup-shifting-serial-search']['view'] : '');
	Route::post('/search/shifting-serial',				  'ShiftingSerialSearchController@postSearch'		)->name(isset(FUNCTION_CD['popup-shifting-serial-search']['search']) ? FUNCTION_CD['popup-shifting-serial-search']['search'] : '');
	Route::post('/search/shifting-serial/item-requested', 'ShiftingSerialSearchController@postItemRequested');

	Route::get('/changePassword/index', 'ChangePasswordController@getIndex')->name(isset(FUNCTION_CD['popup-change-password']['view']) ? FUNCTION_CD['popup-change-password']['view'] : '');
	Route::post('/changePassword/save', 'ChangePasswordController@postSave')->name(isset(FUNCTION_CD['popup-change-password']['save']) ? FUNCTION_CD['popup-change-password']['save'] : '');

});
