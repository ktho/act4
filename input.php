<html>
<head>
	<?php
		// define variables and initialize with empty values
		$inputErr = "";
		$inputtype = "";

		// Session
		$wErr = $mErr = $loginErr = $logoutErr = "";
		$worker = $machine = $login = $logout = $sessNotes= "";

		// Inspection
		$sErr = $ratingErr = $nextinspectionErr = "";
		$sess = $rating = $nextinspection = $inspectionNotes = "";

		// Repair
		$s2Err = $costErr = "";
		$sess2 = $cost = $repairNotes = "";

		$reset = true;

		if ($_SERVER[REQUEST_METHOD] == "POST") {
		    if (empty($_POST["inputtype"]))  {
		        $inputErr = "Missing";
		        $reset = false;
		    } else {
		        $inputtype= $_POST["inputtype"];
		    }

		    //Session
			if ($inputtype = "Session") {
			    if (empty($_POST["worker"])) {
			        $wErr = "Missing";
			        $reset = false;
			    }  else {
			        $worker = $_POST["worker"];
			    }
		    
			    if (empty($_POST["machine"])) {
			        $mErr = "Missing";
			        $reset = false;
			    }  else {
			        $machine = $_POST["machine"];
			    }

			    if (empty($_POST["login"])) {
			        $loginErr = "Missing";
			        $reset = false;
			    }  else {
			        $login = $_POST["login"];
			    }
		    		    
			    if (empty($_POST["logout"])) {
			        $logoutErr = "Missing";
			        $reset = false;
			    }  else {
			        $logout = $_POST["logout"];
			    }
		    
			    $sessNotes = $_POST["sessNotes"];
			}		    

		// Inspection
			if ($inputtype = "Inspection") {
			    if (empty($_POST["sess"])) {
			        $sErr = "Missing";
			        $reset = false;
			    }  else {
			        $sess = $_POST["sess"];
			    }
		    
			    if (empty($_POST["rating"])) {
			        $ratingErr = "Missing";
			        $reset = false;
			    }  else {
			        $rating = $_POST["rating"];
			    }

			    if (empty($_POST["nextinspection"])) {
			        $nextInspectionErr = "Missing";
			        $reset = false;
			    }  else {
			        $nextinspection = $_POST["nextinspection"];
			    }		    		  
		    
			    $inspectionNotes = $_POST["inspectionNotes"];
			}		    

		// Repair
			if ($inputtype = "Repair") {
			    if (empty($_POST["sess2"])) {
			        $s2Err = "Missing";
			        $reset = false;
			    }  else {
			        $sess2 = $_POST["sess2"];
			    }
		    
			    if (empty($_POST["cost"])) {
			        $costErr = "Missing";
			        $reset = false;
			    }  else {
			        $cost = $_POST["cost"];
			    }	    		  
		    
			    $repairNotes = $_POST["repairNotes"];
			}	
		    
		    if($reset){
				$inputtype = "";
				$worker = $machine = $login = $logout = $sessnotesErr= "";
				$sess = $rating = $nextinspection = $inspectionNotes = "";
				$session = $cost = $repairNotes = "";
		    }
		}
	?>	
<title>Input Factory Data</title>
</head>
<body>
	<p>Go Back <font size="-1">  [<a href="index.html">front door</a></font>] </p>
	<h2>Enter information regarding sessions, inspections or repairs</h2>
	<form name="input" action="input.php" method="POST" >
		<p>What Input Type?</p>
		<select name="inputtype">
			<option value="Session">Session</option>
			<option value="Inspection">Inspection</option>
			<option value="Repair">Repair</option>
		</select>
		<br/>


		<h3>Enter Session information here:</h3>
		<p>Worker ID:
		<input type="text" name="worker" value="<?php echo htmlspecialchars($worker);?>"/>
		<span class="error"><?php echo $wErr;?></span></p>

		<p>Machine ID:
		<input type="text" name="machine" value="<?php echo htmlspecialchars($machine);?>"/>
		<span class="error"><?php echo $mErr;?></span></p>

		<p>Login Time:
		<input type="text" name="login" value="<?php echo htmlspecialchars($login);?>"/>
		<span class="error"><?php echo $loginErr;?></span></p>

		<p>Logout Time:
		<input type="text" name="logout" value="<?php echo htmlspecialchars($logout);?>"/>
		<span class="error"><?php echo $logoutErr;?></span></p>
		
		<p>Session Notes:</p>
		<textarea rows="2" cols="98" name="sessNotes"><?php echo htmlspecialchars($sessNotes);?></textarea>



		<h3>Enter Inspection information here:</h3>
		<p>Session ID:
		<input type="text" name="sess" value="<?php echo htmlspecialchars($sess);?>"/>
		<span class="error"><?php echo $sErr;?></span></p>

		<p>Rating:
		<input type="text" name="rating" value="<?php echo htmlspecialchars($rating);?>"/>
		<span class="error"><?php echo $ratingErr;?></span></p>

		<p>Next Inspection Date:
		<input type="text" name="nextinspection" value="<?php echo htmlspecialchars($nextinspection);?>"/>
		<span class="error"><?php echo $nextinspectionErr;?></span></p>
		
		<p>Inspection Notes:</p>
		<textarea rows="2" cols="98" name="inspectionNotes"><?php echo htmlspecialchars($inspectionNotes);?></textarea>



		<h3>Enter Repair information here:</h3>
		<p>Session ID:
		<input type="text" name="sess2" value="<?php echo htmlspecialchars($sess2);?>"/>
		<span class="error"><?php echo $s2Err;?></span></p>

		<p>Cost:
		<input type="text" name="cost" value="<?php echo htmlspecialchars($cost);?>"/>
		<span class="error"><?php echo $costErr;?></span></p>
		
		<p>Repair Notes:</p>
		<textarea rows="2" cols="98" name="repairNotes"><?php echo htmlspecialchars($repairNotes);?></textarea>


		<br/>
		<input type="submit" value="POST"/>
	</form>
<?php

require './aws.phar'; 
use Aws\S3\S3Client; 
include 'AWSVars.php';

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Enum\Type;
use Aws\DynamoDb\Enum\AttributeAction;
use Aws\DynamoDb\Enum\ReturnValue;


// Create a client 
echo "create client <br>\n"; 
$client = S3Client::factory(array(
    'key'    => $gKey, 
    'secret' => $gSecret
));

// This is the bucket name 
$bucket = 'my-bucket-ktyunho';

// Now create that bucket
echo "create bucket <br>\n"; 
try {
	$result = $client->createBucket(array(
    		'Bucket' => $bucket));
}
catch (BucketAlreadExistsException $e) {
	echo "error: that bucket ($bucket) already exists (message: $e)\n"; 
}

// We have to wait until the bucket is created 
echo "waiting for create bucket <br>\n"; 
// Wait until the bucket is created
$client->waitUntil('BucketExists', array('Bucket' => $bucket));

echo "done waiting <br>\n"; 

if ($_SERVER[REQUEST_METHOD] == "POST" && $reset) {


}


$reset = false;

?>
</body>
</html>



