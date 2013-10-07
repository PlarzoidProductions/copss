<table align="center" width="80%">
	<?php for($i=1; $i<=$numplayers; $i++){?>
		<tr><td class="right"><b>Player <?php echo $i?>:</b></td><td class="left"><?php echo $page->displayVar("player".$i)?></td></tr>
	<?php }?>
</table>
