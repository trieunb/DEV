<?php
/**
*|--------------------------------------------------------------------------
*| Component Order
*|--------------------------------------------------------------------------
*| Package       : Component Order  
*| @author       : TuanNK - ANS818 - tuannk@ans-asia.com
*| @created date : 2017/01/08
*| Description   : 
*/
namespace Modules\ComponentOrder\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\ComboboxController as Combobox;
use Paginator, Dao;

class OrderSearchController extends Controller {
    /**
     * list component order
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/02/13 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function getOrderSearch() {
        try {
            $buy_status_div = Combobox::libraryCode('buy_status_div');
            
            $paginator      = new Paginator(0,0,0,0);
            $paginate       = $paginator->show(1, 'paginate');
            $fillter        = $paginator->fillter();
            
            //get ctl_val1
            $sql            = "SPC_035_COMPONENT_ORDER_SEARCH_INQ1"; 
            $dataCtl        = Dao::call_stored_procedure($sql);

            if(!empty($dataCtl)) {
               $report_number_parts_order = $dataCtl[0][0]['report_number_parts_order'];
            }

            return view('componentorder::OrderSearch.index',compact('paginate', 'fillter', 'buy_status_div', 'report_number_parts_order'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }

    /**
     * search accept list
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/02/13 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postOrderSearch(Request $request) {
        try {
            $param     = $request->all();
            $sql       = "SPC_035_COMPONENT_ORDER_SEARCH_FND1";
            $data      = Dao::call_stored_procedure($sql, $param, true);
            
            $orderList = isset($data[0]) ? $data[0] : array();
            $paginator = new Paginator($data[1][0]['pageMax'], $data[1][0]['pagesize'], $data[1][0]['page'], $data[1][0]['totalRecord']);
            $paginate  = $paginator->show($data[1][0]['page'], 'paginate');
            $fillter   = $paginator->fillter();
            
            $html      = view('componentorder::OrderSearch.list',compact('orderList','paginate', 'fillter'))->render();
            return response()->json(array(
                'response' => true,
                'html'     => $html
            ));
        } catch (\Exception $e) {
            return response()->json(array(
                                'response'  =>  false,
                                'error'     =>  $e->getMessage()
                            ));
        }
    }

    /**
     * approve order search
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/06/01 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postApproved(Request $request) {
        try {
            //get data from client
            $data                  = $request->all();
            $data['data_approved'] =  json_encode($data['data_approved']);//parse json to string

            $data['cre_user_cd']   = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']    = '035_component-order-search';
            $data['cre_ip']        = \GetUserInfo::getInfo('user_ip');
            
            //call stored
            $sql                   = "SPC_035_COMPONENT_ORDER_SEARCH_ACT1";
            $result                = Dao::call_stored_procedure($sql, $data);
            
            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'    => true,
                    'error_cd'    => $result[1][0]['error_cd'],
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
