<?php

/**
 * Units class
 *
 * Create units object and all of its required
 * features
 *
 * @package    University Dorms
 * @subpackage Unit
 * @author     Schmar James <loyd.slj@gmail.com>
 */
class Unit {
	
	// properties
	public $unit_id;
	public $dorm_num;
	public $room_amount;
	public $unit_gender;
	public $floor_num;
	public $rooms_used = array();
	
	/**
 	 * Constructor
 	 *
 	 * @access	public
 	 */
	public function __construct($dorm_num, $u_id, $fl_num) {
		$this->unit_id = $u_id;
		$this->dorm_num = $dorm_num;
		$this->room_amount = MAX_UNIT_CAPACITY;
		$this->floor_num = $fl_num;
		$this->unit_gender = $this->set_unit_gender();
		
		$this->check_rooms_used();
	}
	
	/**
 	 * check_rooms_used
 	 *
 	 * get the amount of rooms that are occupied
 	 * within the unit
 	 * 
 	 */
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
	
	/**
 	 * set_unit_gender
 	 *
 	 * determines if the unit is occupied and if so
 	 * what is there gender
 	 * 
 	 * @return string or bool
 	 */
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