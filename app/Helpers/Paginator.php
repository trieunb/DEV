<?php
	/**
	*-------------------------------------------------------------------------*
	* Apel
	* Helpers Paginator
	*
	* 処理概要/process overview  :
	* 作成日/create date		 :   2017/01/08
	* 作成者/creater			 :   trieunb – trieunb@ans-asia.com
	*
	* @package				 :   MASTER
	* @copyright			 :   Copyright (c) ANS-ASIA
	* @version				 :   1.0.0
	*-------------------------------------------------------------------------*
	* DESCRIPTION
	*
	*
	*
	*
	*/
namespace App\Helpers;

class Paginator {

	private $_number;	//number record of a page current
	private $_limit;	//number limit of query
    private $_page;		//number page current
    private $_total;	//total record of query

    public function __construct($number, $limit, $page, $total) {
	    $this->_number 		= $number;
	    $this->_limit 		= $limit;
	    $this->_page 		= $page;
	    $this->_total 		= $total;
	     
	}
	/**
	* show pagination
	* -----------------------------------------------
	* @author	  :   Trieunb	 - 2017/08/01 - create
	* @param	  :   null
	* @return	  :   null
	* @access	  :   public
	* @see		  :   remark
	*/
	public function show($links, $list_class) {

		try {
			if ( $this->_limit == 'all' ) {
		        return '';
		    }
		 
		    $last       = ceil( $this->_total / $this->_limit );
		 
		    $start      = ( ( $this->_page ) > 2 ) ? $this->_page - 1 : 1;
		    $end        = ( ( $this->_page ) < $last ) ? $this->_page + 1 : $last;

		    $html       = '<ul class="pagination" id="' . $list_class . '" style="float: right">';
		 
		    $class      = ( $this->_page == 1 ) ? "disabled" : "";
		    $html       .= '<li class="' . $class . '"><button '. $class .' data-page="' . ( $this->_page - 1 ) . '">&laquo;Prev</button></li>';
		 
		    if ( $start >= 2 ) {
		        $html   .= '<li><button data-page="1">1</button></li>';
		        $html   .= '<li class="disabled"><span>...</span></li>';
		    }
		 
		    for ( $i = $start ; $i <= $end; $i++ ) {
		        $class  	= ( $this->_page == $i ) ? "active" : "";
		        $disabled  	= ( $this->_page == $i ) ? "disabled" : "";
		        $html   .= '<li class="' . $class . '"><button ' . $disabled . ' class="' . $class . '" data-page="' . $i . '">' . $i . '</button></li>';
		    }
		 
		    if ( $end < $last ) {
		    	$html   .= '<li class="disabled"><span>...</span></li>';
		        $html   .= '<li><button data-page="' . $last . '">' . $last . '</button></li>';
		    }
		 
		    $class      = ( $this->_page == $last ) ? "disabled" : "";
		    $html       .= '<li class="' . $class . '"><button '. $class .' class="' . $class . '" data-page="' . ( $this->_page + 1 ) . '">Next&raquo;</button></li>';
		 
		    $html       .= '</ul>';
		 
		    return  ($this->_total > 0) ? $html : '';
		} catch (\Exception $e) {
            echo $e->getMessage();
        }
	}

	/**
	* show fillter
	* -----------------------------------------------
	* @author	  :   Trieunb	 - 2017/08/01 - create
	* @param	  :   null
	* @return	  :   null
	* @access	  :   public
	* @see		  :   remark
	*/
	public function fillter() {
		try {
			$record_start = min(($this->_page-1)*$this->_limit+1, $this->_total);//($this->_page * $this->_limit) + 1;
			$record_end   = min(($this->_page)*$this->_limit, $this->_total);//$record_start + $this->_number - 1;
			$size10 = ($this->_limit == 10) ?  'selected="selected"' : '';
			$size50 = ($this->_limit == 50) ?  'selected="selected"' : '';
			$size100 = ($this->_limit ==100) ?  'selected="selected"' : '';
			$html 	=	'<div class="pagi-fillter">';
			$html  .=   '<select name="select" class="form-control show-item-paging" id="page-size" style="width:78px;float: left;">
								<option value="10" '.$size10.'>10 件</option>
								<option value="50" '.$size50.'>50 件</option>
								<option value="100" '.$size100.'>100 件</option>
							</select>';
			if($this->_total > 0){
				$html  .=   '<label class="total-record"> '.$this->_total.'件中 '.$record_start.'-'.$record_end.'件</label>';
			}else{
				$html  .=   '<label class="total-record">検索結果 </label>';
			}
			$html  .=	'</div>';
			return ($this->_total > 0) ? $html : '';
		} catch (\Exception $e) {
            echo $e->getMessage();
        }
	}
}