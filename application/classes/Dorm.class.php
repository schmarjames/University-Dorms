<?php

class Dormitory {
	
	// Properties
	public $dorm_id;
	public $dorm_max_capacity;
	public $units_amount;
	public $capacity_amount;
	public $units = array();
	
	// __construct()
	// **** construct (index_num, data[])
	// 			The dorm construct will use the data in the data[] to populate the dorm object properties
	
	public function __construct($index_num, $standards) {
		$this->dorm_id = $index_num;
		$this->dorm_max_capacity = $this->get_max_capacity($standards['floors_per_dorm'], $standards['units_per_floor'], $standards['max_unit_capacity']);
		$this->units_amount = $this->dorm_units_amount($standards['floors_per_dorm'], $standards['units_per_floor']);
		$this->capacity_amount = $this->dorm_capacity();
		
		$this->generate_units();
		
	}
	
	// dorm_capacity()
	public function dorm_capacity() {
		$db = new DB;
		$db->query('select capacity_amount from dormitory where dorm_id = :dorm_id');
		$db->bind(':dorm_id', $this->dorm_id);
		
		// check to see if row amount greater than 0
		if($db->row_count() != 0) {
			// if it is than that means that the dorm has occupants
			$row = $db->single();
			return $row;
		} else {
			// else the dorm has no occupants yet
			return 0;
		}
		
		$db = null;
	}
	
	// dorm_units_amount()
	public function dorm_units_amount($floor_num, $units_per_fl) {
		return $floor_num*$units_per_fl;
	}
	
	// dorm_max_capacity()
	public function get_max_capacity($floor_num, $units_per_fl, $max_unit_cap) {
		return ($floor_num*$units_per_fl)*$max_unit_cap;
	} 
	
	// generate_units()
	// create units for dormitory based on the $unit_amount
	public function generate_units() {
		$unit;
		$x=0;
		$j=1;
		// for each unit we will iterate over the 
		// units_per_floor constant and
		// floors_per_dorm constant which will allows us to assign
		// a unit number and floor number to each unit with the dormitory
		for ($i=0; $i<$this->units_amount; $i++) {
			$j++;
			if(($i%UNITS_PER_FLOOR) == 0) { $x++; }
			if(($i%UNITS_PER_FLOOR) == 0) { $j=1; }	
			$this->units['unit'. $this->dorm_id . '-' . $x . '-' .($i+1)] = new Unit($this->dorm_id, $j, $x);
		}
		return $this->units;
	}
}
	
?>