/**
 * ****************************************************************************
 * APEL Project
 *
 * 処理概要     :   
 * 作成日      :   2017/12/19
 * 作成者      :   Daonx
 *
 * 更新日      :
 * 更新者      :
 * 更新内容     :
 *
 * @package     :   Suppliers
 * @copyright   :   Copyright (c) ANS
 * @version     :   1.0.0
 * ****************************************************************************
 */
var btnid   = $('#btnid').attr('btnid').trim();
var istable = $('#istable').attr('istable').trim();
var multi   = $('#multi').attr('multi').trim();

$(document).ready(function () {
	initEvents();
});
/**
 * init Events
 * @author  :   DuyTP - 2017/06/15
 * @modify  :   ANS804 - 2017/11/20
 * @param
 * @return
 */
function initEvents() {
	try {
		$("#table-popup").tablesorter();

		// button search
		$(document).on('click', '#btn-search-popup', function () {
			try {
                search();
            } catch (e) {
                console.log('#btn-search-popup: ' + e.message);
            }
		});

		$(document).on('click', '#paginate li button', function() {
            _PAGE = $(this).data('page');
            search();
        });

        //change paging 
        $(document).on('change', '#page-size', function() {
            try {
                if ($('#table-result').find('td.w-popup-nodata').length == 0){      
                    _PAGE_SIZE = ($('.nav-pagination').children('.pagi-fillter').length > 0) ? $('#page-size').val() : 10;
                    _PAGE      = 1;
                    search();
                }
            } catch (e) {
                alert('#page-size' + e.message);
            }
        });

		// refer data from popup
 		$(document).on('dblclick', '#table-popup tbody tr.tr-table', function(){
            try {
                var city_cd = $(this).find('.city_cd').text().trim();
                var city_nm = $(this).find('.city_nm').text().trim();

     			parent.$('.popup-city').find('.city_cd').val(city_cd);
                parent.$('.popup-city').find('.city_nm').text(city_nm);

                parent.$('.popup-city').find('.city_cd').trigger('change');

                parent.$.colorbox.close();
            } catch (e) {
                alert('refer data from popup: ' + e.message);
            }
 		});

	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}

/**
 * Search client
 * 
 * @author : ANS804 - 2017/12/20 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function search() {
    try{
        var data = {
            lib_cd    	: $('#lib_cd').val().trim(),
            lib_nm   	: $('#lib_nm').val().trim(),
            page      	: _PAGE,
            page_size 	: _PAGE_SIZE
        };
        $.ajax({
            type        : 'POST',
            url         : '/popup/search/city',
            dataType    : 'json',
            data        : data,
			loading		: true,
            success : function(res) {
                $('#city_list').html(res.html);
                _setTabIndex();
            }
        });
    } catch(e) {
        console.log('search: ' + e.message)
    }
}