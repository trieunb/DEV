<?php

Route::group(['middleware' => ['web', 'authUserDefine'], 'prefix' => 'export', 'namespace' => 'Modules\Export\Http\Controllers'], function()
{
    //ANS342 - KHADV -  product master search.
    Route::post('/suppliers-master-search/output', 'SuppilersExportController@postOutput')->name(isset(FUNCTION_CD['suppliers-master-search']['output']) ? FUNCTION_CD['suppliers-master-search']['output'] : '');

    Route::post('/product-master-search/output', 'ProductExportController@postOutput')->name(isset(FUNCTION_CD['product-master-search']['output']) ? FUNCTION_CD['product-master-search']['output'] : '');
    
    Route::post('/component-master-search/output', 'ComponentExportController@postOutput')->name(isset(FUNCTION_CD['component-master-search']['output']) ? FUNCTION_CD['component-master-search']['output'] : '');

    Route::post('/component-list-search/output', 'ComponentListExportController@postOutput')->name(isset(FUNCTION_CD['component-list-search']['output']) ? FUNCTION_CD['component-list-search']['output'] : '');

    Route::post('/selling-unit-price-by-client-search/output', 'SellingUnitPriceByClientExportController@postOutput')->name(isset(FUNCTION_CD['selling-unit-price-by-client-search']['output']) ? FUNCTION_CD['selling-unit-price-by-client-search']['output'] : '');

    Route::post('/user-master-search/output', 'UserMasterExportController@postOutput')->name(isset(FUNCTION_CD['user-master-search']['output']) ? FUNCTION_CD['user-master-search']['output'] : '');

    Route::post('/pi-output', 'PiExportController@postOutput')->name(isset(FUNCTION_CD['pi-search']['output']) ? FUNCTION_CD['pi-search']['output'] : '');

    Route::post('/pi-export', 'PiExportController@postPiPrint')->name(isset(FUNCTION_CD['pi-search']['print']) ? FUNCTION_CD['pi-search']['print'] : '');

	Route::post('/working-time', 'WorkingTimeExportController@postWorkingTimeOutput')->name(isset(FUNCTION_CD['working-time-search']['output']) ? FUNCTION_CD['working-time-search']['output'] : '');

	Route::post('/stock-search', 'StockSearchExportController@postStockOutput')->name(isset(FUNCTION_CD['stock-search']['output']) ? FUNCTION_CD['stock-search']['output'] : '');

	//ANS810 - DungNN - internal-order-search
    Route::post('/internal-order-search',              'InternalOrderSearchExportController@postInternalOrderOutput')->name(isset(FUNCTION_CD['internalorder-search']['output']) ? FUNCTION_CD['internalorder-search']['output'] : '');
    Route::post('/internal-order-search/export-excel', 'InternalOrderSearchExportController@postExportExcel'        )->name(isset(FUNCTION_CD['internalorder-search']['export-excel']) ? FUNCTION_CD['internalorder-search']['export-excel'] : '');
    Route::post('/internal-order-detail/export-excel', 'InternalOrderSearchExportController@postExportExcel'        )->name(isset(FUNCTION_CD['internalorder-detail']['export-excel']) ? FUNCTION_CD['internalorder-detail']['export-excel'] : '');

    //ANS810 - DungNN - manufacturing-instruction-report
    Route::post('/manufacturing-instruction-report/export-excel',      'ManufacturingInstructionReportExportController@postManufactureReportOutput')->name(isset(FUNCTION_CD['manufacturing-instruction-report']['output']) ? FUNCTION_CD['manufacturing-instruction-report']['output'] : '');
    Route::post('/manufacturing-instruction-report/export-excel-list', 'ManufacturingInstructionReportExportController@postManufactureReportExport')->name(isset(FUNCTION_CD['manufacturing-instruction-report']['export-excel']) ? FUNCTION_CD['manufacturing-instruction-report']['export-excel'] : '');

    //ANS810 - DungNN - packing-list-report
    Route::post('/packing-list-report/export-excel',      'PackingListExportController@postPackingListReportOutput')->name(isset(FUNCTION_CD['packing-list']['output']) ? FUNCTION_CD['packing-list']['output'] : '');
    Route::post('/packing-list-report/export-excel-list', 'PackingListExportController@postPackingListReportExport')->name(isset(FUNCTION_CD['packing-list']['export-excel']) ? FUNCTION_CD['packing-list']['export-excel'] : '');
    Route::post('/invoice-detail/export-excel-list',      'PackingListExportController@postPackingListReportExport')->name(isset(FUNCTION_CD['invoice-detail']['export-list']) ? FUNCTION_CD['invoice-detail']['export-list'] : '');

	Route::post('/accept-search', 'AcceptSearchExportController@postExcelOutput')->name(isset(FUNCTION_CD['accept-search']['output']) ? FUNCTION_CD['accept-search']['output'] : '');

	// Route::post('/internal-order-detail', 'InternalOrderDetailExportController@postExportExcel')->name(isset(FUNCTION_CD['internalorder-detail']['export-excel']) ? FUNCTION_CD['internalorder-detail']['export-excel'] : '');

	Route::post('/stock-manage/input-output-search/export', 'StockIputOutputSearchExportController@postStockInputOutput')->name(isset(FUNCTION_CD['input-output-search']['output']) ? FUNCTION_CD['input-output-search']['output'] : '');

    //ANS810 - DungNN - shifting-request-search
    Route::post('/shifting-request-search/export-excel',      'ShiftingRequestSearchExportController@postShiftingRequestSearchOutput')->name(isset(FUNCTION_CD['shifting-request-search']['output']) ? FUNCTION_CD['shifting-request-search']['output'] : '');
    Route::post('/shifting-request-search/export-excel-list', 'ShiftingRequestSearchExportController@postShiftingRequestSearchIssue' )->name(isset(FUNCTION_CD['shifting-request-search']['export-excel']) ? FUNCTION_CD['shifting-request-search']['export-excel'] : '');
    Route::post('/shifting-request-detail/export-excel-list', 'ShiftingRequestSearchExportController@postShiftingRequestSearchIssue' )->name(isset(FUNCTION_CD['shifting-request-detail']['export-excel']) ? FUNCTION_CD['shifting-request-detail']['export-excel'] : '');


    //ANS831 - Khadv - Provisional Shipment
	Route::post('/provisional-shipment-search',              'ProvisionalShipmentSearchExportController@postProvisionalShipmentOutput')->name(isset(FUNCTION_CD['provisional-shipment-search']['output']) ? FUNCTION_CD['provisional-shipment-search']['output'] : '');
    Route::post('/provisional-shipment-search/export-excel', 'ProvisionalShipmentSearchExportController@postExportExcel'              )->name(isset(FUNCTION_CD['provisional-shipment-search']['export-excel']) ? FUNCTION_CD['provisional-shipment-search']['export-excel'] : '');
	Route::post('/provisional-shipment-detail/export-excel', 'ProvisionalShipmentSearchExportController@postExportExcel'              )->name(isset(FUNCTION_CD['provisional-shipment-detail']['export-excel']) ? FUNCTION_CD['provisional-shipment-detail']['export-excel'] : '');
	
	Route::post('/deposit-search', 'DepositSearchExportController@postDepositOutput')->name(isset(FUNCTION_CD['deposit-search']['output']) ? FUNCTION_CD['deposit-search']['output'] : '');

    Route::post('/component-order-search-output', 'ComponentOrderSearchExportController@postExcelOutput')->name(isset(FUNCTION_CD['order-search']['output']) ? FUNCTION_CD['order-search']['output'] : '');
    Route::post('/component-order-search-export', 'ComponentOrderSearchExportController@postExportExcel')->name(isset(FUNCTION_CD['order-search']['export-excel']) ? FUNCTION_CD['order-search']['export-excel'] : '');

    //ANS806 - Trieunb - Purchase request output
    Route::post('/purchase-request-search/output', 'PurchaseRequestSearchExportController@postOutput')->name(isset(FUNCTION_CD['purchase-request-search']['output']) ? FUNCTION_CD['purchase-request-search']['output'] : '');
    Route::post('/purchase-request-export',        'PurchaseRequestSearchExportController@postExport')->name(isset(FUNCTION_CD['purchase-request-search']['export-excel']) ? FUNCTION_CD['purchase-request-search']['export-excel'] : '');
    Route::post('/purchase-request-detail-export', 'PurchaseRequestSearchExportController@postExport')->name(isset(FUNCTION_CD['purchase-request-detail']['export-excel']) ? FUNCTION_CD['purchase-request-detail']['export-excel'] : '');
    
    //ANS806 - Trieunb - shipment output
    Route::post('/shipment/output', 'ShipmentExportController@postOutput')->name(isset(FUNCTION_CD['shipment-search']['output']) ? FUNCTION_CD['shipment-search']['output'] : '');
    //ANS831 - Khadv - Shipment Export Excel
    Route::post('/shipment-search/export-excel', 'ShipmentExportController@postExportExcel')->name(isset(FUNCTION_CD['shipment-search']['export-excel']) ? FUNCTION_CD['shipment-search']['export-excel'] : '');
    Route::post('/shipment-detail/export-excel', 'ShipmentExportController@postExportExcel')->name(isset(FUNCTION_CD['shipment-detail']['export-excel']) ? FUNCTION_CD['shipment-detail']['export-excel'] : '');

    Route::post('/invoice-search',                      'InvoiceSearchExportController@postExcelOutput'  )->name(isset(FUNCTION_CD['invoice-search']['output']) ? FUNCTION_CD['invoice-search']['output'] : '');
    Route::post('/invoice-search/invoice-export',       'InvoiceSearchExportController@postInvoiceExport')->name(isset(FUNCTION_CD['invoice-search']['invoice-export']) ? FUNCTION_CD['invoice-search']['invoice-export'] : '');
    Route::post('/invoice-search/delivery-note-export', 'InvoiceSearchExportController@postInvoiceExport')->name(isset(FUNCTION_CD['invoice-search']['delivery-note-export']) ? FUNCTION_CD['invoice-search']['delivery-note-export'] : '');
    Route::post('/invoice-detail/invoice-export',       'InvoiceSearchExportController@postInvoiceExport')->name(isset(FUNCTION_CD['invoice-detail']['invoice-export']) ? FUNCTION_CD['invoice-detail']['invoice-export'] : '');
    Route::post('/invoice-detail/delivery-note-export', 'InvoiceSearchExportController@postInvoiceExport')->name(isset(FUNCTION_CD['invoice-detail']['delivery-note-export']) ? FUNCTION_CD['invoice-detail']['delivery-note-export'] : '');

    Route::post('/invoice-detail/print-mark', 'InvoiceDetailExportController@postPrintMark')->name(isset(FUNCTION_CD['invoice-detail']['print']) ? FUNCTION_CD['invoice-detail']['print'] : '');

    //manufacturing-instruction-search
    Route::post('/manufacturing-instruction-search/export-excel',      'ManufacturingInstructionSearchExportController@postManufacturingSearchOutput')->name(isset(FUNCTION_CD['manufacturing-instruction-search']['output']) ? FUNCTION_CD['manufacturing-instruction-search']['output'] : '');
    Route::post('/manufacturing-instruction-search/export-excel-list', 'ManufacturingInstructionSearchExportController@postManufacturingSearchExport')->name(isset(FUNCTION_CD['manufacturing-instruction-search']['export-excel']) ? FUNCTION_CD['manufacturing-instruction-search']['export-excel'] : '');

    //ANS804 - DaoNX - stocking-detail
    Route::post('/stocking-detail/export-excel', 'StockingDetailExportController@postExportExcel')->name(isset(FUNCTION_CD['stocking-detail']['output']) ? FUNCTION_CD['stocking-detail']['output'] : '');
    Route::post('/stocking-search-output', 'StockingSearchExportController@postExcelOutput')->name(isset(FUNCTION_CD['stocking-search']['output']) ? FUNCTION_CD['stocking-search']['output'] : '');
});
