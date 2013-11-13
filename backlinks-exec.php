<?php session_start(); error_reporting(0);
require_once 'functions/exceptionsave.php';
require_once 'functions/exceptionrecover.php';
require_once 'functions/scrubdown.php';

//Get the POST
$cleanedurlfromform = scrubdown(strtolower(exceptionsave($_POST[theinputtedurl])));
//Restore the non-alphanumeric characters we have saved i.e. allowed
$restoredurlfromform = exceptionrecover($cleanedurlfromform);
//This now has valid URl characters only
$_SESSION[CLEANLINKNAME] = $cleanedurlfromform;

//Clear any SESSION data for Additional Analysis
//Because this is a new link submission we are looking at
$_SESSION[EXTRARETURNDATA] = '';

###Use the stored DB result if we can
//Do we already have a 'Part 1' result in the DB?
//If so, use that and return
$query = "SELECT * FROM backlinkdata WHERE theurlencoded = '$cleanedurlfromform' LIMIT 1";
require 'connectup/connreadwrite.php';
	//run the query
	$result = mysql_query ($query);
	
	if (mysql_errno($connreadwrite) != 0) {
    $_SESSION[SUCCESS] = '';
    $_SESSION[ERROR] = 'A connection could not be established';
	header("location: backlinks.php");
	
} else {
	if (mysql_num_rows($result) > 0) {

		$fromdb = array();
		$row = mysql_fetch_assoc($result);
		foreach($row AS $key=>$value) {
	$fromdb[$key] = $row[$key];
		}
	$dbdataforreturn = array();
	$dbdataforreturn[Result] = array();
	$dbdataforreturn[Result] = $fromdb;
	$_SESSION[RETURNDATA] = $dbdataforreturn;
    $_SESSION[SUCCESS] = 'This backlink data was retrieved from a previous test';
    $_SESSION[ERROR] = '';
	header("location: backlinks.php");	
	exit;
	}//End of if num rows > 0 

	}
	
//Values for the API URL
$apiaddress = 'http://api.ahrefs.com/';
$enquiry = 'get_backlinks_count_ext.php?';
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
$enquiryurl .= '&countt=';
$enquiryurl .= $recordscount;
$enquiryurl .= '&AhrefsKey=';
$enquiryurl .= $AhrefsKey;


/*
echo "The enquiry url is: $enquiryurl";
exit;
*/

//Should test that have valid response, etc.

$apiresponse = file_get_contents($enquiryurl);
$responsedataarray = json_decode($apiresponse,true);
/*
print_r($responsedataarray);
exit;
*/

//Gather values for DB insert of retrieved data

$theurlencoded = $cleanedurlfromform;

$Backlinks = $responsedataarray[Result][Backlinks];
$RefPages = $responsedataarray[Result][RefPages];
$Pages = $responsedataarray[Result][Pages];
$Text = $responsedataarray[Result][Text];
$Image = $responsedataarray[Result][Image];
$Redirect = $responsedataarray[Result][Redirect];
$Frame = $responsedataarray[Result][Frame];
$Form = $responsedataarray[Result][Form];
$Canonical = $responsedataarray[Result][Canonical];
$Sitewide = $responsedataarray[Result][Sitewide];
$NotSitewide = $responsedataarray[Result][NotSitewide];
$NoFollow = $responsedataarray[Result][NoFollow];
$DoFollow = $responsedataarray[Result][DoFollow];
$Gov = $responsedataarray[Result][Gov];
$Edu = $responsedataarray[Result][Edu];

###Create a record for the new data
$query = "INSERT INTO backlinkdata (
theurlencoded,
Backlinks,
RefPages,
Pages,
Text,
Image,
Redirect,
Frame,
Form,
Canonical,
Sitewide,
NotSitewide,
NoFollow,
DoFollow,
Gov,
Edu
) VALUES (
'$theurlencoded',
$Backlinks,
$RefPages,
$Pages,
$Text,
$Image,
$Redirect,
$Frame,
$Form,
$Canonical,
$Sitewide,
$NotSitewide,
$NoFollow,
$DoFollow,
$Gov,
$Edu
)";

require 'connectup/connreadwrite.php';

//run the query
	$result = mysql_query ($query);
	
	if (mysql_errno($connreadwrite) != 0) {
    $_SESSION[SUCCESS] = '';
    $_SESSION[ERROR] = 'A connection could not be established';
	
	header("location: backlinks.php");
	exit;
	
} else {
	$_SESSION[SUCCESS] = 'Result stored';
    $_SESSION[ERROR] = '';

	}


//Put the responsedata array into the SESSION and return to view
$_SESSION[RETURNDATA] = array();
$_SESSION[RETURNDATA] = $responsedataarray;

	header("location: backlinks.php");
	exit;

 ?>