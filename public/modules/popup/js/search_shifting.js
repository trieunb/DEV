/**
 * ****************************************************************************
 * COMPANY
 *
 * 処理概要     :   
 * 作成日      :   2017/04/06
 * 作成者      :   DaoNX
 *
 * 更新日      :
 * 更新者      :
 * 更新内容     :
 *
 * @package     :   SHIFTING
 * @copyright   :   Copyright (c) ANS
 * @version     :   1.0.0
 * ****************************************************************************
 */
$(document).ready(function () {
    initCombobox();
    initEvents();
});

function initCombobox() {
    var name = 'JP';    
    //_getComboboxData(name, 'move_status_div');
}

/**
 * init Events
 * @author  :   DaoNX - 2017/04/06 - create
 * @param
 * @return
 */
function initEvents() {
    try {
        //sort clumn table
        $("#table-shifting").tablesorter({
            headers: { 
                0: { 
                    sorter: false 
                }
            } 
        });

        //init event check all for checkbox
        checkAll('check-all');

        //click line table pi
        $(document).on('dblclick', '#table-shifting tbody tr.tr-table', function(){
            var shifting_id = $(this).find('td.DSP_move_no').text().trim();
            parent.$('.popup-shifting').find('.TXT_move_no').val(shifting_id);

            parent.$('.popup-shifting').find('.TXT_move_no').trigger('change');
            parent.$.colorbox.close();
        });

        // button search
        $(document).on('click', '#btn-search-popup', function() {
            try {
                if (_checkDateFromTo('date-estimate') && _checkDateFromTo('date-from-to') ) {
                    if(!_isBackScreen){
                        _PAGE = 1;
                    }
                    search();
                }
            } catch (e) {
                alert('#btn-search: ' + e.message);
            }
        });

        // Change 製造指示書番号
        $(document).on('change', '.TXT_manufacture_no', function() {
            try {
                var _this           = $(this);
                var manufacture_no  = $(this).val().trim();
                referManufacture(manufacture_no, _this);
            } catch (e) {
                console.log('change .TXT_manufacture_no: ' + e.message);
            }
        });

        //change in warehouse div  
        $(document).on('change', '.TXT_out_warehouse_div', function() {
            try {
                var _this         = $(this);
                var warehouse_div = $(this).val().trim();
                _referWarehouse(warehouse_div, _this, '', true);      
            } catch (e) {
                console.log('change .TXT_out_warehouse_div: ' + e.message);
            }
        });

        //change in warehouse div  
        $(document).on('change', '.TXT_in_warehouse_div', function() {
            try {
                var _this         = $(this);
                var warehouse_div = $(this).val().trim();
                _referWarehouse(warehouse_div, _this, '', true);      
            } catch (e) {
                console.log('change .TXT_in_warehouse_div: ' + e.message);
            }
        });

        //change paging 
        $(document).on('change', '#page-size', function() {
            try {
                _PAGE_SIZE = ($('.nav-pagination').children('.pagi-fillter').length > 0) ? $('#page-size').val() : 10
                _PAGE = 1;
                search();
            } catch (e) {
                alert('#page-size: ' + e.message);
            }
        });

        //click paging
        $(document).on('click', '#paginate li button', function() {
            _PAGE = $(this).data('page');
            search();
        });
    } catch (e) {
        alert('initEvents: ' + e.message);
    }
}
/**
 * search shifting search list detail
 * 
 * @author : ANS804 - 2018/04/06 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function search() {
    try {
        var data = {
            item_cd                                 : $.trim($('#item_cd').val()),
            manufacture_no                          : $.trim($('.TXT_manufacture_no').val()),
            move_no                                 : $.trim($('#move_no').val()),
            register_date_from                      : $.trim($('.TXT_register_date_from').val()),
            register_date_to                        : $.trim($('.TXT_register_date_to').val()),
            desire_date_move_from                   : $.trim($('.TXT_desire_date_move_from').val()),
            desire_date_move_to                     : $.trim($('.TXT_desire_date_move_to').val()),
            out_warehouse_div                       : $.trim($('.TXT_out_warehouse_div').val()),
            in_warehouse_div                        : $.trim($('.TXT_in_warehouse_div').val()),
            CMB_move_status_div                     : $.trim($('.CMB_move_status_div').val()),
            page                                    : _PAGE,
            page_size                               : _PAGE_SIZE
        };

        $.ajax({
            type        : 'POST',
            url         : '/popup/search/shifting',
            dataType    : 'json',
            data        : data,
            loading     : true,
            success : function(res) {
                // Do something here
                $('#div-shifting-list').html(res.html);
                $("#table-shifting").tablesorter({
                    headers: { 
                        0: { 
                            sorter: false 
                        }
                    }
                });

                // run again tooltip
                $(function () {
                  $('[data-toggle="tooltip"]').tooltip()
                });

                _setTabIndex();
            }
        });
    } catch(e) {
        alert('search: ' + e.message)
    }
}

/**
 *refer data manufacture
 * 
 * @author : ANS804 - 2017/12/26 - create
 * @params : 
 * @return : null
 * @access : public
 * @see :
 */
function referManufacture(data, element) {
    try {
        $.ajax({
            type        : 'GET',
            url         : '/common/refer/refer-manufacture',
            dataType    : 'json',
            data        : {manufacture_no : data},
            success: function(res) {
                if (res.response) {
                    //remove error
                    element.parents('.popup').find('.manufacturinginstruction_cd').val(res.data.manufacture_no);
                }

                //element.parents('.popup').find('.manufacture_cd').focus();
            }
        });
        
    } catch(e) {
        console.log('referManufacture: ' + e.message)
    }
}