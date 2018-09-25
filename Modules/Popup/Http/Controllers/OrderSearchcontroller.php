<?php
/**
*|--------------------------------------------------------------------------
*| Component Order
*|--------------------------------------------------------------------------
*| Package       : Component Order  
*| @author       : DaoNX - ANS804 - daonx@ans-asia.com
*| @created date : 2017/01/08
*| Description   : 
*/
namespace Modules\Popup\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\ComboboxController as Combobox;
use Paginator, Dao;

class OrderSearchController extends Controller {
    /**
     * list component order
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/06/05 - create
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
            return view('popup::OrderSearch.index',compact('paginate', 'fillter', 'buy_status_div'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }

    /**
     * search accept list
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/06/05 - create
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
            
            $html      = view('popup::OrderSearch.list',compact('orderList','paginate', 'fillter'))->render();
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
}
