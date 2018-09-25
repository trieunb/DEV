<?php 

/**
  *-------------------------------------------------------------------------*
  * Helpers 
  * @created         :   2016/11/24
  * @author          :   tannq@ans-asia.com
  * @package         :   common
  * @copyright       :   Copyright (c) ANS-ASIA
  * @version         :   1.0.0
  *-------------------------------------------------------------------------*
  *
  */
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

/*
 * Add timestamp version 
 */
if(!function_exists('file_cached'))
{
	function file_cached($path, $bustQuery = false)
	{
		// Get the full path to the file.
		$realPath = public_path($path);

		if ( ! file_exists($realPath)) {
			throw new \LogicException("File not found at [{$realPath}]");
		}

		// Get the last updated timestamp of the file.
		$timestamp = filemtime($realPath);

		if ( ! $bustQuery) {
			// Get the extension of the file.
			$extension = pathinfo($realPath, PATHINFO_EXTENSION);

			// Strip the extension off of the path.
			$stripped = substr($path, 0, -(strlen($extension) + 1));

			// Put the timestamp between the filename and the extension.
			$path = implode('.', array($stripped, $timestamp, $extension));
		} else {
			// Append the timestamp to the path as a query string.
			$path  .= '?v=' . $timestamp;
		}

		return asset($path);
	}
}
/*
 * Call url file
 */
if(!function_exists('public_uri'))
{
	function public_url($url,$attributes=null)
	{
		if(file_exists($url))
		{
			$attr = '';
			if(!empty($attributes) && is_array($attributes))
			{
				foreach($attributes as $key=>$val)
				{
					$attr .= $key.'="'.$val.'" ';
				}
			}
			$attr = rtrim($attr);
			if(ends_with($url,'.css'))
			{
				return '<link rel="stylesheet" href="'.file_cached($url,true).'" type="text/css" '.$attr.'>';
			}
			elseif(ends_with($url,'.js'))
			{
				return '<script src="'.file_cached($url,true).'" type="text/javascript" charset="utf-8" '.$attr.'></script>';
			}
			else
			{
				return asset($url);
			}
		}
		$console = 'File:['.$url.'] not found';
		return "<script>console.log('".$console."')</script>";
	}
}

if(!function_exists('formatNumber')){
	function formatNumber($number='',$decimal=0){
		if($number=='')
			return $number;

		$number = 1*$number;
		if(($number - round($number))!=0){
			$number = number_format($number,$decimal,'.',',');
		}else{
			$number = number_format($number,0,'.',',');
		}
		return $number;
;	}
}

if(!function_exists('dodownload')){
	function dodownload($folderName = '', $allFiles = []) {
		$zip = new ZipArchive;
		$date = Carbon::now();
		$zipFolderName = $date->getTimestamp().'.zip';
		$path = public_path('/uploads/'.$folderName.'/');

		//encode path with japanese character for php to access
		$encoded_path = $path;

		//open zip file (no need to encode japanese character)
		// if ($zip->open($path.$zipFolderName, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
		if ($zip->open(public_path('/uploads/tmp/').$zipFolderName, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
			//add files to zip
			if (count($allFiles) == count($allFiles,COUNT_RECURSIVE)) {
				$allFiles = array($allFiles);
			}
			// var_dump($allFiles);die;
			foreach ($allFiles as $key => $value) {
				if (($allFiles[$key]['file_store_nm']) != '' && file_exists('wfio://'.$encoded_path.$allFiles[$key]['file_store_nm'])) {
					//does not work since fopen could not read japanese directories
					//---------------------------------------------------------------
					// //convert files to binary
					// $file = $encoded_path.$allFiles[$key]['file_store_nm'];
					// $handle = fopen($file, "r");
					// $contents = fread($handle, filesize($file));
					// fclose($handle);

					// // add binary contents to file then zip
					// $zip->addFromString($allFiles[$key]['file_display_nm'], $contents);
					//---------------------------------------------------------------


					//workaround
					//1. copy does not work
					//2. move to tmp folder then move back
					rename('wfio://'.$encoded_path.$allFiles[$key]['file_store_nm'], 'wfio://'.public_path('/uploads/tmp/').$allFiles[$key]['file_store_nm']);
					$zip->addFile(public_path('/uploads/tmp/').$allFiles[$key]['file_store_nm'], mb_convert_encoding($allFiles[$key]['file_display_nm'],"SJIS-win", "UTF-8"));
				}
			}
			//close
			$zip->close();

			//move back to original folder
			foreach ($allFiles as $key => $value) {
				if (($allFiles[$key]['file_store_nm']) != '' && File::exists(public_path('/uploads/tmp/').$allFiles[$key]['file_store_nm'])) {
					rename('wfio://'.public_path('/uploads/tmp/').$allFiles[$key]['file_store_nm'], 'wfio://'.$encoded_path.$allFiles[$key]['file_store_nm']);
				}
			}

		}
		
		//download zip file
		// $zipFile = $encoded_path.$zipFolderName;
		$zipFile = public_path('/uploads/tmp/').$zipFolderName;

		if(file_exists('wfio://'.$zipFile))
		{	
			echo '/common/dodownload?folderName='.$folderName.'&zipFolderName='.$zipFolderName;
		} else {
			echo '202';
		}
	}
}

if(!function_exists('initSession')){
	function initSession($screen,$clear_session = false){
		$screenSession = null;
		$searchFlag = 0;
		$oldConditionSearchHtml = null;
		$back_link = '/toppage';
		$back_screen = '';
		$back_data = ['back_link'=>'/toppage'];
		$oldPageIndex = 1;
		$oldPageSize = null;
		$search_html = '';
		$is_from_search = '0';
		if(session::has('link-session.'.$screen)) {
			$screenSession  = session::get('link-session.'.$screen);
			if (isset($screenSession['init_data']['search_flag'])){
				$searchFlag = $screenSession['init_data']['search_flag'];
			}
			if (isset($screenSession['init_data']['message_search_condition'])) {
				$oldConditionSearchHtml = $screenSession['init_data']['message_search_condition'];
			}
			if(isset($screenSession['back_data']['search_flag'])){
				$is_from_search = $screenSession['back_data']['search_flag'];
			}
			if(isset($screenSession['back_data']['message_search_condition'])){
				$search_html = $screenSession['back_data']['message_search_condition'];
			}
			if (isset($screenSession['init_data']['pageSize'])) {
				$oldPageSize = $screenSession['init_data']['pageSize'];
			}
			if (isset($screenSession['init_data']['pageIndex'])) {
				$oldPageIndex = $screenSession['init_data']['pageIndex'];
			}
			if(isset($screenSession['back_link'])){
				$back_link = $screenSession['back_link'];
			}
			if(isset($screenSession['back_screen'])){
				$back_screen = $screenSession['back_screen'];
			}
			if(isset($screenSession['back_data'])){
				$back_data = $screenSession['back_data'];
				if($is_from_search == '1')
					unset($back_data['message_search_condition']);
			}
			if($clear_session)
				session::forget('link-session.'.$screen);
		}

		return (
		[
			'searchFlag' => $searchFlag
			,	'oldConditionSearchHtml' => $oldConditionSearchHtml
			,	'is_from_search' => $is_from_search
			,	'search_html' => $search_html
			,	'oldPageSize' => $oldPageSize
			,	'oldPageIndex' => $oldPageIndex
			,	'back_link' => $back_link
			,	'back_screen' => $back_screen
			,	'back_data' => json_encode($back_data)
			,	'screen' => $screen
		]
		);
	}
}

//if(!function_exists('viewSession')){
//	function viewSession(){
//		$html = '';
//		$html .= '<script type="text/javascript">';
//		$html .= "var __screen = '{{$screen}}';";
//		$html .= 'var _back_link = '{{$back_link}}';
//		$html .= 'var _back_screen = '{{$back_screen}}';
//		$html .= 'var _is_from_search = '{{$is_from_search}}';
//		$html .= 'var _back_data = htmlEntities('{{$back_data}}');
//		$html .= '_back_data = JSON.parse(_back_data);
//		$html .= '</script>';
//		return $html;
//	}
//}

/**
* show infomation user create/update and date create/update
* -----------------------------------------------
* @author      :   ANS806 - 2017/08/08 - create
* @param       :   string: $created_by, $updated_by | date: $created_at, $updated_at
* @return      :   mixed
* @access      :   public
* @see         :   remark
*/
if (!function_exists('infoMemberCreUp')) {
	function infoMemberCreUp($created_by, $created_at, $updated_by, $updated_at) {
		$html = '';
		if ($created_by !== '') {
			$html .= '<div class="info-created">';
			$html .= 	'<div class="heading-elements" style="margin-top: -8px;">';
			$html .=		'<span class="text-bold">登録者</span>&nbsp;&nbsp;'.$created_by.'&nbsp;&nbsp;アペレ 太郎
							 <span class="text-bold">登録日</span>&nbsp;&nbsp;'.$created_at.'&nbsp;&nbsp;&nbsp;&nbsp;';
			if ($updated_by !== '') {
				$html .=	'<span class="text-bold">更新者</span>&nbsp;&nbsp;'.$updated_by.'&nbsp;&nbsp;アペレ 太郎
							 <span class="text-bold">更新日</span>&nbsp;&nbsp;'.$updated_at.'';
			}
			$html .=	'</div>';
			$html .= '</div>';
		}
		return $html;
	}
}

if (!function_exists('convertCurrency')) {
	function convertCurrency($from, $to) {
	    $exchange_url = 'http://apilayer.net/api/live';
		$params = array(
		    'access_key' 	=> '37092d6d36da4f96d2547abf64d4afb6',
		    'source' 		=> $from,
		    'currencies' 	=> $to,
		    'format' 		=> 1 // 1 = JSON
		);
		// make cURL request // parse JSON
		$curl = curl_init();
		curl_setopt_array($curl, array(
		    CURLOPT_URL => $exchange_url . '?' . http_build_query($params),
		    CURLOPT_RETURNTRANSFER => true
		));
		$response = json_decode(curl_exec($curl), true);
		curl_close($curl);

		if (!empty($response['quotes']['USDVND'])) {
		    // convert 150 USD to JPY ( Japanese Yen )
		    return $response['quotes'][$from.$to];
		}
	}
}
/*
* show button flow mode
* -----------------------------------------------
* @author      :   ANS806 - 2017/08/08 - create
* @param       :   string: mode, id button
* @return      :   mixed
* @access      :   public
* @see         :   remark
*/
if (!function_exists('showButton')) {
	function showButton($mode, $from, $button) {
		$checkMode = true;
		//show button with mode Insert
		if ($mode == 'I') {
			if (!in_array($button, ['btn-back', 'btn-save'])) {
				$checkMode 	=	false;
			}
		}
		// hide button with mode Request
		if ($mode == 'R') {
			if (in_array($button, ['btn-cancel-approve', 'btn-print', 'btn-cancel-order', 'btn-issue'])) {
				$checkMode 	=	false;
			}
		}
		// hide button with mode Approved
		if ($mode == 'A') {
			if (in_array($button, ['btn-approve', 'btn-delete'])) {
				$checkMode 	=	false;
			}
		}
		// hide button with mode ordered
		if ($mode == 'O') {
			if (in_array($button, ['btn-approve', 'btn-cancel-approve', 'btn-delete'])) {
				$checkMode 	=	false;
			}
		}
		// hide button with mode lost of order
		if ($mode == 'L') {
			if (in_array($button, ['btn-print', 'btn-approve', 'btn-cancel-approve', 'btn-cancel-order', 'btn-delete'])) {
				$checkMode 	=	false;
			}
		}
		// hide button back
		if (strpos($from, 'Search') == false) {
			if (in_array($button, ['btn-back'])) {
				$checkMode 	=	false;
			}
		}
		return $checkMode;
	}
}
/*
* get status by status div
* -----------------------------------------------
* @author      :   ANS806 - 2018/01/17 - create
* @param       :   
* @return      :   string
* @access      :   public
* @see         :   remark
*/
if (!function_exists('getStatusCd')) {
	function getStatusCd($status_div) {
			$status 	=	'';
			switch ($status_div) {
                case "10":
                    $status   =   'R';
                    break;
                case "20":
                    $status   =   'A';
                    break;
                case "30":
                    $status   =   'O';
                    break;
                case "90":
                    $status   =   'L';
                    break;
                default:
                    $status   =   'R';
                    break;
            }
        return $status;
	}
}
/*
* get Prg code of screen
* -----------------------------------------------
* @author      :   ANS806 - 2017/08/08 - create
* @param       :   
* @return      :   string
* @access      :   public
* @see         :   remark
*/
if (!function_exists('getPrgCd')) {
	function getPrgCd() {
		$request = Request();
	    $prg_arr = explode('Controller@', $request->route()->getAction()['controller']);
	    $prg     = explode("\\", $prg_arr[0]);
	    return end($prg);
	}
}
/*
* get number lines of a row in excel
* -----------------------------------------------
* @author      :   ANS806 - 2018/01/17 - create
* @param       :   
* @return      :   string
* @access      :   public
* @see         :   remark
*/
if (!function_exists('numLineOfRowExcel')) {
	function numLineOfRowExcel($str, $mxleng) {
		// dd($str);
		$leng 		=	mb_strwidth($str);
		$lines 		=	'';
		if ($str != '' && $mxleng > 0) {
			if ($leng <= $mxleng) {
				$lines 	=	1;
			} else {
				$lines 	=	$leng/$mxleng;
			}
		} else {
			$lines 	=	1;
		}
	    return ceil($lines);
	}
}

/*
* get number lines header excel
* -----------------------------------------------
* @author      :   ANS806 - 2018/01/17 - create
* @param       :   
* @return      :   string
* @access      :   public
* @see         :   remark
*/
if (!function_exists('numLinesDataExcel')) {
	function numLinesDataExcel($data, $is_check) {
		$row        =   '';
		if ($is_check) {
	        $arr_data   =   '';
	        $line       =   '';
			foreach ($data as $key => $value) {
				if (is_array($value)) {
					if (count($value) == 1) {
						$arr_data = $value[0];
	                	$line = numLineOfRowExcel($arr_data['value'], $arr_data['leng']);
					} else if (count($value) > 1) {
						foreach ($value as $k => $val) {
		                    $arr_data[] = $val['value'];
		                    $lines[] = numLineOfRowExcel($val['value'], $val['leng']);
		                }
		                $line = max($lines);
					}
				} else {
					$line =	$value;
				}
				$row = $row + $line;
	        }
		} else {
			$row 	=	count($data);
		}
        return $row;
	}
}
/*
* get number lines header excel
* -----------------------------------------------
* @author      :   ANS806 - 2018/01/17 - create
* @param       :   
* @return      :   string
* @access      :   public
* @see         :   remark
*/
if (!function_exists('pagiDataExcel')) {
	function pagiDataExcel($data, $line_detail) {
		$row        =   '';
        $arr_data   =   '';
        $line       =   '';
        $pagi 		= 	'';
        $pagis 		= 	'';
        $line_pagi  =   '';
        $len 		=	count($data);
		foreach ($data as $key => $value) {
			if (is_array($value)) {
				if (count($value) == 1) {
					$arr_data = $value[0];
                	$line = numLineOfRowExcel($arr_data['value'], $arr_data['leng']);
				} else if (count($value) > 1) {
					foreach ($value as $k => $val) {
	                    $arr_data[] = $val['value'];
	                    $lines[] = numLineOfRowExcel($val['value'], $val['leng']);
	                }
	                $line = max($lines);
				}
			} else {
				$line =	$value;
			}
			$row = $row + $line;
			if ($key == ($len - 1)) {
				if ($row  <= $line_detail) {
					// $pagi[] = 	['line' => $row, 'pos' => $key + 1];
					$pagi[] = 	$key + 1;
				} else {
					// $pagi[] = 	['line' => $row - $line, 'pos' => $key];
					// $pagi[] = 	['line' => $line, 'pos' => $key + 1];
					$pagi[] = 	$key;
					$pagi[] = 	$key + 1;
				}
				$numLineEnd = $row;
			} else {
				if ($row  > $line_detail) {
					// $pagi[] = 	['line' => $row - $line, 'pos' => $key];
					$pagi[] = 	$key;
					$row 	=	$line;
				}
			}
        }
        return [$pagi, count($pagi)];
	}
}
/*
* get data for page in excel
* -----------------------------------------------
* @author      :   ANS806 - 2018/01/17 - create
* @param       :   
* @return      :   string
* @access      :   public
* @see         :   remark
*/
if (!function_exists('dataPageExcel')) {
	function dataPageExcel($data, $pagi) {
			$array          = '';
	        $data_page      = '';
	        $from           = '';
	        $to             = '';
	        if (is_array($pagi)) {
		        foreach ($pagi as $key => $value) {
		            $array = '';
		            if ($key == 0) {
		                $from   =   0;
		                $to     =   $value;
		            } else {
		                $from   =   $pagi[$key - 1];
		                $to     =   $value;
		            }
		            for ($i = 0; $i < count($data); $i++) { 
		                if ($i >= $from && $i < $to) {
		                    $array[] = $data[$i];
		                }
		            }
		            $data_page[]   =   $array;
		        }
		    } else {
				$data_page 	=	array_chunk($data, $pagi);
			}
        return $data_page;
	}
}
/*
* get style for excel
* -----------------------------------------------
* @author      :   ANS806 - 2018/01/17 - create
* @param       :   
* @return      :   string
* @access      :   public
* @see         :   remark
*/
if (!function_exists('getStyleExcel')) {
	function getStyleExcel($name) {
        //font style
        $fontBold = array(
            'font' => array(
                'bold'      =>  true
            )
        );
        $fontTitle = array(
                'font' => array(
                    'name'      =>  'Arial',
                    'size'      =>  16,
                    'bold'      =>  true
                )
            );
        //border style
        $styleAllBorder = array(
            'borders' => array(
                'allborders' => array(
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => '000000'),
                )
            )
        );
        //border style
        $styleAllBorderBol = array(
            'borders' => array(
                'allborders' => array(
                    'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                    'color' => array('rgb' => '000000'),
                )
            )
        );
        //border style
        $styleAllBorderDotted = array(
            'borders' => array(
                'allborders' => array(
                    'style' => \PHPExcel_Style_Border::BORDER_DOTTED,
                    'color' => array('rgb' => '000000'),
                )
            )
        );
        //border style
        $styleOutlineBorder = array(
            'borders' => array(
                'outline' => array(
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => '000000'),
                )
            )
        );
        $arr_style    =   [
            'fontBold'                  =>  $fontBold,
            'fontTitle'                 =>  $fontTitle,
            'styleAllBorder'            =>  $styleAllBorder,
            'styleAllBorderBol'         =>  $styleAllBorderBol,
            'styleAllBorderDotted'      =>  $styleAllBorderDotted,
            'styleOutlineBorder'        =>  $styleOutlineBorder
        ];
        if ($name == '') {
        	return null;
        } else {
        	return $arr_style[$name];
        }
	}
}
/*
* get data buy key
* -----------------------------------------------
* @author      :   ANS806 - 2018/03/01- create
* @param       :   
* @return      :   string
* @access      :   public
* @see         :   remark
*/
if (!function_exists('getDataByKey')) {
	function getDataByKey($key, $data) {
		$result = [];
		foreach ($data as $item => $value) {
			if ($value[array_keys($key)[0]] == array_values($key)[0]) {
				$result[]	=	$value;
			}
		}
		return $result;
	}
}

/**
* get Handle String
* -----------------------------------------------
* @author      :   ANS831 - 2018/03/21 - create
* @param       :    string,length_line_excel
* @return      :   string result serial (1~3,5,7~10)
* @access      :   public
* @see         :   remark
*/
if (!function_exists('getHandleString')) { 
	function getHandleString($string,$length_line_excel) {
		//get string serial to array
	    $string_result = explode("; ", $string); 
	    $count = count($string_result);
	    //Add 1 element with the last element 
	    $string_result[$count] = $string_result[$count-1];
	    //Position the first element in the sequence
	    $val_first  = 0;
	    //The first element in the sequence
	    $first      = '';
	    //The last element in the sequence
	    $last       = '';
	    //string result
	    $result     = ''; 
	    for ($i= 0 ,$j =1  ; $i < $count ; $i++, $j++){
	        $first =  $string_result[$val_first] ;
	        //If not a consecutive element
	        if((($string_result[$j] - $string_result[$i]) != 1) || $j ==  $count){
	            $last = $string_result[$i];
	            $val_first = $j;
	            if($first != $last){ 
	                $leng = mb_strwidth($result) % $length_line_excel + 1;
	                if( ($length_line_excel - $leng) > 12 ){
	                    $result = $result . $first . "～". $last . ",";
	                } else {
	                    for ($k = 0; $k < ($length_line_excel - $leng) ; $k++){
	                        $result = $result . " ";
	                    }
	                    $result = $result . $first . "～". $last . ",";
	                }
	            } else {
	                $leng = mb_strwidth($result) % $length_line_excel + 1;
	                if( ($length_line_excel - $leng) > 6 ){
	                    $result = $result . $last . ",";
	                } else {
	                    for ($k = 0; $k < ($length_line_excel - $leng) ; $k++){
	                        $result = $result . " ";
	                    }
	                    $result = $result . $last . ",";
	                }
	                
	            }
	        }
	    }
	    //cut characters "," at the end
	    return substr($result,0,count($result)-2);
	}
}