// Initialize Modules
var parent_mod = ( function() {

	var dorm_manage_config = {
			query_url : 'http://localhost:8888//university_dorms/application/init.php'
		},
		get_dormitories;

	$.extend({
		get_dormitories : function(url) {
			var result = null;
			$.ajax({
				url: url,
				type: 'get',
				dataType: 'json',
				async: false,
				success: function(data) {
					result = data;
				}
			});
			return result;
		}
	});
	
	gen_graph = function() {
		$(this).html('<div>'+JSON.stringify(dorm_data)+'</div>');	
	};
	
	initModule = function() {
		
		var results = $.get_dormitories(dorm_manage_config.query_url);
		
		submission_form.init(results);
		//diagram_generator.init(results);
	};
	
	return { init : initModule };
	
}());