<?php	//there needs to be an EXACT correspondence between characters SAVED (from destruction by the scrubdown function)
		//and the values RECOVERED by its partner, the 'exceptionrecover' function
		// This translates a few chosen characters and tags into database-safe character sequences that can be allowed to go to MySQL
		//and then recovered for output by the 'exceptionrecover' function
		
		
		//preserve allowed characters by encoding each of them as a harmless four letter sequence
	function exceptionsave ($forminput) {
		$forminput = str_ireplace('/','slxx',$forminput);
		$forminput = str_ireplace('-','hyxx',$forminput);
		$forminput = str_ireplace('_','usxx',$forminput);
		$forminput = str_ireplace('?','qmxx',$forminput);
		$forminput = str_ireplace('&','amxx',$forminput);
		$forminput = str_ireplace('=','eqxx',$forminput);
		$forminput = str_ireplace('.','fsxx',$forminput);
		

				
    return $forminput;
}
?>