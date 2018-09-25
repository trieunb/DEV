<?php
/**
*|--------------------------------------------------------------------------
*| LIBRARY
*|--------------------------------------------------------------------------
*| Package       : LIBRARY Master  
*| @author       : Trieunb - ANS806 - trieunb@ans-asia.com
*| @created date : 2017/08/10
*| Description   : 
*/
namespace Modules\systemmanagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session, Dao;

class LibraryMasterController extends Controller
{
    /**
    * library master detail
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/08/08 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function getDetail()
    {
        try {
            $mode  = 'I';
            $from  = 'LibMasterDetail';
            if (Session::has('SELF')) {
                Session::forget('SELF');
                Session::forget('LibMasterDetail');
            } else {
                if (Session::has('LibMasterDetail')) {
                    $param = Session::get('LibMasterDetail');
                    if (!empty($param)) {
                        $mode  = $param['mode'];
                        $from  = $param['from'];
                        if (isset($param['lib_cd']) && !empty($param['lib_cd'])) {
                            $lib_cd = $param['lib_cd'];
                        }
                    }
                }
            }
            return view('systemmanagement::library.detail', compact('lib_cd','mode', 'from'));
        } catch (\Exception $e) {
            return response()->json(array('response' => false));
        }
    }
    /**
    * refer library
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/08/08 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function referLibrary(Request $request)
    {
        try {
            $param = [
                'lib_cd'        => $request->lib_cd
            ];
            $sql   = "SPC_056_LIBRARY_MASTER_INQ1"; 
            $data  = Dao::call_stored_procedure($sql, $param, true);
           
            $libraryDetail      = isset($data[0]) ? $data[0] : array();
            $libraryInput       = isset($data[1]) ? $data[1] : array();
            $header             = isset($libraryDetail[0]) ? $libraryDetail[0] : array();

            $header_html        = view('layouts._operator_info', compact('header'))->render();
            $html_detail_input  = view('systemmanagement::library.table_detail',compact('libraryInput'))->render();
            return response()->json(array(
                'response'             => true,
                'header_html'          => $header_html,
                'libraryDetail'        => $libraryDetail[0],
                'html_detail_input'    => $html_detail_input
            ));
        } catch (\Exception $e) {
            return response()->json(array('response' => false));
        }
    }
    /**
    * Create/Update Library Information
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/11/15 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postSave(Request $request)
    {
        try {
            //get data from client
            $data                   = $request->all();
            $data['lib_val']        =  json_encode($data['lib_val']);//parse json to string
            $data['cre_user_cd']    = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     = '056_library-master';
            $data['cre_ip']         = \GetUserInfo::getInfo('user_ip');
            //call stored
            $sql                    = "SPC_056_LIBRARY_MASTER_ACT1";
            $result                 = Dao::call_stored_procedure($sql, $data);
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
                    'response'=> $e->getMessage()
            ));
        }        
    }
}
