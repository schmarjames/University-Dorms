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
    	<header>
    		<h1 class="welcome">University of Southern Virginia Beach</h1>
    		<p class="message">Join our residency</p>
    		<div id="nav">
		    	<a href="#" class="add_student">Add Student</a>
		    	<a href="#" class="view_residence">View Residences</a>
	    	</div>
    	</header>
    	
    	<div id="main_wrapper">
    		<!-- Dorm Percentage Graph -->
    		<div id="dormitory_percent"></div>
	    	<!-- Dormitory Graph -->
	    	<div id="dormitory_graph"></div>
	    	
	    	<!-- Student Add / Edit Form -->
			<div id="student_form_wrapper">
	    		<div id="form_results"></div>
		    	<form method="post" action="../application/process.php">
		    		<h3>Add New Student</h3>
			    	<div id="student_main_info">
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
			    		<button type="button" class="next_dorms" disabled="disable">Next</button>
			    	</div>
					
					<div id="available_dorms">
						<button type="button" class="next_units" disabled="disable">Next</button>
					</div>
					<div id="avialable_units">
						<input type="submit" value="Submit" disabled="disable">
					</div>
		    	</form>
	    	</div>
    	</div>
		
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script> 
        <script src="js/jquery-ui-1.10.4.custom.min.js"></script>
        <script src="js/main.js"></script>
        <script src="js/submission_form.js"></script>
        <script src="js/student_modal.js"></script>
        <script src="js/diagram.js"></script>
        <script type="text/javascript">
        	parent_mod.init();
        	$("#student_form_wrapper form")[0].reset();
        	$("#student_form_wrapper form input[type=submit]").attr("disabled", "disable");
        	$("#student_form_wrapper button.next_dorms").attr("disabled", "disable");
        </script>
    </body>
</html>
