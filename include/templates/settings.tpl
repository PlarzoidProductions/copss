<form action="<?php echo $form_action?>" method="<?php echo $form_method?>">
<table align="center" width="80%">
	<tr><td class="right"><b>Convention Name:</b></td><td class="left"><?php echo $page->displayVar("name")?></td></tr>
	<tr align="center"><td colspan=2><hr/></td></tr>
	<tr align="center"><td colspan=2><em>This section details the points awarded for every game played.</em></td></tr>
        <tr><td class="right"><b>Team Game Award:</b></td><td class="left"><?php echo $page->displayVar("teamgame")?></td></tr>
	<tr><td class="right"><b>New Opponent Award:</b></td><td class="left"><?php echo $page->displayVar("newopponent")?></td></tr>
	<tr><td class="right"><b>Out of State Award:</b></td><td class="left"><?php echo $page->displayVar("outofstate")?></td></tr>
	<tr><td class="right"><b>Fully Painted Army:</b></td><td class="left"><?php echo $page->displayVar("fullypainted")?></td></tr>
	<tr><td class="right"><b>Fully Painted Table Award:</b></td><td class="left"><?php echo $page->displayVar("fullypaintedall")?></td></tr>
	<tr><td class="right"><b>Scenario Table Award:</b></td><td class="left"><?php echo $page->displayVar("scenariotable")?></td></tr>
	<tr align="center"><td colspan=2><hr/></td></tr>
        <tr align="center"><td colspan=2><em>This section begins the list of one time achievements.</em></td></tr>
        <tr><td class="right"><b>25pt Game Award:</b></td><td class="left"><?php echo $page->displayVar("played25")?></td></tr>
        <tr><td class="right"><b>35pt Game Award:</b></td><td class="left"><?php echo $page->displayVar("played35")?></td></tr>
        <tr><td class="right"><b>50pt Game Award:</b></td><td class="left"><?php echo $page->displayVar("played50")?></td></tr>
        <tr><td class="right"><b>75pt Game Award:</b></td><td class="left"><?php echo $page->displayVar("played75")?></td></tr>
        <tr><td class="right"><b>100pt Game Award:</b></td><td class="left"><?php echo $page->displayVar("played100")?></td></tr>
        <tr><td class="right"><b>Unbound Game Award:</b></td><td class="left"><?php echo $page->displayVar("playedUNBOUND")?></td></tr>
	<tr><td class="right"><b>Play All Game Sizes Award:</b></td><td class="left"><?php echo $page->displayVar("playedall")?></td></tr>
        <tr align="center"><td colspan=2><hr/></td></tr>
	<tr align="center"><td colspan=2><em>This section details awards based upon achieving a certain count of some statistic, <br/>such as games played or opponents from uniquely different states, etc.<br/>To set custom achievement points and their award values, each pair is separated by a semi-colon,<br/>and each value by a comma.  For instance, to set achievements at <br/>5 games, 10 games and 15 games played awarding 1, 2 and 3 points respectively, <br/>the correct code would be:</em> 5,1;10,2;15,3</td></tr>	
        <tr><td class="right"><b>Games Played:</b></td><td class="left"><?php echo $page->displayVar("numgames")?></td></tr>
        <tr><td class="right"><b>Unique Opponents:</b></td><td class="left"><?php echo $page->displayVar("numplayers")?></td></tr>
        <tr><td class="right"><b>Unique Opponent Locations:</b></td><td class="left"><?php echo $page->displayVar("numlocations")?></td></tr>
        <tr><td class="right"><b>Unique Opponent Factions:</b></td><td class="left"><?php echo $page->displayVar("numfactions")?></td></tr>
	<tr align="center"><td colspan=2><hr/></td></tr>
        <tr><td class="right"><b>Play vs Staff:</b></td><td class="left"><?php echo $page->displayVar("vsstaff")?></td></tr>
        <tr align="center"><td colspan=2><hr/></td></tr>
        <tr align="center"><td colspan=2><em>This section allows for custom event bonuses, such as playing in a tournament, or wearing an event shirt.<br/>Set a name in the appropriate field, and set a value.<br/>Events with their award point value set to 0 will not be used.</em></td></tr>
	<tr><td class="right"><b>Event 1 Name:</b></td><td class="left"><?php echo $page->displayVar("event1name")?> = <?php echo $page->displayVar("event1")?> points</td></tr>
        <tr><td class="right"><b>Event 2 Name:</b></td><td class="left"><?php echo $page->displayVar("event2name")?> = <?php echo $page->displayVar("event2")?> points</td></tr>
        <tr><td class="right"><b>Event 3 Name:</b></td><td class="left"><?php echo $page->displayVar("event3name")?> = <?php echo $page->displayVar("event3")?> points</td></tr>
        <tr><td class="right"><b>Event 4 Name:</b></td><td class="left"><?php echo $page->displayVar("event4name")?> = <?php echo $page->displayVar("event4")?> points</td></tr>
        <tr><td class="right"><b>Event 5 Name:</b></td><td class="left"><?php echo $page->displayVar("event5name")?> = <?php echo $page->displayVar("event5")?> points</td></tr>
        <tr><td class="right"><b>Event 6 Name:</b></td><td class="left"><?php echo $page->displayVar("event6name")?> = <?php echo $page->displayVar("event6")?> points</td></tr>
        <tr><td class="right"><b>Event 7 Name:</b></td><td class="left"><?php echo $page->displayVar("event7name")?> = <?php echo $page->displayVar("event7")?> points</td></tr>
        <tr><td class="right"><b>Event 8 Name:</b></td><td class="left"><?php echo $page->displayVar("event8name")?> = <?php echo $page->displayVar("event8")?> points</td></tr>
        <tr><td class="right"><b>Event 9 Name:</b></td><td class="left"><?php echo $page->displayVar("event9name")?> = <?php echo $page->displayVar("event9")?> points</td></tr>
        <tr><td class="right"><b>Event 10 Name:</b></td><td class="left"><?php echo $page->displayVar("event10name")?> = <?php echo $page->displayVar("event10")?> points</td></tr>
        <tr><td class="right"><b>Event 11 Name:</b></td><td class="left"><?php echo $page->displayVar("event11name")?> = <?php echo $page->displayVar("event11")?> points</td></tr>
        <tr><td class="right"><b>Event 12 Name:</b></td><td class="left"><?php echo $page->displayVar("event12name")?> = <?php echo $page->displayVar("event12")?> points</td></tr>
        <tr><td class="right"><b>Event 13 Name:</b></td><td class="left"><?php echo $page->displayVar("event13name")?> = <?php echo $page->displayVar("event13")?> points</td></tr>
        <tr><td class="right"><b>Event 14 Name:</b></td><td class="left"><?php echo $page->displayVar("event14name")?> = <?php echo $page->displayVar("event14")?> points</td></tr>
        <tr><td class="right"><b>Event 15 Name:</b></td><td class="left"><?php echo $page->displayVar("event15name")?> = <?php echo $page->displayVar("event15")?> points</td></tr>
        <tr><td class="right"><b>Event 16 Name:</b></td><td class="left"><?php echo $page->displayVar("event16name")?> = <?php echo $page->displayVar("event16")?> points</td></tr>
        <tr><td class="right"><b>Event 17 Name:</b></td><td class="left"><?php echo $page->displayVar("event17name")?> = <?php echo $page->displayVar("event17")?> points</td></tr>
        <tr><td class="right"><b>Event 18 Name:</b></td><td class="left"><?php echo $page->displayVar("event18name")?> = <?php echo $page->displayVar("event18")?> points</td></tr>
        <tr><td class="right"><b>Event 19 Name:</b></td><td class="left"><?php echo $page->displayVar("event19name")?> = <?php echo $page->displayVar("event19")?> points</td></tr>
        <tr><td class="right"><b>Event 20 Name:</b></td><td class="left"><?php echo $page->displayVar("event20name")?> = <?php echo $page->displayVar("event20")?> points</td></tr>
        <tr align="center"><td colspan=2><hr/></td></tr>
        <tr><td class="right"><b>Min Time Between Games:</b></td><td class="left"><?php echo $page->displayVar("gametimelimit")?> minutes</td></tr>a
	<tr align="center"><td colspan=2><hr/></td></tr>
	<tr align="center"><td colspan=2><?php echo $page->displayVar("settings_submit")?></td></tr>
</table>
</form>	
