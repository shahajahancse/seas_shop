/*Check XSS Code*/
function xss_validation(data) {
	if(typeof data=='object'){
		for (var value of data.values()) {
		   if(typeof value!='object' && (value.trim()!='' && value.indexOf("<script>") != -1)){
		   	toastr["error"]("Failed!! to Continue! XSS Code found as Input!");
		   	return false;
		   }
		}
		return true;
	}
	else{
		if(typeof value!='object' && (data.trim()!='' && data.indexOf("<script>") != -1)){
		   	toastr["error"]("Failed!! to Continue! XSS Code found as Input!");
		   	return false;
		}
		return true;
	}
}
//end