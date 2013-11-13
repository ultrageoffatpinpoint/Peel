<?php session_start(); error_reporting(0);
require_once 'functions/exceptionrecover.php';
$returndata = $_SESSION[RETURNDATA][Result];
$cleanlink = $_SESSION[CLEANLINKNAME];
$linknametoshow = exceptionrecover($_SESSION[CLEANLINKNAME]); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>An API interface</title>
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="css/footable.core.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div class="container">
  <div class="page-header">
    <h1>A Backlinks App <small>with Advanced&nbsp;Analysis</small></h1>
  </div>
  <div class="row">
    <form class="form-search" action = 'backlinks-exec.php' method = 'POST'>
      <input type="text" class="input-xlarge search-query" placeholder="e.g. www.yourownurlhere.com" name = 'theinputtedurl' id = 'theinputtedurl'>
      <button type="submit" class="btn btn-large btn-danger">Submit link</button>
    </form>
  </div>
  <!-- .row -->
  <div class="row">
    <div class="span4">
      <?php
if(is_array($returndata)) {
	
		
		echo "<table id='sgrtable' class = 'table table-condensed'>";
		echo "<thead><tr><th>Link Data";
		if (trim($linknametoshow) != '') {echo " | for $linknametoshow";}
		echo "</th><td></td></tr></thead>";
		echo "<tbody>";	
		echo "<tr><th>Backlinks</th><td>$returndata[Backlinks]</td></tr>";
		echo "<tr><th>Referring Pages</th><td>$returndata[RefPages]</td></tr>";
		echo "<tr><th>Pages</th><td>$returndata[Pages]</td></tr>";
		echo "<tr><th>Text</th><td>$returndata[Text]</td></tr>";
		echo "<tr><th>Image</th><td>$returndata[Image]</td></tr>";
		echo "<tr><th>Redirect</th><td>$returndata[Redirect]</td></tr>";
		echo "<tr><th>Frame</th><td>$returndata[Frame]</td></tr>";
		echo "<tr><th>Form</th><td>$returndata[Form]</td></tr>";
		echo "<tr><th>Canonical</th><td>$returndata[Canonical]</td></tr>";
		echo "<tr><th>Sitewide</th><td>$returndata[Sitewide]</td></tr>";
		echo "<tr><th>Not Sitewide</th><td>$returndata[NotSitewide]</td></tr>";
		echo "<tr><th>No Follow</th><td>$returndata[NoFollow]</td></tr>";
		echo "<tr><th>Do Follow</th><td>$returndata[DoFollow]</td></tr>";
		echo "<tr><th>Government</th><td>$returndata[Gov]</td></tr>";
		echo "<tr><th>Educational</th><td>$returndata[Edu]</td></tr>";
		echo "</body>";
		echo "</table>";
	
/*	
 echo "<br><br><br><br><br><br><br><br>A printr of the return data is: ";
	print_r($returndata);
	exit;
*/
}
	
	

?>
    </div>
    <!-- .span4 --> 
  </div>
  <!-- .row -->
  <?php
if(trim($_SESSION[EXTRARETURNDATA]) != '') {
	//We have Advanced Analysis data to show
	//Get it back to unencoded JSON
	$decodedjsondata = base64_decode($_SESSION[EXTRARETURNDATA]);
	$extrareturndata = json_decode($decodedjsondata,true);
	echo "<div class='row'><div class='span10'>";
	$resultdata = $extrareturndata[Result];
	/*
	echo "The resultdata is: ";
	print_r ($resultdata);
	echo "<br/><br/><br/>";
	*/

if(is_array($resultdata)) {
	
	echo "<span class='btn btn-medium btn-info'>Advanced Analysis</span><br/><br/>";
	
	echo "<label>Show results containing : <input id='thefilterid' type='text' placeholder='[Type here to filter]' /></label>";
	
	//Create the table, table head, headers and start the body element
	echo "<table class='table table-bordered table-condensed footable toggle-arrow-circle-filled' data-filter='#thefilterid'>
	<thead>
	<tr>
	<th data-sort-ignore='true' data-toggle='true'>&nbsp;</th>
	<th data-sort-initial='ascending' data-class='expand' data-type='numeric'>Index</th>
	<th>Title</th>
	<th data-hide='phone'>Anchor</th> 	
	<th data-hide='phone' data-type='numeric'>Rating</th> 	
	<th data-hide='phone,tablet'>URL</th>
	<th data-hide='phone,tablet'>Last Checked</th> 	
	<th data-hide='phone,tablet'>First Seen</th> 	
	<th data-hide='phone,tablet'>Link Type</th> 	
	<th data-hide='phone,tablet'>Text before</th> 	
	<th data-hide='phone,tablet'>Text after</th> 	
	<th data-hide='phone,tablet' class= 'centeredtext minwidth120'>Internal Links</th> 	
	<th data-hide='phone,tablet' class= 'centeredtext minwidth120'>External Links</th> 	
	<th data-hide='phone,tablet'>HTML Size</th> 	
	<th data-hide='phone,tablet'>IP Address</th> 	
	</tr>
	</thead>
	<tbody>";
	
	foreach($resultdata as $key => $value) {
		
		/*
		print_r($value[Links]);
		*/
		
		//The four-times nested array values
		$UrlTo = $value[Links][0][UrlTo];
		//echo "The UrlTo is : $UrlTo<br/>";
		
		$Visited = $value[Links][0][Visited];
		$Visited = date('jS M Y, g:ia',$Visited);
		//echo "The Visited is : $Visited<br/>";
		
		$FirstSeen = $value[Links][0][FirstSeen];
		$FirstSeen = date('jS M Y',$FirstSeen);
		//echo "The FirstSeen is : $FirstSeen<br/>";
		
		$PrevVisited = $value[Links][0][PrevVisited];
		$PrevVisited = date('jS M Y',$PrevVisited);
		//echo "The PrevVisited is : $PrevVisited<br/>";
		
		$Anchor = $value[Links][0][Anchor];
		//echo "The Anchor is : $Anchor<br/>";
		
		$Type = $value[Links][0][Type];
		//echo "The Type is : $Type<br/>";
		
		$TextPre = $value[Links][0][TextPre];
		if(trim($TextPre) == '') {$TextPre = '[none found]';}
		//echo "The TextPre is : $TextPre<br/>";
		
		$TextPost = $value[Links][0][TextPost];
		if(trim($TextPost) == '') {$TextPost = '[none found]';}
		//echo "The TextPost is : $TextPost<br/>";
		
		
		//The two-times nested array values
		
		//Most end users expect indices to start at 1 rather than 0
		$Index = $value[Index] + 1;
		//echo "The Index is : $Index<br/>";
		
		
		$Rating = $value[Rating];
		//Format for 1 decimal place
		$Rating = number_format($Rating,1,'.','');
		//echo "The Rating is : $Rating<br/>";
		
		
		$UrlFrom = $value[UrlFrom];
		//echo "The UrlFrom is : $UrlFrom<br/>";
		
		
		$IpFrom = $value[IpFrom];
		//echo "The IpFrom is : $IpFrom<br/>";
		
		
		$Title = $value[Title];
		if(trim($Title) == '') {$Title = '[not titled]';}
		//echo "The Title is : $Title<br/>";
		
		$LinksInternal = $value[LinksInternal];
		//echo "The LinksInternal is : $LinksInternal<br/>";
		
		$LinksExternal = $value[LinksExternal];
		//echo "The LinksExternal is : $LinksExternal<br/>";
		
		$Size = $value[Size];
		$Size = ($Size/1024);
		$Size = number_format($Size,2,'.','');
		//echo "The Size is : $Size<br/><br/><br/><br/>";
		
		//Output a row in an order as set by the column headers
		echo "<tr>";
		echo "<td class= 'centeredtext'></td>";
		echo "<td class= 'centeredtext boldtext minwidth65'>$Index</td>";
		echo "<td>$Title</th>";
		echo "<td>$Anchor</td>"; 	
		echo "<td class= 'centeredtext minwidth65'>$Rating</td>"; 	
		echo "<td>$UrlFrom</td>";
		echo "<td>$Visited</td>"; 	
		echo "<td>$FirstSeen</td>"; 	
		echo "<td>$Type</td>"; 	
		echo "<td>$TextPre</td>"; 	
		echo "<td>$TextPost</td>"; 	
		echo "<td>$LinksInternal</td>"; 	
		echo "<td>$LinksExternal</td>"; 	
		echo "<td>$Size Kb</td>"; 	
		echo "<td>$IpFrom</td>"; 	
		echo "</tr>";

	}


//Close body, close table
echo "</tbody></table>";	
}

/*
	echo "The resultdata is: <br/>";
	print_r($resultdata);
*/	
	
	echo "</div></div>";
	} 


 if (is_array($_SESSION[RETURNDATA])) {
//The Advanced Analysis, or a button to access it, goes here	
	if(trim($_SESSION[EXTRARETURNDATA]) == '') {
	echo "<div class='row'><div class='span4'><br>
	<form action = 'backlinksmoredata-exec.php' method = 'POST'>
	<input type='hidden' name = 'cleanlink' value = '$cleanlink'>   
  	<button type='submit' name = 'extradatarequested' class='btn  btn-info'>See Advanced Analysis</button>  
	</form></div></row>";	
	}
}

  if (trim($_SESSION[SUCCESS] != '')) {echo "<div class='row'><div class='span12'><span class = 'label label-success'>SUCCESS: $_SESSION[SUCCESS]</span></div></div>";}
  if (trim($_SESSION[ERROR] != '')) {echo "<div class='row'><div class='span12'><span class = 'label label-warning'>ERROR: $_SESSION[ERROR]</span></div></div>";}
?>
</div>
<!-- .container --> 

<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/footable.js" type="text/javascript"></script> 
<script src="js/footable.sort.js" type="text/javascript"></script> 
<script src="js/footable.filter.js" type="text/javascript"></script> 
<script type="text/javascript">
	$(function () {
		$('.footable').footable();
	});
</script>

</body>
</html>