<div class="tab-pane active" id="tab_00">
	<!-- 14 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">取引先略称</label>
		<div class="col-md-3">
			<input type="text" id="TXT_client_ab" class="form-control ime-active" maxlength="60">
		</div>
	</div>

	<!-- 15 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">担当者名</label>
		<div class="col-md-3">
			<input type="text" id="TXT_contact_nm" class="form-control ime-active" maxlength="50">
		</div>
	</div>

	<!-- 16 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">郵便番号</label>
		<div class="col-md-1">
			<input type="text" id="TXT_client_zip" class="form-control disable-ime" maxlength="8">
		</div>
	</div>

	<!-- 17 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">住所1</label>
		<div class="col-md-5">
			<input type="text" id="TXT_client_adr1" class="form-control ime-active" maxlength="120">
		</div>
	</div>

	<!-- 18 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">住所２</label>
		<div class="col-md-5">
			<input type="text" id="TXT_client_adr2" class="form-control ime-active" maxlength="120">
		</div>
	</div>

	<!-- 19 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">都市コード</label>
		<div class="col-md-1">
			@includeIf('popup.searchcity', 
						array(	'is_required'  	=> true,
								'disabled_ime' 	=> 'disabled-ime',
								'id' 			=> 'TXT_city_cd'))
		</div>
	</div>

	<!-- 20 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">国コード</label>
		<div class="col-md-1">
			@includeIf('popup.searchcountry', 
						array(	'is_required'  => true,
								'disabled_ime' => 'disabled-ime',
							  	'id' 			 => 'TXT_country_cd'))
		</div>
	</div>

	<!-- 21 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">港・都市コード</label>
		<div class="col-md-1">
			@includeIf('popup.searchcity', 
						array('is_required'  => true,
							  'disabled_ime' => 'disabled-ime',
							  'id' 			 => 'TXT_post_city_cd')
					)
		</div>
	</div>

	<!-- 22 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold required">港・国コード</label>
		<div class="col-md-1">
			@includeIf('popup.searchcountry',
						array('is_required'  => true,
							  'disabled_ime' => 'disabled-ime',
							  'id' 			 => 'TXT_post_country_cd')
					)
		</div>
	</div>

	<div class="form-group">
		<!-- 23 -->
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">電話番号</label>
		<div class="col-md-2">
			<input type="text" id="TXT_client_tel" class="form-control disabled-ime fax" maxlength="20">
		</div>
		<!-- 24 -->
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">FAX 番号</label>
		<div class="col-md-2"> 
			<input type="text" id="TXT_fax_no" class="form-control disabled-ime fax" maxlength="20">
		</div>
	</div>

	<div class="form-group">
		<!-- 25 -->
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">E-mail 1</label>
		<div class="col-md-2">
			<input type="text" id="TXT_e_mail1" class="form-control email" maxlength="50">
		</div>
		<!-- 26 -->
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">E-mail 2</label>
		<div class="col-md-2">
			<input type="text" id="TXT_e_mail2" class="form-control email" maxlength="50">
		</div>
		<!-- 27 -->
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">E-mail 3</label>
		<div class="col-md-2">
			<input type="text" id="TXT_e_mail3" class="form-control email" maxlength="50">
		</div>
	</div>
	
	<!-- 28 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">URL</label>
		<div class="col-md-3">
			<input type="text" id="TXT_client_url" class="form-control" maxlength="255">
		</div>
	</div>
	
	<!-- 28 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">親取引先コード</label>
		<div class="col-md-1">
			@include('popup.searchsuppliers', 
					  array('val'=>'',
					  		'id' => 'TXT_parent_client_cd'))
		</div>
	</div>
	
	<!-- 29 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">倉庫コード</label>
		<div class="col-md-1">
			@include('popup.searchwarehouse', 
					  array('val' 		=> '', 
					  		'maxlength' => 6,
					  		'id' 		=> 'TXT_warehouse_cd'))
		</div>
	</div>
	
	<!-- 30 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">備考</label>
		<div class="col-md-10">
			<textarea class="form-control ime-active disable-resize" id="TXA_remarks" rows="2" maxlength="200"></textarea>
			<!-- <input type="text" id="" class="form-control"> -->
		</div>
	</div>
</div>