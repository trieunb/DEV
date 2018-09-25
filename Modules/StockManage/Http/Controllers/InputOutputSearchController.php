<?php
/**
*|--------------------------------------------------------------------------
*| stock manage
*|--------------------------------------------------------------------------
*| Package       : stock manage  
*| @author       : TuanNK - ANS818 - tuannk@ans-asia.com
*| @created date : 2017/01/08
*| Description   : 
*/
namespace Modules\StockManage\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\ComboboxController as Combobox;
use Modules\Common\Http\Controllers\ExcelController;
use Modules\Common\Http\Controllers\CsvController as csv;
use Paginator, Dao, Excel;

class InputOutputSearchController extends Controller {
    private $error                    = array();
    private $error_header             = [array('行NO,項目名,内容')];
    private $msg_err_required         = '必須入力です。';
    private $fileErrName              = 'アップロードエラー_';
    private $msg_err_maxlength        = '最大長を超えています。最大長は{0}文字です。';
    // private $msg_err_duplicate_key    = '同じ品目コード、シリアル番号が複数行に指定されています。';
    private $msg_err_date_format      = '日付のフォーマットが正しくありません。';
    private $msg_err_qty_greater_zero = '必要部品数は０より大きいです。';
    private $msg_err_qty_format       = '必要部品数は必ず数字です。';
    private $numberColumn             = 12;

    private $colNameList    =  array(
                            '入出庫No',//DSP_in_out_no
                            '入出庫区分',//DSP_in_out_div
                            '入出庫日',//DSP_in_out_date
                            '入力種別',//DSP_in_out_data_div
                            '倉庫コード',//DSP_warehouse_div
                            '倉庫名',//DSP_warehouse_nm
                            '品目コード',//DSP_item_cd
                            '品目名',//DSP_item_nm_j
                            '規格',//DSP_specification
                            'シリアル',//DSP_serial
                            '数量',//DSP_qty
                            '摘要',//DSP_detail_remarks
                        );

    private $maxLengthList  =  array(
                            '1'  => 1,      //DSP_in_out_div
                            '2'  => 10,     //DSP_in_out_date
                            '4'  => 6,      //DSP_warehouse_div
                            '6'  => 6,      //DSP_item_cd
                            '9'  => 7,      //DSP_serial
                            '10' => 6,      //DSP_qty
                            '11' => 200,    //DSP_detail_remarks
                        );

    private $requiredList   =  array(
                            //0,//DSP_in_out_no
                            1,//DSP_in_out_div
                            2,//DSP_in_out_date
                            4,//DSP_warehouse_div
                            6,//DSP_item_cd
                            10,//DSP_qty
                        );
    
    /**
     * list pi
     * -----------------------------------------------
     * @author      :   ANS806 - 2017/08/10 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function getSearch() {
        try {
            //get library
            $in_out_div          = Combobox::libraryCode('in_out_div');
            $in_out_data_div     = Combobox::libraryCode('in_out_data_div');
            $paginator  = new Paginator(0, 0, 0, 0);
            $paginate   = $paginator->show(1, 'paginate');
            $fillter    = $paginator->fillter();
            return view('stockmanage::InputOutputSearch.index',compact('paginate', 'fillter', 'in_out_div', 'in_out_data_div'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }

    /**
     * search stock manager input output
     * -----------------------------------------------
     * @author      :   ANS806 - 2018/01/16 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postSearch(Request $request) {
        try {
            $param          = $request->all();
            $sql            = "SPC_049_INPUT_OUTPUT_FND1";
            $data           = Dao::call_stored_procedure($sql, $param, true);

            $Lists         = isset($data[0]) ? $data[0] : array();
            $paginator      = new Paginator($data[1][0]['pageMax'], $data[1][0]['pagesize'], $data[1][0]['page'], $data[1][0]['totalRecord']);
            $paginate       = $paginator->show($data[1][0]['page'], 'paginate');
            $fillter        = $paginator->fillter();

            $html           = view('stockmanage::InputOutputSearch.list',compact('Lists','paginate', 'fillter'))->render();
            return response()->json(array(
                'response'      => true,
                'html'          => $html
            ));
        } catch (\Exception $e) {
            return response()->json(array(
                                'response'  =>  false,
                                'error'     =>  $e->getMessage()
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
            //check file no data
            if (count($dataInput) == 0 || count($dataInput[1]) != $this->numberColumn) {
                //file no data
                return response()->json(array(
                    'response' => false,
                    'error_cd' => 'E751'
                ));
            }
            //format serial item
            /*foreach($dataInput as $key => $value){
                $dataInput[$key][9] = str_pad($value[9],7,"0",STR_PAD_LEFT);
            }*/
            //check conent file
            $checkContentFile = $this->checkContentFile($dataInput);
            //data to json
            $data_json = $this->getDataJson($dataInput);
            //param for store
            $param['data_input_list'] = $data_json;
            $param['has_error']       = $checkContentFile ? 0: 1;
            $param['cre_user_cd']     = \GetUserInfo::getInfo('user_cd');
            $param['cre_prg_cd']      = '049_input-output-search';
            $param['cre_ip']          = \GetUserInfo::getInfo('user_ip');
            //call store
            $sql                      = "SPC_049_INPUT_OUTPUT_SEARCH_ACT1"; 
            $result                   = Dao::call_stored_procedure($sql,$param);

            //return result to client
            if (empty($result[0]) && empty($result[2]) && $checkContentFile) {
                return response()->json(array(
                    'response'      => true,
                    'error_cd'      => $result[1][0]['error_cd']
                ));
            } else {
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
     * @author      :   ANS804 - 2018/07/02 - create
     * @param       :
     * @return      :   mixed
     * @access      :   private
     * @see         :   remark
     */
    private function checkContentFile(Array $data){
        try{
            $flag           = true;
            $this->error    = array();
            $countData      = count($data);
            $arr_duplicate  = array();
            $in_out_div     = 1;
            $in_out_date    = 2;
            $warehouse_div  = 4;
            $item_cd        = 6;
            $serial         = 9;
            $qty            = 10;
            $detail_remarks = 11;

            foreach ($data as $key=>$row) {
                for ($i=0; $i < $this->numberColumn; $i++) { 
                    
                    if(!isset($row[$i])){
                        $row[$i] = "";
                    }

                    if ($i == $qty) {
                        $row[$i] = str_replace(',', '', $row[$i]);
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
                    if (($i==$in_out_date || $i==$warehouse_div || $i==$item_cd || $i==$serial || $i==$qty || $i==$detail_remarks) 
                        &&  strlen(trim($row[$i])) > intval($this->maxLengthList[$i])) { 
                        $flag     = false;
                        $lineNo   = $key + 1;
                        $itemName = $this->colNameList[$i];
                        $msgErr   = str_replace('{0}', $this->maxLengthList[$i], $this->msg_err_maxlength);
                        $temp     = array('row_no' => $lineNo, 'item_err' => $itemName, 'msg_err' => $msgErr);
                        array_push($this->error,$temp);
                    }
                }

                // validate date
                if (trim($row[$in_out_date]) != "" && !$this->validateDate($row[$in_out_date])) {
                    $flag     = false;
                    $lineNo   = $key + 1;
                    $itemName = $this->colNameList[$in_out_date];
                    $msgErr   = $this->msg_err_date_format;
                    $temp     = array('row_no' => $lineNo, 'item_err' => $itemName, 'msg_err' => $msgErr);
                    array_push($this->error,$temp);
                }

                //check qty not number
                if (trim($row[$qty]) != "" && !is_numeric($row[$qty])) {
                    $flag     = false;
                    $lineNo   = $key + 1;
                    $itemName = $this->colNameList[$qty];
                    $msgErr   = $this->msg_err_qty_format;
                    $temp     = array('row_no' => $lineNo, 'item_err' => $itemName, 'msg_err' => $msgErr);
                    array_push($this->error,$temp);
                }

                //check qty is greater than 0
                if (trim($row[$qty]) != "" && is_numeric($row[$qty]) && intval($row[$qty]) <= 0) {
                    $flag     = false;
                    $lineNo   = $key + 1;
                    $itemName = $this->colNameList[$qty];
                    $msgErr   = $this->msg_err_qty_greater_zero;
                    $temp     = array('row_no' => $lineNo, 'item_err' => $itemName, 'msg_err' => $msgErr);
                    array_push($this->error,$temp);
                }
                //check numeric only serial item
                if (trim($row[$serial]) != "" && (preg_match('#[^0-9]#', $row[$serial]))) {
                    $flag     = false;
                    $lineNo   = $key + 1;
                    $itemName = $this->colNameList[$serial];
                    $msgErr   = 'シリアル番号は数字だけ入力してください。';
                    $temp     = array('row_no' => $lineNo, 'item_err' => $itemName, 'msg_err' => $msgErr);
                    array_push($this->error,$temp);
                }
                //Check duplicate key
                // if ($key + 1 <= $countData) {
                //     for ($i = $key + 1; $i <= $countData; $i++) { 
                //         $next_row = $data[$i];

                //         if (        trim($row[$item_cd]) != '' 
                //                 &&  trim($row[$serial]) != '' 
                //                 &&  $row[$item_cd] === $next_row[$item_cd] 
                //                 &&  $row[$serial] === $next_row[$serial]) {

                //             $flag     = false;
                //             if (!in_array($i,$arr_duplicate)) {
                //                 array_push($arr_duplicate,$i);

                //                 $lineNo   = $i + 1;
                //                 $itemName = $this->colNameList[$item_cd].'、'.$this->colNameList[$serial];
                //                 $msgErr   = str_replace('{0}', ($key+1), $this->msg_err_duplicate_key);
                //                 $temp     = array('row_no' => $lineNo, 'item_err' => $itemName, 'msg_err' => $msgErr);
                //                 array_push($this->error,$temp);
                //             }
                //         }
                //     }
                // }
            }

            return $flag;
        } catch (\Exception $e) {
            return false;
        }        
    }

    /**
     * convert data to json
     * -----------------------------------------------
     * @author      :   AANS804 - 2018/07/02 - create
     * @param       :
     * @return      :   mixed
     * @access      :   private
     * @see         :   remark
     */
    private function getDataJson(Array $data){
        try{
            $keyNameUse = [
                    '1'  => 'in_out_div',
                    '2'  => 'in_out_date',
                    '4'  => 'warehouse_div',
                    '6'  => 'item_cd',
                    '9'  => 'serial',
                    '10' => 'qty',
                    '11' => 'detail_remarks',
            ];
            $keyNameRemove = [
                    '0' => '',
                    '3' => '',
                    '5' => '',
                    '7' => '',
                    '8' => '',
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
                            if ($nameUse == 'qty') {
                                // IF nameUse is child_item_qty, THEN remove ','
                                $data[$index][$nameUse] = is_numeric(str_replace(',', '',trim($value, ' '))) ? str_replace(',', '',trim($value, ' ')) : 0;
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
     * validate Date
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/07/02 - create
     * @param       :
     * @return      :   mixed
     * @access      :   private
     * @see         :   remark
     */
    private function validateDate($date){
        $d = \DateTime::createFromFormat('Y/m/d', $date);
        return $d && $d->format('Y/m/d') == $date;        
    }

    /**
     * get data using for export
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/07/02 - create
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
     * export file errror
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/07/02 - create
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
}
