!function ($) {
    
    dealWithColumns = function(sidebar_note, sidebar_select, select_columns) {
        
        console.log(select_columns.val());
        
        if (select_columns.val() == '3') {
            sidebar_select.val('1');
            sidebar_select.children('option[value=0]').removeAttr('selected').attr('disabled', 'disabled');
            sidebar_select.children('option[value=1]').removeAttr('disabled').attr('selected', 'selected');
            sidebar_note.html('Sidebar is always enabled for 3 columns');
            sidebar_note.show();
        }
        else if (select_columns.val() == '6' || select_columns.val() == '5') {
            sidebar_select.val('0');
            sidebar_select.children('option[value=1]').removeAttr('selected').attr('disabled', 'disabled');
            sidebar_select.children('option[value=0]').removeAttr('disabled').attr('selected', 'selected'); 
            sidebar_note.html('Sidebar is always disabled for 5 and 6 columns');
            sidebar_note.show();
        }
        else {
            sidebar_select.children('option').removeAttr('disabled');
            sidebar_note.html('');
            sidebar_select.removeAttr('disabled');
        }
    }
     $(function() {
        
        // fonts stuff
        select_heading = $('#idstore_general_generaloptions_gfont_headings');
        preview_heading = $('#row_idstore_general_generaloptions_gfont_headings .gfont-preview');
        select_menu = $('#idstore_general_generaloptions_gfont_menu');
        preview_menu = $('#row_idstore_general_generaloptions_gfont_menu .gfont-preview');
        
        preview_heading.css('font-family', select_heading.val());
        preview_menu.css('font-family', select_menu.val());
        
        select_heading.on('change', function() {
            preview_heading.css('font-family', this.value);
        });
        
        select_menu.on('change', function() {
            preview_menu.css('font-family', this.value);
        });
        
        //columns
        
        sidebar_note = $('#row_idstore_general_product_list_page_layout .note');
        sidebar_select = $('#row_idstore_general_product_list_page_layout .use-sidebar');
        select_columns = $('#idstore_general_product_list_column_count');
        dealWithColumns(sidebar_note, sidebar_select, select_columns);
        sidebar_select.bind('focus', function() {
            el = jQuery(this);
            if(el.hasClass('disabled')) {
                el.blur();
                return;
            }
        });
        select_columns.on('change', function() {dealWithColumns(sidebar_note, sidebar_select, select_columns)});
        
        
    })
}(window.jQuery);

