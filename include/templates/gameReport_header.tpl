<form action="<?php echo $form_action?>" method="<?php echo $form_method?>">
<table align="center" width="80%">

<?php if(!empty($errors)){
	foreach($errors as $e){
		echo'<tr align="center"><td colspan=2><font class="warning">'.$e.'</font></td></tr>';
	}
}?>

<?php if(!$page->submitIsSet("submit_report")){?>		
	<tr><td class="right"><b>Number of Players:</b></td><td class="left"><?php echo $page->displayVar("numopponents")?></td></tr>
	<tr><td colspan=2><hr/></td></tr>
	<tr><label><td class="right"></td><td class="left"><?php echo $page->displayVar("was_scenariotable")?></td></tr>
<?php }?>
	<tr><td colspan=2><hr/></td></tr>
</table>
	
