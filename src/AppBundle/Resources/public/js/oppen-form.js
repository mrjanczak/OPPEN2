function form_init() {

	form_datepicker();		 
	form_button();
	form_date();
	form_date_copy_link();		
	
	//$('select').selectmenu();
	
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

	$('.toggle_items').change(function() {
		var id = $(this).attr("id");
		
		if ($(this).is(':checked')) {
			$('input[type="checkbox"][class="item"][id^="'+id+'"]').not('[disabled]').prop('checked', true);}
		else {
			$('input[type="checkbox"][class="item"][id^="'+id+'"]').not('[disabled]').prop('checked', false);}
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
/*
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
*/
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
					monthNames: ['Styczeń','Luty','Marzec','Kwiecień','Maj','Czerwiec',
					'Lipiec','Sierpień','Wrzesień','Październik','Listopad','Grudzień'],
					monthNamesShort: ['Sty','Lu','Mar','Kw','Maj','Cze',
					'Lip','Sie','Wrz','Pa','Lis','Gru'],
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
		$( "input [id*='submit']" )  .button({icons: {primary: "ui-icon-check"}});
		$( "button[id*='cancel']" ).button({icons: {primary: "ui-icon-arrowreturnthick-1-w"}});
		$( "button[id*='delete']" ).button({icons: {primary: "ui-icon-closethick"}}); 
		$( "button[id*='remove']" ).button({icons: {primary: "ui-icon-closethick"}});
		$( "button[id*='save']" )  .button({icons: {primary: "ui-icon-check"}});
		$( "button[id*='save_return']" )  .button({icons: {primary: "ui-icon-check"}});
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
		
		$('button.confirm').each( function() {
			var $button = $(this);
			var id = $(this).attr('id');
			var title = $(this).find('span.ui-button-text').html();
			var confirm = $(this).data('confirm');
			var $dialog = $('<div id="dialog-'+id+'" title_="'+title+'"><p><span class="dialog-icon ui-icon ui-icon-alert"></span>' + confirm + '</p>');
			$('#dialogs').append($dialog);
			
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

//********************************************
// Prepare form collection functionality
//********************************************
function form_collection($root, d) {
	add_link(   $root,d);
	rem_link(   $root,d);
	form_dialog($root,d);	
	subform_prepare($root,d);
}

function add_link($root,d) {
	
	if(d.hasOwnProperty("add_link_appendto")) {
		var $add_link_appendto = $root.find(d.add_link_appendto).not('[disabled]');
		
		var $add_link = $('<a href="#" class="add_link">' + d.add_link + '</a>');
		$add_link_appendto.append($add_link);
		$add_link_appendto.children('a:last').click( function(e) {
			e.preventDefault();
			var form = $(d.new_form).data('prototype');	
				
			var idx = new Array(0,0);
			for (i = d.lev; i >= 0; i--) {
				idx[i] = $root.data('idx'+i);
				if (i==d.lev) {
					idx[i]++;
					$root.data('idx'+i, idx[i]);
				}	
			}
			
			form = replaceAll(form,"__idx0__",idx[0]);
			if(d.hasOwnProperty('side')) {
				form = replaceAll(form,"__side__",d.side); }
				
			for (j=0; j<=d.replace_names.length-1; j++) {			
				form = replaceAll(form, d.replace_names[j]+"___name__",d.replace_names[j]+"_"+idx[j]);
				form = replaceAll(form, "["+d.replace_names[j]+"][__name__]","["+d.replace_names[j]+"]["+idx[j]+"]"); }

			var $new_form_appendto = $root.find(d.new_form_appendto)
			$new_form_appendto.append(form);
			$new_form = $new_form_appendto.children(':last');
			
			if(d.hasOwnProperty('side')) {
				$new_form.find('input[id$="side"]').val(d.side);
				//alert('side:'+$new_form.find('input[id$="side"]').val());
			}
			form_date();
			
			rem_link($new_form,d);
			form_dialog($new_form,d);
			subform_prepare($new_form,d);
		});
	}
}

function rem_link($root, d) {
	if(d.hasOwnProperty("rem_link")) {
		var $rem_link = $('<a href="#" class="rem_link">' + d.rem_link + '</a>');
		
		var data_attr = '';
		if(d.hasOwnProperty('side')) { data_attr += '[data-side="' + d.side + '"]';}
		
		$root.find(d.rem_link_appendto + data_attr).not('[disabled]').append($rem_link).children('a:last').click( function(e) {
			e.preventDefault();
			$(this).parents(d.parent_toremove).remove();
		});
	}
}

function form_dialog($root, d) {		
	if(d.hasOwnProperty("form_focusin")) {
		
		var data_attr = '';
		if(d.hasOwnProperty('side')) { data_attr += '[data-side="' + d.side + '"]';}
		
		var $coll = $root.find(d.form + data_attr).not('[disabled]').find(d.form_focusin).focusin( function(e) {
			var $form = $(this).parents(d.form);
			var $dialog_form = $(d.dialog_form);
			eval(d.dialog_form.substring(1) + '_init($form, $dialog_form)'); 

			var dialog = $dialog_form.dialog({
				autoOpen: true,
				height: $dialog_form.data("height"),
				width: $dialog_form.data("width"),
				modal: true,
				buttons: {
					"OK": function() { 
					  eval(d.dialog_form.substring(1) + '_ok($form, $dialog_form)'); 
					  dialog.dialog( "destroy" );
					},
					"Anuluj": function() {
					  dialog.dialog( "destroy" );
					}
				} 
			});
		});
	}
}

function subform_prepare($root, d) {
	if (d.hasOwnProperty('subroot')) {
		$coll = $root.find(d.subroot);
		if( $coll.length == 0 ){ $coll = $root; }
		$coll.each( function(idx) {
			for(i=0; i<=d.subdata.length-1; i++) {
				form_collection($( this ), d.subdata[i]);	}
		}); 
	}		
}

function escapeRegExp(string) {
    return string.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
}

function replaceAll(string, find, replace) {
  return string.replace(new RegExp(escapeRegExp(find), 'g'), replace);
}
