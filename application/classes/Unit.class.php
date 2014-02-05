<?php

class Unit {
	
	// properties
	public $unit_id;
	public $dorm_num;
	public $room_amount;
	public $unit_gender;
	public $floor_num;
	public $rooms_used = array();
	
	// __construct()
	public function __construct($dorm_num, $u_id, $fl_num) {
		$this->unit_id = $u_id;
		$this->dorm_num = $dorm_num;
		$this->room_amount = MAX_UNIT_CAPACITY;
		$this->floor_num = $fl_num;
		$this->unit_gender = $this->set_unit_gender();
		
		$this->check_rooms_used();
	}
	
	// check_rooms_used()
	// query the student table to find students that have a matching unit_id
	// the students with a matching unit_id, check their room_num and push this
	// data inside the $rooms_used array
	public function check_rooms_used() {
		$rooms_data;
		$db = new DB;
		
		$db->query('select room_number from student where dorm_build_number = :dorm_num and unit_id = :unit_id and floor_number = :floor_num');
		$db->bind(':dorm_num', $this->dorm_num);
		$db->bind(':unit_id', $this->unit_id);
		$db->bind(':floor_num', $this->floor_num);
		
		$rooms_data = $db->result_set();
		$db = null;
		if(!empty($rooms_data) || count($rooms_data) > 0) {
			
			foreach($rooms_data as $room) {
				array_push($this->rooms_used, $room['room_number']);
			}	
		} 
	}
	
	// set_unit_gender()
	// set the gender of the unit by search for the unit_id with the matching floor number within
	// the unit table. Upon finding a match check the unit_gender attribute 
	// and return this value.
	public function set_unit_gender() {
		$db = new DB;
		$db->query('select unit_gender from unit where dorm_id = :dorm_id and unit_id = :unit_id and floor_number = :floor_num');
		$db->bind(':dorm_id', $this->dorm_num);
		$db->bind(':unit_id', $this->unit_id);
		$db->bind(':floor_num', $this->floor_num);
		
		$gender_data = $db->single();
		$db = null;
		
		return $gender_data;
	}
}

?>