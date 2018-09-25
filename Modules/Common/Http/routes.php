<?php

Route::group(['middleware' => 'web', 'prefix' => 'common', 'namespace' => 'Modules\Common\Http\Controllers'], function()
{
    Route::get('/link/linksession', 'CommonController@postLinkSession');
    Route::get('/combobox/get-combobox-data', 'ComboboxController@getComboboxData');

    Route::post('/message/language-message', 'MessageController@postLanguageMessage');
    
   	Route::post('/refer/product-cd', 'ReferController@postReferProductCd');
   	Route::post('/refer/client-cd', 'ReferController@postReferClientCd');
   	Route::get('/refer/get-tax-rate', 'ReferController@getTaxRate');
    Route::get('/refer/refer-user', 'ReferController@referUser');
    Route::get('/refer/refer-city', 'ReferController@referCity');
    Route::get('/refer/refer-country', 'ReferController@referCountry');
    Route::get('/refer/refer-pi-accept', 'ReferController@referPiAccept');
    Route::get('/refer/refer-item', 'ReferController@referItem');
    Route::get('/refer/refer-warehouse', 'ReferController@referWarehouse');
    Route::get('/refer/refer-manufacture', 'ReferController@referManufacture');
});
