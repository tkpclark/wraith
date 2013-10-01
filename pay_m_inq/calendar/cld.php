	<script type="text/javascript" src="calendar/js/mootools.js"></script>
	<script type="text/javascript" src="calendar/js/calendar.rc4.js"></script>
	<script type="text/javascript">		
	//<![CDATA[
		window.addEvent('domready', function() { 
			//myCal1 = new Calendar({ date1: 'd/m/Y' }, { direction: 1, tweak: {x: 6, y: 0} });
			//myCal2 = new Calendar({ date2: 'd/m/Y' }, { classes: ['dashboard'], direction: 1, tweak: {x: 3, y: -3} });
			//myCal3 = new Calendar({ date3: 'd/m/Y' }, { classes: ['i-heart-ny'], direction: 1, tweak: {x: 3, y: 0} });
			
			myCal1 = new Calendar({ date1: 'Y-m-d' }, { classes: ['dashboard'], direction: 0, tweak: {x: 3, y: -3} });
			myCal2 = new Calendar({ date2: 'Y-m-d' }, { classes: ['dashboard'], direction: 0, tweak: {x: 3, y: -3} });
		});
	//]]>
	</script>
	<link rel="stylesheet" type="text/css" href="calendar/css/dashboard.css" media="screen" />
	<!--
	<link rel="stylesheet" type="text/css" href="calendar/css/iframe.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="calendar/css/calendar.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="calendar/css/dashboard.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="calendar/css/i-heart-ny.css" media="screen" />
	-->
	

<!-- 
<input id="date2" name="date2" type="text" />
-->