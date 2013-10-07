<table align="center" width="80%">
	<tr><td colspan=2 align="center"><h3>Player <?php echo $i?> Information</h3></td></tr>
	<tr><td class="right"><b>Player <?php echo $i?>:</b></td><td class="left"><?php echo $page->displayVar("player".$i)?></td></tr>
	<tr><td class="right"><b>Player <?php echo $i?> Army Size:</b></td><td class="left"><?php echo $page->displayVar("player".$i."size")?></td></tr>
	<tr><td class="right"><b>Player <?php echo $i?> Faction:</b></td><td class="left"><?php echo $page->displayVar("player".$i."faction")?></td></tr>
	<tr><td class="right"><b>Player <?php echo $i?> Painted:</b></td><td class="left"><?php echo $page->displayVar("player".$i."painted")?></td></tr>
	<?php if($page->submitIsSet("submit_report")) {?>
	<tr><td colspan=2>
		<font color="green"><h3>Earned <?php echo $points_gained[$i]?> points for a new total of <?php echo $new_points[$i]?></h3></font>
	</td></tr>
	<?php }?>
</table>
