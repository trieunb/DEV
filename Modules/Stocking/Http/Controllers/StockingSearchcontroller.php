<?php
/**
*|--------------------------------------------------------------------------
*| Stocking Order
*|--------------------------------------------------------------------------
*| Package       : Stocking Order  
*| @author       : tuannt - ANS796 - tuannt@ans-asia.com
*| @created date : 2018/06/26
*| Description   : 
*/
namespace Modules\Stocking\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\ComboboxController as Combobox;
use Paginator, Dao;

class StockingSearchController extends Controller {
    /**
     * list stocking order
     * -----------------------------------------------
     * @author      :   ANS796 - 2018/06/26 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function getStockingSearch() {
        try {
            $buy_status_div = Combobox::libraryCode('buy_status_div');
            
            $paginator      = new Paginator(0,0,0,0);
            $paginate       = $paginator->show(1, 'paginate');
            $fillter        = $paginator->fillter();
            //view
            return view('stocking::StockingSearch.index',compact('paginate', 'fillter', 'buy_status_div'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }

    /**
     * search stocking list
     * -----------------------------------------------
     * @author      :   ANS796 - 2018/06/26 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postStockingSearch(Request $request) {
        try {
            $param          = $request->all();
            $sql            = "SPC_105_STOCKING_SEARCH_FND1";
            $data           = Dao::call_stored_procedure($sql, $param, true);
            
            $stockingList   = isset($data[0]) ? $data[0] : array();
            $paginator      = new Paginator($data[1][0]['pageMax'], $data[1][0]['pagesize'], $data[1][0]['page'], $data[1][0]['totalRecord']);
            $paginate       = $paginator->show($data[1][0]['page'], 'paginate');
            $fillter        = $paginator->fillter();
            
            $html           = view('stocking::StockingSearch.list',compact('stockingList','paginate', 'fillter'))->render();
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
