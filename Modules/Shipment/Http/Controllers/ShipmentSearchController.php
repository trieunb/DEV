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
namespace Modules\Shipment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\ComboboxController as Combobox;
use Paginator, Dao;

class ShipmentSearchController extends Controller
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
    public function getSearch()
    {
        try {
            $fwd_status_div  = Combobox::libraryCode('fwd_status_div');
            $paginator  = new Paginator(0, 0, 0, 0);
            $paginate   = $paginator->show(1, 'paginate');
            $fillter    = $paginator->fillter();
            return view('shipment::ShipmentSearch.index',compact('paginate', 'fillter', 'fwd_status_div'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }
     /**
    * search shipment
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
            $sql            = "SPC_011_SHIPMENT_SEARCH_FND1";

            $data           = Dao::call_stored_procedure($sql, $param, true);
            // return $data;
            $List           = isset($data[0]) ? $data[0] : array();
            $List           = isset($data[0]) ? $data[0] : array();
            $paginator      = new Paginator($data[1][0]['pageMax'], $data[1][0]['pagesize'], $data[1][0]['page'], $data[1][0]['totalRecord']);
            $paginate       = $paginator->show($data[1][0]['page'], 'paginate');
            $fillter        = $paginator->fillter();

            $html           = view('shipment::ShipmentSearch.list',compact('List','paginate', 'fillter'))->render();
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
    * approve fwd detail
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/05 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postApprove(Request $request) {
        try {
            //get data from client
            $data                   = $request->all();
            $data['fwd_list']       =  json_encode($data['fwd_list']);//parse json to string
            $data['cre_user_cd']    = \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     = '011_shipment-search';
            $data['cre_ip']         = \GetUserInfo::getInfo('user_ip');
            //call stored
            $sql                    = "SPC_011_SHIPMENT_SEARCH_ACT1";
            $result                 = Dao::call_stored_procedure($sql, $data);
            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'      => true,
                    'error_cd'      => $result[1][0]['error_cd'],
                ));
            } else {
                return response()->json(array(
                    'response'      => false,
                    'error'         => $result[0][0]['Message'],
                ));
            }
        } catch(\Exception $e) {
            return response()->json(array(
                    'response'      => false,
                    'error'         => $e->getMessage()
                ));
        }
    }
}
