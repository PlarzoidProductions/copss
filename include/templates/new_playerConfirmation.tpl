<form action="<?php echo $form_action?>" method="<?php echo $form_method?>">

<table align="center" width="78%"><tr><td align="center">
	<table align="center">
		<tr align="center"><td><h2><?php if($success){ ?>New Player Successfully Created!
						<?php } else { ?><font class="warning">Player Creation Failed!</font><?php }?>
			</h2></td></tr>
		<tr><td><hr></td></tr>
	</table>
</td><tr>
<tr><td align="center">
	<table align="center" width="78%">
		<tr><td class="right"><b>First Name:</b></td><td class="left"><?php echo $page->displayVar("firstname")?></td></tr>
		<?php if(!$success && $username_errors){ ?><tr><td colspan=2 align="center"><font class="warning"><?php echo $username_errors?></font></td></tr><?php }?>
		<tr><td class="right"><b>Last Name:</b></td><td class="left"><?php echo $page->displayVar("lastname")?></td></tr>
                <?php if(!$success && $password_errors){ ?><tr><td colspan=2 align="center"><font class="warning"><?php echo $password_errors?></font></td></tr><?php }?>
		<tr><td class="right"><b>Forum Name:</b></td><td class="left"><?php echo $page->displayVar("forumname")?></td></tr>
		<tr><td class="right"><b>E-mail:</b></td><td class="left"><?php echo $page->displayVar("email")?></td></tr>
		<tr><td class="right"><b>Location:</b></td><td class="left"><?php echo $page->displayVar("location")?></td></tr>
		<?php if($page->getVar("vip")){?><tr align="center"><td colspan=2>* VIP *</td></tr><?php }?>
		<tr><td colspan=2><hr></td></tr>
		<tr align="center"><td colspan=2><a href="index.php?view=Add_Player"><?php if(!$success) {?>Try Again!<?php } else { ?>Add another player!<?php }?></a></td></tr>
	</table>
</td></tr>
</table>

</form>
