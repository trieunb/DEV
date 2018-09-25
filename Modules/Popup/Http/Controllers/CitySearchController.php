<?php

namespace Modules\Popup\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Paginator,Dao,DB;

class CitySearchController extends Controller
{
	/**
	 * display search
	 *
	 * @author      :   DuyTP 2017/06/15
	 * @author      :   ANS804 - 2017/12/20 - update
	 * @param       :   null
	 * @return      :   null
	 * @access      :   public
	 * @see         :
	 */
	public function getSearchCity(Request $request)
	{
        try {
            $paginator  = new Paginator(0, 0, 0, 0);
            $paginate   = $paginator->show(1, 'paginate');
            $fillter    = $paginator->fillter();
            return view('popup::CitySearch.city', compact('paginate', 'fillter'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
	}


    /**
     * search city
     * -----------------------------------------------
     * @author      :   ANS804 - 2017/12/20 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postSearchCity(Request $request){
        try{
            $param     = $request->all();
            $sql       = "SPC_CITY_SEARCH"; 
            $data      = Dao::call_stored_procedure($sql,$param,true);
            $dataCity  = isset($data[0]) ? $data[0] : array();
            
            $paginator = new Paginator($data[1][0]['pageMax'], $data[1][0]['pagesize'], $data[1][0]['page'], $data[1][0]['totalRecord']);
            $paginate  = $paginator->show($data[1][0]['page'], 'paginate');
            $fillter   = $paginator->fillter();
            
            $html      = view('popup::CitySearch.list',compact('dataCity','paginate', 'fillter'))->render();
            
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
