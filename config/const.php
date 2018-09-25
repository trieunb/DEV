<?php
/*
*
* Valiable constants for all project
* Vulq - creater - 2017/03/29
*
*/

// DELEMITER  : for String concat
define('DELIMITER', 		'|#|@');


// DOWNLOAD_FOLDER : PDF帳票出力のため
define('DOWNLOAD_FOLDER_PUBLIC', 	public_path() . '\download\\');
define('DOWNLOAD_CSV_PUBLIC', 	public_path() . '\download\csv\\');
// forder down load and store excel
define('DOWNLOAD_EXCEL', 	'\download\excel\\');
define('DOWNLOAD_EXCEL_PUBLIC', 	public_path() . DOWNLOAD_EXCEL);


// UPLOAD_FOLDER : PDF帳票出力のため
define('UPLOAD_FOLDER', 	public_path() . '\upload\\');
define('UPLOAD_CSV', 	public_path() . '\upload\csv\\');
define('UPLOAD_EXCEL', 	public_path() . '\upload\excel\\');

// TEMP_FOLDER : PDF帳票出力のため
define('TEMP_FOLDER', 	public_path() . '\temp\\');

// CSV_BACKUP_PATH : Backup file directory for CSV data import
define('CSV_BACKUP_PATH', 	public_path() . '\upload\csv_backup\\');

// CSV_BACKUP_PATH : Backup file directory for CSV data import
define('LINK_REFER_UPLOAD_FOLDER', public_path() . '\upload\link_refer\\');

// CSV_EXPORT_PATH : Directory for CSV,Excel data Export
define('CSV_EXPORT_PATH', 	public_path() . '\download\csv_export\\');

//get function list
$arrFunctionList = [
	"accept-detail" => [	
		"approve"        =>	"accept-detail__approve",
		"approve-cancel" =>	"accept-detail__approve-cancel",
		"cancel-order"   =>	"accept-detail__cancel-order",
		"delete"         =>	"accept-detail__delete",
		"save"           =>	"accept-detail__save",
		"view"           =>	"accept-detail__view",
	],
	"accept-search" => [
		"approve"        =>	"accept-search__approve",
		"output"         =>	"accept-search__output",
		"search"         =>	"accept-search__search",
		"view"           =>	"accept-search__view",
	],
	"authority" => [
		"output"         =>	"authority__output",
		"save"           =>	"authority__save",
		"search"         =>	"authority__search",
		"upload"         =>	"authority__upload",
		"view"           =>	"authority__view",
	],
	"check-outside-ordered-products" => [
		"save"           =>	"check-outside-ordered-products__save",
		"search"         =>	"check-outside-ordered-products__search",
		"view"           =>	"check-outside-ordered-products__view",
	],
	"component-list-detail" => [
		"delete"         =>	"component-list-detail__delete",
		"save"           =>	"component-list-detail__save",
		"view"           =>	"component-list-detail__view",
	],
	"component-list-search" => [
		"search"         =>	"component-list-search__search",
		"upload"         =>	"component-list-search__upload",
		"view"           =>	"component-list-search__view",
	],
	"component-master-detail" => [
		"delete"         =>	"component-master-detail__delete",
		"save"           =>	"component-master-detail__save",
		"view"           =>	"component-master-detail__view",
	],
	"component-master-search" => [
		"search"         =>	"component-master-search__search",
		"view"           =>	"component-master-search__view",
	],
	"deposit-detail" => [
		"delete"         =>	"deposit-detail__delete",
		"save"           =>	"deposit-detail__save",
		"view"           =>	"deposit-detail__view",
	],
	"deposit-search" => [
		"output" 		 =>	"deposit-search__output",
		"search"         =>	"deposit-search__search",
		"view"           =>	"deposit-search__view",
	],
	"input-output-detail" => [
		"save"           =>	"input-output-detail__save",
		"view"           =>	"input-output-detail__view",
	],
	"input-output-search" => [
		"output"         =>	"input-output-search__output",
		"search"         =>	"input-output-search__search",
		"view"           =>	"input-output-search__view",
	],
	"internalorder-detail" => [
		"delete"         =>	"internalorder-detail__delete",
		"export-excel"   =>	"internalorder-detail__export-excel",
		"save"           =>	"internalorder-detail__save",
		"view"           =>	"internalorder-detail__view",
	],
	"internalorder-search" => [
		"export-excel"   =>	"internalorder-search__export-excel",
		"output"         =>	"internalorder-search__output",
		"search"         =>	"internalorder-search__search",
		"view"           =>	"internalorder-search__view",
	],
	"invoice-detail" => [
		"delete"         		=>	"invoice-detail__delete",
		"delivery-note-export" 	=>	"invoice-detail__delivery-note-export",
		"export-list"    		=>	"invoice-detail__export-list",
		"invoice-export" 		=>	"invoice-detail__invoice-export",
		"print"          		=>	"invoice-detail__print",
		"save"           		=>	"invoice-detail__save",
		"view"           		=>	"invoice-detail__view",
	],
	"invoice-search" => [
		"delivery-note-export" 	=>	"invoice-search__delivery-note-export",
		"invoice-export" 		=>	"invoice-search__invoice-export",
		"output"         		=>	"invoice-search__output",
		"search"         		=>	"invoice-search__search",
		"view"           		=>	"invoice-search__view",
	],
	"library-master" => [
		"save"           =>	"library-master__save",
		"view"           =>	"library-master__view",
	],
	"library-master-search" => [
		"search"         =>	"library-master-search__search",
		"view"           =>	"library-master-search__view",
	],
	"manufacturing-completion-process" => [
		"save"           =>	"manufacturing-completion-process__save",
		"view"           =>	"manufacturing-completion-process__view",
	],
	"manufacturing-instruction-report" => [
		"export-excel"   =>	"manufacturing-instruction-report__export-excel",
		"output"         =>	"manufacturing-instruction-report__output",
		"search"         =>	"manufacturing-instruction-report__search",
		"view"           =>	"manufacturing-instruction-report__view",
	],
	"manufacturing-instruction-search" => [
		"export-excel"   =>	"manufacturing-instruction-search__export-excel",
		"output"         =>	"manufacturing-instruction-search__output",
		"save"           =>	"manufacturing-instruction-search__save",
		"search"         =>	"manufacturing-instruction-search__search",
		"view"           =>	"manufacturing-instruction-search__view",
	],
	"order-confirm" => [
		"save"           =>	"order-confirm__save",
		"search"         =>	"order-confirm__search",
		"view"           =>	"order-confirm__view",
	],
	"order-detail" => [
		"approve"        =>	"order-detail__approve",
		"approve-cancel" =>	"order-detail__approve-cancel",
		"delete"         =>	"order-detail__delete",
		"export-excel"   =>	"order-detail__export-excel",
		"save"           =>	"order-detail__save",
		"view"           =>	"order-detail__view",
	],
	"order-search" => [
		"export-excel"   =>	"order-search__export-excel",
		"output"         =>	"order-search__output",
		"search"         =>	"order-search__search",
		"view"           =>	"order-search__view",
	],
	"packing-list" => [
		"export-excel"   =>	"packing-list__export-excel",
		"output"         =>	"packing-list__output",
		"search"         =>	"packing-list__search",
		"view"           =>	"packing-list__view",
	],
	"pi-detail" => [
		"approve"        =>	"pi-detail__approve",
		"approve-cancel" =>	"pi-detail__approve-cancel",
		"delete"         =>	"pi-detail__delete",
		"download"       =>	"pi-detail__download",
		"print"          =>	"pi-detail__print",
		"save"           =>	"pi-detail__save",
		"upload"         =>	"pi-detail__upload",
		"view"           =>	"pi-detail__view",
	],
	"pi-search" => [
		"approve"        =>	"pi-search__approve",
		"print"          =>	"pi-search__print",
		"search"         =>	"pi-search__search",
		"view"           =>	"pi-search__view",
	],
	"product-master-detail" => [
		"delete"         =>	"product-master-detail__delete",
		"save"           =>	"product-master-detail__save",
		"view"           =>	"product-master-detail__view",
	],
	"product-master-search" => [
		"search"         =>	"product-master-search__search",
		"view"           =>	"product-master-search__view",
	],
	"provisional-shipment-detail" => [
		"delete"         =>	"provisional-shipment-detail__delete",
		"export-excel"   =>	"provisional-shipment-detail__export-excel",
		"save"           =>	"provisional-shipment-detail__save",
		"view"           =>	"provisional-shipment-detail__view",
	],
	"provisional-shipment-search" => [
		"export-excel"   =>	"provisional-shipment-search__export-excel",
		"output"         =>	"provisional-shipment-search__output",
		"search"         =>	"provisional-shipment-search__search",
		"view"           =>	"provisional-shipment-search__view",
	],
	"purchase-request-detail" => [
		"approve"        =>	"purchase-request-detail__approve",
		"delete"         =>	"purchase-request-detail__delete",
		"export-excel"   =>	"purchase-request-detail__export-excel",
		"save"           =>	"purchase-request-detail__save",
		"view"           =>	"purchase-request-detail__view",
	],
	"purchase-request-search" => [
		"approve"        =>	"purchase-request-search__approve",
		"export-excel"   =>	"purchase-request-search__export-excel",
		"output"         =>	"purchase-request-search__output",
		"search"         =>	"purchase-request-search__search",
		"view"           =>	"purchase-request-search__view",
	],
	"selling-unit-price-by-client-detail" => [
		"delete"         =>	"selling-unit-price-by-client-detail__delete",
		"save"           =>	"selling-unit-price-by-client-detail__save",
		"view"           =>	"selling-unit-price-by-client-detail__view",
	],
	"selling-unit-price-by-client-search" => [
		"search"           =>	"selling-unit-price-by-client-search__search",
		"view"           =>	"selling-unit-price-by-client-search__view",
	],
	"shifting-request-detail" => [
		"approve"        =>	"shifting-request-detail__approve",
		"delete"         =>	"shifting-request-detail__delete",
		"export-excel"   =>	"shifting-request-detail__export-excel",
		"save"           =>	"shifting-request-detail__save",
		"view"           =>	"shifting-request-detail__view",
	],
	"shifting-request-search" => [
		"approve"        =>	"shifting-request-search__approve",
		"export-excel"   =>	"shifting-request-search__export-excel",
		"output"         =>	"shifting-request-search__output",
		"search"         =>	"shifting-request-search__search",
		"view"           =>	"shifting-request-search__view",
	],
	"shipment-detail" => [
		"approve"        =>	"shipment-detail__approve",
		"approve-cancel" =>	"shipment-detail__approve-cancel",
		"delete"         =>	"shipment-detail__delete",
		"export-excel"   =>	"shipment-detail__export-excel",
		"save"           =>	"shipment-detail__save",
		"view"           =>	"shipment-detail__view",
	],
	"shipment-search" => [
		"approve"        =>	"shipment-search__approve",
		"export-excel"   =>	"shipment-search__export-excel",
		"output"         =>	"shipment-search__output",
		"search"         =>	"shipment-search__search",
		"view"           =>	"shipment-search__view",
	],
	"stocking-detail" => [
		"output"         =>	"stocking-detail__output",
		"save"           =>	"stocking-detail__save",
		"search"         =>	"stocking-detail__search",
		"view"           =>	"stocking-detail__view",
	],
	"stock-search" => [
		"output"         =>	"stock-search__output",
		"search"         =>	"stock-search__search",
		"view"           =>	"stock-search__view",
	],
	"stocking-search" => [
		"output"         =>	"stocking-search__output",
		"search"         =>	"stocking-search__search",
		"view"           =>	"stocking-search__view",
	],
	"suppliers-master-maintenance" => [
		"delete"         =>	"suppliers-master-maintenance__delete",
		"save"           =>	"suppliers-master-maintenance__save",
		"view"           =>	"suppliers-master-maintenance__view",
	],
	"suppliers-master-search" => [
		"search"         =>	"suppliers-master-search__search",
		"view"           =>	"suppliers-master-search__view",
	],
	"user-master-detail" => [
		"delete"         =>	"user-master-detail__delete",
		"save"           =>	"user-master-detail__save",
		"view"           =>	"user-master-detail__view",
	],
	"user-master-search" => [
		"search"         =>	"user-master-search__search",
		"view"           =>	"user-master-search__view",
	],
	"working-time-detail" => [
		"delete"         =>	"working-time-detail__delete",
		"save"           =>	"working-time-detail__save",
		"view"           =>	"working-time-detail__view",
	],
	"working-time-search" => [
		"output"         =>	"working-time-search__output",
		"search"         =>	"working-time-search__search",
		"view"           =>	"working-time-search__view",
	],
	"popup-city-search" => [
		"search"         =>	"popup-city-search__search",
		"view"           =>	"popup-city-search__view",
	],
	"popup-country-search" => [
		"search"         =>	"popup-country-search__search",
		"view"           =>	"popup-country-search__view",
	],
	"popup-user-search" => [
		"search"         =>	"popup-user-search__search",
		"view"           =>	"popup-user-search__view",
	],
	"popup-pi-search" => [
		"search"         =>	"popup-pi-search__search",
		"view"           =>	"popup-pi-search__view",
	],
	"popup-suppliers-master-search" => [
		"search"         =>	"popup-suppliers-master-search__search",
		"view"           =>	"popup-suppliers-master-search__view",
	],
	"popup-product-master-search" => [
		"search"         =>	"popup-product-master-search__search",
		"view"           =>	"popup-product-master-search__view",
	],
	"popup-shipment-search" => [
		"search"         =>	"popup-shipment-search__search",
		"view"           =>	"popup-shipment-search__view",
	],
	"popup-provisional-shipment-search" => [
		"search"         =>	"popup-provisional-shipment-search__search",
		"view"           =>	"popup-provisional-shipment-search__view",
	],
	"popup-accept-search" => [
		"search"         =>	"popup-accept-search__search",
		"view"           =>	"popup-accept-search__view",
	],
	"popup-deposit-search" => [
		"search"         =>	"popup-deposit-search__search",
		"view"           =>	"popup-deposit-search__view",
	],
	"popup-component-master-search" => [
		"search"         =>	"popup-component-master-search__search",
		"view"           =>	"popup-component-master-search__view",
	],
	"popup-component-product-search" => [
		"search"         =>	"popup-component-product-search__search",
		"view"           =>	"popup-component-product-search__view",
	],
	"popup-warehouse-search" => [
		"search"         =>	"popup-warehouse-search__search",
		"view"           =>	"popup-warehouse-search__view",
	],
	"popup-shifting-request-search" => [
		"search"         =>	"popup-shifting-request-search__search",
		"view"           =>	"popup-shifting-request-search__view",
	],
	"popup-internalorder-search" => [
		"search"         =>	"popup-internalorder-search__search",
		"view"           =>	"popup-internalorder-search__view",
	],
	"popup-order-search" => [
		"search"         =>	"popup-order-search__search",
		"view"           =>	"popup-order-search__view",
	],
	"popup-purchaserequest-request-search" => [
		"search"         =>	"popup-purchaserequest-request-search__search",
		"view"           =>	"popup-purchaserequest-request-search__view",
	],
	"popup-input-output-search" => [
		"search"         =>	"popup-input-output-search__search",
		"view"           =>	"popup-input-output-search__view",
	],
	"popup-stock-manage-search" => [
		"search"         =>	"popup-stock-manage-search__search",
		"view"           =>	"popup-stock-manage-search__view",
	],
	"popup-working-time-search" => [
		"search"         =>	"popup-working-time-search__search",
		"view"           =>	"popup-working-time-search__view",
	],
	"popup-shipment-serial-search" => [
		"search"         =>	"popup-shipment-serial-search__search",
		"view"           =>	"popup-shipment-serial-search__view",
	],
	"popup-carton-item-set" => [
		"search"         =>	"popup-carton-item-set__search",
		"view"           =>	"popup-carton-item-set__view",
	],
	"popup-manufacturing-instruction-search" => [
		"search"         =>	"popup-manufacturing-instruction-search__search",
		"view"           =>	"popup-manufacturing-instruction-search__view",
	],
	"popup-check-list" => [
		"search"         =>	"popup-check-list__search",
		"view"           =>	"popup-check-list__view",
	],
	"popup-invoice-search" => [
		"search"         =>	"popup-invoice-search__search",
		"view"           =>	"popup-invoice-search__view",
	],
	"popup-shifting-serial-search" => [
		"search"         =>	"popup-shifting-serial-search__search",
		"view"           =>	"popup-shifting-serial-search__view",
	],
	"popup-change-password" => [
		"save"         =>	"popup-change-password__save",
		"view"           =>	"popup-change-password__view",
	]
];
// FUNCTION_CD: array function list
define('FUNCTION_CD', $arrFunctionList);