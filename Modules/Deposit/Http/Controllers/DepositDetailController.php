<?php
/**
*|--------------------------------------------------------------------------
*| Deposit Detail
*|--------------------------------------------------------------------------
*| Package       : Deposit  
*| @author       : HaVV - ANS817 - havv@ans-asia.com
*| @created date : 2017/01/08
*| Description   : 
*/
namespace Modules\Deposit\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\ComboboxController as Combobox;
use Session, DB, Dao, Button;

class DepositDetailController extends Controller
{
    /**
    * get Deposit Detail
    * -----------------------------------------------
    * @author      :   ANS817 - 2017/01/08 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function getDepositDetail() {
        try {
            $deposit_div       = Combobox::libraryCode('deposit_div');
            $target_div        = Combobox::libraryCode('target_div');
            $bank_div          = Combobox::libraryCode('bank_div');
            $currency_div      = Combobox::libraryCode('currency_div');
            $deposit_way_div   = Combobox::libraryCode('deposit_way_div');
            $rate_confirm_div  = Combobox::libraryCode('rate_confirm_div');

            $mode       = 'U';
            $from       = 'DepositDetail';
            $deposit_no = '';
            if (Session::has('SELF')) {
                Session::forget('SELF');
                Session::forget('DepositDetail');
                $mode  = 'U';
            } else {
                if (Session::has('DepositDetail')) {
                    $param = Session::get('DepositDetail');

                    if (!empty($param)) {
                        $mode  = $param['mode'];
                        $from  = $param['from'];
                        if (isset($param['deposit_no']) && !empty($param['deposit_no'])) {
                            // $depositDetail       = '';
                            // $depositDetailList   = '';
                            $deposit_no = $param['deposit_no'];
                        }
                    }
                }
            }
            // return view('deposit::DepositDetail.detail', compact('depositDetail', 'depositDetailList', 'mode', 'from'));
            return view('deposit::DepositDetail.detail', compact('deposit_no', 'mode', 'from', 'deposit_div', 'target_div', 'bank_div', 'currency_div', 'deposit_way_div', 'rate_confirm_div'));
        } catch (\Exception $e) {
            return response()->json(array('response' => false));
        }
    }

    /**
    * check total order amount < total amount entered
    * -----------------------------------------------
    * @author      :   ANS817 - 2018/01/10 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postValidateTotalAmt(Request $request) {
        try {
            //get data from client
            $data                     = $request->all();
            //call stored
            $sql                      = "SPC_020_DEPOSIT_DETAIL_INQ1";
            $result                   = Dao::call_stored_procedure($sql,$data);
            $error_cd                 = !isset($result[0]) ? '' : $result[0][0]['error_cd'];
            //return result to client
            return response()->json(array(
                'error_cd'      => $error_cd
            ));
        } catch (\Exception $e) {
            return response()->json(array(
                'response'=> $e->getMessage()
            ));
        }        
    }

    /**
    * Create/Update deposit detail
    * -----------------------------------------------
    * @author      :   ANS817 - 2018/01/10 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postSave(Request $request) {
        try {
            //get data from client
            $data                     = $request->all();
            $data['cre_user_cd']      = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']       = '020_deposit-detail';
            $data['cre_ip']           = \GetUserInfo::getInfo('user_ip');
            //call stored
            $sql                      = "SPC_020_DEPOSIT_DETAIL_ACT1";
            $result                   = Dao::call_stored_procedure($sql,$data);
            //get item error
            $data_err                 = isset($result[2]) ? $result[2] : NULL;
            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'      => true,
                    'deposit_no'    => $result[1][0]['deposit_no'],
                    'error_cd'      => $result[1][0]['error_cd'],
                    'data_err'      => $data_err
                ));
            }else{
                return response()->json(array(
                    'response'      => false,
                    'error'         => $result[0][0]['Message'],
                    'error_cd'      => $result[1][0]['error_cd'],
                    'data_err'      => $data_err
                ));
            }
        } catch (\Exception $e) {
            return response()->json(array(
                'response'=> $e->getMessage()
            ));
        }        
    }


    /**
    * delete depost
    * -----------------------------------------------
    * @author      :   ANS817 - 2018/01/10 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postDelete(Request $request){
        try{
            $param                = array($request->deposit_no);
            $param['cre_user_cd'] = \GetUserInfo::getInfo('user_cd');
            $param['cre_prg_cd']  = '020_deposit-detail';
            $param['cre_ip']      = \GetUserInfo::getInfo('user_ip');
            //call stored
            $sql                  = "SPC_020_DEPOSIT_DETAIL_ACT2"; 
            $result               = Dao::call_stored_procedure($sql,$param);
            //return data
            if(empty($result[0])){
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
        }
        catch (\Exception $e) {
            return response()->json(array(
                    'response'=> false
            ));
        }        
    }

    /**
    * refer Deposit
    * -----------------------------------------------
    * @author      :   ANS817 - 2018/01/11 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postReferDeposit(Request $request){
        try{
            $param         = array($request->deposit_no);
            $sql           = "SPC_020_DEPOSIT_DETAIL_INQ2"; 
            $data          = Dao::call_stored_procedure($sql,$param,true);
            $header        = isset($data[0][0]) ? $data[0][0] : array();
            $header_html   = view('layouts._operator_info',compact('header'))->render();
            $header_button = Button::showButtonServer(array('btn-back', 'btn-save','btn-delete'),$request->mode);

            //return data
            if(isset($data[1])){
                //update deposit_no in Session
                $deposit_info = Session::get('DepositDetail');
                if ($deposit_info!=null && $request->mode == 'U') {
                    $deposit_info['deposit_no'] = $data[1][0]['deposit_no'];
                    // $deposit_info['mode']         = 'U';
                    Session::set('DepositDetail',$deposit_info);
                }

                //get data rcv_header, rcv_detail
                $rcv_h_data = isset($data[2][0]) ? $data[2][0] : array();
                $rcv_d_data = isset($data[3]) ? $data[3] : array();
                $rcv_d_html = view('deposit::DepositDetail.table',compact('rcv_d_data'))->render();

                return response()->json(array(
                    'response'      => true,
                    'info_header'   => $header_html,
                    'button_header' => $header_button,
                    'deposit_data'  => $data[1][0],
                    'rcv_h_data'    => $rcv_h_data,
                    'table_rcv_d'   => $rcv_d_html,
                ));
            }else{
                return response()->json(array(
                    'response'            => false
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
    * refer with rcv_no
    * -----------------------------------------------
    * @author      :   ANS817 - 2018/01/12 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postReferRcv(Request $request){
        try{
            $param         = $request->all();
            $sql           = "SPC_020_DEPOSIT_DETAIL_INQ3"; 
            $data          = Dao::call_stored_procedure($sql,$param,true);

            //return data
            if(isset($data[0])){
                //get data rcv_header, rcv_detail
                $rcv_h_data = isset($data[1][0]) ? $data[1][0] : array();
                $rcv_d_data = isset($data[2]) ? $data[2] : array();
                $rcv_d_html = view('deposit::DepositDetail.table',compact('rcv_d_data'))->render();

                return response()->json(array(
                    'response'      => true,
                    'deposit_data'  => $data[0][0],
                    'rcv_h_data'    => $rcv_h_data,
                    'table_rcv_d'   => $rcv_d_html,
                ));
            }else{
                return response()->json(array(
                    'response'            => false
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
    * refer with invoice_no
    * -----------------------------------------------
    * @author      :   ANS817 - 2018/01/17 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postReferInvoice(Request $request){
        try{
            $param         = $request->all();
            $sql           = "SPC_020_DEPOSIT_DETAIL_INQ4"; 
            $data          = Dao::call_stored_procedure($sql,$param,true);

            //return data
            if(isset($data[0])){
                //get data rcv_header, rcv_detail
                $rcv_h_data = isset($data[1][0]) ? $data[1][0] : array();
                $rcv_d_data = isset($data[2]) ? $data[2] : array();
                $rcv_d_html = view('deposit::DepositDetail.table',compact('rcv_d_data'))->render();

                return response()->json(array(
                    'response'      => true,
                    'deposit_data'  => $data[0][0],
                    'rcv_h_data'    => $rcv_h_data,
                    'table_rcv_d'   => $rcv_d_html,
                ));
            }else{
                return response()->json(array(
                    'response'            => false
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
    * refer with client_cd
    * -----------------------------------------------
    * @author      :   ANS817 - 2018/01/17 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postReferClient(Request $request){
        try{
            $param         = $request->all();
            $sql           = "SPC_020_DEPOSIT_DETAIL_INQ5"; 
            $data          = Dao::call_stored_procedure($sql,$param,true);

            //return data
            if(isset($data[0])){
                //get data rcv_header, rcv_detail
                $rcv_h_data = isset($data[1][0]) ? $data[1][0] : array();
                $rcv_d_data = isset($data[2]) ? $data[2] : array();
                $rcv_d_html = view('deposit::DepositDetail.table',compact('rcv_d_data'))->render();

                return response()->json(array(
                    'response'      => true,
                    'deposit_data'  => $data[0][0],
                    'rcv_h_data'    => $rcv_h_data,
                    'table_rcv_d'   => $rcv_d_html,
                ));
            }else{
                return response()->json(array(
                    'response'            => false
                ));
            }
        }
        catch (\Exception $e) {
            return response()->json(array(
                'response'=> false
            ));
        }        
    }
}
