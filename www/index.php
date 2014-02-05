<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/smoothness/jquery-ui-1.10.4.custom.css">
    </head>
    <body>
    	<!-- Dormitory Graph -->
    	<div id="dormitory_graph">
	    	<div id="dormitories">
	    	
	    	</div>
	    	<div id="dorm_units"></div>
    	</div>
    	
    	<!-- Student Add / Edit Form -->
    	<div id="student_form_wrapper">
    		<div id="form_results"></div>
	    	<form method="post" action="../application/process.php">
	    		<div id="student_id_group" class="form_group">
		    		<label>Student Id:</label>
					<input type="text" name="st_id">
	    		</div>
	    		<div id="first_name_group" class="form_group">
					<label>First Names:</label>
					<input type="text" name="first_name">
	    		</div>
	    		<div id="last_name_group" class="form_group">
					<label>Last Name:</label>
					<input type="text" name="last_name">
	    		</div>
	    		<div id="address_group" class="form_group">
					<label>Address:</label>
					<input type="text" name="address">
	    		</div>
	    		<div id="gender_group" class="form_group">
					<label>Gender:</label>
					<input type="radio" name="gender" value="male">Male
					<input type="radio" name="gender" value="female">Female
	    		</div>
	    		<div id="dob_group" class="form_group">
					<label>Date of Birth:</label>
					<input type="text" name="dob" id="date" value"">
	    		</div>
	    		<div id="phone_number_group" class="form_group">
					<label>Phone #:</label>
					<input type="text" name="phone_number">
	    		</div>

				
				<div id="available_dorms"></div>
				<div id="avialable_units">
					<div class="unit">
						<div class="rooms_wrapper">
							<div class="rooms_left">
								<div class="room"></div>
								<div class="room"></div>
							</div>
							<div class="rooms_right">
								<div class="room"></div>
								<div class="room"></div>
							</div>
						</div>
						<div class="unit_access">
							<div class="common">Common</div>
							<div class="kitchen">Kitchen</div>
						</div>
					</div>
				</div>
				
				<input type="submit" value="Submit" disabled="disable">
	    	</form>
    	</div>
    
		
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script> 
        <script src="js/jquery-ui-1.10.4.custom.min.js"></script>
        <script src="js/main.js"></script>
        <script src="js/submission_form.js"></script>
        <script type="text/javascript">
        	parent_mod.init();	
        </script>
    </body>
</html>
