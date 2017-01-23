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
		$add_link.click( function(e) {
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
		
		$add_link_appendto.append($add_link);
	}
}

function rem_link($root, d) {
	if(d.hasOwnProperty("rem_link")) {
		var $rem_link = $('<a href="#" class="rem_link">' + d.rem_link + '</a>');
		$rem_link.click( function(e) {
			e.preventDefault();
			$(this).parents(d.parent_toremove).remove();
		});
		
		var data_attr = '';
		if(d.hasOwnProperty('side')) { data_attr += '[data-side="' + d.side + '"]';}
		
		$root.find(d.rem_link_appendto + data_attr).not('[disabled]').append($rem_link);
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
