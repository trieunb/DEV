<?php

namespace Modules\Popup\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Paginator,DB;

class PopupController extends Controller
{
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
	public function getSearchCity(Request $request)
	{
		$aParams = Input::all();
		$data['data']['btnid'] = isset($aParams['btnid'])?$aParams['btnid']:0;
		$data['data']['istable'] = isset($aParams['istable'])?$aParams['istable']:0;
		$data['data']['puredata'] = isset($aParams['puredata'])?$aParams['puredata']:0;
		$data['data']['multi'] = $request->multi;
		//var_dump($data);die;
		$paginator  = new Paginator(5, 10, 5, 200);
        $paginate   = $paginator->show(1, 'paginate');
        $fillter    = $paginator->fillter();
        $nm = 'lib_val_nm_e';
        $ab = 'lib_val_ab_e';
        if ($request->countryCode == 'JP') {
            $nm = 'lib_val_nm_j';
            $ab = 'lib_val_ab_j';
        }
        $dataCity = DB::table('s_lib_val')->where('lib_cd', 'city_div')->select('lib_val_cd', $nm, 'lib_val_ctl1')->get();
        $dataCity = json_decode($dataCity, true);
		return view('popup::search.city', compact('paginate', 'fillter', 'data', 'dataCity'));
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
	public function getSearchContry(Request $request)
	{
		$aParams = Input::all();
		$data['data']['btnid'] = isset($aParams['btnid'])?$aParams['btnid']:0;
		$data['data']['istable'] = isset($aParams['istable'])?$aParams['istable']:0;
		$data['data']['puredata'] = isset($aParams['puredata'])?$aParams['puredata']:0;
		$data['data']['multi'] = $request->multi;
		//var_dump($data);die;
		$paginator  = new Paginator(5, 10, 5, 200);
        $paginate   = $paginator->show(1, 'paginate');
        $fillter    = $paginator->fillter();

        $nm = 'lib_val_nm_e';
        $ab = 'lib_val_ab_e';
        if ($request->countryCode == 'JP') {
            $nm = 'lib_val_nm_j';
            $ab = 'lib_val_ab_j';
        }
        $dataContry = DB::table('s_lib_val')->where('lib_cd', 'country_div')->select('lib_val_cd', $nm)->get();
        $dataContry = json_decode($dataContry, true);
		return view('popup::search.country', compact('paginate', 'fillter', 'data', 'dataContry'));
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

		return view('popup::search.user', compact('paginate', 'fillter', 'data'));
	}

}
