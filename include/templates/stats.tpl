<table class='basic_table' align="center">
	<tr><td class="right"># of Players:</td><td class="left"><?php echo $num_players?></td></tr>
	<tr><td class="right"># of Games:</td><td class="left"><?php echo $num_games?></td></tr>
	<tr><td colspan=2><hr/></td></tr>
	<tr><td colspan=2><h3>IA Skulls Leaders</h3></td></tr>
	<tr><td colspan=2>
		<table align="center">
			<tr>
				<th>Name</th><td>|</td>
				<th>Points</th><td>|</td>
				<th>Num Games</th>
			</tr>
			<?php for($i=0;$i<=10;$i++){?>
			<tr>
				<td><?php echo $player_list_by_points[$i][lastname].', '.$player_list_by_points[$i][firstname]?></td><td>|</td>
				<td><?php echo $player_list_by_points[$i][points]?></td><td>|</td>
				<td><?php echo $player_list_by_points[$i][numgames]?></td><td>|</td>
			</tr>
			<?}?>
		</table>
	</td></tr>
</table>
