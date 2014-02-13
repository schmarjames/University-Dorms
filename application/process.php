<?php
require('config.php');
require('classes/DB.class.php');
require('classes/validator.class.php');

// writes the given array of rows to a CSV file
	function write_csv($filename, $rows) {
		$file = fopen($filename, 'w');
		
		foreach($rows as $row) {
			fputcsv($file, $row);
		}
		
		fclose($file);
	}

$errors = array(); // holds validation errors
	
if($_SERVER['REQUEST_METHOD'] == 'POST') {

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
		
		$db = null;
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
	$get_results = $_GET;
	$db = new DB();
	$db->query('select st_id, first_name, last_name, address, dob, phone_number, dorm_build_number, unit_id, room_number from student where dorm_build_number = :dorm_build_number and unit_id = :unit_id and floor_number = :floor_number');
	foreach($get_results as $g_results=>$g_bind) {
		$db->bind(':'.$g_results, $g_bind);
	}
	$students = $db->result_set();
	$db = null;
	echo json_encode($students);
}

// If its is a putt request then the user needs to update a student profile
if($_SERVER['REQUEST_METHOD'] == 'PUT') {
	$update_rules = array(
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
				'dob'		=> array (
					'required'		=> true
			),
				'phone_number'		=> array (
					'required'		=> true,
					'numeric'		=> true	
			)
		);
	parse_str(file_get_contents('php://input'), $put_results);
	// Create a new Validator instance
	$validator = new Validator($put_results, $update_rules);
	if($validator->validate()) {
		
		$put_clean_results = $validator->get_fields();
		$db = new DB();
		$db->query('update student set first_name = :first_name, last_name = :last_name, address = :address, dob = :dob, phone_number = :phone_number where st_id = :st_id');
		foreach($put_clean_results as $p_results=>$p_bind) {
			$db->bind(':'.$p_results, $p_bind);
		}
		$db->execute();
		$db = null;
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

// If its is a delete request then the user needs to delete a student
if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
	$delete_rules = array(
			'student_id' => array(
				'required'		=> true,
				'numeric'		=> true,
				'min_length'	=> 8,
				'max_length'	=> 8,
				'not_duplicate'	=> true	
			)
		);
		
	parse_str(file_get_contents('php://input'), $delete_result);
	// Create a new Validator instance
	$validator = new Validator($delete_result, $delete_rules);
	if($validator->validate()) {
		
		$delete_clean_result = $validator->get_fields();
		$db = new DB();
		// select the dorm_build_number, floor_number, unit_id from student of matching id
		$db->query('select dorm_build_number, floor_number, unit_id from student where st_id = :st_id');
		$db->bind('st_id', $delete_clean_result['st_id']);
		$st_dorm_info = $db->result_set()[0];
		$delete_amount = $db->row_count();
		
		// next we will delete this student with the matching st_id from the student table completely
		$db->query('delete from student where st_id = :st_id');
		$db->bind(':st_id', $delete_clean_result['st_id']);
		
		$db->execute();
		
		// next check the student table to see if any other student has a matching dorm_build_number, floor_number, and unit_id
		$db->query('select st_id from student where dorm_build_number = :dorm_build_number and floor_number = :floor_number and unit_id = :unit_id');
		foreach($st_dorm_info as $st_info=>$info) {
			$db->bind(':'.$st_info, $info);
		}
		$db->result_set();
		$unit_mates = $db->row_count();
		
		// if the row_count is > 0 then this delete student is not the only on in the unit
		if($unit_mates > 0) {
			// therefore we will not delete this unit from the unit table
			// we will just subtract 1 from room_used that has a matching dorm_build_number, floor_number, and unit_id
			$db->query('update unit set rooms_used = rooms_used -' . $delete_amount . ' where dorm_id = :dorm_id and floor_number = :floor_number and unit_id = :unit_id');
			$db->bind(':dorm_id', $st_dorm_info['dorm_build_number']);
			$db->bind(':floor_number', $st_dorm_info['floor_number']);
			$db->bind(':unit_id', $st_dorm_info['unit_id']);
			$db->execute();
				
				// else if row_count == 0 this is the only student in the unit
		} else if ($unit_mates == 0) {
			// therefore we delete this unit from the unit table completely that has a matching dorm_build_number, floor_number, and unit_id
			$db->query('delete from unit where dorm_id = :dorm_id and floor_number = :floor_number and unit_id = :unit_id');
			$db->bind(':dorm_id', $st_dorm_info['dorm_build_number']);
			$db->bind(':floor_number', $st_dorm_info['floor_number']);
			$db->bind(':unit_id', $st_dorm_info['unit_id']);
			$db->execute();
		}
				
		// next we will search the dormitory table for the dorm_build_number to see if the capacity_amount is == 1
		$db->query('select capacity_amount from dormitory where dorm_id = :dorm_id');
		$db->bind(':dorm_id', $st_dorm_info['dorm_build_number']);
		$cap_amount = $db->single();
		if($cap_amount == 1) {
			// if so we will delete this row from the table completely
			$db->query('delete from dormitory where dorm_id = :dorm_id');
			$db->bind(':dorm_id', $st_dorm_info['dorm_build_number']);
			$db->execute();

		}		
			// else if capactiy_amount is > 1 
		else if($cap_amount > 1) {
			// we will subtract one from dormitory capacity_amount in the dormitory table that has a matching dorm_build_number
			$db->query('update dormitory set capacity_amount = capacity_amount -' . $delete_amount . ' where dorm_id = :dorm_id');
			$db->bind(':dorm_id', $st_dorm_info['dorm_build_number']);
			$db->execute();
		}
						
		
		
		$db = null;
		$result = array(
			'success' => array('Student has been successfully deleted from our housing!')
		);
		
		echo json_encode($result);	
	} else {
		// Get errors form validator
		$errors = $validator->get_errors_json();
		
		// Return errors
		echo $errors;
	}
}

if($_SERVER['REQUEST_METHOD'] == 'CSV') {

	parse_str(file_get_contents('php://input'), $csv_result);
	
	
	$dorm_title = $csv_result['csv0']['dorm_build_number'];
	$unit_title = $csv_result['csv0']['unit_id'];
	$csv_filename = '../csv_files/dorm-records'.$dorm_title.'-'.$unit_title.'.csv';
	
	array_unshift($csv_result, array_keys($csv_result['csv0']));
	
	write_csv($csv_filename, $csv_result);
	$result = array(
		'success' => array('The students data for dorm'.$dorm_title.' in '.$unit_title. ' has been documented.')
	);
		
	echo json_encode($result);
}


?>