<?php
/**
*|--------------------------------------------------------------------------
*| Stocking
*|--------------------------------------------------------------------------
*| Package          : Stocking
*| @author          : TuanNT - ANS796 - tuannt@ans-asia.com
*| @created date    : 2017/01/08
*| @updater         : 
*| @created date    : 
*| Description      : 
*/
namespace Modules\Stocking\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Paginator, Dao, Session;

class StockingUpdateController extends Controller
{
    /**
     * list stocking
     * -----------------------------------------------
     * @author      :   ANS796 - 2018/06/26 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function getIndex() {
        try {
            $stockInfo = null;
            $purchase_detail_amt_round_div = 1;
            if (Session::has('StockingUpdate')) {
                $param = Session::get('StockingUpdate');
                if (!empty($param)) {
                    $mode  = $param['mode'];
                    $from  = $param['from'];

                    if (isset($param['parts_order_no']) && !empty($param['parts_order_no'])) {
                        $parts_order_no     =  $param['parts_order_no'];
                        $purchase_no        =  $param['purchase_no'];
                        $purchase_detail_no =  $param['purchase_detail_no'];
                        //
                        $sql       = "SPC_106_STOCKING_UPDATE_INQ1";
                        $data      = Dao::call_stored_procedure($sql, array($parts_order_no, $purchase_no, $purchase_detail_no), true);
                        $stockInfo = isset($data[0][0]) ? $data[0][0] : array();
                        $purchase_detail_amt_round_div  = isset($data[1][0]) ? $data[1][0]['purchase_detail_amt_round_div'] : '1';
                    }
                }
            }
            return view('stocking::StockingUpdate.index',compact('stockInfo', 'purchase_detail_amt_round_div'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }
    /**
     * Update Stocking Update
     * -----------------------------------------------
     * @author      :   ANS796- 2018/06/27 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postSave(Request $request) {
        try {
            //get data from client
            $data                = $request->all();
            $data['cre_user_cd'] = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']  = '106_stocking-update';
            $data['cre_ip']      = \GetUserInfo::getInfo('user_ip');
            $sql                 = "SPC_106_STOCKING_UPDATE_ACT1";
            $result              = Dao::call_stored_procedure($sql, $data);
            //get data error
            $data_err            = isset($result[2]) ? $result[2] : NULL;
            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'      => true,
                    'error_cd'      => $result[1][0]['error_cd'],
                    'data_err'      => $data_err
                ));     
            } else {
                return response()->json(array(
                    'response'      => false,
                    'error'         => $result[0][0]['Message'],
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
     * Detele Stocking Update
     * -----------------------------------------------
     * @author      :   ANS796- 2018/06/27 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postDelete(Request $request) {
        try {
            //get data from client
            $param                  = $request->all();
            $param['cre_user_cd']   = \GetUserInfo::getInfo('user_cd');
            $param['cre_prg_cd']    = '106_stocking-update';
            $param['cre_ip']        = \GetUserInfo::getInfo('user_ip');
            $sql                    = "SPC_106_STOCKING_UPDATE_ACT2"; 
            $result                 = Dao::call_stored_procedure($sql, $param);
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
        } catch (\Exception $e) {
            return response()->json(array(
                    'response'=> $e->getMessage()
            ));
        }        
    }
}
