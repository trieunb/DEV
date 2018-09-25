<?php
/**
*|--------------------------------------------------------------------------
*| selling-unit-price-by-client-detail
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
use Session, DB, Dao, Button;
class SellingUnitPriceByClientDetailController extends Controller
{
    /**
    * get detail selling unit price by client
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
            $mode  = 'U';
            $from  = 'SellingUnitPriceByClientDetail';
            $product_cd = '';
            $client_cd  = '';
            $apply_st_date  = '';
            $is_new     =   'false';
            if (Session::has('SELF')) {
                Session::forget('SELF');
                Session::forget('SellingUnitPriceByClientDetail');
                $mode  = 'U';
            } else {
                if (Session::has('SellingUnitPriceByClientDetail')) {
                    $param = Session::get('SellingUnitPriceByClientDetail');
                    if (!empty($param)) {
                        $mode       = $param['mode'];
                        $from       = $param['from'];
                        $is_new     = (isset($param['is_new']) && !empty($param['is_new']))  ? $param['is_new'] : 'false';
                        if (isset($param['product_cd']) && !empty($param['product_cd'])) {
                            $product_cd     = $param['product_cd'];
                            $client_cd      = $param['client_cd'];
                            $apply_st_date  = $param['apply_st_date'];
                        }
                    }
                }
            }
            return view('master::SellingUnitPriceByClientDetail.detail', 
                                compact('product_cd', 'client_cd', 'apply_st_date', 'mode', 'from', 'is_new'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }
    /**
    * refer Sales price information
    * -----------------------------------------------
    * @author      :   ANS796 - 2017/12/12 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postRefer(Request $request){
        try{

            $item_cd      = Session::get('SellingUnitPriceByClientDetail');
            if ($item_cd != null && Session::has('SellingUnitPriceByClientDetail')) {
                $item_cd['product_cd']      = '';
                $item_cd['client_cd']       = '';
                $item_cd['apply_st_date']   = '';
                Session::set('SellingUnitPriceByClientDetail',$item_cd);
            }

            $checkFlg       = true;
            $param          = array($request->product_cd, $request->standard_unit_price, $request->client_cd, $request->start_date);
            $sql            = "SPC_064_SELLING_UNIT_PRICE_BY_CLIENT_DETAIL_INQ1"; 
            $data           = Dao::call_stored_procedure($sql, $param, true);
            $header         = isset($data[0][0]) ? $data[0][0] : array();
            $header_html    = view('layouts._operator_info',compact('header'))->render();
            if(isset($data[0])){
                $header_button  = Button::showButtonServer(array('btn-back', 'btn-save','btn-delete'), 'U');
                $checkFlg       = true;
                //update product_cd in Session
                // $price_info = Session::get('SellingUnitPriceByClientDetail');
                // if ($price_info!=null) {
                //     $price_info['product_cd']=$data[0][0]['product_cd'];
                //     $price_info['client_cd']=$data[0][0]['client_cd'];
                //     $price_info['apply_st_date']=$data[0][0]['apply_st_date'];
                //     Session::set('SellingUnitPriceByClientDetail',$price_info);     
                // }
            }else{
                $header_button  = Button::showButtonServer(array('btn-back', 'btn-save','btn-delete'),'I');
                $checkFlg       = false;
            }
            //return data
            if(isset($data[0]) || isset($data[1])){
                return response()->json(array(
                    'response'      => $checkFlg,
                    'price'         => isset($data[0][0]) ? $data[0][0] : null,
                    'standardPrice' => isset($data[1][0]) ? $data[1][0] : null,
                    'header'        => $header_html,
                    'button'        => $header_button
                ));
            }else{
                return response()->json(array(
                    'response'      => false,
                    'price'         => NULL,
                    'standardPrice' => NULL,
                    'button'        => $header_button
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
    * Create/Update Sales Price Information
    * -----------------------------------------------
    * @author      :   ANS796 - 2017/12/12 - create
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
            $data['cre_user_cd']    = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     = '064_selling-unit-price-by-client-detail';
            $data['cre_ip']         = \GetUserInfo::getInfo('user_ip');
            //call stored
            $sql                    = "SPC_064_SELLING_UNIT_PRICE_BY_CLIENT_DETAIL_ACT1";
            $result                 = Dao::call_stored_procedure($sql, $data);
            //get data error
            $data_err               = isset($result[2]) ? $result[2] : NULL;
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
    * delete sales price information
    * -----------------------------------------------
    * @author      :   ANS796 - 2017/12/12 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postDelete(Request $request){
        try{
            $param                  = $request->all();
            $param['cre_user_cd']   = \GetUserInfo::getInfo('user_cd');
            $param['cre_prg_cd']    = '064_selling-unit-price-by-client-detail';
            $param['cre_ip']        = \GetUserInfo::getInfo('user_ip');
            $sql                    = "SPC_064_SELLING_UNIT_PRICE_BY_CLIENT_DETAIL_ACT2"; 
            $result                 = Dao::call_stored_procedure($sql,$param);
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
