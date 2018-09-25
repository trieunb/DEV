<?php
/**
*|--------------------------------------------------------------------------
*| Shifting Request
*|--------------------------------------------------------------------------
*| Package       : Shifting Request  
*| @author       : DaoNX - ANS804 - trieunb@ans-asia.com
*| @created date : 2017/01/08
*| Description   : 
*/
namespace Modules\Shifting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session, Dao, Button;

class ShiftingRequestDetailController extends Controller {
    /**
     * get detail
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/03/26 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function getDetail() {
        try {
            $mode  = 'U';
            $from  = 'ShiftingRequestDetail';
            if (Session::has('SELF')) {
                Session::forget('SELF');
                Session::forget('ShiftingRequestDetail');
            } else {
                if (Session::has('ShiftingRequestDetail')) {                    
                    $param = Session::get('ShiftingRequestDetail');
                    if (!empty($param)) {
                        $mode  = $param['mode'];
                        $from  = $param['from'];

                        if (isset($param['move_no']) && !empty($param['move_no'])) {
                            $move_no =  $param['move_no'];
                        }
                    }
                }
            }
            return view('shifting::ShiftingRequestDetail.detail', compact('move_no', 'mode', 'from'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }

    /**
     * refer move No
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/03/26 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postReferMove(Request $request) {
        try {
            $move_status_div = 0;
            //get move_no from client
            $param           = $request->all();
            
            $paramStore      = ['move_no' => $param['move_no']];
            
            $mode            = $param['mode'];
            
            $sql             = "SPC_041_SHIFTING_REQUEST_DETAIL_INQ1"; 
            $data            = Dao::call_stored_procedure($sql,$paramStore);
            
            $error           = isset($data[0][0]) ? $data[0][0] : array();
            
            $move_info_h     = isset($data[1][0]) ? $data[1][0] : array();
            
            $move_info_d     = isset($data[2]) ? $data[2] : array();
            $move_table      = view('shifting::ShiftingRequestDetail.table',compact('move_info_d'))->render();
            
            $header          = isset($data[3][0]) ? $data[3][0] : array();
            $header_html     = view('layouts._operator_info',compact('header'))->render();

            if (!empty($move_info_h)) {
                $move_status_div       =   $move_info_h['move_status_div'];
            }

            switch ($move_status_div) {
                case "10":
                    $status   =   'R';
                    break;
                case "20":
                    $status   =   'A';
                    break;
                default:
                    $status   =   'R';
                    break;
            }
            $button      = Button::showButtonServer(array('btn-back', 
                                                          'btn-save', 
                                                          'btn-delete',
                                                          'btn-approve',
                                                          'btn-issue'), ($mode == 'I') ? $mode : $status);

            //return data
            if(empty($data[0][0]['error'])) {
                return response()->json(array(
                    'response'    => true,
                    'move_info_h' => $move_info_h,
                    'move_table'  => $move_table,
                    'header_html' => $header_html,
                    'header_html' => $header_html,
                    'button'      => $button,
                ));
            } else {
                return response()->json(array(
                    'response' => false,
                    'error'    => $data[0][0]['error']
                ));
            }
        } catch (\Exception $e) {
            return response()->json(array(
                'response'=> false
            ));
        }        
    }

    /**
     * refer item No
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/03/26 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postReferItem(Request $request) {
        try {
            //get move_no from client
            $param       = $request->all();//var_dump($param);
            
            $sql         = "SPC_041_SHIFTING_REQUEST_DETAIL_INQ2"; 
            $data        = Dao::call_stored_procedure($sql,$param);
            
            if (empty($data[0][0]['error'])) {
                return response()->json(array(
                    'response' => true,
                    'data'     => $data[1][0]
                ));
            } else {
                return response()->json(array(
                    'response'   => false,
                    'error'      => $data[0][0]['error'],
                    'clear_item' => $data[0][0]['clear_item']
                ));
            }
        } catch (\Exception $e) {
            return response()->json(array(
                'response'=> false
            ));
        }        
    }

    /**
     * Create/Update Shifting Detail
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/03/26 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postSaveShiftingDetail(Request $request) {
        try {
            //get data from client
            $data                = $request->all();
            $data['move_detail'] =  json_encode($data['move_detail']);//parse json to string
            $data['serial_list'] =  json_encode($data['serial_list']);//parse json to string
            
            $data['cre_user_cd'] = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']  = '041_shifting-request-detail';
            $data['cre_ip']      = \GetUserInfo::getInfo('user_ip');
            
            $sql                 = "SPC_041_SHIFTING_REQUEST_DETAIL_ACT1";
            $result              = Dao::call_stored_procedure($sql, $data);

            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'      => true,
                    'error_cd'      => $result[1][0]['error_cd'],
                    'move_no'       => $result[1][0]['move_no'],
                ));     
            } else {
                return response()->json(array(
                    'response'      => false,
                    'error'         => $result[0][0]['Message'],
                ));
            }
        } catch (\Exception $e) {
            return response()->json(array(
                    'response'=> $e->getMessage()
            ));
        }        
    }

    /**
     * Delete Shifting Detail
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/03/26 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postDeleteShiftingDetail(Request $request) {
        try {
            //get data from client
            $data                = $request->all();
            $data['cre_user_cd'] = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']  = '041_shifting-request-detail';
            $data['cre_ip']      = \GetUserInfo::getInfo('user_ip');
            
            //call stored
            $sql                 = "SPC_041_SHIFTING_REQUEST_DETAIL_ACT2";
            $result              = Dao::call_stored_procedure($sql, $data);

            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response' => true,
                    'error_cd' => $result[1][0]['error_cd'],
                    'move_no'  => $result[1][0]['move_no'],
                ));
            } else {
                return response()->json(array(
                    'response' => false,
                    'error'    => $result[0][0]['Message'],
                    'error_cd' => $result[1][0]['error_cd']
                ));
            }
        } catch(\Exception $e) {
            return response()->json(array(
                    'response'=> $e->getMessage()
            ));
        }
    }

    /**
     * approve shifting detail
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/03/26 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function approveShifting(Request $request) {
        try {
            //get data from client
            $data                = $request->all();
            $data['move_detail'] =  json_encode($data['move_detail']);//parse json to string
            $data['serial_list'] =  json_encode($data['serial_list']);//parse json to string
            $data['cre_user_cd'] = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']  = '041_shifting-request-detail';
            $data['cre_ip']      = \GetUserInfo::getInfo('user_ip');
            
            //call stored
            $sql                 = "SPC_041_SHIFTING_REQUEST_DETAIL_ACT3";
            $result              = Dao::call_stored_procedure($sql, $data);
            
            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'    => true,
                    'error_cd'    => $result[1][0]['error_cd'],
                    'move_no'     => $result[1][0]['move_no'],
                    'move_status' => $result[1][0]['move_status'],
                    'item_error'  => $result[2]
                ));
            } else {
                return response()->json(array(
                    'response' => false,
                    'error'    => $result[0][0]['Message'],
                    'error_cd' => $result[1][0]['error_cd']
                ));
            }
        } catch(\Exception $e) {
            return response()->json(array(
                    'response'=> $e->getMessage()
            ));
        }
    }
    /**
     * refer Manufacture No
     * -----------------------------------------------
     * @author      :   ANS796 - 2018/06/29 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postReferManufacture(Request $request) {
        try {
            //get move_no from client
            $param       = $request->all();            
            $sql         = "SPC_041_SHIFTING_REQUEST_DETAIL_INQ3"; 
            $data        = Dao::call_stored_procedure($sql,$param);
            $move_info_d = isset($data[1]) ? $data[1] : array();
            $move_table  = view('shifting::ShiftingRequestDetail.tblDetail',compact('move_info_d'))->render();
            //return view
            if (isset($data[0]) && !empty($data[0])) {
                return response()->json(array(
                    'response'    => true,
                    'move_table'  => $move_table
                ));
            } else {
                return response()->json(array(
                    'response'    => false,
                    'move_table'  => $move_table
                ));
            }
        } catch (\Exception $e) {
            return response()->json(array(
                'response'=> false
            ));
        }        
    }
}
