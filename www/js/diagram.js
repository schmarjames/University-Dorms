// Diagram Javascript Module

var diagram = (function() {
	
	var dorm_graph_data,
		table_data,
		student_edit_data,
		show_dorm_graph, show_units_graph, get_residents, get_table_data, initModule;
	
	
	// ************************************************
	// DOM Manipulation Functions
	// ************************************************
	
	// animates the percentage diagram that displays the occupancy percentage per 
	// dormitory
	dormitory_percent = function() {
		var p_data = dorm_graph_data,
			dorm_percent,
			dorm_max,
			dorm_percent_graph = '<ul class="dg_percent_list">';
		$.each(p_data, function(k, v) {
			console.log(v);
			dorm_percent_graph = dorm_percent_graph + 
								'<li class="dg_percent" data-dorm-max="'+v.dorm_max_capacity+'" data-dorm-percent="'+v.capacity_amount+'">' +
								'<span class="progress_dorm">Dorm '+v.dorm_id+'</span>' +
								'<div class="progress_wrap">' +
								'<div class="progress-bar">' +
								'</div>' +
								'</div>' +
								'</li>';
		});
		
		dorm_percent_graph = dorm_percent_graph + "</ul>";
		
		$("#dormitory_percent").append(dorm_percent_graph);
		$("#dormitory_percent").prepend('<h3>Current Occupant Rate</h3>');
		
		$(".progress-bar").each(function() {
			dorm_percent = $(this).closest(".dg_percent").data('dorm-percent');
			dorm_max = $(this).closest(".dg_percent").data('dorm-max');
			console.log(dorm_percent);
			$(this).animate({ height: ''+((dorm_percent/dorm_max)*100)+'%' }, 
			{ duration: 5000,
			  step: function(now, fx) {
				  if(fx.prop == 'height') {
					  $(this).html(Math.round(now * 100) / 100 + '%');
				  }
			  }			
			}
		);
		});
			
	}
	
	// Generate Dorms Diagram
	show_dorm_graph = function() {
		var data = dorm_graph_data;
		var dorm_list = '<ul class="dg_dorm_list">';
		$.each(data, function(key, value) {
			console.log(value.dorm_id);
			dorm_list = dorm_list+ '<li class="dg_dorm_select" data-dorm-num="'+value.dorm_id+'">'+value.dorm_id+'</li>';
		});	
		dorm_list + '</ul>';
		$("#dormitory_graph").prepend('<h3>Choose a dorm</h3>');
		$("#dormitory_graph").append(dorm_list);
	};
		
	// Generate Units Diagram
	show_units_graph = function(e) {
		var selected_dg_dorm = $(this).data('dorm-num'),
			unit_data = dorm_graph_data,
			u_row_count = 0,
			unit_dg_list = '<ul class="dg_unit_list">';
			
			$("#dormitory_graph").find(".dg_unit_list").remove();
			
			$.each(unit_data, function(k, v) {
				if(selected_dg_dorm == v.dorm_id) {
					$.each(v.units, function(unit, u_data) {
						unit = 	'<li>' +
							'<h2>'+unit+'</h2>' +
							'<div class="unit" data-dorm-num="'+selected_dg_dorm+'" data-floor-num="'+u_data.floor_num+'" data-unit-num="'+u_data.unit_id+'">' +
								'<div class="rooms_wrapper">' +
									'<div class="rooms_left">';
					
						for(var rm=1; rm<=u_data.room_amount; rm++) {
							var room_not_aval = '';
						
							if($.inArray(""+rm+"" ,u_data.rooms_used) != -1) {
								room_not_aval = 'room_not_aval';
							}
							unit = unit + '<div class="room '+room_not_aval+'" data-room-num="'+rm+'">'+rm+'</div>';
							if(rm == 2) {
								unit = unit + '</div><div class="rooms_right">';
							}
						}
						unit = unit + '</div>' +
									'</div>' +
									'<div class="unit_access">' +
										'<div class="common">Common</div>' +
										'<div class="kitchen">Kitchen</div>' +
									'</div>' +
								'</div>' +
							'</li>';
										
									
						
						unit_dg_list = unit_dg_list + unit;
						if(u_row_count == 4) {
							u_row_count = 0;
							unit_dg_list = unit_dg_list+'<br>';
						}
					});
					unit_dg_list + '</ul>';
					$("#dormitory_graph").append(unit_dg_list);
					
				}
			});
			
		e.preventDefault();
	}
	
	// Runs An Ajax Request of all of the Students That Currently Live in the Selected Unit
	get_residents = function(e) {
	
		var chosen_unit = $(this);
		
		if(chosen_unit.find(".room").hasClass("room_not_aval")) {
			//store chosen_unit data attributes and store them within an object
			var unit_info = {
					dorm_build_number	: chosen_unit.data("dorm-num"),
					unit_id 			: chosen_unit.data("unit-num"),
					floor_number 		: chosen_unit.data("floor-num")	
			};
			
			// run an ajax get request and pass data object to server
			// the server will use this data to query students that match it, which means that they live in this unit
		  	$.ajax({
			  	type		: 'get',
			  	url			: '../application/process.php',
			  	data		: unit_info,
			  	dataType	: 'json',
			  	success: function(data) {
			  		// pass success data to the display_students function
			  		// so the student_modal module can obtain it
				  	table_data = data;
				  	display_students(table_data);
			  	}
		  	});
		}
		e.preventDefault();
	}
		
	initModule = function(dorm_form_info) {
		
		dorm_graph_data = dorm_form_info;
		
		dormitory_percent();
		
		// Configure and initilize student_modal module
		student_modal.init();
		
		// Initialize Events Handlers
		$("#dormitory_graph").on("click", ".dg_dorm_list .dg_dorm_select", show_units_graph);
		$("#dormitory_graph").on("click", ".dg_unit_list .unit", get_residents);
		
	};
	
	
	return { 
		init : initModule,
		show_dorm_graph : show_dorm_graph,
		table_data : table_data
	};
	
}()); 