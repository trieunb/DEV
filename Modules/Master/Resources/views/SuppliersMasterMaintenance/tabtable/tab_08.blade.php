<div class="tab-pane" id="tab_08">

	<!-- 77 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">荷受人名</label>
		<div class="col-md-3">
			<input type="text" id="TXT_consignee_nm" class="form-control ime-active" maxlength="120">
		</div>
	</div>

	<!-- 78 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">担当者名</label>
		<div class="col-md-3">
			<input type="text" id="TXT_consignee_staff_nm" class="form-control ime-active" maxlength="50">
		</div>
	</div>

	<!-- 79 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">郵便番号</label>
		<div class="col-md-1">
			<input type="text" id="TXT_consignee_zip" class="form-control ime-active" maxlength="8">
		</div>
	</div>

	<!-- 80 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">住所1</label>
		<div class="col-md-5">
			<input type="text" id="TXT_consignee_adr1" class="form-control ime-active" maxlength="120">
		</div>
	</div>

	<!-- 81 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">住所2</label>
		<div class="col-md-5">
			<input type="text" id="TXT_consignee_adr2" class="form-control" maxlength="120">
		</div>
	</div>

	<!-- 82 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">都市コード</label>
		<div class="col-md-1">
			@include('popup.searchcity', 
					  array('key'=>'', 
					  	    'disabled_ime' => 'disabled-ime',
					  	    'id' => 'TXT_consignee_city_div'))
		</div>
	</div>

	<!-- 83 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">国コード</label>
		<div class="col-md-1">
			@include('popup.searchcountry', 
					  array('key'=>'', 
					  	    'disabled_ime' => 'disabled-ime',
					  	    'id' => 'TXT_consignee_country_div'))
		</div>
	</div>
	
	<div class="form-group">
		<!-- 84 -->
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">電話番号</label>
		<div class="col-md-2">
			<input type="text" id="TXT_consignee_tel" class="form-control ime-active fax" maxlength="20">
		</div>
		<!-- 85 -->
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">FAX 番号</label>
		<div class="col-md-2">
			<input type="text" id="TXT_consignee_fax" class="form-control ime-active fax" maxlength="20">
		</div>
	</div>

	<!-- 86 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">E-mail</label>
		<div class="col-md-3">
			<input type="text" id="TXT_consignee_mail" class="form-control ime-active email" maxlength="50">
		</div>
	</div>
</div>