<?php

class HTML {
	function __construct() {
		$this->title = "";
		$this->headerScriptLinks = array();
		$this->content = "";
		}
	function addHeaderScriptLink($url) {
		$this->headerScriptLinks[] = $url;
	}
	function title($buffer) {
		$this->title = $buffer;
	}
	function addContent($newContent) {
		$this->content .= $newContent;
	}
	function writeToClient() {
			global $strikeDelay;
			echo '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
	<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >';
			foreach ($this->headerScriptLinks as $headerScriptLink) echo '
			<script type="text/javascript" src="' . $headerScriptLink . '"></script>';
			echo '
			<title>' . $this->title . '</title>
		</head>
		<body>
		<div id="wrapper">
			';
		
			for ($ctr = 1; $ctr <= 6; $ctr ++) {
				echo '<strong>Solenoid #' . $ctr . ':</strong>&nbsp;&nbsp;';
				echo '<a id="s' . $ctr . 'on">on</a>&nbsp;';
				echo '<a id="s' . $ctr . 'off">off</a>&nbsp;';
				echo '<a id="s' . $ctr . 'pulse">pulse</a><br>
			';
			}
	
			echo '<hr>
			
			<h2>
				Pulse Delay (microseconds):
			</h2>
			<input id="pulseDelay" value="' . $strikeDelay . '"><br>
			<hr>
			<h2>
				<a id="playSequence">Play Sequence</a>
			</h2><br>
			<textarea id="sequence" cols="80" rows="10">
startup testing each solenoid
[1]{500000}[2]{500000}[3]{500000}[4]{500000}[5]{500000}[6]{500000}

delay for 2 seconds between tests
{2000000}

run chord tests
[1,3]{500000}[2,4]{500000}[3,5]{500000}[4,6]{500000}
</textarea>
		</div>
	</body>
</html>';
	}
}

?>