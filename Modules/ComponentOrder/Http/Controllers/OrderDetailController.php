<?php
/**
 *|--------------------------------------------------------------------------
 *| Component Order 
 *|--------------------------------------------------------------------------
 *| Package       : Component Order  
 *| @author       : DaoNX - ANS804 - daonx@ans-asia.com
 *| @created date : 2018/06/11
 *| Description   : 
 */
namespace Modules\ComponentOrder\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session, Dao, Button;

class OrderDetailController extends Controller {
    /**
     * list 
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/03/26 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function getOrder() {
        try {
            $mode           = 'U';
            $from           = 'ComponentOrderDetail';
            $parts_order_no = '';
            $tax_rate       = 0;

            if (Session::has('SELF')) {
                Session::forget('SELF');
                Session::forget('ComponentOrderDetail');

                //get tax_rate
                $paramStore = ['date' => date('Y-m-d')];
                $sql        = "SPC_034_COMPONENT_ORDER_DETAIL_INQ5";
                $dataTax    = Dao::call_stored_procedure($sql,$paramStore);

                $tax_rate   = !empty($dataTax[0][0]['tax_rate']) ? $dataTax[0][0]['tax_rate'] : 0;
            } else {
                if (Session::has('ComponentOrderDetail')) {                    
                    $param = Session::get('ComponentOrderDetail');
                    if (!empty($param)) {
                        $mode  = $param['mode'];
                        $from  = $param['from'];

                        if (isset($param['parts_order_no']) && !empty($param['parts_order_no'])) {
                            $parts_order_no =  $param['parts_order_no'];
                        } else {                            
                            //get tax_rate
                            $paramStore = ['date' => date('Y-m-d')];
                            $sql        = "SPC_034_COMPONENT_ORDER_DETAIL_INQ5";
                            $dataTax    = Dao::call_stored_procedure($sql,$paramStore);

                            $tax_rate   = !empty($dataTax[0][0]['tax_rate']) ? $dataTax[0][0]['tax_rate'] : 0;
                        }
                    }
                }
            }

            //get ctl_val1
            $sql     = "SPC_034_COMPONENT_ORDER_DETAIL_INQ4"; 
            $dataCtl = Dao::call_stored_procedure($sql);

            if(!empty($dataCtl)) {
               $purchase_detail_amt_round_div  = isset($dataCtl[0][0]['purchase_detail_amt_round_div']) ? $dataCtl[0][0]['purchase_detail_amt_round_div'] : 1;
               $purchase_detail_tax_round_div  = isset($dataCtl[1][0]['purchase_detail_tax_round_div']) ? $dataCtl[1][0]['purchase_detail_tax_round_div'] : 1;
               $purchase_summary_tax_round_div = isset($dataCtl[2][0]['purchase_summary_tax_round_div']) ? $dataCtl[2][0]['purchase_summary_tax_round_div'] : 1;
               $report_number_parts_order      = isset($dataCtl[3][0]['report_number_parts_order']) ? $dataCtl[3][0]['report_number_parts_order'] : '';
            }

            return view('componentorder::OrderDetail.detail', compact(
                                                                'parts_order_no',
                                                                'purchase_detail_amt_round_div',
                                                                'purchase_detail_tax_round_div',
                                                                'purchase_summary_tax_round_div',
                                                                'report_number_parts_order',
                                                                'tax_rate',
                                                                'mode',
                                                                'from'));

        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }

    /**
     * Create/Update Order Detail
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/03/26 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postSaveOrderDetail(Request $request) {
        try {
            //get data from client
            $data                       = $request->all();
            
            $data['parts_order_detail'] =  json_encode($data['parts_order_detail']);//parse json to string
            
            $data['cre_user_cd']        = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']         = '034_component-order-detail';
            $data['cre_ip']             = \GetUserInfo::getInfo('user_ip');
            
            $sql                        = "SPC_034_COMPONENT_ORDER_DETAIL_ACT1";
            $result                     = Dao::call_stored_procedure($sql, $data);

            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'         => true,
                    'error_cd'         => $result[1][0]['error_cd'],
                    'parts_order_no'   => $result[1][0]['parts_order_no'],
                    'header_error'     => $result[2][0],
                    'list_parts_error' => $result[3],
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
     * Delete Order Detail
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/03/26 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postDeleteOrderDetail(Request $request) {
        try {
            //get data
            $data                = $request->all();
            $data['cre_user_cd'] = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']  = '034_component-order-detail';
            $data['cre_ip']      = \GetUserInfo::getInfo('user_ip');
            
            //call stored
            $sql                 = "SPC_034_COMPONENT_ORDER_DETAIL_ACT2";
            $result              = Dao::call_stored_procedure($sql, $data);

            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'       => true,
                    'error_cd'       => $result[1][0]['error_cd'],
                    'parts_order_no' => $result[1][0]['parts_order_no'],
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
     * refer Parts Order
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/03/26 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postReferPartsOrder(Request $request) {
        try {
            $parts_order_status_div = 0;
            $param                  = $request->all();
            $paramStore             = ['parts_order_no' => $param['parts_order_no']];
            $mode                   = $param['mode'];
            $sql                    = "SPC_034_COMPONENT_ORDER_DETAIL_INQ1"; 
            $data                   = Dao::call_stored_procedure($sql,$paramStore);
            
            $error                  = isset($data[0][0]) ? $data[0][0] : array();
            $parts_order_info_h     = isset($data[1][0]) ? $data[1][0] : array();
            $parts_order_info_d     = isset($data[2]) ? $data[2] : array();
            
            $parts_order_table      = view('componentorder::OrderDetail.table',compact('parts_order_info_d'))->render();
            
            $header                 = isset($data[3][0]) ? $data[3][0] : array();
            $header_html            = view('layouts._operator_info',compact('header'))->render();

            if (!empty($parts_order_info_h)) {
                $parts_order_status_div = $parts_order_info_h['parts_order_status_div'];
            }

            switch ($parts_order_status_div) {
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

            $button = Button::showButtonServer(array('btn-back', 
                                                     'btn-save', 
                                                     'btn-delete',
                                                     'btn-approve',
                                                     'btn-cancel-approve',
                                                     'btn-issue'), ($mode == 'I') ? $mode : $status);

            //return data
            if(empty($data[0][0]['error'])) {
                return response()->json(array(
                    'response'           => true,
                    'parts_order_info_h' => $parts_order_info_h,
                    'parts_order_table'  => $parts_order_table,
                    'header_html'        => $header_html,
                    'button'             => $button,
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
     * refer tax
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/03/26 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postReferTax(Request $request) {
        try {
            $param            = $request->all();
            $parts_order_date = $param['parts_order_date'];
            $sql              = "SPC_034_COMPONENT_ORDER_DETAIL_INQ5";
            $dataTax          = Dao::call_stored_procedure($sql,$param);
            
            $tax_rate         = !empty($dataTax[0][0]['tax_rate']) ? $dataTax[0][0]['tax_rate'] : '';

            //return data
            if(!empty($dataTax[0][0]['tax_rate'])) {
                return response()->json(array(
                    'response' => true,
                    'tax_rate' => $tax_rate,
                ));
            } else {
                return response()->json(array(
                    'response' => false,
                ));
            }
        } catch (\Exception $e) {
            return response()->json(array(
                'response'=> false
            ));
        }        
    }

    /**
     * refer Component
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/03/26 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postReferComponent(Request $request) {
        try {
            $param = $request->all();
            
            $sql   = "SPC_034_COMPONENT_ORDER_DETAIL_INQ2"; 
            $data  = Dao::call_stored_procedure($sql,$param);
            
            if ($data[0][0]['error'] == '') {
                return response()->json(array(
                    'response' => true,
                    'data'     => $data[1][0]
                ));
            } else {
                return response()->json(array(
                    'response'   => false,
                    'error'      => $data[0][0]['error'],
                ));
            }
        } catch (\Exception $e) {
            return response()->json(array(
                'response'=> false
            ));
        }     
    }

    /**
     * refer Supplier
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/03/26 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postReferSupplier(Request $request) {
        try {
            $param = $request->all();
            
            $sql   = "SPC_034_COMPONENT_ORDER_DETAIL_INQ3"; 
            $data  = Dao::call_stored_procedure($sql,$param);
            
            if (!empty($data)) {
                return response()->json(array(
                    'response'  =>  true,
                    'data'      =>  $data[0][0])
                );
            } else {
                return response()->json(array(
                    'response'  => false
                ));
            }
        } catch (\Exception $e) {
            return response()->json(array(
                'response'=> false
            ));
        }     
    }

    /**
     * unapprove parts order detail
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/06/21 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postCancelApproved(Request $request) {
        try {
            //get data from client
            $data                  = $request->all();

            $data['cre_user_cd']   = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']    = '034_component-order-detail';
            $data['cre_ip']        = \GetUserInfo::getInfo('user_ip');
            
            //call stored
            $sql                   = "SPC_034_COMPONENT_ORDER_DETAIL_ACT4";
            $result                = Dao::call_stored_procedure($sql, $data);
            
            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'       => true,
                    'error_cd'       => $result[1][0]['error_cd'],
                    'parts_order_no' => $result[1][0]['parts_order_no'],
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
     * approve parts order detail
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/06/21 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postApproved(Request $request) {
        try {
            //get data from client
            $data                  = $request->all();

            $data['cre_user_cd']   = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']    = '034_component-order-detail';
            $data['cre_ip']        = \GetUserInfo::getInfo('user_ip');
            
            //call stored
            $sql                   = "SPC_034_COMPONENT_ORDER_DETAIL_ACT3";
            $result                = Dao::call_stored_procedure($sql, $data);
            
            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'       => true,
                    'error_cd'       => $result[1][0]['error_cd'],
                    'parts_order_no' => $result[1][0]['parts_order_no'],
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
}
