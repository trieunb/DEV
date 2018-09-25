/**
 * ****************************************************************************
 * APEL Project
 *
 * 処理概要     :   
 * 作成日      :   2017/12/07
 * 作成者      :   Trieunb
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
 $(document).ready(function () {
    initEvents();
    if(parent.isCheckedCustomerPopup == 'true'){
        $('#CHK_customer').prop('checked', true);
    }
    if(parent.isCheckedSuppliersPopup == 'true'){
        $('#CHK_suppliers').prop('checked', true);
    }
    if(parent.isCheckedOutsourcerPopup == 'true'){
        $('#CHK_outsourcer').prop('checked', true);
    }
});

/**
 * init Events
 * @author  :   DuyTP - 2017/06/09 - create
 * @author  :   ANS 804 - 2017/12/19 - update
 * @param
 * @return
 */
function initEvents() {
    try {
        //sort column table
        $("#table-suppliers-master").tablesorter();

        // refer data from table-suppliers-master
        $(document).on('dblclick', '#table-suppliers-master tbody tr.tr-table', function(){
            try {
                var client_cd = $(this).find('.client_cd').text().trim();
                var client_nm = $(this).find('.client_nm').text().trim();

                parent.$('.popup-suppliers').find('.suppliers_cd').val(client_cd);
                parent.$('.popup-suppliers').find('.suppliers_nm').text(client_nm);
                parent.$('.popup-suppliers').find('.suppliers_cd').trigger('change');

                parent.$.colorbox.close();
            } catch (e) {
                console.log('refer data from table-suppliers-master: ' + e.message);
            }
        });

        // button search
        $(document).on('click', '#btn-search-popup', function() {
            try {
                _PAGE = 1;
                search();
            } catch (e) {
                console.log('#btn-search-popup ' + e.message);
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
        // Change 取引先コード
        $(document).on('change', '.TXT_client_cd', function() {
            try {
                _getClientName($(this).val(), $(this), '', true);
            } catch (e) {
                console.log('change: .TXT_client_cd ' + e.message);
            }
        });

        //change 国コード  
        $(document).on('change', '.country_cd', function() {
            $(this).parent().addClass('popup-country');
            _referCountry($(this).val().trim(), '', $(this), '', true);
        });
    } catch (e) {
        alert('initEvents: ' + e.message);
    }
}

/**
 * Search client
 * 
 * @author : ANS804 - 2017/12/19 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function search() {
    try{
        var data = {
            client_cd           : $('#TXT_client_cd').val().trim(),
            client_nm           : $('#TXT_client_nm').val().trim(),
            parent_client_cd    : $('#TXT_parent_client_cd').val().trim(),
            client_cd_from      : $('#TXT_client_cd_from').val().trim(),
            client_cd_to        : $('#TXT_client_cd_to').val().trim(),
            client_country_div  : $('#TXT_country_cd').val().trim(),
            cust_div            : $('#CHK_customer').prop('checked') ? 1 : 0,
            supplier_div        : $('#CHK_suppliers').prop('checked') ? 1 : 0,
            outsourcer_div      : $('#CHK_outsourcer').prop('checked') ? 1 : 0,
            page                : _PAGE,
            page_size           : _PAGE_SIZE
        };
        $.ajax({
            type        : 'POST',
            url         : '/popup/search/suppliers',
            dataType    : 'json',
            data        : data,
			loading		: true,
            success : function(res) {
                $('#client_list').html(res.html);

                // run again tooltip
                $(function () {
                  $('[data-toggle="tooltip"]').tooltip()
                });

                // run again stickytable
                $( document ).trigger( "stickyTable" );

                _setTabIndex();
            }
        });
    } catch(e) {
        console.log('search: ' + e.message)
    }
}