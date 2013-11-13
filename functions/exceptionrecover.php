<?php	//there needs to be an EXACT correspondence between characters SAVED (from destruction by the scrubdown function)
		//and the values RECOVERED by its partner, the 'exceptionrecover' function
		// This translates back from the DB safe alphanumerics created by exceptionsave
		
		
		//preserve allowed characters by encoding each of them as a harmless four letter sequence
	function exceptionrecover ($forminput) {
		$forminput = str_ireplace('slxx','/',$forminput);
		$forminput = str_ireplace('hyxx','-',$forminput);
		$forminput = str_ireplace('usxx','_',$forminput);
		$forminput = str_ireplace('qmxx','?',$forminput);
		$forminput = str_ireplace('amxx','&',$forminput);
		$forminput = str_ireplace('eqxx','=',$forminput);
		$forminput = str_ireplace('fsxx','.',$forminput);
		

				
    return $forminput;
}
?>