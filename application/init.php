<?php

// set constants
// These constants will be used by the dorm class to set
// standards for each dorm object
// - Amount of dorms				dorm_amount
// - Amount of floors per dorm		floors_per_dorm
// - Units per floor				units_per_floor
// - Max capacity for each unit		max_unit_capacity
define('DORM_AMOUNT', 6);
define('FLOORS_PER_DORM', 3);
define('UNITS_PER_FLOOR', 4);
define('MAX_UNIT_CAPACITY', 4);

//include all classes
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

// upon making each dorm object we will push this inside of an array which will be JSON encoded
// this JSON encoded variable will be passed to javascript		




?>