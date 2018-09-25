<?php
/**
*|--------------------------------------------------------------------------
*| Shipment
*|--------------------------------------------------------------------------
*| Package       : Shipment  
*| @author       : TuanNK - ANS818 - tuannk@ans-asia.com
*| @created date : 2017/01/08
*| Description   : 
*/
namespace Modules\Popup\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Paginator, Dao;

class ShipmentSerialSearchController extends Controller
{
    /**
    * list shipment
    * -----------------------------------------------
    * @author      :   ANS818 - 2017/01/08 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function getSearch(Request $request)
    {
        try {
            $id         = '';
            $id         = $request->get('id');
            $paginator  = new Paginator(0, 0, 0, 0);
            $paginate   = $paginator->show(1, 'paginate');
            $fillter    = $paginator->fillter();
            return view('popup::ShipmentSerialSearch.index',compact('paginate', 'fillter', 'id'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }
     /**
    * search shipment serial
    * -----------------------------------------------
    * @author      :   ANS806 - 2018/03/05 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postSearchIndex(Request $request) {
        try {
            $param          = $request->all();
            $sql            = "SPC_102_SHIPMENT_SERIAL_SEARCH_FND2";
            $data           = Dao::call_stored_procedure($sql, $param, true);
            $List           = isset($data[0]) ? $data[0] : array();
            // return $List; die;  
            $html           = view('popup::ShipmentSerialSearch.list',compact('List'))->render();
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
     /**
    * search shipment serial
    * -----------------------------------------------
    * @author      :   ANS806 - 2018/03/05 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postSearch(Request $request) {
        try {
            $param          = $request->all();
            $sql            = "SPC_102_SHIPMENT_SERIAL_SEARCH_FND1";

            $data           = Dao::call_stored_procedure($sql, $param, true);
            $List           = isset($data[0]) ? $data[0] : array();
            // return $List; die;  
            $html           = view('popup::ShipmentSerialSearch.list',compact('List'))->render();
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
