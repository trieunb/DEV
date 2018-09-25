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
namespace Modules\Popup\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Paginator;

class StockManageSearchController extends Controller
{
    /**
    * list pi
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/08/10 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function getSearch()
    {
        try {
            $paginator  = new Paginator(5, 10, 5, 200);
            $paginate   = $paginator->show(1, 'paginate');
            $fillter    = $paginator->fillter();
            return view('popup::StockManageSearch.index',compact('paginate', 'fillter'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }
}
