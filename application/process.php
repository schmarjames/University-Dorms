<?php
require('classes/DB.class.php');
require('classes/validator.class.php');
	
$validation_rules = array(
			'student_id' => array(
				'required'		=> true,
				'numeric'		=> true,
				'min_length'	=> 8,
				'max_length'	=> 8,
				'not_duplicate'	=> true	
		),
			'first_name' => array(
				'required'		=> true,
				'alpha'			=> true,
				'min_length'	=> 1	
		),	
			'last_name' => array(
				'required'		=> true,
				'alpha'			=> true,
				'min_length'	=> 1	
		),
			'address' 	=> array(
				'required'		=> true,
				'min_length'	=> 5	
		),
			'gender'	=> array (
				'required'		=> true
		),
			'dob'		=> array (
				'required'		=> true
		),
			'phone_number'		=> array (
				'required'		=> true,
				'numeric'		=> true	
		),
			'dorm_num'			=> array (
				'required'		=> true,
				'alpha_num'		=> true
		),
			'unit_num'			=> array (
				'required'		=> true,
				'numeric'		=> true
		),
			'floor_num'			=> array (
				'required'		=> true,
				'numeric'		=> true
		),
			'room_num'			=> array (
				'required'		=> true,
				'numeric'		=> true
		)
);

$errors = array(); // holds validation errors
	
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	// Create a new Validator instance
	$validator = new Validator($_POST, $validation_rules);
	
	// If form has passed validation
	if($validator->validate()) {
		
		$clean_data = $validator->get_fields();
		// Insert new student into database
		$db = new DB();
		$db->query('insert into student (st_id, first_name, last_name, address, gender, dob, phone_number, dorm_build_number, floor_number, unit_id, room_number) values (:student_id, :first_name, :last_name, :address, :gender, :dob, :phone_number, :dorm_num, :floor_num, :unit_num, :room_num)');
		
		// Loop through each key value pair of the $clean_data array to store them as parameter 
		// for the bind function
		foreach($clean_data as $c_data=>$bind) {
			$db->bind(':'.$c_data, $bind);
		}
		$db->execute();
		
		// Run select statement to see how many students now live in this unit
		// The row count will be the new total for the rooms_used attribute in the units table
		
		// First see if the unit is already in use
		// Check if unit is already stored in database
		$db->query('select unit_id from unit where unit_id = :unit_num and dorm_id = :dorm_num and floor_number = :floor_num');
		$db->bind(':unit_num', $clean_data['unit_num']);
		$db->bind(':dorm_num', $clean_data['dorm_num']);
		$db->bind(':floor_num', $clean_data['floor_num']);
		$db->result_set();
		
		if($db->row_count() > 0) {
			// If it is then run an update query
			$db->query('select unit_id from student where unit_id = :unit_num and dorm_build_number = :dorm_num and floor_number = :floor_num');
			$db->bind(':unit_num', $clean_data['unit_num']);
			$db->bind(':dorm_num', $clean_data['dorm_num']);
			$db->bind(':floor_num', $clean_data['floor_num']);
			$db->result_set();
			
			// Store new rooms used total in Unit table
			$rooms_used_amount = $db->row_count();
			$db->query('update unit set rooms_used = :rooms_used where unit_id = :unit_num and dorm_id = :dorm_num and floor_number = :floor_num');
			$db->bind(':rooms_used', $rooms_used_amount);
			$db->bind(':unit_num', $clean_data['unit_num']);
			$db->bind(':dorm_num', $clean_data['dorm_num']);
			$db->bind(':floor_num', $clean_data['floor_num']);
			$db->execute();
		} else  {
			// If not then run an insert query
			$db->query('insert into unit (unit_id, room_amount, rooms_used, unit_gender, floor_number, dorm_id) values (:unit_num, :room_amount, :rooms_used, :unit_gender, :floor_num, :dorm_num)');
			$db->bind(':unit_num', $clean_data['unit_num']);
			$db->bind(':room_amount', 4);
			$db->bind(':rooms_used', 1);
			$db->bind(':unit_gender', $clean_data['gender']);
			$db->bind(':floor_num', $clean_data['floor_num']);
			$db->bind(':dorm_num', $clean_data['dorm_num']);
			$db->execute();
		}
		
		// We must also update the capacity amount within the dormitory table
		// First see if the dormitory is already in use
		// Check if dormitory is already stored in database
		$db->query('select dorm_id from dormitory where dorm_id = :dorm_num');
		$db->bind(':dorm_num', $clean_data['dorm_num']);
		$db->result_set();
		
		if($db->row_count() > 0) {
			// If it is then run an update query
			$db->query('select dorm_build_number from student where dorm_build_number = :dorm_num');
			$db->bind(':dorm_num', $clean_data['dorm_num']);
			$db->result_set();
			
			// Store new dorm capacity total in Dormitory table
			$dorm_capacity_amount = $db->row_count();
			$db->query('update dormitory set capacity_amount = :capacity_amount where dorm_id = :dorm_num');
			$db->bind(':capacity_amount', $dorm_capacity_amount);
			$db->bind(':dorm_num', $clean_data['dorm_num']);
			$db->execute();
		} else {
			// If not then run an insert query
			$db->query('insert into dormitory (dorm_id, capacity_amount) values (:dorm_num, :capacity_amount)');
			$db->bind(':dorm_num', $clean_data['dorm_num']);
			$db->bind(':capacity_amount', 1);
			$db->execute();
		}
		
		
		$result = array(
			'success' => array('The form has been successfully submitted!')
		);
		
		echo json_encode($result);
	} else {
		// Get errors form validator
		$errors = $validator->get_errors_json();
		
		// Return errors
		echo $errors;
	}
	
}

// If it is a get request then the user needs student data to populate an edit form
if($_SERVER['REQUEST_METHOD'] == 'GET') {
	
}

// If its is a post request then the user needs to update a student profile
if($_SERVER['REQUEST_METHOD'] == 'PUT') {
	
}

// If its is a delete request then the user needs to delete a student
if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
	
}


?>