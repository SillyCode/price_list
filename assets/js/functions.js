$(document).ready(function() {
	$('button.clear_cpbx_astribank').bind('click', function() {
		$('table.table_cpbx_astribank').find('input').val('');
	});
});
