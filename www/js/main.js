// Parent Modules
var parent_mod = ( function() {
	
	// Dorm manage config contains the init.php url and home page url
	var dorm_manage_config = {
			query_url : 'http://localhost:8888//university_dorms/application/init.php',
			home_loc : 'http://localhost:8888/university_dorms/www/'
		},
		get_dormitories;
	
	// extend ajax object used to request dormitory and unit objects
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
	
	// ************************************************
	// Event Handler Functions
	// ************************************************

	// Displays the student add / edit form	
	show_student_form = function() {
		$("#dormitory_percent").fadeOut(100);
		$("#dormitory_graph").fadeOut(100, function(){
			$(this).html('');
			
		});
		$("#student_form_wrapper").fadeIn(250);
		return false;	
	};
	
	// Display the dormitory diagram
	view_dorm_graph = function(e) {
		$("#dormitory_percent").fadeOut(100);
		$("#student_form_wrapper").fadeOut(100);
		if ($("#dormitory_graph").css('display') == 'none') {
			$("#dormitory_graph").fadeIn(250);
			$("#dormitory_graph .next_units").attr("disabled", "disable");
			diagram.show_dorm_graph();
		}
		
		e.preventDefault();
	};
	
	initModule = function() {
		
		var results = $.get_dormitories(dorm_manage_config.query_url);
		
		// initialize modules
		submission_form.init(results);
		diagram.init(results);
		
		// initialize click events
		$("#nav a.add_student").click(show_student_form);
		$("#nav a.view_residence").click(view_dorm_graph);
	};
	
	return { init : initModule };
	
}());