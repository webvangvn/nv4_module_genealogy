<!-- BEGIN: main  -->
<link href="/assets/js/genealogy/day.css" type="text/css" rel="stylesheet">
<script type="text/javascript" language="JavaScript" src="/assets/js/genealogy/day.js"></script>
<div id="anniversary">
	<table >
	<tbody><tr>
	<td colspan="2"><div  id="namd" class="namd"></div></td></tr>
	<tr><td colspan="2"><div  id="thangd" class="thangd"></div></td></tr>
	<tr><td colspan="2"><div id="ngayd" class="ngayd"></div></td></tr>
	<tr><td colspan="2"><div  id="thu" class="thuduong"></div></td></tr>

	<tr>
	<td><div  class="canchi"> 
	<div id="thangam" class="thangnamam"></div>
	<div id="ngayam" class="ngayam"></div>
	<div id="namam" class="thangnamam"></div>
	</div></td>
	<td><div  class="canchi">
	<div id="canchithang" class="gioam"></div>
	
	<div id="canchingay" class="gioam"></div>
	<div id="canchigio" class="gioam"></div>
	<div id="tietkhi" class="gioam"></div>
	
	</div></td>
	</tr>
	<tr>
	<td colspan="2">
	<div id="dayinfo" class="info"></div>
	</td>
	</tr>

	
	</tbody></table>
	
</div>
<script type="text/javascript" language="JavaScript">
	var YEARLY_EVENTS = new Array(
	<!-- BEGIN: anniversary -->
		new YearlyEvent({ANNIVERSARY.anniversary_day},{ANNIVERSARY.anniversary_mont},' {LANG.anniversary}: {ANNIVERSARY.full_name} ({ANNIVERSARY.anniversary_day}/{ANNIVERSARY.anniversary_mont})')
			<!-- BEGIN: comma -->
			,
			<!-- END: comma -->
	<!-- END: anniversary -->
	
	);
	showDateTime();

</script>
<!-- END: main -->