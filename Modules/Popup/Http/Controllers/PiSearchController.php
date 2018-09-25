<?php
/**
*|--------------------------------------------------------------------------
*| PI
*|--------------------------------------------------------------------------
*| Package       : PI  
*| @author       : Trieunb - ANS806 - trieunb@ans-asia.com
*| @created date : 2017/01/08
*| Description   : 
*/
namespace Modules\Popup\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\ComboboxController as Combobox;
use Paginator, Dao;

class PiSearchController extends Controller
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
    public function getList()
    {
        try {
            //get library
            $pi_status_div = Combobox::libraryCode('pi_status_div');
            $paginator  = new Paginator(0, 0, 0, 0);
            $paginate   = $paginator->show(1, 'paginate');
            $fillter    = $paginator->fillter();
            return view('popup::PiSearch.index', compact('paginate', 'fillter', 'pi_status_div'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }
    /**
    * search pi
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/12/114 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postSearch(Request $request) {
        try {
            $param          = $request->all();
            $sql            = "SPC_002_PI_SEARCH_FND1";

            $data           = Dao::call_stored_procedure($sql, $param, true);
            $piList         = isset($data[0]) ? $data[0] : array();
            $paginator      = new Paginator($data[1][0]['pageMax'], $data[1][0]['pagesize'], $data[1][0]['page'], $data[1][0]['totalRecord']);
            $paginate       = $paginator->show($data[1][0]['page'], 'paginate');
            $fillter        = $paginator->fillter();

            $html           = view('popup::PiSearch.list',compact('piList','paginate', 'fillter'))->render();
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
