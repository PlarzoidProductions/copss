<form action="<?php echo $form_action?>" method="<?php echo $form_method?>">
<table align="center" width="80%">

<?php if(!$page->submitIsSet("submit_event")) {?>		
	<tr><td class="right"><b>Number of Players:</b></td><td class="left"><?php echo $page->displayVar("numplayers")?></td></tr>
	<tr><td class="right"><b>Event:</b></td><td class="left"><?php echo $page->displayVar("event_chooser")?></td></tr>
<?php }?>
	<tr><td colspan=2><hr/></td></tr>
</table>
	
