<?php
namespace Modules\Common\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Excel;
/**
*-------------------------------------------------------------------------*
* Common
* 
*  
* 処理概要/process overview	:	read file excel
* 作成日/create date			:	2018/06/04
* 作成者/creater				:	ANS817 - HaVV – havv@ans-asia.com
* 
* @package    	 			: 	Common
* @copyright   				: 	Copyright (c) ANS-ASIA
* @version					: 	1.0.0
*-------------------------------------------------------------------------*
* read file excel
* 
* 
*
*/

class ExcelController extends Controller {
	
	private $folder_temp;
	private $folder_download;

	/**
	* contructor
	* -----------------------------------------------
	* 
	* 
	* @author      :   HaVV 	- 2018/06/04 - create
	* @param       :   null
	* @return      :   null
	* @access      :   public
	* @see         :   remark
	* @updater     :   
	*/
	public function __construct()
	{
		$this->folder_temp 		= TEMP_FOLDER;
		$this->folder_download 	= DOWNLOAD_EXCEL_PUBLIC;
	}

	/**
	* import CSV
	* -----------------------------------------------
	* 
	* @author      :   ANS817 - 2018/06/04 - create
	* @param       :   $filename 	: name of file
	* @param       :   $isEncoding 	: if true then encoding
	* @return      :   $data 		: data readed from file 
	* @access      :   public
	* @see         :   remark
	* @updater     :   
	*/
	public static function inputExcel($filename)
	{
		try {
			$data   = array();

			$objExcel = Excel::load($filename);
			foreach ($objExcel->getWorksheetIterator() as $worksheet) {
				//get row max
				$highestRow         = $worksheet->getHighestRow();
				//get column max
				$highestColumn      = $worksheet->getHighestColumn();
				//get index column from column name string, eg: A => index is 0, B => index is 1
				$highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);

				for ($row = 1; $row <= $highestRow; $row++) {
					$rowData = array();
					//get data cell
					for ($col = 0; $col < $highestColumnIndex; $col++) {
						//get cell
						$cell     = $worksheet->getCellByColumnAndRow($col, $row);
						//get value of cell
						if (\PHPExcel_Shared_Date::isDateTime($cell)) {
							//data fiels is date
							// $timestamp= \PHPExcel_Shared_Date::ExcelToPHP($cell->getValue());

							// if ($timestamp <= PHP_INT_MAX && $timestamp >= ~PHP_INT_MAX) {
							// 	$val      = date('Y/m/d', $timestamp);
							// } else {
							// 	$val      = $cell->getValue();
							// }

							$val = \PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'YYYY/MM/DD');
						} else {
							//data fiels not date
							$val      = (string) $cell->getCalculatedValue();
						}
						//add value to rowData
						array_push($rowData, $val);
					}
					//add rowData to data
					if (count($rowData) > 0) {
						array_push($data, $rowData);
					}
				}
			}

			return $data;
		} catch (Exception $e) {
			return null;
		}
	}
} 
