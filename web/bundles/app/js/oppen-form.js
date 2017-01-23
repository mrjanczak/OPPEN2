function form_init() {

	form_datepicker();		 
	form_button();
	form_date();
	form_date_copy_link();		
		
	$('.fileinput-button').each(function () {
		var input = $(this).find('input:file').detach();
		$(this)
			.button({icons: {primary: 'ui-icon-circle-arrow-n'}})
			.append(input);
	});


	$('#progressbar').progressbar({value: 0});
	
	$('#fileupload')
		.click(function() {$('#progressbar').progressbar( "option", { value: 0 });} )
		.fileupload({
			progressall: function (e, data) {
				var progress = parseInt(data.loaded / data.total * 100, 10);
				$('#progressbar').progressbar( "option", { value: progress });
		}		
	});	
	
	$("tr[class!='even']:odd").addClass("odd");	
	
	$("div[class~='even_odd']:even").removeClass("odd");	
	$("div[class~='even_odd']:odd").addClass("odd");
	
	$('.toggle_items').change(function() {
		var id = $(this).attr("id");
		var id_attr = '';
		if (typeof id !='undefined') { 
			id_attr = '[id^="'+id+'"]'; }
		
		if ($(this).is(':checked')) {
			$('input[type="checkbox"][class="item"]'+id_attr).not('[disabled]').prop('checked', true);}
		else {
			$('input[type="checkbox"][class="item"]'+id_attr).not('[disabled]').prop('checked', false);}
	});


	$('div[id*="error"]').each(function () {
		$(this).addClass('ui-widget');	
		$(this).html('<div class="ui-state-error ui-corner-all" style="padding: 0px 0.7em">'
			+'<p style="margin:1em 0"><span class="ui-icon ui-icon-alert" style="float: left; margin-right:.3em;"></span>'
			+$(this).html()+'</p></div>');	
	});	
	$('div[id*="highlight"]').each(function () {
		$(this).addClass('ui-widget');	
		$(this).html('<div class="ui-state-highlight ui-corner-all"  style="padding: 0px 0.7em">'
			+'<p><span class="ui-icon ui-icon-info" style="float: left; margin-right:.3em;"></span>'
			+$(this).html()+'</p></div>');	
	});	

	$( document ).tooltip();

	tinymce.init({
		selector: "textarea.tinymce",
		inline: false,
		height: 600,
		plugins: [
			"advlist autolink lists link image charmap print preview anchor pagebreak",
			"searchreplace visualblocks code fullscreen",
			"insertdatetime media table contextmenu paste"
		],
		pagebreak_separator: "<div class='page-break'></div>",
		toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
	 });

}

function form_datepicker() {
	/* Polish initialisation for the jQuery UI date picker plugin. */
	/* Written by Jacek Wysocki (jacek.wysocki@gmail.com). */
	jQuery(function($){
			$.datepicker.regional['pl'] = {
					closeText: 'Zamknij',
					prevText: '&#x3c;Poprzedni',
					nextText: 'Następny&#x3e;',
					currentText: 'Dziś',
					monthNames: ['Styczeń','Luty','Marzec','Kwiecień','Maj','Czerwiec',	'Lipiec','Sierpień','Wrzesień','Październik','Listopad','Grudzień'],
					monthNamesShort: ['Sty','Lu','Mar','Kw','Maj','Cze', 'Lip','Sie','Wrz','Pa','Lis','Gru'],
					dayNames: ['Niedziela','Poniedziałek','Wtorek','Środa','Czwartek','Piątek','Sobota'],
					dayNamesShort: ['Nie','Pn','Wt','Śr','Czw','Pt','So'],
					dayNamesMin: ['N','Pn','Wt','Śr','Cz','Pt','So'],
					weekHeader: 'Tydz',
					dateFormat: 'yy-mm-dd',
					firstDay: 1,
					isRTL: false,
					showMonthAfterYear: false,
					yearSuffix: ''};
			$.datepicker.setDefaults($.datepicker.regional['pl']);
	});		
}

function form_button() {
		//typical buttons
		$( "input [id*='submit']" ).button({icons: {primary: "ui-icon-check"}});
		$( "button[id*='cancel']" ).button({icons: {primary: "ui-icon-arrowreturnthick-1-w"}});
		$( "button[id*='delete']" ).button({icons: {primary: "ui-icon-closethick"}}); 
		$( "button[id*='remove']" ).button({icons: {primary: "ui-icon-closethick"}});
		$( "button[id*='save']" )  .button({icons: {primary: "ui-icon-check"}});
		$( "button[id*='save_return']" ).button({icons: {primary: "ui-icon-check"}});
		//special buttons
		$( "button[id*='select']" ).button({icons: {primary: "ui-icon-circle-check"}}); 
		$( "button[id*='accept']" ).button({icons: {primary: "ui-icon-circle-check"}}); 
		$( "button[id*='set']" ).button({icons: {primary: "ui-icon-circle-check"}}); 
		$( "button[id*='search']" ).button({icons: {primary: "ui-icon-search"}});
		$( "button[id*='generate']" ).button({icons: {primary: "ui-icon-gear"}});
		
		$( "button[id*='send']" ).button({icons: {primary: "ui-icon-mail-closed"}});
		$( "button[id*='upload']" ).button({icons: {primary: "ui-icon-circle-arrow-n"}});
		$( "button[id*='download']" ).button({icons: {primary: "ui-icon-circle-arrow-s"}});
		$( "button[id*='add']" ).button({icons: {primary: "ui-icon-circle-plus"}});
		
		$( "button[id$='Date']" ).button({icons: {primary: "ui-icon-calendar"}});
		$( "button[id*='close']" ).button({icons: {primary: "ui-icon-locked"}});
		$( "button[id*='activate']" ).button({icons: {primary: "ui-icon-flag"}});

		$( "button[id*='print']" ).button({icons: {primary: "ui-icon-print"}});
		
		$('.confirm').each( function() {
			var $button = $(this);
			var title = $(this).find('span.ui-button-text').html();
			var confirm = $(this).data('confirm');
			var $dialog = $('<div id="dialog-confirm" title_="'+title+'"><p><span class="dialog-icon ui-icon ui-icon-alert"></span>' + confirm + '</p>');
			$('#dialogs').html($dialog);
			
			$(this).click(function(e) {
				if(!$dialog.dialog( "isOpen" )) {
					e.preventDefault();	
					$dialog.dialog( "open" )
				}
			});
		
			$dialog.dialog({
			  autoOpen: false,
			  height:160,
			  modal: true,
			  buttons : { 
				  'Tak' : function(){
						$button.click();
						$( this ).dialog( "close" ); },
				  'Anuluj' : function(){
						$( this ).dialog( "close" ); }}
			});
		});

		$('button[class="pager_link"]').button().click(function(e) {
				e.preventDefault();
				$('input[id*="page"]').val($(this).data('page'));
				$('button[id*="search"]').click();
		});
		
		$('.show_as_link').each(function(e) { 
			var button = $(this);
			button.hide();
			var $link = $('<a href="#" class="link">' + button.text() + '</a>');
			button.parent().append($link);
			button.parent().find('a').click( function(e) {
				e.preventDefault();
				button.click();
			});
		});					
}

function form_date() {	
	$( '.date').each(function(e) {
		var date = $(this).val();
		$(this).attr('type','text')
				.datepicker({changeMonth: true, 
							 changeYear: true,
							 yearRange: "-100:+0"})
				.datepicker("option", "dateFormat", "yy-mm-dd")
				.val(date);
	});
	
    $( ".from_date" ).each(function(e) {
		var $to_date = $(this).parents('[class*="_form"]').find('.to_date');
		var date = $(this).val();
		$(this).attr('type','text')
		       .datepicker({changeMonth: true, changeYear: true, onClose: function( from_date ) {
					$to_date.datepicker( "option", "minDate", from_date )
				} })
			   .datepicker("option", "dateFormat", "yy-mm-dd")
			   .datepicker("option", "maxDate", $to_date.val() )
			   .val(date);
	});
	
    $( ".to_date" ).each(function(e) {
		var $from_date = $(this).parents('[class*="_form"]').find('.from_date');
		var date = $(this).val();
		$(this).attr('type','text')
		       .datepicker({ changeMonth: true, changeYear: true, onClose: function( to_date ) {
					$from_date.datepicker( "option", "maxDate", to_date )
				} })
			   .datepicker("option", "dateFormat", "yy-mm-dd")
			   .datepicker("option", "minDate", $from_date.val() )
			   .val(date);	
    });	
}

function form_date_copy_link() {	
	$('.date').not('.date_item').not('.no_copy_link').each( function(e) {
		$copy_link = $('<a href="#">(c)</a>');
		$(this).parent().append($copy_link);
		$(this).next().click( function(e) {
			var val = $(this).prev().val();
			e.preventDefault();
			$('.date').not('.date_item').val(val); 
		});	
	});			
};

// Ajax functions

function ajax_update_Account($obj) {

	$obj.change(function(e) {
		
		var $form = $(this).parents('form');
		
		for (var lev=1;lev<=3;lev++) {
			$form.find('select[id$="FileLev' + lev + '"] option:gt(0)').remove();
			$form.find('label[for$="FileLev' + lev + '"]').text('Brak kartoteki').parents('form-row').slideUp();
		}

		var account_id = $(this).find('option:selected').val();

		 $.ajax({
			type: "POST",
			data: "account_id=" + account_id,
			url: $(this).attr('action').replace(/__account_id__/g, account_id),   
			async: false,
			success: function(data){
				
				console.log( JSON.stringify(data) );
				for (var lev=1;lev<=3;lev++) {
					
					if(data[0][lev-1].length > 1) {
						$form.find('select[id$="FileLev' + lev + '"]').append( data[0][lev-1].join(''));
						$form.find('label[for$="FileLev' + lev + '"]').text(   data[1][lev-1]).parents('form-row').slideDown(); 
					}
				}
			}
		});
	});	

}

function ajax_update_DocCat($obj) {
	
	$obj.change(function(e) {

		var doc_cat_id  = $(this).find('option:selected').val();
		 $.ajax({
			type: "POST",
			url: $(this).attr('action').replace(/__doc_cat_id__/g, doc_cat_id),
			success: function(data){
				
				console.log( JSON.stringify(data) );
				$('#doc_File').parent().slideUp();
				
				$('#doc_File option:gt(0)').remove();
				$('#doc_File').append( data[0].join());
				
				$('label[for="doc_File"]').text(data[1]);
				
				if(data[0].length > 1) {
					$('#doc_File').parent().slideDown(); }
			}
		});
	});	
}

var ajax_prepare_dialog = function(data_html) {
	
	$('#dialogs').html(data_html);
	
	var $dialog = $('#dialog-ajax-form');
	
	$dialog.dialog({
		autoOpen: true,
		modal: true,
		minHeight:250,
		minWidth:800,
		title: $dialog.data('title'),
	}); 
	
	form_date();
	form_button();
	
	// Add all updaters existing in modal dialogs
	ajax_update_Account( $('#bookk_entry_Account') );		

	$dialog.find('.ajax_submit_button').on('click', function(e){
		
		e.preventDefault();
		
		var $button = $(this);
		var $form = $button.parents('form');
		
		var form_data = $form.first().serializeArray();
		form_data.push( {'name':$(this).attr('name')});

		$.ajax({
			type: 'POST',
			url: $(this).parents('form').first().attr('action'),
			data : form_data,
			success: function(data) {
				
				var id    = '#'+$(data.html).attr('id');
				var classAttr = $(data.html).attr('class');
				var list  = $(data.html).data('list');
				
				var $item = $(document).find(id);
				var $list = $(document).find(list);
				
		console.log( JSON.stringify(data) );
		console.log( [$form, $item, $list] );
					
				switch(data.js) {
					case 'REFRESH_FORM' :
						$dialog = ajax_prepare_dialog( data.html);
						break;
						
					case 'APPEND' :
						$list.append( ajax_prepare_item( $(data.html) ));
						$dialog.dialog( 'destroy' );
						break;
						
					case 'REPLACE':	

						if ((classAttr == 'bookk_entry') && ($form.data('side') != $(data.html).data('side'))) 
						{
							$item.remove();
							$list.append( ajax_prepare_item( $(data.html) ));
						} else {
							$item.replaceWith( ajax_prepare_item( $(data.html) ));	
						}					
						$dialog.dialog( 'destroy' );
						break;
						
					case 'REMOVE':
						$item.remove();
						$dialog.dialog( 'destroy' );
						break;	
						
					case 'CANCEL':
						$dialog.dialog( 'destroy' );
						break;														
				}
				$("div[class~='even_odd']:even").removeClass("odd");	
				$("div[class~='even_odd']:odd").addClass("odd");	
			}
		})
	})
	
	return $dialog;
};

var ajax_prepare_item = function($item) {
	
	$("div[class~='even_odd']:even").removeClass("odd");	
	$("div[class~='even_odd']:odd").addClass("odd");	
		
	$item.find('.ajax_init_form' ).on('click', function(e){
		
		e.preventDefault();
		
		$.ajax({
			type        : $(this).attr( 'method' ),
			url         : $(this).attr( 'href' ),
			success     : function(data) {
				
				var $dialog = ajax_prepare_dialog( data.html );
				
				return false;
			}
		})
	});
	
	return $item;
};


// Utilities

function escapeRegExp(string) {
    return string.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
}

function replaceAll(string, find, replace) {
  return string.replace(new RegExp(escapeRegExp(find), 'g'), replace);
}

/**
 * Function : dump()
 * Arguments: The data - array,hash(associative array),object
 *    The level - OPTIONAL
 * Returns  : The textual representation of the array.
 * This function was inspired by the print_r function of PHP.
 * This will accept some data as the argument and return a
 * text that will be a more readable version of the
 * array/hash/object that is given.
 * Docs: http://www.openjs.com/scripts/others/dump_function_php_print_r.php
 */
function dump(arr,level) {
	var dumped_text = "";
	if(!level) level = 0;
	
	//The padding given at the beginning of the line.
	var level_padding = "";
	for(var j=0;j<level+1;j++) level_padding += "    ";
	
	if(typeof(arr) == 'object') { //Array/Hashes/Objects 
		for(var item in arr) {
			var value = arr[item];
			
			if(typeof(value) == 'object') { //If it is an array,
				dumped_text += level_padding + "'" + item + "' ...\n";
				dumped_text += dump(value,level+1);
			} else {
				dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
			}
		}
	} else { //Stings/Chars/Numbers etc.
		dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
	}
	return dumped_text;
}
