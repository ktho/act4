<html>
<head>
<title>AWS Examples</title>
</head>
<body>
<?php
/* filename: test01.php 
 */
require './aws.phar'; 
use Aws\S3\S3Client; 
include 'AWSVars.php';

// Create a client 
echo "create client <br>\n"; 
$client = S3Client::factory(array(
    'key'    => $gKey, 
    'secret' => $gSecret
));

// This is the bucket name 
$bucket = 'my-bucket-dgh04';

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
?>
</body>
</html>



