<?php
/**
*|--------------------------------------------------------------------------
*| Library Master Search
*|--------------------------------------------------------------------------
*| Package       : Library Master 
*| @author       : Trieunb - ANS806 - trieunb@ans-asia.com
*| @created date : 2017/08/10
*| Description   : 
*/
namespace Modules\systemmanagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\ComboboxController as Combobox;
use Paginator, Dao;

class LibraryMasterSearchController extends Controller
{
    /**
    * get Search index
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/08/08 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function getSearch()
    {
        try {
            //get library
            $possible_div          = Combobox::libraryCode('possible_div');
            $paginator  = new Paginator(0, 0, 0, 0);
            $paginate   = $paginator->show(1, 'paginate');
            $fillter    = $paginator->fillter();
            return view('systemmanagement::library.index', compact('paginate', 'fillter', 'possible_div'));
        } catch (\Exception $e) {
            return response()->json(array('response' => false));
        }
    }
    /**
    * search library
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/11/09 - search
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postSearch(Request $request)
    {
        try {
            $param          = $request->all();
            $sql            = "SPC_057_LIBRARY_MASTER_SEARCH_FND1"; 
            $data           = Dao::call_stored_procedure($sql, $param, true);
            $libraryList    = isset($data[0]) ? $data[0] : array();
            $paginator      = new Paginator($data[1][0]['pageMax'], $data[1][0]['pagesize'], $data[1][0]['page'], $data[1][0]['totalRecord']);
            $paginate       = $paginator->show($data[1][0]['page'], 'paginate');
            $fillter        = $paginator->fillter();
            $html           = view('systemmanagement::library.list',compact('libraryList','paginate', 'fillter'))->render();
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
