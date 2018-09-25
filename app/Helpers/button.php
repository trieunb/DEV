<?php
	/**
	*-------------------------------------------------------------------------*
	* APEL
	* Helpers button
	*
	* 処理概要/process overview  :
	* 作成日/create date		 	:   2017/08/08
	* 作成者/creater			 	:   trieunb – trieunb@ans-asia.com
	*
	* @package				  	:   MASTER
	* @copyright				:   Copyright (c) ANS-ASIA
	* @version				  	:   1.0.0
	*-------------------------------------------------------------------------*
	* DESCRIPTION
	*
	*
	*
	*
	*/
namespace App\Helpers;
use Form,Lang,Session;

class Button {

	//register button
	protected static $button_val = array(
		'btn-save'		=>	[
							'id'			=>	'btn-save',						
							'class'			=>	'btn',
							'icon'			=>	'fa fa-save',			
							'label'			=>	'保存',		 
							'data_popup'	=>	'tooltip', 
							'color' 		=>	'text-primary'],
		'btn-search'	=>   [
							'id'			=>	'btn-search',						  
							'class'			=>	'btn',
							'icon'			=>	'fa fa-search',		  
							'label'			=>	'検索',		 
							'data_popup'	=>	'tooltip', 
							'color' 		=>	'text-primary'],
		'btn-back'			=>   [
							'id'			=>	'btn-back',							
							'class'			=>	'btn',
							'icon'			=>	'fa fa-mail-reply',	  
							'label'			=>	'戻る',		 
							'data_popup'	=>	'tooltip', 
							'color' 		=>	'text-danger' ],
		'btn-add-new'		=>   [
							'id'			=>	'btn-add-new',						 
							'class'			=>	'btn',
							'icon'			=>	'fa fa-plus',			
							'label'			=>	'新規追加',	 
							'data_popup'	=>	'tooltip', 
							'color' 		=>	'text-primary'],
		'btn-add-line'		=>   [
							'id'			=>	'btn-add-line',						 
							'class'			=>	'btn',
							'icon'			=>	'fa fa-plus',			
							'label'			=>	'行追加',	 
							'data_popup'	=>	'tooltip', 
							'color' 		=>	'text-primary'],
		'btn-sub-line'		=>   [
							'id'			=>	'btn-sub-line',						 
							'class'			=>	'btn',
							'icon'			=>	'fa fa-minus',			
							'label'			=>	'行削除',	 
							'data_popup'	=>	'tooltip', 
							'color' 		=>	'text-primary'],
		'btn-edit'			=>   [
							'id'			=>	'btn-edit',							
							'class'			=>	'btn',
							'icon'			=>	'fa fa-pencil',		  
							'label'			=>	'修正',		 
							'data_popup'	=>	'tooltip', 
							'color' 		=>	'text-primary'],  
		'btn-delete'		=>   [
							'id'			=>	'btn-delete',						  
							'class'			=>	'btn',
							'icon'			=>	'fa fa-trash-o',		 
							'label'			=>	'削除',		 
							'data_popup'	=>	'tooltip', 
							'color' 		=>	'text-danger' ],
		'btn-csv'			=>   [
							'id'			=>	'btn-csv',							 
							'class'			=>	'btn',
							'icon'			=>	'icon-file-text2',	   
							'label'			=>	'CSV',		  
							'data_popup'	=>	'tooltip', 
							'color' 		=>	'text-primary'],
		'btn-send'			=>   [
							'id'			=>	'btn-send',							
							'class'			=>	'btn',
							'icon'			=>	'fa fa-send',			
							'label'			=>	'送信',		 
							'data_popup'	=>	'tooltip', 
							'color' 		=>	'text-primary'],
		'btn-print'			=>   [
							'id'			=>	'btn-print',						   
							'class'			=>	'btn',
							'icon'			=>	'fa fa-print',		   
							'label'			=>	'印刷',		 
							'data_popup'	=>	'tooltip', 
							'color' 		=>	'text-primary'],
		'btn-print-packing'	=>   [
							'id'			=>	'btn-print-packing',						   
							'class'			=>	'btn',
							'icon'			=>	'fa fa-print',		   
							'label'			=>	'P/L',		 
							'data_popup'	=>	'tooltip', 
							'color' 		=>	'text-primary'],
		'btn-print-mark'	=>   [
							'id'			=>	'btn-print-mark',						   
							'class'			=>	'btn',
							'icon'			=>	'fa fa-print',		   
							'label'			=>	'Mark',		 
							'data_popup'	=>	'tooltip', 
							'color' 		=>	'text-primary'],
		'btn-export'		=>   [
							'id'			=>	'btn-export',						  
							'class'			=>	'btn',
							'icon'			=>	'fa fa-print',		   
							'label'			=>	'出力',		 
							'data_popup'	=>	'tooltip', 
							'color' 		=>	'text-primary'],   
		'btn-cancel'		=>   [
							'id'			=>	'btn-cancel',						  
							'class'			=>	'btn',
							'icon'			=>	'fa fa-trash-o',		 
							'label'			=>	'〆解除',	   
							'data_popup'	=>	'tooltip', 
							'color' 		=>	'text-danger' ],
		'btn-confirm'		=>   [
							'id'			=>	'btn-confirm',						 
							'class'			=>	'btn',
							'icon'			=>	'fa fa-check',		   
							'label'			=>	'確認',		 
							'data_popup'	=>	'tooltip', 
							'color' 		=>	'text-primary'],
		'btn-download'		=>   [
							'id'			=>	'btn-download-csv',					  
							'class'			=>	'btn',
							'icon'			=>	'fa fa-download',		
							'label'			=>	'取込',		 
							'data_popup'	=>	'tooltip', 
							'color' 		=>	'text-primary'],
		'btn-flow-change'	=>   [
							'id'			=>	'btn-flow-change',					 
							'class'			=>	'btn',
							'icon'			=>	'fa fa-refresh',		 
							'label'			=>	'フロー更新',   
							'data_popup'	=>	'tooltip', 
							'color' 		=>  'text-primary'],
		'btn-cancel-estimate'		=>   [
									'id'			=>'btn-cancel-estimate',					 
									'class'			=>'btn',
									'icon'			=>'fa fa-thumbs-down',		 
									'label'			=>'伝票取消',   
									'data_popup'	=>'tooltip', 
									'color' 		=> 'text-primary'],
		'btn-approve-estimate'		=>   [
									'id'			=>	'btn-approve-estimate',					 
									'class'			=>	'btn',
									'icon'			=>	'fa fa-thumbs-up',
									'label'			=>	'伝票承認',
									'data_popup'	=>	'tooltip', 
									'color' 		=>	'text-primary'],
		'btn-generate'				=>	[
									'id'			=>	'btn-generate',						
									'class'			=>	'btn',
									'icon'			=>	'fa fa-save',			
									'label'			=>	'世代更新',		 
									'data_popup'	=>	'tooltip', 
									'color' 		=>	'text-primary'],
		'btn-copy'					=>	[
									'id'			=>	'btn-copy',						
									'class'			=>	'btn',
									'icon'			=>	'fa fa-copyright',			
									'label'			=>	'コピー',		 
									'data_popup'	=>	'tooltip', 
									'color' 		=>	'text-primary'],
		'btn-cancel-order'			=>	[
									'id'			=>	'btn-cancel-order',						
									'class'			=>	'btn',
									'icon'			=>	'glyphicon glyphicon-floppy-remove',			
									'label'			=>	'受注確定取消',		 
									'data_popup'	=>	'tooltip', 
									'color' 		=>	'text-primary'],
		'btn-cancel-document'		=>	[
									'id'			=>	'btn-cancel-document',						
									'class'			=>	'btn',
									'icon'			=>	'fa fa-save',			
									'label'			=>	'（見積不要）伝票取消',		 
									'data_popup'	=>	'tooltip', 
									'color' 		=>	'text-primary'],
		'btn-approve'		=>	[
									'id'			=>	'btn-approve',						
									'class'			=>	'btn',
									'icon'			=>	'fa fa-thumbs-up',			
									'label'			=>	'承認',		 
									'data_popup'	=>	'tooltip', 
									'color' 		=>	'text-primary'],
		'btn-cancel-approve'		=>	[
									'id'			=>	'btn-cancel-approve',						
									'class'			=>	'btn',
									'icon'			=>	'fa fa-thumbs-down',			
									'label'			=>	'承認取消',		 
									'data_popup'	=>	'tooltip', 
									'color' 		=>	'text-primary'],
		'btn-remove'			=>	[
									'id'			=>	'btn-remove',						
									'class'			=>	'btn',
									'icon'			=>	'fa fa-times',			
									'label'			=>	'失注',		 
									'data_popup'	=>	'tooltip', 
									'color' 		=>	'text-primary'],
		'btn-clear'			=>	[
									'id'			=>	'btn-remove',						
									'class'			=>	'btn',
									'icon'			=>	'fa fa-times',			
									'label'			=>	'クリア',		 
									'data_popup'	=>	'tooltip', 
									'color' 		=>	'text-primary'],
		'btn-cancel-approve-shipment'		=>	[
											'id'			=>	'btn-cancel-approve-shipment',						
											'class'			=>	'btn',
											'icon'			=>	'fa fa-save',			
											'label'			=>	'承認取消',		 
											'data_popup'	=>	'tooltip', 
											'color' 		=>	'text-primary'],
		'btn-issue'							=>	[
											'id'			=>	'btn-issue',						
											'class'			=>	'btn',
											'icon'			=>	'fa fa-print',			
											'label'			=>	'発行',		 
											'data_popup'	=>	'tooltip', 
											'color' 		=>	'text-primary'],
		'btn-upload'						=>	[
											'id'			=> 	'btn-upload',						
											'class'			=>	'btn',
											'icon'			=>	'fa fa-cloud-upload',			
											'label'			=>	'アップロード',		 
											'data_popup'	=>	'tooltip', 
											'color' 		=>	'text-primary'],
		'btn-issue-instruction'			=>  [
											'id'			=>	'btn-issue-instruction',						   
											'class'			=>	'btn',
											'icon'			=>	'fa fa-print',		   
											'label'			=>	'指示書発行',		 
											'data_popup'	=>	'tooltip', 
											'color' 		=>	'text-primary'],
		'btn-reissue-instruction'		=>  [
											'id'			=>	'btn-reissue-instruction',						   
											'class'			=>	'btn',
											'icon'			=>	'fa fa-print',		   
											'label'			=>	'指示書再発行',		 
											'data_popup'	=>	'tooltip', 
											'color' 		=>	'text-primary'],
		'btn-manufacturing-instruction'	=>  [
											'id'			=>	'btn-manufacturing-instruction',						   
											'class'			=>	'btn',
											'icon'			=>	'fa fa-print',		   
											'label'			=>	'製造指示書',		 
											'data_popup'	=>	'tooltip', 
											'color' 		=>	'text-primary'],
		'btn-good-issue-source'			=>  [
											'id'			=>	'btn-good-issue-source',						   
											'class'			=>	'btn',
											'icon'			=>	'fa fa-floppy-o',		   
											'label'			=>	'出庫元作成',		 
											'data_popup'	=>	'tooltip', 
											'color' 		=>	'text-primary'],
		'btn-invoice'					=>	[
											'id'			=>	'btn-invoice',						
											'class'			=>	'btn',
											'icon'			=>	'fa fa-print',			
											'label'			=>	'Invoice',		 
											'data_popup'	=>	'tooltip', 
											'color' 		=>	'text-primary'],
		'btn-delivery-note'				=>	[
											'id'			=>	'btn-delivery-note',						
											'class'			=>	'btn',
											'icon'			=>	'fa fa-print',			
											'label'			=>	'納品書',		 
											'data_popup'	=>	'tooltip', 
											'color' 		=>	'text-primary'],
	);


	/**
	* show button left
	* -----------------------------------------------
	* @author	  :   vuongvt	 - 2016/06/28 - create
	* @param	   :   null
	* @return	  :   null
	* @access	  :   public
	* @see		 :   remark
	*/
	public static function button_left(array $array, $mode = '') {
		$from = '';
		if (Session::has('screen')) {
            $from = Session::get('screen');
        }
		echo '<ul class="nav navbar-nav">';
		foreach ($array as $key => $value) {
			if(array_key_exists($value,self::$button_val))
			{
				if (showButton($mode, $from, $value)) {
					$btn_lang_tootip = 'tooltip.'.self::$button_val[$value]['id'];
					echo '<li class="'.'cl-'.self::$button_val[$value]['id'].'" id="'. self::$button_val[$value]['id'].'">';
					echo '<a tabindex="0" class="btn btn-link">';
					echo '<i class="'. self::$button_val[$value]['icon']. ' ' . self::$button_val[$value]['color']. ' ">'.'</i><span class="' . self::$button_val[$value]['color']. ' ">';
					echo ' ' . self::$button_val[$value]['label'];
					echo '</span></a></li>';
				}
			}
		}
		echo '</ul>';
	}

	/**
	* show button right
	* -----------------------------------------------
	* @author	  :   vuongvt	 - 2016/06/28 - create
	* @updater	 :   vulq - 2016/10/04 
	* @param	   :   null
	* @return	  :   null
	* @access	  :   public
	* @see		 :   remark
	*/
	public static function button_right(array $array) {


		echo '<ul class="nav navbar-nav">';
		foreach ($array as $key => $value) {
			if(array_key_exists($value,self::$button_val))
			{
				$btn_lang_tootip = 'tooltip.'.self::$button_val[$value]['id'];

				echo '<li tabindex="0" class="'.'cl-'.self::$button_val[$value]['id'].'" id="'.self::$button_val[$value]['id'].'" data-original-title="'.self::$button_val[$value]['label'].'" data-popup="'.self::$button_val[$value]['data_popup'].'">';

				if($value=="btn-close"){
					//add link back vulq 2016-10-04
					$url_old = \URL::previous();
					if (strpos($url_old,'maintenance')) {
						$url_old = \Request::url();
					}
					//end
					echo '<a href="javascript:void(0)" link="'.$url_old.'" class="btn btn-link">';
				}
				else{
					echo '<a class="btn btn-link">';
				}

				 echo '<i class="'. self::$button_val[$value]['icon']. ' ' . self::$button_val[$value]['color']. ' ">'.'</i><span class="' . self::$button_val[$value]['color']. ' ">';
				echo ' ' . self::$button_val[$value]['label'];
				echo '</span></a></li>';

			}
		}
		echo '</ul>';

	}

	/**
	 * show button left
	 * -----------------------------------------------
	 * @author	  :   mịnhpt	 - 2017/04/17 - create
	 * @param	   :   null
	 * @return	  :   null
	 * @access	  :   public
	 * @see		 :   remark
	 */
	public static function button_search($inline) {
		if($inline)
		{
			echo '<div class="form-group">';
			echo '<div class="col-md-12 text-right">';
			echo '<button type="button" class="btn btn-primary" id="btn-search"><i class="icon-search4"> 検索 </i></button>';
			echo '</div>';
			echo '</div>';
		}
		else
		{
			echo '<div class="col-md-1 pull-right text-right" >';
			echo '<button type="button" class="btn btn-primary" id="btn-search"><i class="icon-search4"> 検索 </i></button>';
			echo '</div>';
		}

	}

	public  static function button_bottom(array $array)
	{
		foreach ($array as $key => $value) {
			if(array_key_exists($value,self::$button_val))
			{
				echo '<button class="btn btn-primary" id="'.self::$button_val[$value]['id'].'"><i class="'. self::$button_val[$value]['icon']. '"></i>'.' ' . self::$button_val[$value]['label'].'</button>';
			}
		}
	}
	/**
	* show button from Server
	* -----------------------------------------------
	* @author	  :   tuannt - 2017/11/15 - create
	* @param	  :   null
	* @return	  :   null
	* @access	  :   public
	* @see		  :   remark
	*/
	public static function showButtonServer(array $array, $mode = '') {
		$from = '';
		if (Session::has('screen')) {
            $from = Session::get('screen');
        }
		$html = '<ul class="nav navbar-nav">';
		foreach ($array as $key => $value) {
			if(array_key_exists($value,self::$button_val))
			{
				if (showButton($mode, $from, $value)) {
					$btn_lang_tootip = 'tooltip.'.self::$button_val[$value]['id'];
					$html .= '<li class="'.'cl-'.self::$button_val[$value]['id'].'" id="'. self::$button_val[$value]['id'].'">';
					$html .= '<a tabindex="0" class="btn btn-link">';
					$html .= '<i class="'. self::$button_val[$value]['icon']. ' ' . self::$button_val[$value]['color']. ' ">'.'</i><span class="' . self::$button_val[$value]['color']. ' ">';
					$html .= ' ' . self::$button_val[$value]['label'];
					$html .= '</span></a></li>';
				}
			}
		}
		$html .= '</ul>';
		return $html;
	}
}