var URL = "{{ path('oppen_account_files', {'account_id' : '__account_id__'}) }}";			

 $.ajax({
	type: "POST",
	data: "account_id=" + $(this).val(),
	url: URL.replace(/__account_id__/g, account_id),
	success: function(data){
		$('#bookk_entry_FileLev1').append(data[0].join());
		$('#bookk_entry_FileLev2').append(data[1].join());
		$('#bookk_entry_FileLev3').append(data[2].join());
	}
});

$('#bookk_entry_Account').change(function(){
	$('#bookk_entry_FileLev1 option:gt(0)').remove();
	$('#bookk_entry_FileLev2 option:gt(0)').remove();
	$('#bookk_entry_FileLev3 option:gt(0)').remove();

	if($(this).val()){
	
		 $.ajax({
			type: "POST",
			data: "account_id=" + $(this).val(),
			url: URL.replace(/__account_id__/g, $(this).val()),
			success: function(data){
			alert(data.join());
				$('#bookk_entry_FileLev1').append(data[0].join());
				$('#bookk_entry_FileLev2').append(data[1].join());
				$('#bookk_entry_FileLev3').append(data[2].join());
			}
		});
	}
});			
