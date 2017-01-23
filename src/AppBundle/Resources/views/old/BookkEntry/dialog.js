	var Value = $('#BookkEntry_Value');
	var Account = $('#BookkEntry_Account');

	Account.change( function() {
		$.ajax({
			type: "GET",
			data: "data=" + Value.val() + "|" + Account.val(),
			url:"bookk/{{ bookk_id }}/bookk_entry/" + bookk_entry_id + "/edit",
			success: function(msg){
				Dialog.html(msg);
			}
       });
	});	

