$(document).ready(function() {
	$('button.clear_cpbx_astribank').bind('click', function() {
		$('table.table_cpbx_astribank').find('input').val('');
	});

	$('button.create_quote').bind('click', function() {
		//TODO: find all inputs and values > 0
		$('ul.sidebar-nav a.calc_link')[0].click();
	});
});
