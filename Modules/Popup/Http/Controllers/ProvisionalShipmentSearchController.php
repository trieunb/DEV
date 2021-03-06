<?php
/**
*|--------------------------------------------------------------------------
*| Shipment
*|--------------------------------------------------------------------------
*| Package       : Provisional Shipment  
*| @author       : KhaDV - ANS831 - khadv@ans-asia.com
*| @created date : 2018/02/02
*| Description   : 
*/
namespace Modules\Popup\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Paginator,Dao;

class ProvisionalShipmentSearchController extends Controller
{
    /**
    * list provisional shipment
    * -----------------------------------------------
    * @author      :   ANS831 - 2018/02/02 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function getSearch()
    {
        try {
            $paginator  = new Paginator(0,0,0,0);
            $paginate   = $paginator->show(1, 'paginate');
            $fillter    = $paginator->fillter();
            return view('popup::ProvisionalShipmentSearch.index',compact('paginate', 'fillter'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }
    /**
    * search provisional shipment detail
    * -----------------------------------------------
    * @author      :   ANS831 - 2017/02/02 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postSearch(Request $request) {
        try {
            $param          = $request->all();
            $sql            = "SPC_008_PROVISIONAL_SHIPMENT_SEARCH_FND1";
            $data           = Dao::call_stored_procedure($sql, $param, true);
            $shipmentList   = isset($data[0]) ? $data[0] : array();
            $paginator      = new Paginator($data[1][0]['pageMax'], $data[1][0]['pagesize'], $data[1][0]['page'], $data[1][0]['totalRecord']);
            $paginate       = $paginator->show($data[1][0]['page'], 'paginate');
            $fillter        = $paginator->fillter();

            $html           = view('popup::ProvisionalShipmentSearch.list',compact('shipmentList','paginate', 'fillter'))->render();
            
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
