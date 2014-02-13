// Student Javascript Modal Modules
var student_modal = ( function() {

	var modal_config = {
			map : Object,
			html: '<div id="modal_overlay"><div id="student_modal"></div></div>'
		},
		edit_data, 
		captured_e_data,
		config_module, initModule;

	generate_modal = function() {
		$(modal_config.html).prependTo("body");	
	};
	
	display_students = function(st_data) {
		var st_table = '<table><tbody>',
			st_head	= '<thead>',
			table_header_titles = [],
			st_head_arr,
			st_links,
			str_head
			edit_data = st_data;
		$(modal_config.html).find("table").remove();
		
		for(var i=0; i<st_data.length; i++) {
			var st_obj = st_data[i];
			
			st_table = st_table + '<tr>';
			for(var st_v in st_obj) {
				if(st_v == "id" || st_v == 'dorm_build_number' || st_v == 'unit_id' || st_v == 'room_number') { continue; }
				if(table_header_titles.indexOf(st_v) == -1) {
					table_header_titles.push(st_v);
				}
				if(st_obj[st_v] == 'dorm_build_number' || st_obj[st_v] == 'unit_id' || st_obj[st_v] == 'room_number') {
					continue;
				}
				st_links =	'<td><a href="'+st_obj["st_id"]+'" class="st_edit">Edit</a></td>' +
							'<td><a href="'+st_obj["st_id"]+'" class="st_delete">Remove</a></td>';
				st_table = st_table + '<td>'+st_obj[st_v]+'</td>';
			}
			st_table = st_table + st_links + '</tr>';
		}
		
		st_table = st_table + '</tbody></table>';
		
		st_head_arr = table_header_titles.map(function(title) {
			return "<th>"+title+"</th>"
		});
		str_head = st_head_arr.toString(table_header_titles);
		str_head = str_head.replace(/,/g, "");
		st_head = st_head + str_head + '</thead>';
		
		$("#modal_overlay")
			.css({
				"width"		: $("body").width(),
				"height"	: $("body").height()
			});
		$("#student_modal").append(st_table);
		$("#student_modal table").prepend(st_head);
		$("#student_modal").append('<div class="close">x</div><a href="#" class="st_csv">Create CSV</a>');
		$("#modal_overlay").fadeIn(250);
		generate_edit_form();
	};
	
	close_modal = function() {
		$("#modal_overlay").fadeOut(250, function() {
			$(this).children().html('');
			edit_data = undefined;
		});
		return false;
	};
	
	reposition_modal = function(e) {
		var window = $(this);
		$("#student_modal").animate({top :  (window.scrollTop()+100) +'px'}, 200);	
	};
	
	generate_edit_form = function() {
		var parent = $("#student_modal"),
			edit_from_html = 	'<form id="edit_form">' +
									'<h3>Edit Students Information</h3>' +
									'<div id="form_update_results"></div>' +
									'<input type="hidden" name="st_id">' +
									'<div id="student_id_group" class="form_group">' +
									'<label>First Name:</label>' +
									'<input type="text" name="first_name">' +
									'</div>' +
									'<div id="student_id_group" class="form_group">' +
									'<label>Last Name:</label>' +
									'<input type="text" name="last_name">' +
									'</div>' +
									'<div id="student_id_group" class="form_group">' +
									'<label>Address</label>' +
									'<input type="text" name="address">' +
									'</div>' +
									'<div id="student_id_group" class="form_group">' +
									'<label>Date of Birth:</label>' +
									'<input type="text" name="dob" id="update_date" value"">' +
									'</div>' +
									'<div id="student_id_group" class="form_group">' +
									'<label>Phone #:</label>' +
									'<input type="text" name="phone_number">' +
									'</div>' +
									'<button type="button">Update</button>'
								'</form>';
			parent.append(edit_from_html);
	};
	
	// Edit event function
	get_edit_data = function() {
		var st_id = $(this).attr("href"),
			update_inputs = $("#edit_form input");
		for(var n=0; n<edit_data.length; n++) {
			var e_obj = edit_data[n];
			for(var e_v in e_obj) {
				if(e_obj[e_v] == st_id) { 
					captured_e_data = e_obj; 
					break; 
				}
			}
		}
		for(var r=0; r<update_inputs.length; r++) {
			for(var c_data in captured_e_data) {
				if($(update_inputs[r]).attr("name") == c_data) {
					$(update_inputs[r]).val(captured_e_data[c_data]);
				}
				
			}
			
		}
		$('#update_date').datepicker({
        	dateFormat: 'yy-mm-dd',
			changeMonth: true,
            changeYear: true,
            yearRange: "-100:",
            minDate: new Date(1979, 1, 1),
            maxDate: new Date
        });
		$("#student_modal table").fadeOut(200);
		$("#edit_form").fadeIn(200);
		
		return false;
	};
	
	update = function(e) {
	
		var update_data = {
			first_name		: $("#edit_form input[name=first_name]").val(),
			last_name		: $("#edit_form input[name=last_name]").val(),
			address			: $("#edit_form input[name=address]").val(),
			dob				: $("#edit_form input[name=dob]").val(),
			phone_number	: $("#edit_form input[name=phone_number]").val(),
			st_id			: $("#edit_form input[name=st_id]").val()
		}
		console.log(update_data);
		//process form data
	  	$.ajax({
		  	type		: 'put',
		  	url			: '../application/process.php',
		  	data		: update_data,
		  	dataType	: 'json',
		  	success: function(data) {
			  	
			  	$("#form_results").empty();
			  	
			  	var update_result = data;
			  	console.log(update_result);
			  	
			  	$.each(update_result, function(i, element) {
			  		if (i == "success") { setTimeout(function(){close_modal();}, 1000)  }
				  		$('<h3></h3>').html(i).appendTo($("#form_update_results"));
				  	
					  	$.each(element, function(j, sub) {
						  	$('<p></p>').addClass('error').html(sub).appendTo("#form_update_results");
					  	});
					  	setTimeout(function() {location.reload();}, 1000);
			  	});
		  	}
	  	});
	  	
		e.preventDefault();
	};
	
	// Delete event function
	remove = function(e) {
		// Grab student id from href attr
		var update_data = { st_id : $(this).attr("href") };
		// pass id to ajax delete request to send it over to php
		$.ajax({
		  	type		: 'delete',
		  	url			: '../application/process.php',
		  	data		: update_data,
		  	dataType	: 'json',
		  	success: function(data) {
			  	
			  	$("#form_results").empty();
			  	
			  	var update_result = data;
			  	console.log(update_result);
			  	
			  	$.each(update_result, function(i, element) {
			  		if (i == "success") { setTimeout(function(){location.reload();}, 1000)  }
			  			$("#student_modal table").fadeOut();
				  		$('<h3></h3>').html(i).appendTo($("#student_modal"));
				  	
					  	$.each(element, function(j, sub) {
						  	$('<p></p>').addClass('error').html(sub).appendTo("#student_modal");
					  	});
			  	});
		  	}
	  	});
	  	e.preventDefault();
	};
	
	// CSV event function
	get_csv_info = function() {
		var csv_obj,
			csv_data = {};
	
		//console.log(edit_data);	
		for(var l=0; l<edit_data.length; l++) {
			csv_obj = edit_data[l];
			csv_data['csv'+l] = edit_data[l];
			console.log(edit_data);
		}
		console.log(csv_data);
		$.ajax({
		  	type		: 'csv',
		  	url			: '../application/process.php',
		  	data		: csv_data,
		  	dataType	: 'json',
		  	success: function(data) {
			  	
			  	$("#form_results").empty();
			  	
			  	var csv_result = data;
			  	console.log(csv_result);
			  	
			  	$.each(csv_result, function(i, element) {
			  		if (i == "success") { setTimeout(function(){location.reload();}, 1000)  }
				  		$('<h3></h3>').html(i).appendTo($("#form_update_results"));
				  	
					  	$.each(element, function(j, sub) {
						  	$('<p></p>').addClass('error').html(sub).appendTo("#form_update_results");
					  	});
			  	});
		  	}
	  	});
		
	};
	
	config_module = function(input_map) {
		modal_config.map = input_map; 
	};
		
	initModule = function() {
		edit_data = undefined;
		captured_e_data = undefined;
		generate_modal();
		
		// initialize click events
		$("#student_modal").on("click", ".close", close_modal);
		$(window).scroll(reposition_modal);
		$("#student_modal").on("click", ".st_edit", get_edit_data);
		$("#student_modal").on("click", ".st_delete", remove);
		$("#student_modal").on("click", ".st_csv", get_csv_info);
		$("#student_modal").on("click", "#edit_form button", update);
	};
	
	return { 
		config				: config_module,
		init				: initModule
	};
	
}());