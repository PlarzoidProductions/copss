<form action="<?php echo $form_action?>" method="<?php echo $form_method?>">

	<table align="center" width="78%">
		<tr align="center"><td colspan=2><font class="warning"><h3>Modify Player</h3></font><td></tr>
		<tr align="center"><td colspan=2><hr/></td></tr>
		<?php if(!$selected){?><tr align="center"><td class="right">Choose Player to Modify:</td><td class="left"><?php echo $page->displayVar("player")?></td>
		<tr align="center"><td colspan=2><hr/></td></tr>
		<?php } else { ?>
		<tr><td colspan=2><input type="hidden" name="player" value="<?php echo $pid ?>">
		<tr align="center"><td class="right">First Name:</td><td class="left"><?php echo $page->displayVar("firstname")?><?php if($errors[firstname]){?><font color="red"><?php echo $errors[firstname]?></font><?php }?></td></tr>
		<tr align="center"><td class="right">Last Name:</td><td class="left"><?php echo $page->displayVar("lastname")?><?php if($errors[lastname]){?><font color="red"><?php echo $errors[lastname]?></font><?php }?></td></tr>
		<tr align="center"><td class="right">E-mail:</td><td class="left"><?php echo $page->displayVar("email")?><?php if($errors[email]){?><font color="red"><?php echo $errors[email]?></font><?php }?></td></tr>
		<tr align="center"><td class="right">Forum Name:</td><td class="left"><?php echo $page->displayVar("forumname")?><?php if($errors[forumname]){?><font color="red"><?php echo $errors[forumname]?></font><?php }?></td></tr>
		<tr align="center"><td class="right">Location:</td><td class="left"><?php echo $page->displayVar("location_type")?> | <?php echo $page->displayVar("location")?></td></tr>
		<tr align="center"><td colspan=2><label><?php echo $page->displayVar("vip")?></label></td></tr>
		<tr align="center"><td colspan=2 align="center"><?php echo $page->displayVar("updateplayer_submit")?> <input type="submit" name="deleteplayer_submit" value="Delete Player" onclick="confirm('Delete Player?')"></td></tr>
		<?php } ?>
	</table>
</form>
