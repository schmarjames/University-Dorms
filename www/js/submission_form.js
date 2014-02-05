// Submission Form Javascript Module

var submission_form = (function() {

	var student_fields = $("#student_form_wrapper form input"),
		dorm_fields = $("#student_form_wrapper .dorm_select"),
		first_sec_finish = false, 
		form_data = {},
		dorm_info, generate_dorm_select, generate_unit_select, check_first_sec, chosen_room, highlight_room, submit, initModule;
	
	// Generate Dorm Select Funcion
	generate_dorm_select = function(data) {
		var dorm_list = '<ul class="second_sec">',
			dorm_active = 'active';
		$.each(data, function(key, value) {
			console.log(value.dorm_id);
			if(value.capacity_amount == value.dorm_max_capacity) {
				dorm_active = "";
				key = "";
			}
			dorm_list = dorm_list+ '<li class="dorm_select ' + dorm_active + '" data-dorm-num="'+value.dorm_id+'">'+value.dorm_id+'</li>';
		});	
		dorm_list + '</ul>';
		$("#available_dorms").html(dorm_list);
	};
	
	// Units Generate Event Function
	generate_unit_select = function(chosen, data) {
		var selected_dorm = chosen.data('dorm-num'),
			unit,
			unit_list = '<ul class="third_sec">',
			u_row_count = 0,
			chosen_gender = $("#student_form_wrapper form input[name=gender]:checked").val();
		
		$.each(data, function(key, value) {
			
			if(selected_dorm == value.dorm_id) {
				
				$.each(value.units, function(unit, u_data) {
					console.log(u_data.rooms_used);
					var not_aval = '';
					if(u_data.room_amount == u_data.rooms_used.length) {
						console.log(u_data.room_amount + ' - ' + u_data.rooms_used.length);
						not_aval = 'not_aval';
					}
					if(u_data.unit_gender != false) {
						if(u_data.unit_gender.unit_gender != chosen_gender) {
							console.log(u_data.unit_gender.unit_gender + ' - ' + chosen_gender);
							not_aval = 'not_aval';
						}
					}
					unit = 	'<li>' +
							'<h2>'+unit+'</h2>' +
							'<div class="unit '+not_aval+'" data-floor-num="'+u_data.floor_num+'" data-unit-num="'+u_data.unit_id+'">' +
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
										
									
						
					unit_list = unit_list + unit;
					u_row_count++;
					if(u_row_count == 4) {
						u_row_count = 0;
						unit_list = unit_list+'<br>';
					}
				});
				unit_list + '</ul>';
				$("#avialable_units").html(unit_list);
				
			}
				
			
		});
		
	};
	
	check_first_sec = function() {
		$(this).filter(function() {
			var empty_fields = student_fields.filter(function() {
				return $.trim(this.value) === "";
			});
			if(!empty_fields.length) {
				$("#student_form_wrapper .second_sec").css("display", "block");
			} else {
				$("#student_form_wrapper .second_sec").css("display", "none");
				$("#student_form_wrapper form input[type=submit]").attr("disabled", true);
				$("#avialable_units").html("");
			}
		});
	};
	
	chosen_room = function() {
		if(!$(this).hasClass('room_not_aval') && !$(this).closest('.unit').hasClass('not_aval')) {
			form_data.unit_num = $(this).closest(".unit").data("unit-num");
			form_data.floor_num = $(this).closest(".unit").data("floor-num");
			form_data.room_num = $(this).data("room-num");
			$(".unit .room").removeClass('selected');
			$(this).addClass('selected');
			$("#student_form_wrapper form input[type=submit]").attr("disabled", false);
		}
	};	
	
	highlight_room = function() {
		//$(this).addClass('selected');
		console.log("room clicked");
	};
	
	// Submit Event Function
	submit = function(e) {
	
		$('.form-group').removeClass('has-error'); // remove the error class
		$('.help-block').remove(); // remove the error text
		
		form_data['student_id'] = $("#student_form_wrapper form input[name=st_id]").val();
		form_data['first_name'] = $("#student_form_wrapper form input[name=first_name]").val();
		form_data['last_name'] = $("#student_form_wrapper form input[name=last_name]").val();
		form_data['address'] = $("#student_form_wrapper form input[name=address]").val();
		form_data['gender'] = $("#student_form_wrapper form input[name=gender]:checked").val();
		form_data['dob'] = $("#student_form_wrapper form input[name=dob]").val();
		form_data['phone_number'] = $("#student_form_wrapper form input[name=phone_number]").val();
		
		//process form data
	  	$.ajax({
		  	type		: 'post',
		  	url			: '../application/process.php',
		  	data		: form_data,
		  	dataType	: 'json',
		  	success: function(data) {
			  	
			  	$("#form_results").empty();
			  	
			  	var result = data;
			  	console.log(result);
			  	
			  	$.each(result, function(i, element) {
				  	$('<h3></h3>').html(i).appendTo($("#form_results"));
				  	
				  	$.each(element, function(j, sub) {
					  	$('<p></p>').addClass('error').html(sub).appendTo("#form_results");
				  	});
			  	});
		  	}
	  	});
	  	
	  	e.preventDefault();
	};
	
	initModule = function(dorm_form_info) {
		
		$('#date').datepicker({
        	dateFormat: 'yy-mm-dd',
			changeMonth: true,
            changeYear: true,
            yearRange: "-100:",
            minDate: new Date(1979, 1, 1),
            maxDate: new Date
        });
        
		generate_dorm_select(dorm_form_info);
		student_fields.keyup(check_first_sec);
		
		$("#available_dorms ul li.dorm_select").bind("click", function() {
			var selected = $(this);
			
			if (!$(this).hasClass('select') && $(this).hasClass('active')) {
				selected.addClass('select').siblings().removeClass('select');
				$("#student_form_wrapper form input[type=submit]").attr("disabled", true);
				form_data.dorm_num = selected.data('dorm-num');
				generate_unit_select(selected, dorm_form_info);
			}
		});
		
		$("#avialable_units").on("click", "ul li .unit .room", chosen_room);
		$("#student_form_wrapper form").submit(submit);
	};
	
	
	return { init : initModule };
	
}()); 