<?php
/**
*|--------------------------------------------------------------------------
*| stock manage
*|--------------------------------------------------------------------------
*| Package       : stock manage  
*| @author       : TuanNK - ANS818 - tuannk@ans-asia.com
*| @created date : 2017/01/08
*| Description   : 
*/
namespace Modules\StockManage\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Paginator, Dao;

class StockSearchController extends Controller
{
    /**
    * stock
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/08/10 - create
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
            return view('stockmanage::StockSearch.index',compact('paginate', 'fillter'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }
    /**
    * search stock
    * -----------------------------------------------
    * @author      :   ANS796 - 2018/01/11 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postSearch(Request $request){
        try{
            $param              = $request->all();
            $sql                = "SPC_050_STOCK_SEARCH_FND1"; 
            $data               = Dao::call_stored_procedure($sql, $param, true);
            $stockList          = isset($data[0]) ? $data[0] : array();
            $paginator          = new Paginator($data[1][0]['pageMax'], $data[1][0]['pagesize'], $data[1][0]['page'], $data[1][0]['totalRecord']);
            $paginate           = $paginator->show($data[1][0]['page'], 'paginate');
            $fillter            = $paginator->fillter();
            $html               = view('stockmanage::StockSearch.list',compact('stockList','paginate', 'fillter'))->render();
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
