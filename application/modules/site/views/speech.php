<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Untitled Document</title>
        
        <script type="text/javascript" 
          src="http://www.speechapi.com/static/lib/speechapi-1.1.js"></script>
  <script type="text/javascript" 
          src="http://www.speechapi.com/static/lib/swfobject.js"></script>
  <script language="JavaScript" type="text/javascript" >

        function onLoaded() {
  		speechapi.setupRecognition("SIMPLE", 
                            document.getElementById('words').value,false);
	}

	var flashvars = {speechServer : 
                           "rtmp://www.speechapi.com:1935/firstapp"};
        var params = {allowscriptaccess : "always"};
	var attributes = {};
	attributes.id = "flashContent";
	swfobject.embedSWF(
                   "http://www.speechapi.com/static/lib/speechapi-1.1.swf", 
                   "myAlternativeContent", "215", "138", "9.0.28", false,
                   flashvars, params, attributes);
	speechapi.setup("eli","password",onResult, 
			onFinishTTS, onLoaded, "flashContent");

	function onResult(result) {
		document.getElementById('answer').innerHTML = result.text;
		speechapi.speak(result.text,"male");
	}

	function onFinishTTS() { }
	function ResetGrammar() {
		speechapi.setupRecognition("SIMPLE", 
                              document.getElementById('words').value,false);
	}	
</script>
    </head>
    
    


    <body>
       <div id="myAlternativeContent"></div>
       <div id="flashContent"></div>
    </body>
</html>