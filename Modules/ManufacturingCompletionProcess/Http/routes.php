<?php

Route::group(['middleware' => ['web', 'authUserDefine'], 'prefix' => 'manufacturing-completion-process', 'namespace' => 'Modules\ManufacturingCompletionProcess\Http\Controllers'], function()
{
    Route::get('/', 						'ManufacturingCompletionProcessController@getIndex')->name(isset(FUNCTION_CD['manufacturing-completion-process']['view']) ? FUNCTION_CD['manufacturing-completion-process']['view'] : '');
    Route::post('/save', 					'ManufacturingCompletionProcessController@postSave')->name(isset(FUNCTION_CD['manufacturing-completion-process']['save']) ? FUNCTION_CD['manufacturing-completion-process']['save'] : '');
    Route::post('/refer-manufacture-no', 	'ManufacturingCompletionProcessController@postRefermanufactureNo');
});
