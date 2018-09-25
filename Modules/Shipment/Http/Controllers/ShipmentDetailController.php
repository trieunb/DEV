<?php
/**
*|--------------------------------------------------------------------------
*| Order
*|--------------------------------------------------------------------------
*| Package       : Shipment Detail 
*| @author       : Trieunb - ANS806 - trieunb@ans-asia.com
*| @created date : 2017/01/08
*| Description   : 
*/
namespace Modules\Shipment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\ComboboxController as Combobox;
use Session, Dao, Button;

class ShipmentDetailController extends Controller
{
    /**
    * list shipment
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/01/08 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function getDetail()
    {
        try {

            $forwarding_div             = Combobox::libraryCode('forwarding_div');
            // $forwarding_way_div         = Combobox::libraryCode('forwarding_way_div');
            $forwarding_way_div         = Combobox::libraryCode('shipment_div');
            $forwarding_warehouse_div   = Combobox::libraryCode('forwarding_warehouse_div');
            $forwarder_div              = Combobox::libraryCode('forwarder_div');
            $packing_method_div         = Combobox::libraryCode('packing_method_div');
            $user_training_check_div    = Combobox::libraryCode('user_training_check_div');


            $mode  = 'U';
            $from  = 'ShipmentDetail';
            if (Session::has('SELF')) {
                Session::forget('SELF');
                Session::forget('ShipmentDetail');
            } else {
                if (Session::has('ShipmentDetail')) {                    
                    $param = Session::get('ShipmentDetail');
                    if (!empty($param)) {
                        $mode  = $param['mode'];
                        $from  = $param['from'];
                        if (isset($param['shipment_no']) && !empty($param['shipment_no'])) {
                            $shipment_no =  $param['shipment_no'];
                        }
                    }
                }
            }
            return view('shipment::ShipmentDetail.detail', compact('shipment_no', 'mode', 'from', 'forwarding_div', 'forwarding_way_div', 'forwarding_warehouse_div', 'forwarder_div', 'packing_method_div', 'user_training_check_div'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }
    /**
    * refer receice No
    * -----------------------------------------------
    * @author      :   ANS831 - 2018/02/28 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postReferReceive(Request $request){
        try{
            //get rcv_cd from client
            $param                  = array($request->rcv_cd);
            $sql                    = "SPC_010_SHIPMENT_DETAIL_INQ1"; 
            $data                   = Dao::call_stored_procedure($sql,$param);
            $received_info          = isset($data[0][0]) ? $data[0][0] : array();
            $received_info_table    = isset($data[1]) ? $data[1] : array();
            $received_html          = view('shipment::ShipmentDetail.table',compact('received_info_table'))->render();
            //return data
            if(isset($data[1])){
                return response()->json(array(
                    'response'             => true,
                    'received_info'        => $received_info,
                    'received_html'        => $received_html
                ));
            }else{
                return response()->json(array(
                    'response'            => false,
                    'error'               => isset($data[0][0]['error']) ? $data[0][0]['error'] : $data[2][0]['error']
                ));
            }
        }
        catch (\Exception $e) {
            return response()->json(array(
                'response'=> false
            ));
        }        
    }
    /**
    * Refer Pi No
    * -----------------------------------------------
    * @author      :   ANS831 - 2018/02/28 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postReferPiNo(Request $request){
        try{
            //get pi_no from client
            $param                  = array($request->pi_no);
            $sql                    = "SPC_010_SHIPMENT_DETAIL_INQ2"; 
            $data                   = Dao::call_stored_procedure($sql,$param,true);
            $received_info          = isset($data[0][0]) ? $data[0][0] : array();
            $received_info_table    = isset($data[1]) ? $data[1] : array();
            $received_html          = view('shipment::ShipmentDetail.table',compact('received_info_table'))->render();
            //return data
            if(isset($data[1])){
                return response()->json(array(
                    'response'             => true,
                    'received_info'        => $received_info,
                    'received_html'        => $received_html
                ));
            }else{
                return response()->json(array(
                    'response'            => false,
                    'error'               => isset($data[0][0]['error']) ? $data[0][0]['error'] : $data[2][0]['error']
                ));
            }
        }
        catch (\Exception $e) {
            return response()->json(array(
                'response'=> false
            ));
        }        
    }
    /**
    * Refer shipment
    * -----------------------------------------------
    * @author      :   ANS831 - 2018/02/28 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postReferShipment(Request $request){
        try{
            //get data from client
            $param                  = array($request->fwd_no);
            $mode                   = array($request->mode);

            //call stored
            $sql                    = "SPC_010_SHIPMENT_DETAIL_INQ3"; 
            $data                   = Dao::call_stored_procedure($sql,$param);

            $shipment_info          = isset($data[0][0]) ? $data[0][0] : array();

            $received_info_table    = isset($data[1]) ? $data[1] : array();
            $received_info          = isset($data[2][0]) ? $data[2][0] : array();
            $header                 = isset($data[3][0]) ? $data[3][0] : array();
            $header_html            = view('layouts._operator_info',compact('header'))->render();
            $received_html          = view('shipment::ShipmentDetail.table',compact('received_info_table','shipment_info'))->render();
            if (!empty($shipment_info)) {
                $fwd_status_div       =   $shipment_info['fwd_status_div'];
            }
            switch ($fwd_status_div) {
                case "10":
                    $status   =   'R';
                    break;
                case "20":
                    $status   =   'A';
                    break;
                case "90":
                    $status   =   'O';
                    break;
                case "91":
                    $status   =   'O';
                    break;
                case "92":
                    $status   =   'O';
                    break;
                default:
                    $status   =   'R';
                    break;
            }
            $button      = Button::showButtonServer(array('btn-back', 
                                                          'btn-save', 
                                                          'btn-delete',
                                                          'btn-approve', 
                                                          'btn-cancel-approve',
                                                          'btn-issue'), ($mode[0] != 'I') ? $status : $mode[0]);
            //return data
            if(isset($data[1])){
                return response()->json(array(
                    'response'             => true,
                    'shipment_info'        => $shipment_info,
                    'received_info'        => $received_info,
                    'received_html'        => $received_html,
                    'header_html'          => $header_html,
                    'button'               => $button,
                ));
            }else{
                return response()->json(array(
                    'response'            => false,
                    'button'               => $button,

                ));
            }
        }
        catch (\Exception $e) {
            return response()->json(array(
                'response'=> false
            ));
        }        
    }
    /**
     * Create/Update Shipment Detail
     * -----------------------------------------------
     * @author      :   ANS831 - 2018/03/05 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postSaveShipmentDetail(Request $request) {
        try {
            //get data from client
            $data                   = $request->all();
            $data['t_fwd_d']        =  json_encode($data['t_fwd_d']);//parse json to string
            $data['cre_user_cd']    = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     = '010_shipment-detail';
            $data['cre_ip']         = \GetUserInfo::getInfo('user_ip');
            $sql                    = "SPC_010_SHIPMENT_DETAIL_ACT1";
            $result                 = Dao::call_stored_procedure($sql, $data);
            $error_list             =  isset($result[2]) ? $result[2] : array();
            $error_serial_list      =  isset($result[3]) ? $result[3] : array();
            // dd($error_serial_list);

            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'          => true,
                    'error_cd'          => $result[1][0]['error_cd'],
                    'error_fwd_no'      => $result[1][0]['error_fwd_no'],
                    'error_rcv'         => $result[1][0]['error_rcv'],
                    'error_pi_no'       => $result[1][0]['error_pi_no'],
                    'fwd_no_h'          => $result[1][0]['fwd_no_h'],
                    'inv_no'            => $result[1][0]['inv_no'],
                    'error_list'        => $error_list,
                    'error_serial_list' => $error_serial_list,
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
     * Delete Shipment Detail
     * -----------------------------------------------
     * @author      :   ANS831 - 2018/03/09 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postDeleteShipmentDetail(Request $request) {
        try {
            //get data from client
            $data                   = $request->all();
            $data['cre_user_cd']    = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     = '010-shipment-detail';
            $data['cre_ip']         = \GetUserInfo::getInfo('user_ip');

            //call stored
            $sql                    = "SPC_010_SHIPMENT_DETAIL_ACT2";
            $result                 = Dao::call_stored_procedure($sql, $data);
            //return result to client
            if (!isset($result[0])) {
                return response()->json(array(
                    'response'      => true,
                    'error_cd'      => $result[1][0]['error_cd'],
                    'fwd_no'        => $result[1][0]['fwd_no'],
                    'inv_no'        => $result[1][0]['inv_no'],
                ));
            } else {
                return response()->json(array(
                    'response'      => false,
                    'error'         => $result[0][0]['Message'],
                    'error_cd'      => $result[1][0]['error_cd']
                ));
            }
        } catch(\Exception $e) {
            return response()->json(array(
                    'response'=> $e->getMessage()
            ));
        }
    }
    /**
     * approve shipment detail
     * -----------------------------------------------
     * @author      :   ANS831 - 2018/03/12 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function approveShipment(Request $request) {
        try {
            //get data from client
            $data                   = $request->all();
            $data['cre_user_cd']    = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     = '010-shipment-detail';
            $data['cre_ip']         = \GetUserInfo::getInfo('user_ip');
            //call stored
            $sql                    = "SPC_010_SHIPMENT_DETAIL_ACT3";
            $result                 = Dao::call_stored_procedure($sql, $data);
            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'      => true,
                    'error_cd'      => $result[1][0]['error_cd'],
                    'fwd_no'        => $result[1][0]['fwd_no'],
                    'fwd_status'    => $result[1][0]['fwd_status']
                ));
            } else {
                return response()->json(array(
                    'response'      => false,
                    'error'         => $result[0][0]['Message'],
                    'error_cd'      => $result[1][0]['error_cd']
                ));
            }
        } catch(\Exception $e) {
            return response()->json(array(
                    'response'=> $e->getMessage()
            ));
        }
    }

    /**
     * approve cancel shipment detail
     * -----------------------------------------------
     * @author      :   ANS831 - 2018/03/12 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function cancelApproveShipment(Request $request) {
        try {
            //get data from client
            $data                   = $request->all();
            $data['cre_user_cd']    = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     = '010-shipment-detail';
            $data['cre_ip']         = \GetUserInfo::getInfo('user_ip');
            //call stored
            $sql                    = "SPC_010_SHIPMENT_DETAIL_ACT4";
            $result                 = Dao::call_stored_procedure($sql, $data);
            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'      => true,
                    'error_cd'      => $result[1][0]['error_cd'],
                    'fwd_no'        => $result[1][0]['fwd_no'],
                    'fwd_status'    => $result[1][0]['fwd_status'],
                    'inv_no'        => $result[1][0]['inv_no'],
                ));
            } else {
                return response()->json(array(
                    'response'      => false,
                    'error'         => $result[0][0]['Message'],
                    'error_cd'      => $result[1][0]['error_cd']
                ));
            }
        } catch(\Exception $e) {
            return response()->json(array(
                    'response'=> $e->getMessage()
            ));
        }
    }
}
