<?php
/**
*|--------------------------------------------------------------------------
*| Deposit Detail
*|--------------------------------------------------------------------------
*| Package       : Deposit  
*| @author       : DaoNX - ANS804 - daonx@ans-asia.com
*| @created date : 2017/01/08
*| Description   : 
*/
namespace Modules\Deposit\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\ComboboxController as Combobox;
use Paginator, Dao;

class DepositSearchController extends Controller
{
    /**
    * list deposit
    * -----------------------------------------------
    * @author      :   ANS804 - 2018/01/31 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function getDepositSearch() {
        try {
            $target_div        = Combobox::libraryCode('target_div');

            $paginator  = new Paginator(0,0,0,0);
            $paginate   = $paginator->show(1, 'paginate');
            $fillter    = $paginator->fillter();
            return view('deposit::DepositSearch.index',compact('paginate', 'fillter', 'target_div'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }
    /**
     * search deposit list
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/01/31 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postSearch(Request $request) {
        try {
            $param       = $request->all();
            $sql         = "SPC_021_DEPOSIT_SEARCH_FND1";
            $data        = Dao::call_stored_procedure($sql, $param, true);
            
            $depositList = isset($data[0]) ? $data[0] : array();
            $paginator   = new Paginator($data[1][0]['pageMax'], $data[1][0]['pagesize'], $data[1][0]['page'], $data[1][0]['totalRecord']);
            $paginate    = $paginator->show($data[1][0]['page'], 'paginate');
            $fillter     = $paginator->fillter();
            
            $html      = view('deposit::DepositSearch.list',compact('depositList','paginate', 'fillter'))->render();
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
}
