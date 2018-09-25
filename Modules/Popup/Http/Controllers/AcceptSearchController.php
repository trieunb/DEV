<?php
/**
 *|--------------------------------------------------------------------------
 *| Accpet
 *|--------------------------------------------------------------------------
 *| Package       : Accpet  
 *| @author       : TuanNK - ANS818 - tuannk@ans-asia.com
 *| @created date : 2017/01/08
 *| Description   : 
 */
namespace Modules\Popup\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\ComboboxController as Combobox;
use Paginator, Dao;

class AcceptSearchController extends Controller
{
    /**
     * list accept
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/01/15 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function getSearch() {
        try {
            $rcv_status_div       = Combobox::libraryCode('rcv_status_div');

            $paginator  = new Paginator(0, 0, 0, 0);
            $paginate   = $paginator->show(1, 'paginate');
            $fillter    = $paginator->fillter();
            return view('popup::AcceptSearch.index',compact('paginate', 'fillter', 'rcv_status_div'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }

    /**
     * search user
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/01/15 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postSearch(Request $request){
        try{
            $param     = $request->all();
            $sql       = "SPC_006_ACCEPT_SEARCH_FND1"; 
            $data      = Dao::call_stored_procedure($sql,$param,true);
            $rcvList   = isset($data[0]) ? $data[0] : array();
            
            $paginator = new Paginator($data[1][0]['pageMax'], $data[1][0]['pagesize'], $data[1][0]['page'], $data[1][0]['totalRecord']);
            $paginate  = $paginator->show($data[1][0]['page'], 'paginate');
            $fillter   = $paginator->fillter();
            
            $html      = view('popup::AcceptSearch.list',compact('rcvList','paginate', 'fillter'))->render();
            
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
