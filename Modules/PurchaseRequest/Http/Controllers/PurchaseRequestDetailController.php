<?php
/**
*|--------------------------------------------------------------------------
*| Purchase Request
*|--------------------------------------------------------------------------
*| Package       : Purchase Request  
*| @author       : Trieunb - ANS806 - trieunb@ans-asia.com
*| @created date : 2017/01/08
*| Description   : 
*/
namespace Modules\PurchaseRequest\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session, Dao, Button;

class PurchaseRequestDetailController extends Controller
{
    /**
    * list buy
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
            $mode  = 'U';
            $from  = 'PurchaseRequestDetail';
            if (Session::has('SELF')) {
                Session::forget('SELF');
                Session::forget('PurchaseRequestDetail');
            } else {
                if (Session::has('PurchaseRequestDetail')) {
                    $param = Session::get('PurchaseRequestDetail');
                    if (!empty($param)) {
                        $mode  = $param['mode'];
                        $from  = $param['from'];
                        if (isset($param['buy_no']) && !empty($param['buy_no'])) {
                            $buy_no          = $param['buy_no'];
                        }
                    }
                }
            }
            return view('purchaserequest::PurchaseRequestDetail.detail', compact('buy_no', 'mode', 'from'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }
    /**
    * Create/Update Purchase Request
    * -----------------------------------------------
    * @author      :   ANS806 - 2018/02/03 - create
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
            $data['t_buy_d']        =  json_encode($data['t_buy_d']);//parse json to string
            $data['cre_user_cd']    = \GetUserInfo::getInfo('user_cd');

            $data['cre_prg_cd']     = '031_purchase_request-detail';
            $data['cre_ip']         = \GetUserInfo::getInfo('user_ip');
            //call stored
            $sql                    = "SPC_031_PURCHASE_REQUEST_DETAIL_ACT1";
            $result                 = Dao::call_stored_procedure($sql, $data);
            // return $result; die;
            $error_list             = isset($result[3]) ? $result[3] : array();
            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'      => true,
                    'error_cd'      => $result[1][0]['error_cd'],
                    'buy_no'        => $result[1][0]['buy_no'],
                    'errors_item'   => $result[2][0],
                    'error_list'    => $error_list
                ));
            } else {
                return response()->json(array(
                    'response'      => false,
                    'error'         => $result[0][0]['Message'],
                ));
            }
        } catch (\Exception $e) {
            return response()->json(array(
                        'response'      => false,
                        'error'         => $e->getMessage(),
                    ));
        }        
    }
     /**
    * refer purhchase request
    * -----------------------------------------------
    * @author      :   ANS806 - 2018/02/03 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function referPurchaseRequest(Request $request) 
    {
        try {
            $param      =   ['buy_no' => $request->buy_no];
            $mode       =   $request->mode;

            $sql        =   "SPC_031_PURCHASE_REQUEST_DETAIL_INQ1"; 
            $data       =   Dao::call_stored_procedure($sql, $param, true);
            // return $data; die;
            $buy_h       =   isset($data[0]) ? $data[0][0] : array();
            $buy_d       =   isset($data[1]) ? $data[1] : array();
            // return $buy_d; die;
            $buy_status  =   '';
            if (!empty($buy_h)) {
                $buy_status       =   $data[0][0]['buy_status_div'];
            }
            $status         =   getStatusCd($buy_status);
            $header         =   $buy_h;
            $header_html    =   view('layouts._operator_info',compact('header'))->render();
            //Approve mode
            if($status == 'A'){
                $button         =   Button::showButtonServer(array('btn-back', 'btn-issue'));
            }else{
                $button         =   Button::showButtonServer(array('btn-back', 'btn-save', 
                                                                'btn-delete', 'btn-approve', 'btn-issue'), $mode);
            }
            $html_buy_d  =  view('purchaserequest::PurchaseRequestDetail.purchase_request_table',compact('buy_d'))->render();

            if (isset($data) && !empty($data)) {
                return response()->json([
                                'response'      =>  true,
                                'html_buy_d'    =>  $html_buy_d,
                                'header_html'   =>  $header_html,
                                'buy_h'         =>  $buy_h,
                                'button'        =>  $button,
                                'mode'          =>  $mode,
                                'status'        =>  $status,
                            ]);
            } else {
                return response()->json([
                                'response'      =>  false,
                                'html_buy_d'    =>  '',
                                'header_html'   =>  '',
                                'buy_h'         =>  '',
                                'button'        =>  $button,
                                'mode'          =>  $mode,
                                'status'        =>  $status,
                            ]);
            }
        } catch (\Exception $e) {
            return response()->json(['response' => false]);
        }
    }
    /**
    * refer parts detail
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/07 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function referParts(Request $request)
    {
        try {
            $param  =   $request->all(); 
            $sql    =   "SPC_031_PURCHASE_REQUEST_DETAIL_INQ2"; 
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
    * Delete purchase request
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/05 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function deletePurchaseRequest(Request $request) {
        try {
            //get data from client
            $data                   = $request->all();
            $data['cre_user_cd']    = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     = '031_purchase_request';
            $data['cre_ip']         = \GetUserInfo::getInfo('user_ip');
            //call stored
            $sql                    = "SPC_031_PURCHASE_REQUEST_DETAIL_ACT2";
            $result                 = Dao::call_stored_procedure($sql, $data);
            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'      => true,
                    'error_cd'      => $result[1][0]['error_cd'],
                    'buy_no'        => $result[1][0]['buy_no'],
                ));
            } else {
                return response()->json(array(
                    'response'      => false,
                    'error'         => $result[0][0]['Message'],
                ));
            }
        } catch(\Exception $e) {
            return response()->json(array(
                        'response'      => false,
                        'error'         => $e->getMessage(),
                    ));
        }
    }
    /**
    * approve purchase request
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/05 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function approvePurchaseRequest(Request $request) {
        try {
            //get data from client
            $data                   = $request->all();
            $data['cre_user_cd']    = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     = '031_purchase_request';
            $data['cre_ip']         = \GetUserInfo::getInfo('user_ip');
            //call stored
            $sql                    = "SPC_031_PURCHASE_REQUEST_DETAIL_ACT3";
            $result                 = Dao::call_stored_procedure($sql, $data);
            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'      => true,
                    'error_cd'      => $result[1][0]['error_cd'],
                    'buy_no'         => $result[1][0]['buy_no'],
                    'errors_item'   => $result[2][0],
                ));
            } else {
                return response()->json(array(
                    'response'      => false,
                    'error'         => $result[0][0]['Message'],
                ));
            }
        } catch(\Exception $e) {
            return response()->json(array(
                        'response'      => false,
                        'error'         => $e->getMessage(),
                    ));
        }
    }
}
