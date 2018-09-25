<?php

namespace Modules\Popup\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Paginator,DB,Dao;

class UserSearchController extends Controller
{
	/**
    * index
    * -----------------------------------------------
    * @author      :   ANS806 - 2017/01/08 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function getIndex()
    {
        try {
            $paginator  = new Paginator(0, 0, 0, 0);
            $paginate   = $paginator->show(1, 'paginate');
            $fillter    = $paginator->fillter();
            return view('popup::UserSearch.user', compact('paginate', 'fillter'));
        } catch (\Exception $e) {
            return response()->json(array('response'=>false));
        }
    }
    /**
    * search user
    * -----------------------------------------------
    * @author      :   ANS796 - 2017/11/09 - create
    * @param       :
    * @return      :   mixed
    * @access      :   public
    * @see         :   remark
    */
    public function postSearch(Request $request){
        try{
            $param          = $request->all();
            $sql            = "SPC_077_USER_MASTER_SEARCH_FND1"; 
            $data           = Dao::call_stored_procedure($sql,$param,true);
            $userList       = isset($data[0]) ? $data[0] : array();
            $paginator      = new Paginator($data[1][0]['pageMax'], $data[1][0]['pagesize'], $data[1][0]['page'], $data[1][0]['totalRecord']);
            $paginate       = $paginator->show($data[1][0]['page'], 'paginate');
            $fillter        = $paginator->fillter();
            $html           = view('popup::UserSearch.list',compact('userList','paginate', 'fillter'))->render();
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
	 * display search
	 *
	 * @author      :   DuyTP 2017/06/15
	 * @author      :
	 * @param       :   null
	 * @return      :   null
	 * @access      :   public
	 * @see         :
	 */
	public function getSearchUser(Request $request)
	{
		$paginator  = new Paginator(5, 10, 5, 200);
        $paginate   = $paginator->show(1, 'paginate');
        $fillter    = $paginator->fillter();

        $nm = 'lib_val_nm_e';
        $ab = 'lib_val_ab_e';
        if ($request->countryCode == 'JP') {
            $nm = 'lib_val_nm_j';
            $ab = 'lib_val_ab_j';
        }

		return view('popup::UserSearch.user', compact('paginate', 'fillter', 'data'));
	}

}
