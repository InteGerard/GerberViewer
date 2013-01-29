<html>
<head>
<title>File read demo</title>
</head>
<body>
<?php

// Generate canvas script, open file, get D02* to indicate beginning of new line, get each of the following
$script = NULL;

$file = "GERBER - orig.gbl";
$f = fopen($file, "r");
while ( $line = fgets($f, 1000) ) {
	

	
	//Decode D02 command
	//@debug print "'" . $line . "'"  . "</br>";
	//print "'" . "D02*" . "'"  . "</br></br>";
	if ( strcmp( trim($line), "D02*") == 0 ) {
		
		$script .= "context.beginPath();\n";
		
		$line = fgets($f, 1000);
		while ( $line[0] == "X") {				//if we're looking at a coordinate line
		
			$coord_num = 0;

			$re1='(X)';	# Any Single Word Character (Not Whitespace) 1
			$re2='(\\d+)';	# Integer Number 1
			$re3='(Y)';	# Any Single Word Character (Not Whitespace) 2
			$re4='(\\d+)';	# Integer Number 2

			if ($c=preg_match_all ("/".$re1.$re2.$re3.$re4."/is", $line, $matches)) {
				$xVal =$matches[2][0];
				$yVal=$matches[4][0];
				
				echo "$xVal , $yVal\n";
				print "</br>";	
				
				// if it's the first co-ord in the new track, move to the initial coordinate
				if ($coord_num = 0) {				
					$script .= "context.moveTo(" . $xVal/10 . "," . $yVal/10 . ");\n";
				} else {
					$script .= "context.lineTo(" . $xVal/10 . "," . $yVal/10 . ");\n";
				}
			}

			echo "\n\n";
			$line = fgets($f, 1000);
			$coord_num += 1;
		}
		$script .= "context.stroke();";		
		
	}
}
?>




<canvas id="myCanvas" width="578" height="200"></canvas>
    <script>
	
      var canvas = document.getElementById('myCanvas');
      var context = canvas.getContext('2d');
	  
	<?php
		echo $script;
	 ?>
	  
</script>
	
	
</body>
</html>