<?php

namespace Modules\Popup\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\ComboboxController as Combobox;
use Session, Dao;
use Paginator;

class ManufacturingInstructionSearchController extends Controller
{
	/**
	 *
	 * @author      :   DuyTP 2017/06/15
	 * @author      :
	 * @param       :   null
	 * @return      :   null
	 * @access      :   public
	 * @see         :
	 */
	public function getSearch()
	{
        //get library
        $manufacture_kind_div   = Combobox::libraryCode('manufacture_kind_div');
        $outsourcing_div        = Combobox::libraryCode('outsourcing_div');
        $production_status_div  = Combobox::libraryCode('production_status_div');
        $done_div               = Combobox::libraryCode('done_div');
		$paginator  = new Paginator(0, 0, 0, 0);
        $paginate   = $paginator->show(1, 'paginate');
        $fillter    = $paginator->fillter();
		return view('popup::ManufacturingInstructionSearch.search', compact('paginate', 'fillter', 'manufacture_kind_div', 'outsourcing_div', 'production_status_div', 'done_div'));
	}
	/**
    * search manufacturing instruction search
    * -----------------------------------------------
    * @author      :   ANS796 - 2017/11/09 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postSearch(Request $request){
        try{
            $param         					= $request->all();
            $sql           					= "SPC_027_MANUFACTURING_INSTRUCTION_SEARCH_FND1";
            $data          					= Dao::call_stored_procedure($sql,$param,true);
            $manufacturingInstructionList  	= isset($data[0]) ? $data[0] : array();
            $paginator     					= new Paginator($data[1][0]['pageMax'], $data[1][0]['pagesize'], $data[1][0]['page'], $data[1][0]['totalRecord']);
            $paginate      					= $paginator->show($data[1][0]['page'], 'paginate');
            $fillter       					= $paginator->fillter();
            $html           				= view('popup::ManufacturingInstructionSearch.list',compact('manufacturingInstructionList','paginate', 'fillter'))->render();
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
