<?php

namespace Modules\Common\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Dao, Input, Session, File, ZipArchive;

class CommonController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function postLinkSession(Request $request)
    {
         try {
            $screen_id  = $request->to_ScreenId;
            $parram     = $request->parram;
            if (Session::has('SELF')) {
                Session::forget('SELF');
            }
            Session::set($screen_id, $parram);
            Session::set('screen', $request->from_ScreenId);
            return response()->json(array('response' => true, 'status' => 'ok'));
        } catch(\Exception $e){
            return response()->json(array('response' => false, 'status' => 'ng'));
        }
    }
    /**
    * get constant from s_ctl table into constant.js
    *
    * @author      :   ANS796 - 2017/11/29 - create
    * @param       :   null
    * @return      :   null
    * @access      :   public
    * @see         :
    */
    public static function getAllConstant(){
        // language folder path
        $lang_folder_path = base_path() . '/public/js/common/';
            // get file path of messages.php file based on language
        $lang_file_path = $lang_folder_path . 'constant' . '.js';
        
        $_constCd      =    '';
        $_constNm      =    '';
        $_constVal1    =    '';
        $_constVal2    =    '';
        $_constVal3    =    '';
        $_constOrder   =    '';
        $_constFlg     =    '';
        $script_ctl     =    '';
        if (File::exists($lang_folder_path)) {
            $constant_data = self::getConstant();

            if (!empty($constant_data)) {
                foreach ($constant_data as $row){

                    $_constCd[$row['ctl_cd']]       = htmlspecialchars_decode($row['ctl_cd']);
                    $_constNm[$row['ctl_cd']]       = $row['ctl_nm'];
                    $_constVal1[$row['ctl_cd']]     = $row['ctl_val1'];
                    $_constVal2[$row['ctl_cd']]     = $row['ctl_val2'];
                    $_constVal3[$row['ctl_cd']]     = $row['ctl_val3'];
                    $_constOrder[$row['ctl_cd']]    = $row['disp_order'];
                    $_constFlg[$row['ctl_cd']]      = $row['disp_flg'];

                    $script_ctl  = "var _constCd = " . json_encode($_constCd, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) . ";";
                    $script_ctl .= "var _constNm = " . json_encode($_constNm, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) . ";";
                    $script_ctl .= "var _constVal1 = " . json_encode($_constVal1, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) . ";";
                    $script_ctl .= "var _constVal2 = " . json_encode($_constVal2, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) . ";";
                    $script_ctl .= "var _constVal3 = " . json_encode($_constVal3, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) . ";";
                    $script_ctl .= "var _constOrder = " . json_encode($_constOrder, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) . ";";
                    $script_ctl .= "var _constFlg = " . json_encode($_constFlg, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) . ";";
                }
            }
            // write into file
            $bytes_written = File::put($lang_file_path, $script_ctl);
            return response()->json(array('response'=>'true','status'=>'ok'));
        }else{
            return response()->json(array('response'=>'false','status'=>'ng'));
        }
    } 
    /**
    * get constant
    *
    * @author      :   ANS796 - 2017/11/29 - create
    * @param       :   null
    * @return      :   null
    * @access      :   public
    * @see         :
    */
    private static function getConstant(){
        //execute store procedure
        $data = Dao::call_stored_procedure('SPC_GET_CONSTANT',array(),'default');
        if(isset($data[0])){
            return $data[0];
        }

    }
    /**
    * get data header for layout master export
    *
    * @author      :   ANS806 - 2018/01/10 - create
    * @param       :   null
    * @return      :   null
    * @access      :   public
    * @see         :
    */
    public static function getDataHeaderExcel($ctl_val = '') {
        try {
            if(empty($ctl_val) || $ctl_val == ''){
                $ctl_val    =   'ctl_val2';
            }
            if($ctl_val == 'JP'){
                $ctl_val    =   'ctl_val1';
            }
            /*if (empty($ctl_val) || $ctl_val !== 'JP') {
                $ctl_val    =   'ctl_val2';
            } else {
                $ctl_val    =   'ctl_val1';
            }*/
            $result         = self::getConstant();
            $CONSTANT       = [];
            //set index for result array
            foreach($result as $key => $value){
                $CONSTANT[$value['ctl_cd']] = $value;
            }
            $header     =   [
                            'company_nm'            =>  isset($CONSTANT['company_nm'][$ctl_val]) ? $CONSTANT['company_nm'][$ctl_val] : '',
                            'company_zip'           =>  isset($CONSTANT['company_zip'][$ctl_val]) ? $CONSTANT['company_zip'][$ctl_val] : '',
                            'company_zip_address'   =>  isset($CONSTANT['company_zip_address'][$ctl_val]) ? $CONSTANT['company_zip_address'][$ctl_val] : '',
                            'company_address'       =>  (isset($CONSTANT['company_address1'][$ctl_val]) ? $CONSTANT['company_address1'][$ctl_val] : '') . (isset($CONSTANT['company_address2'][$ctl_val]) ? $CONSTANT['company_address2'][$ctl_val] : ''),
                            'company_tel'           =>  isset($CONSTANT['company_tel'][$ctl_val]) ? $CONSTANT['company_tel'][$ctl_val] : '',
                            'company_fax'           =>  isset($CONSTANT['company_fax'][$ctl_val]) ? $CONSTANT['company_fax'][$ctl_val] : '',
                            'company_mail'          =>  isset($CONSTANT['company_mail'][$ctl_val]) ? $CONSTANT['company_mail'][$ctl_val] : '',
                            'company_url'           =>  isset($CONSTANT['company_url'][$ctl_val]) ? $CONSTANT['company_url'][$ctl_val] : '',
                        ];
            return $header;
        } catch(\Exception $e) {
            return response()->json(array('response' => false, 'status' => 'ng'));
        }
    }
    /**
    * common zip file
    *
    * @author      :   ANS806 - 2018/01/10 - create
    * @param       :   null
    * @return      :   null
    * @access      :   public
    * @see         :
    */
    public static function ZipFile($zipFilePath, $zipFileName, $zip_array) {
        try {
            foreach ($zip_array as $key => $value) {
                $fileName   =   $value;
                $isZip      =   false;
                $zip        =   new ZipArchive;
                $isZip      =   $zip->open($zipFilePath.$zipFileName, ZipArchive::CREATE);
                $fileNew    =   $key.'_output_excel.xlsx';
                if ($isZip) { 
                    rename($zipFilePath.mb_convert_encoding($fileName,"SJIS-win", "UTF-8"), $zipFilePath.$fileNew);
                    
                    $zip->addFile($zipFilePath.$fileNew, $fileNew);
                    
                    $zip->renameIndex($key, mb_convert_encoding($fileName, "SJIS-win", "UTF-8"));

                    $zip->close();
                    $isZip  =   true;

                }
                \File::delete($zipFilePath.$fileNew);
            }
            return $isZip;
        } catch(\Exception $e) {
            return response()->json(array('response' => false, 'status' => 'ng'));
        }
    }
}
