<h1><?php echo $this->param('heading'); ?></h1>

<div id="content">

<p><?php echo $this->param('username'); ?></p>

<?php echo $this->param('message'); ?>

<h2><?php echo _('Change password'); ?></h2>

<form action="account.php" method="post">

<label for="old-pass"><?php echo _('Old password:'); ?></label>
<input type="password" name="old-pass" value="" required><br>

<label for="new-pass"><?php echo _('New password:'); ?></label>
<input type="password" name="new-pass" value="" required><br>

<label for="new-pass-2"><?php echo _('Enter new password again:'); ?></label>
<input type="password" name="new-pass-2" value="" required><br>

<input type="submit" value="Send">

</form>

<h2><?php echo _('Other tasks'); ?></h2>

<?php if ($this->param('is-admin')) { ?>
<form action="admin.php" method="GET"><input type="submit" value="<?php echo _('Administration'); ?>"></form>
<?php } ?>
</div>