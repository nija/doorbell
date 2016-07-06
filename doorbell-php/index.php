<?php

$__admin_login__ = false;
$__include_prefix__ = '';

include($__include_prefix__ . 'HTML.php');
include($__include_prefix__ . 'Solenoid.php');

$html = new HTML();

$html->title('VinVista Chime Server');
$html->addHeaderScriptLink($__include_prefix__ . "jquery-1.7.1.min.js");
$html->addHeaderScriptLink($__include_prefix__ . "jS.js");

$strikeDelay = 300000;

$sol = array();
$sol['1'] = new Solenoid(21,$strikeDelay);
$sol['2'] = new Solenoid(20,$strikeDelay);
$sol['3'] = new Solenoid(16,$strikeDelay);
$sol['4'] = new Solenoid(24,$strikeDelay);
$sol['5'] = new Solenoid(23,$strikeDelay);
$sol['6'] = new Solenoid(18,$strikeDelay);

if (isset($_POST) && (sizeof($_POST) > 0)) {	// if there's an id specified in the post array, then this is an AJAX request...
	if (isset($_POST['action'])) switch ($_POST['action']) {
		case 'on': $sol[$_POST['solenoid']]->activate(); break;
		case 'off': $sol[$_POST['solenoid']]->deactivate(); break;
		case 'pulse': $sol[$_POST['solenoid']]->pulse($_POST['delay']); break;
		case 'playSequence': playSequence($_POST['sequence']); break;
		default:
	} 
} else {
	$html->writeToClient();
}

function playSequence ($sequence) {
	global $sol, $strikeDelay;
	$currentPos = 0;
	$sequenceDone = false;
	while (! $sequenceDone) {
		// find the next strike and wait locations in the sequence string
		$nextStrikePos = strpos($sequence, '[', $currentPos);
		$nextWaitPos = strpos($sequence, '{', $currentPos);

		// assume the next action is a wait command
		$strikeNext = false;
		
		if ($nextStrikePos === false) {
			// no more strike commands left, so we are done with sequence
			$sequenceDone = true;
		} else {
			if ($nextWaitPos === false) {
				// no more wait commands left so set the strikeNext flag
				$strikeNext = true;
			} else if ($nextStrikePos < $nextWaitPos) {
				// strike happens sooner, so set the strikeNext flag
				$strikeNext = true;		
			}
			
			// STRIKE
			if ($strikeNext) {
				// bump forward the currentPos
				$currentPos = $nextStrikePos + 1;
				
				// clear out the "strikeNext" flags in each Solenoid object
				for ($ctr = 1; $ctr <= 6; $ctr++) {
					$sol[strval($ctr)]->strikeNext = false;
				}
				
				// find the matching end bracket location - if none then kill the program
				$endOfStrikePos = strpos($sequence, ']', $currentPos);
				if ($endOfStrikePos === false) die();
	
				// find the next comma location, if any
				$commaPos = strpos($sequence, ',', $currentPos);
				
				// assume no comma, but if there was one found, and it's sooner than the matching end bracket, then there is legit comma separated list
				$hasComma = false;
				if (($commaPos !== false) && ($commaPos < $endOfStrikePos)) {
					$hasComma = true;
				}
				
				// if a comma separated list, parse everything before the last in the list, then do the last one outside the loop.
				while ($hasComma) {
					// extract the data (substring)
					$solenoidNum = substr($sequence, $currentPos, ($commaPos - $currentPos));
					
					// if the data is a legit solenoid # then flag that solenoid to strikeNext
					if (isset($sol[$solenoidNum])) {
						$sol[$solenoidNum]->strikeNext = true;
					}
					
					// repeat the same search as before to determine if any more commas before the matching end bracket
					$currentPos = $commaPos + 1;
					$commaPos = strpos($sequence, ',', $currentPos);
					$hasComma = false;
					if (($commaPos !== false) && ($commaPos < $endOfStrikePos)) {
						$hasComma = true;
					}
				}
				
				// no more commas, so extract the data
				$solenoidNum = substr($sequence, $currentPos, ($endOfStrikePos - $currentPos));
				if (isset($sol[$solenoidNum])) {
					$sol[$solenoidNum]->strikeNext = true;
				}
				
				// bump forward the currentPos
				$currentPos = $endOfStrikePos + 1;
	
				// activate any solenoids that have the strikeNext flag
				for ($ctr = 1; $ctr <= 6; $ctr++) {
					$sol[strval($ctr)]->strikeIfNext();
				}
				
				// delay
				usleep($strikeDelay);

				// deactivate those solenoids
				for ($ctr = 1; $ctr <= 6; $ctr++) {
					$sol[strval($ctr)]->deactivateIfNext();
				}
			} else { // WAIT
				// bump forward the currentPos
				$currentPos = $nextWaitPos + 1;
				
				// find the matching end bracket location - if none then kill the program
				$endOfWaitPos = strpos($sequence, '}', $currentPos);
				if ($endOfWaitPos === false) die();
	
				// extract the data
				$delayTime = intval(substr($sequence, $currentPos, ($endOfWaitPos - $currentPos)));

				// bump forward the currentPos
				$currentPos = $endOfWaitPos + 1;
	
				// delay (minus the previous strikeDelay!)
				if ($delayTime > $strikeDelay) {
					usleep($delayTime - $strikeDelay);
				}
			}
		}
	}
		


}


?>
