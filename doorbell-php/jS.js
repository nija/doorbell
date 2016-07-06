
jS = { 
	init: function() {
		for (var ctr = 1; ctr <= 6; ctr ++) {
			$('#s' + ctr + 'on').click(jS['s' + ctr + 'on']);
			$('#s' + ctr + 'off').click(jS['s' + ctr + 'off']);
			$('#s' + ctr + 'pulse').click(jS['s' + ctr + 'pulse']);
		}
		$('#playSequence').click(jS.playSequence);
	},
	playSequence: function() {
		var sequence = $('#sequence').val();
		$.post(jS.indexURL, {'action': 'playSequence', 'sequence': sequence });
	},
	
	s1on: function() { $.post(jS.indexURL, {'solenoid': '1', 'action': 'on' }); },
	s2on: function() { $.post(jS.indexURL, {'solenoid': '2', 'action': 'on' }); },
	s3on: function() { $.post(jS.indexURL, {'solenoid': '3', 'action': 'on' }); },
	s4on: function() { $.post(jS.indexURL, {'solenoid': '4', 'action': 'on' }); },
	s5on: function() { $.post(jS.indexURL, {'solenoid': '5', 'action': 'on' }); },
	s6on: function() { $.post(jS.indexURL, {'solenoid': '6', 'action': 'on' }); },

	s1off: function() { $.post(jS.indexURL, {'solenoid': '1', 'action': 'off' }); },
	s2off: function() { $.post(jS.indexURL, {'solenoid': '2', 'action': 'off' }); },
	s3off: function() { $.post(jS.indexURL, {'solenoid': '3', 'action': 'off' }); },
	s4off: function() { $.post(jS.indexURL, {'solenoid': '4', 'action': 'off' }); },
	s5off: function() { $.post(jS.indexURL, {'solenoid': '5', 'action': 'off' }); },
	s6off: function() { $.post(jS.indexURL, {'solenoid': '6', 'action': 'off' }); },

	s1pulse: function() { $.post(jS.indexURL, {'solenoid': '1', 'action': 'pulse', 'delay': $('#pulseDelay').val() }); },
	s2pulse: function() { $.post(jS.indexURL, {'solenoid': '2', 'action': 'pulse', 'delay': $('#pulseDelay').val() }); },
	s3pulse: function() { $.post(jS.indexURL, {'solenoid': '3', 'action': 'pulse', 'delay': $('#pulseDelay').val() }); },
	s4pulse: function() { $.post(jS.indexURL, {'solenoid': '4', 'action': 'pulse', 'delay': $('#pulseDelay').val() }); },
	s5pulse: function() { $.post(jS.indexURL, {'solenoid': '5', 'action': 'pulse', 'delay': $('#pulseDelay').val() }); },
	s6pulse: function() { $.post(jS.indexURL, {'solenoid': '6', 'action': 'pulse', 'delay': $('#pulseDelay').val() }); },
	
	indexURL: 'index.php'
};

$(jS.init);

