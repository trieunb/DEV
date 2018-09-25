<?php
/**
*|--------------------------------------------------------------------------
*| Component list search
*|--------------------------------------------------------------------------
*| Package       : Master  
*| @author       : Trieunb - ANS806 - trieunb@ans-asia.com
*| @created date : 2017/01/08
*| Description   : 
*/
namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\ExcelController;
use Modules\Common\Http\Controllers\CsvController as csv;
use Paginator, Dao, Excel;

class ComponentListSearchController extends Controller
{
    private $error                          = array();
    private $error_header                   = [array('行NO,項目名,内容')];
    private $fileErrName                    = 'アップロードエラー_';
    private $numberColumn                   = 9;
    private $msg_err_required               = '必須入力です。';
    private $msg_err_parent_child           = '親品目コードと子品目コードは同じ値を指定できません。';
    private $msg_err_maxlength              = '最大長を超えています。最大長は{0}文字です。';
    private $msg_err_duplicate_key          = 'この親品目コード、子品目コードが{0}行目に指定されています。';
    private $msg_err_date_format            = '日付のフォーマットが正しくありません。';
    private $msg_err_date_start_end         = '終了日は開始日より小さくない。';
    private $msg_err_child_qty_greater_zero = '必要部品数は０より大きいです。';
    private $msg_err_child_qty_format       = '必要部品数は必ず数字です。';

    private $colNameList    =  array(
                            '製品コード',//parent_item_cd
                            '製品名和文',//parent_item_nm_j
                            '製品規格名',//parent_specification
                            '部品コード',//child_item_cd
                            '部品名和文',//child_item_nm_j
                            '部品規格名',//child_specification
                            '必要部品数',//child_item_qty
                            '開始日',//apply_st_date
                            '終了日',//apply_ed_date
                        );
    private $maxLengthList  =  array(
                            '0' => 6, //parent_item_cd
                            '3' => 6, //child_item_cd
                            '6' => 6, //child_item_qty
                            '7' => 10, //apply_st_date
                            '8' => 10, //apply_ed_date
                        );
    private $requiredList   =  array(
                            0,//parent_item_cd
                            3,//child_item_cd
                            6//child_item_qty
                        );

    /**
    * list pi
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/01/08 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function getSearch()
    {
        try {
            $paginator  = new Paginator(0, 0, 0, 0);
            $paginate   = $paginator->show(1, 'paginate');
            $fillter    = $paginator->fillter();
            return view('master::ComponentListSearch.index', compact('paginate', 'fillter'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }
    /**
    * search component list
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/20 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postSearch(Request $request) {
        try {
            $param          = $request->all();
            $sql            = "SPC_072_COMPONENT_LIST_SEARCH_FND1";
            $data           = Dao::call_stored_procedure($sql, $param);
            $List           = isset($data[0]) ? $data[0] : array();
            $paginator      = new Paginator($data[1][0]['pageMax'], $data[1][0]['pagesize'], $data[1][0]['page'], $data[1][0]['totalRecord']);
            $paginate       = $paginator->show($data[1][0]['page'], 'paginate');
            $fillter        = $paginator->fillter();

            $html           = view('master::ComponentListSearch.list',compact('List','paginate', 'fillter'))->render();
            return response()->json(array(
                'response'      => true,
                'html'          => $html
            ));
        } catch (\Exception $e) {
            return response()->json(array(
                                'response'  => false,
                                'error'     => $e->getMessage()
                            ));
        }
    }

    /**
    * upload file excel
    * -----------------------------------------------
    * @author      :   ANS817 - 2018/06/04 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postUpload(Request $request) {
        try {
            $input    = $request->all();
            //move file
            $movefile = $input['file']->move(TEMP_FOLDER,'file_upload.xlsx');
            $filePath = TEMP_FOLDER.'file_upload.xlsx';
            //get data
            $dataInput= ExcelController::inputExcel($filePath);
            //remove header
            unset($dataInput[0]);
            //check file no data  OR check number column
            if (count($dataInput) == 0 || count($dataInput[1]) != $this->numberColumn) {
                //file no data
                return response()->json(array(
                    'response' => false,
                    'error_cd' => 'E751'
                ));
            }
            //check conent file
            $checkContentFile = $this->checkContentFile($dataInput);
            //data to json
            $data_json = $this->getDataJson($dataInput);
            //param for store
            $param['data_input_list'] = $data_json;
            $param['has_error']       = $checkContentFile ? 0: 1;
            $param['cre_user_cd']     = \GetUserInfo::getInfo('user_cd');
            $param['cre_prg_cd']      = '072_component-list-search';
            $param['cre_ip']          = \GetUserInfo::getInfo('user_ip');
            //call store
            $sql                      = "SPC_072_COMPONENT_LIST_SEARCH_ACT1"; 
            $result                   = Dao::call_stored_procedure($sql,$param);

            //return result to client
            if (empty($result[0]) && empty($result[2]) && $checkContentFile) {
                return response()->json(array(
                    'response'      => true,
                    'error_cd'      => $result[1][0]['error_cd']
                ));
            }else{
                if (!$checkContentFile || isset($result[2])) {
                    //export file err
                    $dataTemp = array();

                    if (count($this->error) > 0) {
                        $dataTemp = $this->error;
                    }
                    if (isset($result[2])) {
                        $dataTemp = array_merge($dataTemp, $result[2]);
                    }

                    $dataExport = $this->getDataExport($dataTemp);
                    $filePath   = $this->exportErrorFile($dataExport);

                    return response()->json(array(
                        'response' => false,
                        'error_cd' => 'E751',
                        'data'     => $filePath,
                    ));
                }

                if (isset($result[0])) {
                    return response()->json(array(
                        'response'      => false,
                        'error'         => $result[0][0]['Message'],
                        'error_cd'      => $result[1][0]['error_cd']
                    ));
                }
            }
        } catch (\Exception $e) {
            return response()->json(array(
                'response'  => false,
                'error'     => $e->getMessage()
            ));
        }
    }

    /**
    * check content file
    * -----------------------------------------------
    * @author      :   ANS817 - 2018/06/05 - create
    * @param       :
    * @return      :   mixed
    * @access      :   private
    * @see         :   remark
    */
    private function checkContentFile(Array $data){
        try{
            $flag                = true;
            $this->error         = array();
            $countData           = count($data);
            $arr_duplicate       = array();
            $parent_index        = 0;
            $child_index         = 3;
            $child_qty_index     = 6;
            $apply_st_date_index = 7;
            $apply_ed_date_index = 8;

            foreach ($data as $key=>$row) {
                for ($i=0; $i < $this->numberColumn; $i++) { 
                    
                    if(!isset($row[$i])){
                        $row[$i]="";
                    }

                    if ($i == $child_qty_index) {
                        $row[$i]=str_replace(',', '', $row[$i]);
                    }

                    // Check required
                    if (in_array($i,$this->requiredList) && trim($row[$i])=="") { 
                        $flag     = false;
                        $lineNo   = $key + 1;
                        $itemName = $this->colNameList[$i];
                        $msgErr   = $this->msg_err_required;
                        $temp     = array('row_no' => $lineNo, 'item_err' => $itemName, 'msg_err' => $msgErr);
                        array_push($this->error,$temp);
                    } 
                    // Check Maxlength
                    if (        ($i==$parent_index || $i==$child_index || $i==$child_qty_index || $i==$apply_st_date_index || $i==$apply_ed_date_index) 
                            &&  strlen(trim($row[$i])) > intval($this->maxLengthList[$i])) { 
                        $flag     = false;
                        $lineNo   = $key + 1;
                        $itemName = $this->colNameList[$i];
                        $msgErr   = str_replace('{0}', $this->maxLengthList[$i], $this->msg_err_maxlength);
                        $temp     = array('row_no' => $lineNo, 'item_err' => $itemName, 'msg_err' => $msgErr);
                        array_push($this->error,$temp);
                    }

                    // validate date
                    if (($i==$apply_st_date_index || $i==$apply_ed_date_index) && !$this->validateDate($row[$i]) && trim($row[$i])!="") {
                        $flag     = false;
                        $lineNo   = $key + 1;
                        $itemName = $this->colNameList[$i];
                        $msgErr   = $this->msg_err_date_format;
                        $temp     = array('row_no' => $lineNo, 'item_err' => $itemName, 'msg_err' => $msgErr);
                        array_push($this->error,$temp);
                    }
                }

                //check child_qty not number
                if (trim($row[$child_qty_index])!="" && !is_numeric($row[$child_qty_index])) {
                    $flag     = false;
                    $lineNo   = $key + 1;
                    $itemName = $this->colNameList[$child_qty_index];
                    $msgErr   = $this->msg_err_child_qty_format;
                    $temp     = array('row_no' => $lineNo, 'item_err' => $itemName, 'msg_err' => $msgErr);
                    array_push($this->error,$temp);
                }

                //check child_qty is greater than 0
                if (trim($row[$child_qty_index])!="" && is_numeric($row[$child_qty_index]) && intval($row[$child_qty_index]) <= 0) {
                    $flag     = false;
                    $lineNo   = $key + 1;
                    $itemName = $this->colNameList[$child_qty_index];
                    $msgErr   = $this->msg_err_child_qty_greater_zero;
                    $temp     = array('row_no' => $lineNo, 'item_err' => $itemName, 'msg_err' => $msgErr);
                    array_push($this->error,$temp);
                }

                //Check apply_st_date > apply_ed_date
                if (        trim($row[$apply_st_date_index]) != '' 
                        &&  trim($row[$apply_ed_date_index]) != '' 
                        &&  $this->validateDate($row[$apply_st_date_index]) 
                        &&  $this->validateDate($row[$apply_ed_date_index])
                        &&  $row[$apply_st_date_index] > $row[$apply_ed_date_index]) {

                    $flag     = false;
                    $lineNo   = $key + 1;
                    $itemName = $this->colNameList[$apply_st_date_index];
                    $msgErr   = $this->msg_err_date_start_end;
                    $temp     = array('row_no' => $lineNo, 'item_err' => $itemName, 'msg_err' => $msgErr);
                    array_push($this->error,$temp);
                }

                //Check parent_item_cd = child_item_cd
                if (trim($row[$parent_index]) != '' && $row[$parent_index] == $row[$child_index]) {
                    $flag     = false;
                    $lineNo   = $key + 1;
                    $itemName = $this->colNameList[$parent_index].'、'.$this->colNameList[$child_index];
                    $msgErr   = $this->msg_err_parent_child;
                    $temp     = array('row_no' => $lineNo, 'item_err' => $itemName, 'msg_err' => $msgErr);
                    array_push($this->error,$temp);
                }

                //Check duplicate key
                if ($key + 1 <= $countData) {
                    for ($i=$key + 1; $i <= $countData; $i++) { 
                        $next_row = $data[$i];

                        if (        trim($row[$parent_index]) != '' 
                                &&  trim($row[$child_index]) != '' 
                                &&  $row[$parent_index] === $next_row[$parent_index] 
                                &&  $row[$child_index] === $next_row[$child_index]) {

                            $flag     = false;
                            if (!in_array($i,$arr_duplicate)) {
                                array_push($arr_duplicate,$i);

                                $lineNo   = $i + 1;
                                $itemName = $this->colNameList[$parent_index].'、'.$this->colNameList[$child_index];
                                $msgErr   = str_replace('{0}', ($key+1), $this->msg_err_duplicate_key);
                                $temp     = array('row_no' => $lineNo, 'item_err' => $itemName, 'msg_err' => $msgErr);
                                array_push($this->error,$temp);
                            }
                        }
                    }
                }
            }

            return $flag;
        } catch (\Exception $e) {
            return false;
        }        
    }

    /**
    * convert data to json
    * -----------------------------------------------
    * @author      :   ANS817 - 2018/06/05 - create
    * @param       :
    * @return      :   mixed
    * @access      :   private
    * @see         :   remark
    */
    private function getDataJson(Array $data){
        try{
            $keyNameUse = [
                    '0' => 'parent_item_cd'
                ,   '3' => 'child_item_cd'
                ,   '6' => 'child_item_qty'
                ,   '7' => 'apply_st_date'
                ,   '8' => 'apply_ed_date'
            ];
            $keyNameRemove = [
                    '1' => 'parent_item_nm_j'
                ,   '2' => 'parent_specification'
                ,   '4' => 'child_item_nm_j'
                ,   '5' => 'child_specification'
            ];

            $data_json = '';
            foreach ($data as $index=>$row) {
                foreach ($row as $key => $value) {
                    //remove key
                    foreach ($keyNameRemove as $j => $nameRemove) {
                        if ($key == $j) {
                            unset($data[$index][$key]);
                        }
                    }
                    //change key
                    foreach ($keyNameUse as $i => $nameUse) {
                        if ($key == $i) {
                            if ($nameUse == 'child_item_qty') {
                                // IF nameUse is child_item_qty, THEN remove ','
                                $data[$index][$nameUse] = str_replace(',', '',trim($value, ' '));
                            } else {
                                $data[$index][$nameUse] = trim($value, ' ');
                            }
                            unset($data[$index][$key]);
                        }
                    }
                }

                //json encode
                $data_json .= json_encode($data[$index]).',';
            }
            //remove last ','
            $data_json = rtrim($data_json,",");

            return '['.$data_json.']';
        } catch (\Exception $e) {
            return '';
        }        
    }

    /**
    * export file errror
    * -----------------------------------------------
    * @author      :   ANS817 - 2018/06/06 - create
    * @param       :
    * @return      :   mixed
    * @access      :   private
    * @see         :   remark
    */
    private function exportErrorFile(Array $data){
        try{
            //create csv file
            $csv          = new csv;
            $filenameTemp = 'err_file_';
            $fileTemp     = $csv->outputCSV($data, $filenameTemp);
            //rename file
            $filename     = DOWNLOAD_CSV_PUBLIC.$this->fileErrName.date('YmdHis').'.csv';
            rename(public_path().$fileTemp, mb_convert_encoding($filename, 'SJIS', 'UTF-8'));

            return str_replace(public_path(), '', $filename);
        } catch (\Exception $e) {
            return '';
        }        
    }

    /**
    * get data using for export
    * -----------------------------------------------
    * @author      :   ANS817 - 2018/06/06 - create
    * @param       :
    * @return      :   mixed
    * @access      :   private
    * @see         :   remark
    */
    private function getDataExport(Array $data){
        try{
            asort($data);

            $dataExport = $this->error_header;

            foreach ($data as $key => $value) {
                $temp     = array($value['row_no'].','.$value['item_err'].','.$value['msg_err']);
                array_push($dataExport, $temp);
            }

            return $dataExport;

        } catch (\Exception $e) {
            return null;
        }        
    }

    /**
    * validate Date
    * -----------------------------------------------
    * @author      :   ANS817 - 2018/06/06 - create
    * @param       :
    * @return      :   mixed
    * @access      :   private
    * @see         :   remark
    */
    private function validateDate($date){
        $d = \DateTime::createFromFormat('Y/m/d', $date);
        return $d && $d->format('Y/m/d') == $date;        
    }
}
