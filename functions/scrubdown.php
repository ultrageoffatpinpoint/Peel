<?php
//To sanitise FORM input values, only allows English alphanumerics and whitespaces
	function scrubdown ($forminput) {
		
		$forminput = trim(preg_replace('/[^a-zA-Z 0-9 \s]/','',$forminput));
		
    return $forminput;
}
?>