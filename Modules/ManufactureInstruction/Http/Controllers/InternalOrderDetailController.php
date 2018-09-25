<?php
/**
*|--------------------------------------------------------------------------
*| Order
*|--------------------------------------------------------------------------
*| Package       : ManufactureInstruction  
*| @author       : DungNN - ANS810 - dungnn@ans-asia.com
*| @created date : 2018/01/05
*| Description   : 
*/

namespace Modules\ManufactureInstruction\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\ComboboxController as Combobox;
use Session, DB, Dao, Button;

class InternalOrderDetailController extends Controller
{

	/**
	 *
	 * @author      :   DuyTP 2017/06/15
	 * @author      :
	 * @param       :   null
	 * @return      :   null
	 * @access      :   public
	 * @see         :
	 */
	public function getDetail()
	{
		try {
            $manufacture_kind_div       = Combobox::libraryCode('manufacture_kind_div');
			$mode  = 'U';
	        $from  = 'InternalOrderDetail';

	        if (Session::has('SELF')) {
	            Session::forget('SELF');
	            Session::forget('InternalOrderDetail');
	        } else {
	            if (Session::has('InternalOrderDetail')) {
	                $param = Session::get('InternalOrderDetail');
	                if (!empty($param)) {
	                    $mode  = $param['mode'];
	                    $from  = $param['from'];

	                    if (isset($param['internal_order_no']) && !empty($param['internal_order_no'])) {
                            $internal_order_no      = $param['internal_order_no'];
	                    }
	                }
	            }
	        }
			return view('manufactureinstruction::InternalOrderDetail.detail',
					compact('internal_order_no','mode', 'from', 'manufacture_kind_div'));
		} catch (\Exception $e) {
            return response()->json(array('response' => false));
        }
	}

    /**
    * Create/Update internalorder detail
    * -----------------------------------------------
    * @author      :   ANS810 - 2018/01/05 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postSave(Request $request) {
        try {
            //get data from client
            $param                     = $request->all();

            $param['t_in_order_d'] 	   = json_encode($param['t_in_order_d']);//parse json to string
            $param['cre_user_cd']      = \GetUserInfo::getInfo('user_cd');
            $param['cre_prg_cd']       = '025_internal-order-detail';
            $param['cre_ip']           = \GetUserInfo::getInfo('user_ip');

            $sql                       = "SPC_025_INTERNAL_ORDER_DETAIL_ACT1";
            $result                    = Dao::call_stored_procedure($sql,$param);
            $error_product             = isset($result[2]) ? $result[2] : '';            
            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'      => true,
                    'in_order_no'   => $result[1][0]['in_order_no'],
                    'error_cd'      => $result[1][0]['error_cd'],
                    'error_product' => $error_product
                ));
            }else{
                return response()->json(array(
                    'response'      => false,
                    'in_order_no'   => $result[1][0]['in_order_no'],
                    'error_cd'      => $result[1][0]['error_cd'],
                    'error_product' => $error_product
                ));
            }
        } catch (\Exception $e) {
            return response()->json(array(
                'response'=> $e->getMessage()
            ));
        }
    }

    /**
    * refer product
    * -----------------------------------------------
    * @author      :   ANS810 - 2018/01/05 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postReferProduct(Request $request){
        try{
            $param          = $request->all();
            $sql            = 'SPC_REFER_PRODUCT_CD';
            $result         = Dao::call_stored_procedure($sql, $param, true);
            //
            if (!empty($result)) {
                return response()->json(array(
                    'response'  =>  true,
                    'data'      =>  $result[0][0])
                );
            }else{
                return response()->json(array('response'=>false));
            }
        }
        catch (\Exception $e) {
            return response()->json(array(
                'response'=> false
            ));
        }
    }


    /**
    * refer Internal Order
    * -----------------------------------------------
    * @author      :   ANS810 - 2018/01/08 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postReferInternalOrder(Request $request){
        try{
            $manufacture_kind_div       = Combobox::libraryCode('manufacture_kind_div');
            $param          = array($request->TXT_internalorder_cd);

            $sql            = "SPC_025_INTERNAL_ORDER_DETAIL_INQ1";
            $result         = Dao::call_stored_procedure($sql,$param,true);
            $header         = isset($result[0][0]) ? $result[0][0] : array();
            $data_table     = isset($result[1]) ? $result[1] : array();
            $isManufactured = false;
            if(isset($data_table) && !empty($data_table)){
                foreach ($data_table as $value){
                    if(intval(preg_replace('/\,/','',$value['manufacture_qty'])) > 0){
                        $isManufactured = true;
                    }
                }
            }
            $mode           = isset($header) ? 'U' : 'I';
            $header_button  = Button::showButtonServer(array('btn-back', 'btn-save','btn-delete', 'btn-issue'),$request->mode);
            $header_html    = view('layouts._operator_info',compact('header'))->render();            
            $table_html     = view('manufactureinstruction::InternalOrderDetail.table',compact('data_table','mode', 'manufacture_kind_div', 'isManufactured'))->render();
            //return data
            if(isset($result[1])){
                return response()->json(array(
                    'response'              => true,
                    'header_html'           => $header_html,
                    'table_html'            => $table_html,
                    'header_button'         => $header_button
                ));
            }else{
                return response()->json(array(
                    'response'              => false
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
    * delete internal order
    * -----------------------------------------------
    * @author      :   ANS810 - 2018/01/11 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postDelete(Request $request){
        try{
            $param                = array($request->internalorder_cd);
            $param['cre_user_cd'] = \GetUserInfo::getInfo('user_cd');
            $param['cre_prg_cd']  = '025_internal-order-detail';
            $param['cre_ip']      = \GetUserInfo::getInfo('user_ip');
            $sql                  = "SPC_025_INTERNAL_ORDER_DETAIL_ACT2"; 

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
