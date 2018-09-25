<?php
/**
*|--------------------------------------------------------------------------
*| Order
*|--------------------------------------------------------------------------
*| Package       : Order Confirm
*| @author       : TuanNK - ANS818 - tuannk@ans-asia.com
*| @created date : 2017/01/08
*| Description   : 
*/
namespace Modules\Order\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Paginator, Dao;

class OrderConfirmController extends Controller
{
    /**
    * list confirm order
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/01/08 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function getSearch()
    {
        try {
            $paginator  = new Paginator(0, 0, 0, 0);
            $paginate   = $paginator->show(1, 'paginate');
            $fillter    = $paginator->fillter();
            return view('order::OrderConfirm.index',compact('paginate', 'fillter'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }
    /**
    * search pi order confirm
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/114 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postSearch(Request $request) {
        try {
            $param          = $request->all();
            $sql            = "SPC_004_PI_ORDER_CONFIRM_FND1";
            $data           = Dao::call_stored_procedure($sql, $param, true);

            $piList          = isset($data[0]) ? $data[0] : array();
            $paginator      = new Paginator($data[1][0]['pageMax'], $data[1][0]['pagesize'], $data[1][0]['page'], $data[1][0]['totalRecord']);
            $paginate       = $paginator->show($data[1][0]['page'], 'paginate');
            $fillter        = $paginator->fillter();

            $html           = view('order::OrderConfirm.list',compact('piList','paginate', 'fillter'))->render();
            return response()->json(array(
                'response'      => true,
                'html'          => $html
            ));
        } catch (\Exception $e) {
            return response()->json(array(
                                'response'  =>  false,
                                'error'     =>  $e->getMessage()
                            ));
        }
    }
    /**
    * save Pi Order Confirm
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/05 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function savePiOrderConfirm(Request $request) {
        try {
            //get data from client
            $data                   = $request->all();
            $data['pi_list']        =  json_encode($data['pi_list']);//parse json to string
            $data['cre_user_cd']    = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     = '004_pi-order-confirm';
            $data['cre_ip']         = \GetUserInfo::getInfo('user_ip');
            //call stored
            $sql                    = "SPC_004_PI_ORDER_CONFIRM_ACT1";
            $result                 = Dao::call_stored_procedure($sql, $data);
            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'      => true,
                    'error_cd'      => $result[1][0]['error_cd'],
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
