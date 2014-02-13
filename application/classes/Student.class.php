<?php

/**
 * Student class
 *
 * Creates a student obect when user enters
 * a new student into the system
 *
 * @package    University Dorms
 * @subpackage Student
 * @author     Schmar James <loyd.slj@gmail.com>
 */
class Student {

	//property
	public $st_id;
	public $name;
	public $address;
	public $gender;
	public $dob;
	public $phone_num;
	public $dorm_build_num;
	public $unit_id;
	public $room_num;
	
	/**
 	 * Constructor
 	 *
 	 * @access	public
 	 */
	public function __construct($data) {
		$this->st_id = $data['st_id'];
		$this->name = $data['name'];
		$this->address = $data['address'];
		$this->gender = $data['gender'];
		$this->dob = $data['dob'];
		$this->phone_num = $data['phone_num'];
		$this->dorm_build_num = $data['dorm_build_num'];
		$this->unit_id = $data['unit_id'];
		$this->room_num = $data['room_num'];
	}
	
	/**
 	 * save
 	 *
 	 * saves new student in the database
 	 * 
 	 * @return int
 	 */
	public function save() {
		$db = new DB();
		$db->query('insert into student (st_id, name, address, gender, dob, phone_num, dorm_build_num, unit_id, room_num) value(:st_id, :name, :address, :gender, :dob, :phone, :dorm_num, :unit_id, :room_num)');
		$db->bind(':st_id', $this->st_id);
		$db->bind(':name', $this->name);
		$db->bind(':address', $this->address);
		$db->bind(':gender', $this->gender);
		$db->bind(':dob', $this->dob);
		$db->bind(':phone_num', $this->phone_num);
		$db->bind(':dorm_build_num', $this->dorm_build_num);
		$db->bind(':unit_id', $this->unit_id);
		$db->bind(':room_num', $this->room_num);
		
		$db->execute();
		
		return $db->last_insert_id();
	}
}

?>