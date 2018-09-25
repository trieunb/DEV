<?php

namespace Modules\Popup\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Paginator,DB;

class CartonItemSetController extends Controller
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
	public function getCartonItemSet(Request $request)
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

		return view('popup::CartonItemSet.item');
	}

}
