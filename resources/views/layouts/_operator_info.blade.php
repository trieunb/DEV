<div class="heading-elements" style="margin-top: -8px;">
	<span class="text-bold">登録者&nbsp;&nbsp;</span>
	<span id="DSP_cre_user_cd">{{$header['cre_user_cd'] or ''}}</span>&nbsp;&nbsp;{{ (isset($header['cre_user_nm']) && $header['cre_user_nm']!='') ? ':'.$header['cre_user_nm'] : '' }}
	<span class="text-bold">登録日&nbsp;&nbsp;</span>
	<span id="DSP_cre_datetime">{{$header['cre_datetime'] or ''}}</span>&nbsp;&nbsp;&nbsp;&nbsp;
	<span class="text-bold">更新者&nbsp;&nbsp;</span>
	<span id="DSP_upd_user_cd">{{$header['upd_user_cd'] or ''}}</span>&nbsp;&nbsp;{{ (isset($header['upd_user_nm']) && $header['upd_user_nm']!='') ? ':'.$header['upd_user_nm'] : '' }}
	<span class="text-bold">更新日&nbsp;&nbsp;</span>
	<span id="DSP_upd_datetime">{{$header['upd_datetime'] or ''}}</span>
</div>