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

class ComponentMasterDetailController extends Controller
{
    /**
    * get component detail
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/08/08 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function getDetail() {
        try {
            $data                = [];
            $data['cre_user_cd'] = \GetUserInfo::getInfo('user_cd');
            $unit_q_div          = Combobox::libraryCode('unit_q_div');
            $parts_kind_div      = Combobox::libraryCode('parts_kind_div');
            $exists_div          = Combobox::libraryCode('exists_div');
            $parts_order_div     = Combobox::libraryCode('parts_order_div');
            $order_level_div     = Combobox::libraryCode('order_level_div');
            
            $sql                 = "SPC_067_COMPONENT_MASTER_DETAIL_INQ3";
            $lastestComponent    = Dao::call_stored_procedure($sql,$data);
            $lastestComponent    = isset($lastestComponent[0][0]) ? $lastestComponent[0][0] : null;
            
            $mode                = 'U';
            $from                = 'ComponentMasterDetail';
            $is_new              = 'false';
            $component_id        = '';
            if (Session::has('SELF')) {
                Session::forget('SELF');
                Session::forget('ComponentMasterDetail');
                $mode  = 'U';
            } else {
                if (Session::has('ComponentMasterDetail')) {
                    $param = Session::get('ComponentMasterDetail');
                    if (!empty($param)) {
                        $mode   = $param['mode'];
                        $from   = $param['from'];
                        $is_new = (isset($param['is_new']) && !empty($param['is_new']))  ? $param['is_new'] : 'false';
                        if (isset($param['component_id']) && !empty($param['component_id'])) {
                            $component_id   = $param['component_id'];
                        }
                    }
                }
            }
            return view('master::ComponentMasterDetail.detail', compact('component_id', 'mode', 'from', 'is_new', 'unit_q_div', 'parts_kind_div', 'exists_div', 'parts_order_div', 'order_level_div', 'lastestComponent'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }

    /**
    * Create/Update component detail
    * -----------------------------------------------
    * @author      :   ANS817 - 2017/12/13 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postSave(Request $request) {
        try {
            //get data from client
            $data                     = $request->all();
            $data['m_purchase_price'] = json_encode($data['m_purchase_price']);//parse json to string
            $data['cre_user_cd']      = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']       = '067_component-master-detail';
            $data['cre_ip']           = \GetUserInfo::getInfo('user_ip');
            //call stored
            $sql                      = "SPC_067_COMPONENT_MASTER_DETAIL_ACT1";
            $result                   = Dao::call_stored_procedure($sql,$data);
            //get data error
            $data_err                 = isset($result[2]) ? $result[2] : NULL;
            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'      => true,
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
    * refer Part
    * -----------------------------------------------
    * @author      :   ANS817 - 2017/12/13 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postReferPart(Request $request){
        try{
            $param                = array($request->parts_cd);
            $param['cre_user_cd'] = \GetUserInfo::getInfo('user_cd');

            $sql                  = "SPC_067_COMPONENT_MASTER_DETAIL_INQ1"; 
            $data                 = Dao::call_stored_procedure($sql,$param,true);

            $header               = isset($data[0][0]) ? $data[0][0] : array();  
            $lastestComponent     = !empty($data[3][0]) ? $data[3][0] : array();  

            $header_html          = view('layouts._operator_info',compact('header'))->render();       
            
            //render list button Header
            $header_button  = Button::showButtonServer(array('btn-back', 'btn-save','btn-delete'), 'I');
            if(isset($data[0])){
                $header_button  = Button::showButtonServer(array('btn-back', 'btn-save','btn-delete'), 'U');
            }

            //update parts_cd in Session
            $component_info = Session::get('ComponentMasterDetail');

            //return data
            if(isset($data[1])){
                if ($component_info!=null && $request->mode == 'U') {
                    $component_info['component_id'] = '';
                    $component_info['mode']         = 'U';
                    Session::set('ComponentMasterDetail', $component_info);
                }

                $purchase_price_data = isset($data[2]) ? $data[2] : array();
                $purchase_price_html = view('master::ComponentMasterDetail.table',compact('purchase_price_data'))->render();

                return response()->json(array(
                    'response'             => true,
                    'info_header'          => $header_html,
                    'button_header'        => $header_button,
                    'component_data'       => $data[1][0],
                    'table_purchase_price' => $purchase_price_html,
                    'lastestComponent'     => $lastestComponent,
                ));
            } else {
                if (Session::has('ComponentMasterDetail')) {
                    $component_info['component_id'] = '';
                    $product_info['mode']      = 'I';
                    Session::set('ComponentMasterDetail', $component_info);
                }
                return response()->json(array(
                    'response'             => false,
                    'button_header'        => $header_button
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
    * refer purchase price
    * -----------------------------------------------
    * @author      :   ANS817 - 2017/12/13 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postReferPurchasePrice(Request $request){
        try{
            $param         = $request->all();
            $sql           = "SPC_067_COMPONENT_MASTER_DETAIL_INQ2"; 
            $data          = Dao::call_stored_procedure($sql,$param,true);

            //return data
            if(isset($data[0][0]['client_nm'])){
                return response()->json(array(
                    'response'  => true,
                    'data'      => $data[0][0]
                ));
            }else{
                return response()->json(array(
                    'response' => false
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
    * delete component
    * -----------------------------------------------
    * @author      :   ANS817 - 2017/12/13 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postDelete(Request $request){
        try{
            $param                = array($request->parts_cd);
            $param['cre_user_cd'] = \GetUserInfo::getInfo('user_cd');
            $param['cre_prg_cd']  = '067_component-master-detail';
            $param['cre_ip']      = \GetUserInfo::getInfo('user_ip');
            $sql                  = "SPC_067_COMPONENT_MASTER_DETAIL_ACT2"; 
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
