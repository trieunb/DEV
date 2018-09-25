<?php
/**
 *|--------------------------------------------------------------------------
 *| Order
 *|--------------------------------------------------------------------------
 *| Package       : Master  
 *| @author       : Trieunb - ANS806 - trieunb@ans-asia.com
 *| @created date : 2017/01/08
 *| Description   : 
 */
namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\ComboboxController as Combobox;
use Session, DB, Dao, Button;

class SuppliersMasterMaintenanceController extends Controller
{
    /**
     * detail suppliers
     * -----------------------------------------------
     * @author      :   ANS806 - 2017/01/08 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function getDetail() {
        try {//get library
            $payment_conditions_div        = Combobox::libraryCode('payment_conditions_div');
            $payment_nums_div              = Combobox::libraryCode('payment_nums_div');
            $postpay_date_div              = Combobox::libraryCode('postpay_date_div');
            $exists_div                    = Combobox::libraryCode('exists_div');
            $allocation_div                = Combobox::libraryCode('allocation_div');
            $paydate_condition_div         = Combobox::libraryCode('paydate_condition_div');
            $payday_condition_div          = Combobox::libraryCode('payday_condition_div');
            $bank_div                      = Combobox::libraryCode('bank_div');
            $currency_div                  = Combobox::libraryCode('currency_div');
            $round_div                     = Combobox::libraryCode('round_div');


            $mode                       = 'U';
            $from                       = 'SuppliersMasterMaintenance';
            $suppliersMasterMaintenance = '';
            $is_new                     = 'false';
            if (Session::has('SELF')) {
                Session::forget('SELF');
                Session::forget('SuppliersMasterMaintenance');
                $mode  = 'U';
            } else {
                if (Session::has('SuppliersMasterMaintenance')) {
                    $param = Session::get('SuppliersMasterMaintenance');
                    if (!empty($param)) {
                        $mode   = $param['mode'];
                        $from   = $param['from'];
                        $is_new = (isset($param['is_new']) && !empty($param['is_new']))  ? $param['is_new'] : 'false';
                        if (isset($param['client_cd']) && !empty($param['client_cd'])) {
                            $suppliersMasterMaintenance = $param['client_cd'];
                        }
                    }
                }
            }
            // dd($suppliersMasterMaintenance);
            return view('master::SuppliersMasterMaintenance.detail', compact('suppliersMasterMaintenance', 'mode', 'from', 'is_new', 'payment_conditions_div','payment_nums_div','postpay_date_div', 'exists_div', 'allocation_div',  'paydate_condition_div', 'payday_condition_div', 'bank_div', 'currency_div', 'round_div'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }

    /**
     * Create/Update Suppliers Information
     * -----------------------------------------------
     * @author      :   ANS804 - 2017/12/14 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postSave(Request $request) {
        try {
            //get data from client
            $data                     = $request->all();
            $data['m_client_payment'] =  json_encode($data['m_client_payment']);
            $data['cre_user_cd']      = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']       = '053_suppliers-master-maintenance';
            $data['cre_ip']           = \GetUserInfo::getInfo('user_ip');
            
            //call stored
            $sql                      = "SPC_053_SUPPLIERS_MASTER_MAINTENANCE_ACT1";
            $result                   = Dao::call_stored_procedure($sql,$data);
            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'   => true,
                    'error_cd'   => $result[1][0]['error_cd'],
                    'client_cd'  => $result[1][0]['client_cd'],
                    'error_list' => $result[2][0],
                ));
            } else {
                return response()->json(array(
                    'response' => false,
                    'error'    => $result[0][0]['Message'],
                    'error_cd' => $result[1][0]['error_cd']
                ));
            }
        } catch (\Exception $e) {
            return response()->json(array(
                    'response'=> $e->getMessage()
            ));
        }        
    }

    /**
     * refer user information
     * -----------------------------------------------
     * @author      :   ANS804 - 2017/12/14 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postRefer(Request $request){
        try{
            $param         = array($request->client_cd);
            
            $sql           = "SPC_053_SUPPLIERS_MASTER_MAINTENANCE_INQ1"; 
            $data          = Dao::call_stored_procedure($sql,$param,true);
            
            $header        = isset($data[0][0]) ? $data[0][0] : array();           
            $header_html   = view('layouts._operator_info',compact('header'))->render();
            
            $header_button = Button::showButtonServer(array('btn-back', 'btn-save','btn-delete','btn-copy'),'U');
            
            //update client_cd in Session
            $client_info   = Session::get('SuppliersMasterMaintenance');

            //return data
            if(isset($data[0])) {
                if ($client_info != null  && Session::has('SuppliersMasterMaintenance')) {
                    $client_info['client_cd'] = '';
                    $client_info['mode']      = 'U';
                    Session::set('SuppliersMasterMaintenance',$client_info);     
                }

                if (isset($data[1])) {
                    $client_payment = $data[1];
                } else {
                    $client_payment = [];
                }

                return response()->json(array(
                    'response'       => true,
                    'client'         => $data[0][0],
                    'client_payment' => $client_payment,
                    'header'         => $header_html,
                    'button'         => $header_button
                ));
            } else {
                return response()->json(array(
                    'response'      => false,
                    'button'        => $header_button
                ));
            }
        } catch (\Exception $e) {
            return response()->json(array(
                    'response'=> false
            ));
        }        
    }

    /**
     * delete supplier information
     * -----------------------------------------------
     * @author      :   ANS804 - 2017/12/19 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postDelete(Request $request){
        try{
            $param                = array($request->client_cd);
            
            $param['cre_user_cd'] = \GetUserInfo::getInfo('user_cd');
            $param['cre_prg_cd']  = '053_suppliers-master-maintenance';
            $param['cre_ip']      = \GetUserInfo::getInfo('user_ip');

            $sql                  = "SPC_053_SUPPLIERS_MASTER_MAINTENANCE_ACT2"; 
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
}
