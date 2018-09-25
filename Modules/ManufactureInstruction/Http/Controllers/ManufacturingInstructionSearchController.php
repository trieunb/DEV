<?php

namespace Modules\ManufactureInstruction\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use Modules\Common\Http\Controllers\ComboboxController as Combobox;
use Session, Paginator, Dao;

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
        $manufacture_kind_div   = Combobox::libraryCode('manufacture_kind_div');
        $outsourcing_div        = Combobox::libraryCode('outsourcing_div');
        $production_status_div  = Combobox::libraryCode('production_status_div');
        $done_div               = Combobox::libraryCode('done_div');
		$paginator  = new Paginator(0, 0, 0, 0);
        $paginate   = $paginator->show(1, 'paginate');
        $fillter    = $paginator->fillter();
		return view('manufactureinstruction::ManufacturingInstructionSearch.search', compact('paginate', 'fillter', 'manufacture_kind_div', 'outsourcing_div', 'production_status_div', 'done_div'));
	}
	/**
    * search manufacture instruction
    * -----------------------------------------------
    * @author      :   ANS796 - 2018/04/06 - create
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
            $html          					= view('manufactureinstruction::ManufacturingInstructionSearch.list',compact('manufacturingInstructionList','paginate', 'fillter'))->render();
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
    /**
     * Create Goods Issue Source
     * -----------------------------------------------
     * @author      :   ANS796 - 2018/04/13 - create
     * @param       :
     * @return      :   mixed
     * @access      :   public
     * @see         :   remark
     */
    public function postCreateGoodsIssueSoucre(Request $request) {
        try {
            //get data from client
            $data                   =  $request->all();
            $data['update_list']    =  json_encode($data['update_list']);//parse json to string
            $data['cre_user_cd']    =  \GetUserInfo::getInfo('user_cd');
            $data['cre_prg_cd']     =  '027_manufacturing-instruction-search';
            $data['cre_ip']         =  \GetUserInfo::getInfo('user_ip');

            $sql                    =  "SPC_027_MANUFACTURING_INSTRUCTION_SEARCH_ACT1";
            $result                 =  Dao::call_stored_procedure($sql, $data);
            //return result to client
            if (empty($result[0])) {
                return response()->json(array(
                    'response'          => true,
                    'error_cd'          => $result[1][0]['error_cd']
                ));
            } else {
                return response()->json(array(
                    'response'          => false,
                    'error'             => $result[0][0]['Message'],
                    'error_cd'          => $result[1][0]['error_cd']
                ));
            }
        } catch (\Exception $e) {
            return response()->json(array(
                'response'  => false,
                'response'  => $e->getMessage()
            ));
        }        
    }
}
