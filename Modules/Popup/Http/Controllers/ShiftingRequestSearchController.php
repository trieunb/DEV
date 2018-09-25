<?php
/**
*|--------------------------------------------------------------------------
*| Shifting
*|--------------------------------------------------------------------------
*| Package       : Shifting  
*| @author       : DaoNX - ANS804 - tuannk@ans-asia.com
*| @created date : 2017/04/06
*| Description   : 
*/
namespace Modules\Popup\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\ComboboxController as Combobox;
use Paginator, Dao;

class ShiftingRequestSearchController extends Controller
{
    /**
    * list shifting
    * -----------------------------------------------
    * @author      :   ANS804 - 2017/04/06 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function getSearch()
    {
        try {
            //get library
            $move_status_div = Combobox::libraryCode('move_status_div');
            $paginator  = new Paginator(0, 0, 0, 0);
            $paginate   = $paginator->show(1, 'paginate');
            $fillter    = $paginator->fillter();
            return view('popup::ShiftingRequestSearch.index',compact('paginate', 'fillter', 'move_status_div'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }
    /**
     * popup search shifting
     * -----------------------------------------------
     * @author      :   ANS804 - 2018/02/21 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postSearch(Request $request) {
        try {
            $param     = $request->all();
            $sql       = "SPC_042_SHIFTING_REQUEST_LIST_SEARCH_FND1";
            
            $data      = Dao::call_stored_procedure($sql, $param, true);
            
            $list      = isset($data[0]) ? $data[0] : array();
            $paginator = new Paginator($data[1][0]['pageMax'], $data[1][0]['pagesize'], $data[1][0]['page'], $data[1][0]['totalRecord']);
            $paginate  = $paginator->show($data[1][0]['page'], 'paginate');
            $fillter   = $paginator->fillter();
            $html      = view('popup::ShiftingRequestSearch.list',compact('list','paginate', 'fillter'))->render();

            return response()->json(array(
                'response' => true,
                'html'     => $html
            ));
        } catch (\Exception $e) {
            return response()->json(array(
                                'response'  => false,
                                'error'     => $e->getMessage()
                            ));
        }
    }
}
