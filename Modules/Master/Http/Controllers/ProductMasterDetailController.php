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
class ProductMasterDetailController extends Controller
{
    /**
    * list pi
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
            $outsourcing_div         = Combobox::libraryCode('outsourcing_div');
            $exists_div              = Combobox::libraryCode('exists_div');
            $unit_w_div              = Combobox::libraryCode('unit_w_div');
            $unit_m_div              = Combobox::libraryCode('unit_m_div');
            $unit_q_div              = Combobox::libraryCode('unit_q_div');
            $param_store         = ['lib_cd' => 'exists_div'];
            $sql                 = "SPC_COM_GET_COMBOBOX";
            $data                = Dao::call_stored_procedure($sql, $param_store);
            $dataSerialMnagement = $data[0];
            if (isset($dataSerialMnagement[0]['Data']) && $dataSerialMnagement[0]['Data'] == 'EXCEPTION') {
                $dataSerialMnagement  = null;
            }

            $mode       = 'U';
            $from       = 'ProductMasterDetail';
            $product_cd = '';
            $is_new     = 'false';
            if (Session::has('SELF')) {
                Session::forget('SELF');
                Session::forget('ProductMasterDetail');
                $mode       = 'U';
            } else {
                if (Session::has('ProductMasterDetail')) {
                    $param = Session::get('ProductMasterDetail');
                    if (!empty($param)) {
                        $mode   = $param['mode'];
                        $from   = $param['from'];
                        $is_new = (isset($param['is_new']) && !empty($param['is_new']))  ? $param['is_new'] : 'false';
                        if (isset($param['product_cd']) && !empty($param['product_cd'])) {
                            $product_cd   = $param['product_cd'];
                        }
                    }
                }
            }

            return view('master::ProductMasterDetail.detail', compact('product_cd', 'mode', 'from', 'dataSerialMnagement', 'is_new', 'outsourcing_div', 'exists_div', 'unit_w_div','unit_m_div', 'unit_q_div'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }

    /**
    * Create/Update product detail
    * -----------------------------------------------
    * @author      :   ANS817 - 2017/12/18 - create
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
            $data['cre_prg_cd']       = '062_product-master-detail';
            $data['cre_ip']           = \GetUserInfo::getInfo('user_ip');
            //call stored
            $sql                      = "SPC_062_PRODUCT_MASTER_DETAIL_ACT1";
            $result                   = Dao::call_stored_procedure($sql,$data);
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

    /**
    * refer product
    * -----------------------------------------------
    * @author      :   ANS817 - 2017/12/18 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postRefer(Request $request){
        try{
            $param         = array($request->product_cd);
            $sql           = "SPC_062_PRODUCT_MASTER_DETAIL_INQ1"; 
            $data          = Dao::call_stored_procedure($sql,$param,true);
            $header        = isset($data[0][0]) ? $data[0][0] : array();
            $header_html   = view('layouts._operator_info',compact('header'))->render();
            //render list button Header
            $header_button  = Button::showButtonServer(array('btn-back', 'btn-save','btn-delete'), 'I');
            if(isset($data[0])){
                $header_button  = Button::showButtonServer(array('btn-back', 'btn-save','btn-delete'), 'U');
            }
            //update product_cd in Session
            $product_info = Session::get('ProductMasterDetail');
            //return data
            if(isset($data[1])){
                if ($product_info!=null && $request->mode == 'U') {
                    //$product_info['product_cd'] = $data[1][0]['product_cd'];
                    $product_info['product_cd'] = '';
                    $product_info['mode']       = 'U';
                    Session::set('ProductMasterDetail',$product_info);
                }
                return response()->json(array(
                    'response'      => true,
                    'info_header'   => $header_html,
                    'button_header' => $header_button,
                    'data'          => isset($data[1][0]) ? $data[1][0] : array(),
                ));
            }else{
                if (Session::has('ProductMasterDetail')) {
                    $product_info['product_cd'] = '';
                    $product_info['mode']      = 'I';
                    Session::set('ProductMasterDetail',$product_info);
                }
                return response()->json(array(
                    'response'      => false,
                    'button_header' => $header_button
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
    * delete product
    * -----------------------------------------------
    * @author      :   ANS817 - 2017/12/20 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postDelete(Request $request){
        try{
            $param                = array($request->product_cd);
            $param['cre_user_cd'] = \GetUserInfo::getInfo('user_cd');
            $param['cre_prg_cd']  = '062_product-master-detail';
            $param['cre_ip']      = \GetUserInfo::getInfo('user_ip');
            $sql                  = "SPC_062_PRODUCT_MASTER_DETAIL_ACT2"; 
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
