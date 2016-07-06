<?php


class Solenoid {
	function __construct($pin, $delay) {
		$this->pin = $pin;
		$this->delay = $delay;
		$this->strikeNext = false;
		shell_exec("/usr/local/bin/gpio -g mode " . $this->pin . " out");
	}
	function activate() {
		shell_exec("/usr/local/bin/gpio -g write " . $this->pin . " 1");
	}
	function deactivate() {
		shell_exec("/usr/local/bin/gpio -g write " . $this->pin . " 0");
	}
	function pulse($delay = null) {
		$this->activate();
		usleep(isset($delay) ? $delay : $this->delay);
		$this->deactivate();
	}
	function strikeIfNext() {
		if ($this->strikeNext) {
			$this->activate();
		}
	}
	function deactivateIfNext() {
		if ($this->strikeNext) {
			$this->deactivate();
		}
	}
	
}

?>
