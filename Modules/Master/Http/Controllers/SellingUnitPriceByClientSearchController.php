<?php
/**
*|--------------------------------------------------------------------------
*| selling-unit-price-by-client-search
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
use Paginator, Dao;

class SellingUnitPriceByClientSearchController extends Controller
{
    /**
    * search selling unit price by client
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/08/08 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function index()
    {
        try {
            $paginator  = new Paginator(0, 0, 0, 0);
            $paginate   = $paginator->show(1, 'paginate');
            $fillter    = $paginator->fillter();
            return view('master::SellingUnitPriceByClientSearch.index', compact('paginate', 'fillter'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }
    /**
    * search sales price
    * -----------------------------------------------
    * @author      :   ANS796 - 2017/12/13 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postSearch(Request $request){
        try{
            $param          = $request->all();
            $sql            = "SPC_065_SELLING_UNIT_PRICE_BY_CLIENT_SEARCH_FND1"; 
            $data           = Dao::call_stored_procedure($sql,$param,true);
            $salesPriceList = isset($data[0]) ? $data[0] : array();
            $paginator      = new Paginator($data[1][0]['pageMax'], $data[1][0]['pagesize'], $data[1][0]['page'], $data[1][0]['totalRecord']);
            $paginate       = $paginator->show($data[1][0]['page'], 'paginate');
            $fillter        = $paginator->fillter();
            $html           = view('master::SellingUnitPriceByClientSearch.list',compact('salesPriceList','paginate', 'fillter'))->render();
            //return data
            return response()->json(array(
                'response'      => true,
                'html'          => $html
            ));
        }
        catch (\Exception $e) {
            return response()->json(array(
                'response'  => false,
                'error'     => $e->getMessage()
            ));
        }        
    }
}
