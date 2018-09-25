<div class="tab-pane" id="tab_07">

	<!-- 67 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">納品先名</label>
		<div class="col-md-3">
			<input type="text" id="TXT_delivery_nm" class="form-control ime-active" maxlength="120">
		</div>
	</div>

	<!-- 68 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">担当者名</label>
		<div class="col-md-3">
			<input type="text" id="TXT_delivery_staff_nm" class="form-control ime-active" maxlength="50">
		</div>
	</div>

	<!-- 69 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">郵便番号</label>
		<div class="col-md-1">
			<input type="text" id="TXT_delivery_zip" class="form-control ime-active" maxlength="8">
		</div>
	</div>

	<!-- 70 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">住所1</label>
		<div class="col-md-5">
			<input type="text" id="TXT_delivery_adr1" class="form-control ime-active" maxlength="120">
		</div>
	</div>

	<!-- 71 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">住所2</label>
		<div class="col-md-5">
			<input type="text" id="TXT_delivery_adr2" class="form-control ime-active" maxlength="120">
		</div>
	</div>

	<!-- 72 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">都市コード</label>
		<div class="col-md-1">
			@include('popup.searchcity', 
					  array('key'=>'', 
					        'disabled_ime' => 'disabled-ime',
					        'id' => "TXT_delivery_city_div"))
		</div>
	</div>

	<!-- 73 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">国コード</label>
		<div class="col-md-1">
			@include('popup.searchcountry', 
					  array('key'=>'', 
					        'disabled_ime' => 'disabled-ime',
					        'id' => "TXT_delivery_country_div"))
		</div>
	</div>
	
	<div class="form-group">
		<!-- 74 -->
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">電話番号</label>
		<div class="col-md-2">
			<input type="text" id="TXT_delivery_tel" class="form-control ime-active fax" maxlength="20">
		</div>
		<!-- 75 -->
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">FAX 番号</label>
		<div class="col-md-2">
			<input type="text" id="TXT_delivery_fax" class="form-control ime-active fax" maxlength="20">
		</div>
	</div>

	<!-- 76 -->
	<div class="form-group">
		<label class="col-md-1 col-md-1-cus control-label text-right text-bold">E-mail</label>
		<div class="col-md-3">
			<input type="text" id="TXT_delivery_mail" class="form-control ime-active email" maxlength="50">
		</div>
	</div>
</div>