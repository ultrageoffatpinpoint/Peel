<?php session_start(); error_reporting(0);
require_once 'functions/exceptionsave.php';
require_once 'functions/exceptionrecover.php';
require_once 'functions/scrubdown.php';

//Get the POST, scrub it down
$cleanedurlfromform = scrubdown($_POST[cleanlink]);

//Restore the non-alphanumeric characters we have saved i.e. allowed
$restoredurlfromform = exceptionrecover($cleanedurlfromform);
//This now has valid URl characters only

/*
echo "The cleaned URL is: $cleanedurlfromform";
exit;
*/

###Use the stored DB Additional Analysis result, if we have one
//Indexing additional column havestoredresult, because theurlencoded is too long to be indexed
$query = "SELECT storedresultarray FROM backlinkdata WHERE havestoredresult = 1 AND theurlencoded = '$cleanedurlfromform' LIMIT 1";
require 'connectup/connreadwrite.php';
	//run the query
	$result = mysql_query ($query);
	
	if (mysql_errno($connreadwrite) != 0) {
    $_SESSION[SUCCESS] = '';
    $_SESSION[ERROR] = 'A connection could not be established for the Advanced Analysis';
	header("location: backlinks.php");
	
} else {
	if (mysql_num_rows($result) > 0) {
		$row = mysql_fetch_assoc($result);
	$extrareturndata = $row[storedresultarray];
	$_SESSION[EXTRARETURNDATA] = $extrareturndata;
    $_SESSION[SUCCESS] = 'This Advanced Analysis data was retrieved from a previous test';
    $_SESSION[ERROR] = '';
	header("location: backlinks.php");	
	exit;
	}//End of if num rows > 0 

	}


###If we are not re-using a result, we will get to here	
//Values for the API URL
$apiaddress = 'http://api.ahrefs.com/';
//This is different
$enquiry = 'get_backlinks.php?';
$target = $restoredurlfromform;
$mode = 'domain';
$outputformat = 'json';
$recordscount = 100;
$AhrefsKey = 'b36e90ef48bcb79c8dc39a46db7d37bd';

//Concatenate a URL for the API
$enquiryurl = $apiaddress;
$enquiryurl .= $enquiry;
$enquiryurl .= 'target=';
$enquiryurl .= $restoredurlfromform;
$enquiryurl .= '&mode=';
$enquiryurl .= $mode;
$enquiryurl .= '&output=';
$enquiryurl .= $outputformat;
$enquiryurl .= '&count=';
$enquiryurl .= $recordscount;
$enquiryurl .= '&AhrefsKey=';
$enquiryurl .= $AhrefsKey;


/*
echo "The enquiry url is: $enquiryurl";
exit;
*/

//Should test that have valid response, etc.

$apiresponse = file_get_contents($enquiryurl);
//API response is a JSON object i.e. a string that can be stored if not too weird
//Could base64 encode to take care of null bytes, other non-standard characters
//If passes via SESSION 
$encodedextradata = base64_encode($apiresponse);
//$encodedextradata = $apiresponse;

/*
echo "The encoded extra data is: $encodedextradata";
exit;
*/

/*
print_r($responsedataarray);
exit;
*/

//Gather values for DB insert of retrieved data

$theurlencoded = $cleanedurlfromform;


###Update the existing record to store the Advanced Analysis data
//Stored as a serialised data string
$query = "UPDATE backlinkdata SET havestoredresult = 1, storedresultarray = '$encodedextradata' WHERE theurlencoded = '$theurlencoded' ";
require 'connectup/connreadwrite.php';
//run the query
	$result = mysql_query ($query);
	
	if (mysql_errno($connreadwrite) != 0) {
    $_SESSION[SUCCESS] = '';
    $_SESSION[ERROR] = 'A connection could not be established';
	
	header("location: backlinks.php");
	exit;
	
} else {
	$_SESSION[SUCCESS] = 'Advanced Analysis Result stored';
    $_SESSION[ERROR] = '';

	}


//Put the responsedata array into the SESSION and return to view
$_SESSION[EXTRARETURNDATA] = "$encodedextradata";

	header("location: backlinks.php");
	exit;


 ?>