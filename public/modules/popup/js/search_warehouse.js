/**
 * APEL Project
 *
 * @copyright    	:    ANS
 * @author        	:   DuyTP - 2017/06/15
 * @author  		:   ANS804 - 2017/01/03 - update
 *
 */
$(document).ready(function () {
	init();
	initEvents();
});

/**
 * init
 *
 * @author  :   DuyTP - 2017/01/03
 * @author  :   ANS804 - 2017/01/03 - update
 * @param
 * @return
 */
function init() {
	try {
		//sort clumn table
		$("#table-popup").tablesorter(); 
	} catch (e) {
		alert('init: ' + e.message);
	}
}
/**
 * init Events
 * @author  :   DuyTP - 2017/06/15
 * @author  :   ANS804 - 2017/01/03 - update
 * @param
 * @return
 */
function initEvents() {
	try {
		//init event search
		$(document).on('click', '#btn-search-popup', function () {
			try {
                search();
            } catch (e) {
                alert('#btn-search-popup: ' + e.message);
            }
		});

		$(document).on('click', '#paginate li button', function() {
            try {
                _PAGE = $(this).data('page');
                search();
            } catch (e) {
                alert('#paginate li button' + e.message);
            }
        });

        //change paging 
        $(document).on('change', '#page-size', function() {
            try {
                if ($('#table-result').find('td.w-popup-nodata').length == 0){      
                    _PAGE_SIZE = ($('.nav-pagination').children('.pagi-fillter').length > 0) ? $('#page-size').val() : 10;
                    _PAGE = 1;
                    search();
                }
            } catch (e) {
                alert('#page-size' + e.message);
            }
        });

		//click line table pi
 		$(document).on('dblclick', '#table-popup tbody tr.tr-tbl', function(){
            try {
                var warehouse_cd = $(this).attr('data-lib-val-cd').trim();
     			var warehouse_nm = $(this).attr('data-lib-val-nm-j').trim();

                parent.$('.popup-warehouse').find('.warehouse_cd').val(warehouse_cd);
     			parent.$('.popup-warehouse').find('.warehouse_nm').text(warehouse_nm);

                parent.$.colorbox.close();
            } catch (e) {
                alert('#table-popup tbody tr' + e.message);
            }
 		});

	} catch (e) {
		alert('initEvents: ' + e.message);
	}
}

/**
 * Search client
 * 
 * @author : ANS804 - 2017/12/25 - create
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
            url         : '/popup/search/warehouse',
            dataType    : 'json',
            data        : data,
			loading		: true,
            success : function(res) {
                $('#warehouse_list').html(res.html);
                _setTabIndex();
            }
        });
    } catch(e) {
        console.log('search: ' + e.message)
    }
}
