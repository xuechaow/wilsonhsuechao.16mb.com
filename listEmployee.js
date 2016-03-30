//Display the data by the option user selected.
function listEmployee(str){
  var xhttp;
  if(str == "") {
    document.getElementById("textFromServer").innerHTML = "Select an option";
	return; //Avoid unnecessary scripts.
  }
  xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function () {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
	  document.getElementById("txtFromServer").innerHTML = xhttp.responseText;
	}
	if(xhttp.status == 404){
	  document.getElementById("errFromServer").innerHTML = "404:Server Not Found"  
	}
  };
  xhttp.open("GET","dataServer.php?queryString="+str,true);
  xhttp.send();
  
}