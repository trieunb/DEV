<?php

namespace Modules\Popup\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\ComboboxController as Combobox;
use Session, Paginator, Dao;

class InternalOrderSearchController extends Controller
{
	
	/**
	 *
	 * @author      :   DungNN 2018/01/11
	 * @author      :
	 * @param       :   null
	 * @return      :   null
	 * @access      :   public
	 * @see         :
	 */
	public function getSearch()
	{
		try {
            //get library
            $manufacture_status_div = Combobox::libraryCode('manufacture_status_div');
            $paginator  = new Paginator(0, 0, 0, 0);
            $paginate   = $paginator->show(1, 'paginate');
            $fillter    = $paginator->fillter();            
            return view('popup::InternalOrderSearch.search', compact('paginate', 'fillter', 'manufacture_status_div'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
	}

	/**
    * search internal order
    * -----------------------------------------------
    * @author      :   ANS810 - 2018/01/09 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postSearch(Request $request){
        try{            
            $param         = $request->all();
            $sql           = "SPC_026_INTERNAL_ORDER_SEARCH_FND1";
            $data          = Dao::call_stored_procedure($sql,$param,true);

            $internalList  = isset($data[0]) ? $data[0] : array();
            $paginator     = new Paginator($data[1][0]['pageMax'], $data[1][0]['pagesize'], $data[1][0]['page'], $data[1][0]['totalRecord']);
            $paginate      = $paginator->show($data[1][0]['page'], 'paginate');
            $fillter       = $paginator->fillter();

            $html          = view('popup::InternalOrderSearch.list',compact('internalList','paginate', 'fillter'))->render();
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
