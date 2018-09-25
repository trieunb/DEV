<?php
/**
*|--------------------------------------------------------------------------
*| Order
*|--------------------------------------------------------------------------
*| Package       : Master  
*| @author       : Trieunb - ANS806 - trieunb@ans-asia.com
*| @created date : 2017/01/08
*| Description   : 
*/
namespace Modules\Popup\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Paginator,Dao;

class SuppliersMasterSearchController extends Controller
{
    /**
     * list pi
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
            return view('popup::SuppliersMasterSearch.index', compact('paginate', 'fillter'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }

    /**
     * search user
     * -----------------------------------------------
     * @author      :   ANS804 - 2017/12/19 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postSearch(Request $request){
        try{
            $param      = $request->all();
            $sql        = "SPC_055_SUPPLIERS_MASTER_SEARCH_FND1"; 
            $data       = Dao::call_stored_procedure($sql,$param,true);
            $clientList = isset($data[0]) ? $data[0] : array();

            $paginator  = new Paginator($data[1][0]['pageMax'], $data[1][0]['pagesize'], $data[1][0]['page'], $data[1][0]['totalRecord']);
            $paginate   = $paginator->show($data[1][0]['page'], 'paginate');
            $fillter    = $paginator->fillter();

            $html       = view('popup::SuppliersMasterSearch.list',compact('clientList','paginate', 'fillter'))->render();
            
            //return data
            return response()->json(array(
                'response'      => true,
                'html'          => $html
            ));
        } catch (\Exception $e) {
            return response()->json(array(
                'response'  => false,
                'error'     => $e->getMessage()
            ));
        }        
    }
}
