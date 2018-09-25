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
* 処理概要/process overview	:	export and save file csv
* 作成日/create date			:	2016/12/26
* 作成者/creater				:	vulq – vulq@ans-asia.com
* 
* @package    	 			: 	Common
* @copyright   				: 	Copyright (c) ANS-ASIA
* @version					: 	1.0.0
*-------------------------------------------------------------------------*
* export and save file csv 	
* folder : public/download/yyyy-mm-dd/userlogin_id/filename
* 
* 
*
*/

class CsvController extends Controller {
	
	private $folder_temp;
	private $folder_download;

	/**
	* contructor
	* -----------------------------------------------
	* 
	* 
	* @author      :   Vulq 	- 2016/12/05 - create
	* @param       :   null
	* @return      :   null
	* @access      :   public
	* @see         :   remark
	* @updater     :   
	*/
	public function __construct()
	{
		$this->folder_temp 		= TEMP_FOLDER;
		$this->folder_download 	= DOWNLOAD_CSV_PUBLIC;
	}

	/**
	* outputCSV
	* -----------------------------------------------
	* 
	* @author      :   vulq - 2016/12/05 - create
	* @param       :   $result 		: data for exports
	* @param       :   $filename 	: name of file
	* @param       :   $save 		: if true then copy file from temp to download directory
	* @return      :   file 
	* @access      :   public
	* @see         :   remark
	* @updater     :   
	*/
	public function outputCSV($result, $filename, $save = false)
	{
		return $this->writefile($result, $filename);
	}

	/**
	* import CSV
	* -----------------------------------------------
	* 
	* @author      :   vulq - 2016/12/05 - create
	* @param       :   $result 		: data for exports
	* @param       :   $filename 	: name of file
	* @param       :   $save 		: if true then copy file from temp to download directory
	* @return      :   file 
	* @access      :   public
	* @see         :   remark
	* @updater     :   
	*/
	public static function inputCSV($filename, $isEncoding = true)
	{
		$data = array();
		if (\File::exists($filename)) {
			$file = fopen($filename,"r");
	        while(!feof($file)) {
	          $row = fgetcsv($file);
	          if($row !== false) {
	          	$arr  = array();
	          	foreach ($row as $key => $value) {
	          		if ($isEncoding) {
		          		//If encoding is SJIS then conver to UTF-8
		          		$arr[] = mb_convert_encoding($value, 'UTF-8', 'SJIS');
	          		} else {
	          			$arr[] = $value;
	          		}
	          	}
	            $data[] = $arr;
	          }           
	        }
	        //close file
	        fclose($file);
		} else {
			$data = Null;
		}
        return $data;
	}

	/**
	* save file export csv or txt in public/download/yyyy-mm-dd/filename
	* -----------------------------------------------
	* @author      :   vulq - 2016/12/05 - create
	* @param       :   $result : data for exports
	* @param       :   $filename : name of file
	* @param       :   $save : if true then copy file from temp to download directory
	* @return      :   function
	* @access      :   private
	* @see         :   remark
	*/
	private function writefile($result, $filename)
	{
		//create name file temp for write file
		$filename_temp = $filename.strtotime(date('Y-m-d h:i:s'));
		$output	=	'';
	 	foreach ($result as $row) {
	     	$output.=  implode(",",$row)."\n";
	 	}
	 	// get folder temp sava file 
		if (!file_exists($this->folder_download)) {
			//create folder if is exitst
    		mkdir($this->folder_download, 0777, true);
		}

		$file 	=	fopen($this->folder_download.$filename_temp.'.csv', 'w+');
		file_put_contents($this->folder_download.$filename_temp.'.csv', "\xEF\xBB\xBF".  $output);
		fclose($file);

		return str_replace(public_path(), '',$this->folder_download.$filename_temp.'.csv');
	}

	/**
	* output export csv or txt for client
	* -----------------------------------------------
	* @author      :   vulq - 2016/12/05 - create
	* @param       :   $result 			: data for exports
	* @param       :   $filename 		: name of file
	* @param       :   $filename_temp 	: file in output in temp folder
	* @return      :   download file
	* @access      :   private
	* @see         :   remark
	*/
	private function output($result, $filename, $filename_temp)
	{
		$filePath 	= $this->folder_temp.$filename_temp.'.csv';
		return response()->download($filePath,$filename.'.csv')->deleteFileAfterSend(true);
	}

	/**
	* move file to directory upload csv
	* -----------------------------------------------
	* @author      :   vulq - 2016/12/05 - create
	* @param       :   $result 			: data for exports
	* @param       :   $filename 		: name of file
	* @param       :   $filename_temp 	: file in output in temp folder
	* @return      :   download file
	* @access      :   public
	* @see         :   remark
	*/
	public function moveFile($file, $filename = '')
	{
		if($filename != '') {
			$extension 		= 	$file->getClientOriginalExtension();
            $path           =   $this->folder_download;
            
            $file_full 		=   $path.$filename.'.'.$extension;            
            $res            =   $file->move($path,$filename.'.'.$extension);

            return $res == true ? $file_full : null ;
        } else {
        	return null;
        }
	}
} 
