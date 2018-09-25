<div class="tab-pane" id="tab_06">
	<!-- 57 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">請求先名</label>
		<div class="col-md-3">
			<input type="text" id="TXT_billing_nm" class="form-control ime-active" maxlength="120">
		</div>
	</div>
	
	<!-- 58 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">担当者名</label>
		<div class="col-md-3">
			<input type="text" id="TXT_billing_staff_nm" class="form-control ime-active" maxlength="50">
		</div>
	</div>
	
	<!-- 59 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">郵便番号</label>
		<div class="col-md-1">
			<input type="text" id="TXT_billing_zip" class="form-control" maxlength="8">
		</div>
	</div>
	
	<!-- 60 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">住所1</label>
		<div class="col-md-5">
			<input type="text" id="TXT_billing_adr1" class="form-control ime-active" maxlength="120">
		</div>
	</div>
	
	<!-- 61 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">住所2</label>
		<div class="col-md-5">
			<input type="text" id="TXT_billing_adr2" class="form-control ime-active" maxlength="120">
		</div>
	</div>
	
	<!-- 62 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">都市コード</label>
		<div class="col-md-1">
			@include('popup.searchcity', 
					  array('key'=>'',
					  	    'disabled_ime' => 'disabled-ime',
					  	    'id' => 'TXT_billing_city_div'))
		</div>
	</div>
	
	<!-- 63 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">国コード</label>
		<div class="col-md-1">
			@include('popup.searchcountry', 
				      array('key'=>'', 
				            'disabled_ime' => 'disabled-ime',
				            'id' => 'TXT_billing_country_div'))
		</div>
	</div>

	<div class="form-group">
		<!-- 64 -->
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">電話番号</label>
		<div class="col-md-2">
			<input type="text" id="TXT_billing_tel" class="form-control ime-active fax" maxlength="20">
		</div>
		<!-- 65 -->
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">FAX 番号</label>
		<div class="col-md-2">
			<input type="text" id="TXT_billing_fax" class="form-control ime-active fax" maxlength="20">
		</div>
	</div>
	
	<!-- 66 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">E-mail</label>
		<div class="col-md-3">
			<input type="text" id="TXT_billing_mail" class="form-control ime-active email" maxlength="50">
		</div>
	</div>
</div>