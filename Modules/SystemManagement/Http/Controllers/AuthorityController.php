<?php
/**
*|--------------------------------------------------------------------------
*| Authority
*|--------------------------------------------------------------------------
*| Package       : SystemManagement  
*| @author       : HaVV - ANS817 - havv@ans-asia.com
*| @created date : 2018/05/14
*| Description   : 
*/
namespace Modules\SystemManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\ComboboxController as Combobox;
use Modules\Common\Http\Controllers\CsvController as csv;
use Paginator, Dao;

class AuthorityController extends Controller
{
    private $error    = array();

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getAuthority()
    {
        try {
            //get library
            $auth_role_div          = Combobox::libraryCode('auth_role_div');
            return view('systemmanagement::authority.index', compact('auth_role_div'));
        } catch (\Exception $e) {
            return response()->json(array('response' => false));
        }
    }

    /**
    * search Authority
    * -----------------------------------------------
    * @author      :   ANS817 - 2018/05/14 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postSearch(Request $request){
        try{
            $param         = $request->all();
            $sql           = "SPC_075_AUTHORITY_FND1"; 
            $data          = Dao::call_stored_procedure($sql,$param,true);
            $authorityList = isset($data[0]) ? $data[0] : array();
            $html          = view('systemmanagement::authority.list',compact('authorityList'))->render();

            //return data
            return response()->json(array(
                'response'      => true,
                'html'          => $html
            ));
        }
        catch (\Exception $e) {
            return response()->json(array(
                'response'  => false,
                'error'     => $e->getMessage()
            ));
        }        
    }

    /**
    * save Authority
    * -----------------------------------------------
    * @author      :   ANS817 - 2018/05/15 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postSave(Request $request){
        try{
            $param                = $request->all();
            $param['m_auth']      = json_encode($param['m_auth']);//parse json to string
            $param['cre_user_cd'] = \GetUserInfo::getInfo('user_cd');
            $param['cre_prg_cd']  = '075_authority';
            $param['cre_ip']      = \GetUserInfo::getInfo('user_ip');

            $sql                  = "SPC_075_AUTHORITY_ACT1"; 
            $result               = Dao::call_stored_procedure($sql,$param);
            
            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'      => true
                ));
            }else{
                return response()->json(array(
                    'response'      => false,
                    'error'         => $result[0][0]['Message'],
                ));
            }
        }
        catch (\Exception $e) {
            return response()->json(array(
                'response'  => false,
                'error'     => $e->getMessage()
            ));
        }        
    }

    /**
    * export csv
    * -----------------------------------------------
    * @author      :   ANS817 - 2018/05/17 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function getExport(Request $request){
        try {
            //get data
            $sql           = "SPC_075_AUTHORITY_INQ1"; 
            $data          = Dao::call_stored_procedure($sql, array(), true);
            $header        = isset($data[0]) ? $data[0] : array();
            $authorityList = isset($data[1]) ? $data[1] : array();

            $dataExport    = array_merge($header,$authorityList);

            //create csv file
            $csv          = new csv;
            $filenameTemp = 'authority_';
            $fileTemp     = $csv->outputCSV($dataExport, $filenameTemp);
            //rename file
            $filename     = DOWNLOAD_CSV_PUBLIC.'権限設定マスタ_'.date('YmdHis').'.csv';
            rename(public_path().$fileTemp, mb_convert_encoding($filename, 'SJIS', 'UTF-8'));

            return response()->json([
                'response'  =>  true,
                'file'      =>  str_replace(public_path(), '', $filename)
            ]);
        } catch(\Exception $e) {
            return response()->json(array(
                'response' => false, 
                'status'   => 'ng', 
                'error'    => $e->getMessage()
            ));

        }
    }

    /**
    * upload csv
    * -----------------------------------------------
    * @author      :   ANS817 - 2018/05/17 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postUpload(Request $request){
        try{
            $file               = $request->file('file');

            //get data input
            $fileName           = $file->getClientOriginalName();
            $destinationPath    = UPLOAD_CSV;
            $file->move($destinationPath, mb_convert_encoding($fileName, 'SJIS', 'UTF-8'));
            // $file->move($destinationPath, $fileName);
            $filePath           = $destinationPath . '/' . $fileName;
            $dataInput          = csv::inputCSV(mb_convert_encoding($filePath, 'SJIS'), false);
            //remove header
            unset($dataInput[0]);

            //check conent file
            $checkContentFile = $this->checkContentFile($dataInput);
            if (!$checkContentFile) {
                //check content file not OK
                return response()->json(array(
                    'response'      => false,
                    'error_cd'      => 'E751',
                ));
            }

            //param for store
            $data_json                = $this->getDataJson($dataInput);
            $param['data_input_list'] = $data_json;
            $param['cre_user_cd']     = \GetUserInfo::getInfo('user_cd');
            $param['cre_prg_cd']      = '075_authority';
            $param['cre_ip']          = \GetUserInfo::getInfo('user_ip');
            //call store
            $sql                = "SPC_075_AUTHORITY_ACT2"; 
            $result             = Dao::call_stored_procedure($sql,$param);

            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'      => true,
                    'error_cd'      => $result[1][0]['error_cd']
                ));
            }else{
                return response()->json(array(
                    'response'      => false,
                    'error'         => $result[0][0]['Message'],
                    'error_cd'      => $result[1][0]['error_cd']
                ));
            }
        } catch (\Exception $e) {
            return response()->json(array(
                'response'  => false,
                'error'     => $e->getMessage()
            ));
        }        
    }

    /**
    * convert data to json
    * -----------------------------------------------
    * @author      :   ANS817 - 2018/05/18 - create
    * @param       :
    * @return      :   mixed
    * @access      :   private
    * @see         :   remark
    */
    private function getDataJson(Array $data){
        try{
            //change key
            $data_json = '';
            foreach ($data as $index=>$row) {
                foreach ($row as $key => $value) {
                    if ($key == 0) {
                        $data[$index]['auth_role_div'] = trim($value, ' ');
                        unset($data[$index][$key]);
                    }
                    if ($key == 1) {
                        $data[$index]['auth_role_div_nm'] = trim($value, ' ');
                        unset($data[$index][$key]);
                    }
                    if ($key == 2) {
                        $data[$index]['prg_cd'] = trim($value, ' ');
                        unset($data[$index][$key]);
                    }
                    if ($key == 3) {
                        $data[$index]['prg_nm'] = trim($value, ' ');
                        unset($data[$index][$key]);
                    }
                    if ($key == 4) {
                        $data[$index]['fnc_cd'] = trim($value, ' ');
                        unset($data[$index][$key]);
                    }
                    if ($key == 5) {
                        $data[$index]['fnc_nm'] = trim($value, ' ');
                        unset($data[$index][$key]);
                    }
                    if ($key == 6) {
                        $data[$index]['fnc_use_div'] = trim($value, ' ');
                        unset($data[$index][$key]);
                    }
                }
                $data_json .= json_encode($data[$index]).',';
            }
            $data_json = rtrim($data_json,",");

            return '['.$data_json.']';
        } catch (\Exception $e) {
            return '';
        }        
    }

    /**
    * check content file
    * -----------------------------------------------
    * @author      :   ANS817 - 2018/05/18 - create
    * @param       :
    * @return      :   mixed
    * @access      :   private
    * @see         :   remark
    */
    private function checkContentFile(Array $data){
        try{
            $flag         = true;
            $numberColumn = 7;

            $colNameList    =  array(
                                    '権限区分コード',//auth_role_div
                                    '権限区分',//auth_role_div_nm
                                    'プログラムコード',//prg_cd
                                    'プログラム名',//prg_nm
                                    '機能コード',//fnc_cd
                                    '機能名称',//fnc_nm
                                    '機能利用区分'//fnc_use_div
                                );
            $maxLengthList  =  array(3, 100, 20, 100, 50, 30, 1);
            $requiredList   =  array(
                                    0, //auth_role_div
                                    2, //prg_cd
                                    4, //fnc_cd
                                    6, //fnc_use_div
                                );

            foreach ($data as $key=>$row) {
                for ($i=0; $i < $numberColumn; $i++) { 
                    $temp = array();

                    if(!isset($row[$i])){
                        $row[$i]="";
                    }

                    // Check required
                    if (in_array($i,$requiredList) && trim($row[$i])=="") { 
                        $flag    = false;
                        $temp[]  = $i;
                    } 
                    // Check Maxlength
                    if (strlen(trim($row[$i])) > intval($maxLengthList[$i])) { 
                        $flag    = false;
                        $temp[]  = $i;
                    }
                    //Check HalfSize
                    // if (trim($row[$i])!="" && $this->isJapanese(trim($row[$i]))){ 
                    //     $flag    = false;
                    //     $temp[]  = $i;
                    // }

                    if(!$flag){
                        $temp = array_unique($temp);

                        foreach ($temp as $value) {
                            $this->error[]  = [
                                'line' => $key,
                                'col'  => $colNameList[$value],
                                'msg'  => 'msg'
                            ];
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
     * Check isJapanese
     * -----------------------------------------------
     * @author      :   ANS817 - 2018/05/18 - create
     * @param       :
     * @return      :  
     * @access      :   private
     * @see         :   remark
     */
    private function isJapanese($str) {
        return preg_match('/[\x{4E00}-\x{9FBF}\x{3040}-\x{309F}\x{30A0}-\x{30FF}]/u', $str);
    }
}
