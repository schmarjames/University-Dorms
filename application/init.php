<?php

//include all classes
require('config.php');
require('classes/DB.class.php');
require('classes/Student.class.php');
require('classes/Unit.class.php');
require('classes/Dorm.class.php');

// set data array variable, which will be passed into dorm_class constructor
$dorm_standard_data = array(
	'dorm_amount' 		=> DORM_AMOUNT, 
	'floors_per_dorm' 	=> FLOORS_PER_DORM, 
	'units_per_floor' 	=> UNITS_PER_FLOOR,  
	'max_unit_capacity' => MAX_UNIT_CAPACITY
);

$dorms = array();

for ($i=1; $i<=DORM_AMOUNT; $i++) {
	$dorms['dorm'.$i] = new Dormitory($i, $dorm_standard_data);
}

echo json_encode($dorms);
		

?>