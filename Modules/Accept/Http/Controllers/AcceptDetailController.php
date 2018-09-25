<?php
/**
*|--------------------------------------------------------------------------
*| Accept
*|--------------------------------------------------------------------------
*| Package       : Accept  
*| @author       : Trieunb - ANS806 - trieunb@ans-asia.com
*| @created date : 2017/01/08
*| Description   : 
*/
namespace Modules\Accept\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\ComboboxController as Combobox;
use Session, DB, Dao, Button;

class AcceptDetailController extends Controller {
    /**
     * list accept
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/01/05 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function getDetail() {
        try {
             $port_city_div     = Combobox::libraryCode('port_city_div');
             $port_country_div  = Combobox::libraryCode('port_country_div');
             $shipment_div      = Combobox::libraryCode('shipment_div');
             $currency_div      = Combobox::libraryCode('currency_div');
             $trade_terms_div   = Combobox::libraryCode('trade_terms_div');
             $sales_detail_div  = Combobox::libraryCode('sales_detail_div');
             $unit_q_div        = Combobox::libraryCode('unit_q_div');
             $unit_w_div        = Combobox::libraryCode('unit_w_div');
             $unit_m_div        = Combobox::libraryCode('unit_m_div');

            $mode  = 'U';
            $from  = 'AcceptDetail';

            if (Session::has('SELF')) {
                Session::forget('SELF');
                Session::forget('AcceptDetail');
            } else {
                if (Session::has('AcceptDetail')) {
                    $param = Session::get('AcceptDetail');
                    if (!empty($param)) {
                        $mode  = $param['mode'];
                        $from  = $param['from'];
                        if (isset($param['accept_no']) && !empty($param['accept_no'])) {
                            $acceptDetail_no = $param['accept_no'];
                        }
                    }
                }
            }

            $cre_user_cd = \GetUserInfo::getInfo('user_cd');
            $cre_user_nm = \GetUserInfo::getInfo('user_nm_j');

            return view('accept::AcceptDetail.detail', compact('acceptDetail_no', 'mode', 'from', 'cre_user_cd', 'cre_user_nm', 'port_city_div', 'port_country_div', 'shipment_div', 'currency_div', 'trade_terms_div', 'sales_detail_div', 'unit_q_div', 'unit_w_div', 'unit_m_div'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }
   
    /**
     * refer accept detail
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/01/05 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function referAcceptDetail(Request $request) {
        try {
            $sales_detail_div  = Combobox::libraryCode('sales_detail_div');
            $unit_q_div        = Combobox::libraryCode('unit_q_div');
            $unit_w_div        = Combobox::libraryCode('unit_w_div');
            $unit_m_div        = Combobox::libraryCode('unit_m_div');
            //
            $param = ['rcv_no' => $request->rcv_no];
            $mode  = $request->mode;
            
            $sql   = "SPC_005_ACCEPT_DETAIL_INQ1"; 
            $data  = Dao::call_stored_procedure($sql, $param, true);
            
            $rcv_h = isset($data[0]) ? $data[0] : array();
            $rcv_d = isset($data[1]) ? $data[1] : array();

            if (!empty($rcv_h)) {
                $rcv_status       =   $data[0][0]['rcv_status_div'];
            }
            switch ($rcv_status) {
                case "10":
                    $status   =   'R';
                    break;
                case "20":
                    $status   =   'A';
                    break;
                case "90":
                    $status   =   'L';
                    break;
                default:
                    $status   =   'R';
                    break;
            }

            $header_html = view('layouts._operator_info',compact('rcv_h'))->render();

            $button      = Button::showButtonServer(array('btn-back', 
                                                          'btn-save', 
                                                          'btn-delete',
                                                          'btn-approve', 
                                                          'btn-cancel-approve',
                                                          'btn-cancel-order'
                                                            ), ($mode != 'I') ? $status : $mode);

            $html_rcv_d  =  view('accept::AcceptDetail.table_accept',compact('rcv_d', 'sales_detail_div', 'unit_q_div', 'unit_w_div', 'unit_m_div'))->render();

            if (isset($data) && !empty($data)) {
                return response()->json([
                                'response'   =>  true,
                                'html_rcv_d' =>  $html_rcv_d,
                                'rcv_h'      =>  $rcv_h[0],
                                'button'     =>  $button,
                                'mode'       =>  $mode,
                                'status'     =>  $status,
                                'rcv_status' =>  $rcv_status,
                            ]);
            } else {
                return response()->json([
                                'response'   =>  false,
                                'html_rcv_d' =>  '',
                                'rcv_h'      =>  '',
                                'button'     =>  $button,
                                'mode'       =>  $mode,
                                'status'     =>  $status,
                            ]);
            }
            
        } catch (\Exception $e) {
            return response()->json(['response' => false]);
        }
    }

    /**
     * refer cust m client
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/01/05 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function referSuppliers(Request $request) {
        try {
            $param  =   $request->all(); 
            $sql    =   "SPC_005_ACCEPT_DETAIL_INQ2"; 
            $data   =   Dao::call_stored_procedure($sql, $param, true);

            if (isset($data[0]) && !empty($data[0])) {
                return response()->json(array(
                    'response'      => true,
                    'data'          => $data[0][0]
                ));
            } else {
                return response()->json(array(
                    'response'      => false,
                ));
            }
        } catch (\Exception $e) {
            return response()->json(array(
                'response'=> $e->getMessage()
            ));
        }    
    }

    /**
     * Create/Update Accept Information
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/01/05 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postSave(Request $request) {
        try {
            //get data from client
            $data                = $request->all();
            $data['t_rcv_d']     =  json_encode($data['t_rcv_d']);//parse json to string
            $data['cre_user_cd'] = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']  = '005_accept-detail';
            $data['cre_ip']      = \GetUserInfo::getInfo('user_ip');
            
            $sql                 = "SPC_005_ACCEPT_DETAIL_ACT1";
            $result              = Dao::call_stored_procedure($sql, $data);
            
            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'          => true,
                    'error_cd'          => $result[1][0]['error_cd'],
                    'rcv_no'            => $result[1][0]['rcv_no'],
                    'rcv_status'        => $result[1][0]['rcv_status'],
                    'error_list'        => $result[2][0],
                    'detail_error_list' => $result[3]
                ));
            } else {
                return response()->json(array(
                    'response'          => false,
                    'error'             => $result[0][0]['Message'],
                    'error_cd'          => $result[1][0]['error_cd']
                ));
            }
        } catch (\Exception $e) {
            return response()->json(array(
                    'response'=> $e->getMessage()
            ));
        }        
    }

    /**
     * Delete accept detail
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/01/05 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function deleteAccept(Request $request) {
        try {
            //get data from client
            $data                   = $request->all();
            $data['cre_user_cd']    = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     = '005_accept-detail';
            $data['cre_ip']         = \GetUserInfo::getInfo('user_ip');

            //call stored
            $sql                    = "SPC_005_ACCEPT_DETAIL_ACT2";
            $result                 = Dao::call_stored_procedure($sql, $data);

            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'      => true,
                    'error_cd'      => $result[1][0]['error_cd'],
                    'rcv_no'        => $result[1][0]['rcv_no'],
                    'rcv_status'    => $result[1][0]['rcv_status']
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
     * approve accept detail
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/01/05 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function approveAccept(Request $request) {
        try {
            //get data from client
            $data                   = $request->all();
            $data['cre_user_cd']    = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     = '005_accept-detail';
            $data['cre_ip']         = \GetUserInfo::getInfo('user_ip');

            //call stored
            $sql                    = "SPC_005_ACCEPT_DETAIL_ACT3";
            $result                 = Dao::call_stored_procedure($sql, $data);

            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'      => true,
                    'error_cd'      => $result[1][0]['error_cd'],
                    'rcv_no'        => $result[1][0]['rcv_no'],
                    'rcv_status'    => $result[1][0]['rcv_status']
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
     * approve cancel accept detail
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/01/05 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function cancelApproveAccept(Request $request) {
        try {
            //get data from client
            $data                   = $request->all();
            $data['cre_user_cd']    = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     = '005_accept-detail';
            $data['cre_ip']         = \GetUserInfo::getInfo('user_ip');

            //call stored
            $sql                    = "SPC_005_ACCEPT_DETAIL_ACT4";
            $result                 = Dao::call_stored_procedure($sql, $data);

            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'      => true,
                    'error_cd'      => $result[1][0]['error_cd'],
                    'rcv_no'        => $result[1][0]['rcv_no'],
                    'rcv_status'    => $result[1][0]['rcv_status']
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
     * cancel order accept detail
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/01/12 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function cancelOrderAccept(Request $request) {
        try {
            //get data from client
            $data                   = $request->all();
            $data['cre_user_cd']    = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     = '005_accept-detail';
            $data['cre_ip']         = \GetUserInfo::getInfo('user_ip');

            //call stored
            $sql                    = "SPC_005_ACCEPT_DETAIL_ACT5";
            $result                 = Dao::call_stored_procedure($sql, $data);

            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'      => true,
                    'error_cd'      => $result[1][0]['error_cd'],
                    'rcv_no'        => $result[1][0]['rcv_no'],
                    'rcv_status'    => $result[1][0]['rcv_status']
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
     * refer product accept detail
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/01/12 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function referProduct(Request $request) {
        try {
            $param  =   $request->all(); 
            $sql    =   "SPC_005_ACCEPT_DETAIL_INQ3"; 
            $data   =   Dao::call_stored_procedure($sql, $param, true);
            
            if (isset($data[0]) && !empty($data[0])) {
                return response()->json(array(
                    'response'      => true,
                    'data'          => $data[0][0]
                ));
            } else {
                return response()->json(array(
                    'response'      => false,
                ));
            }
        } catch (\Exception $e) {
            return response()->json(array(
                    'response'=> $e->getMessage()
            ));
        }    
    }
}
