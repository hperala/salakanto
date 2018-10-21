<h1><?php echo $this->param('heading'); ?></h1>

<div id="content">

<form method="post" action="login.php?a=signup">

<?php
if (!$this->param('root_exists')) {
    echo sprintf('<div class="notification">%s</div>',
                 _('Root user does not exist and must be created next.'));
}
if ($this->param('error') !== '') {
    echo "\n";
    echo sprintf('<div class="field-error">%s</div>',
                 $this->param('error'));
}
?>
<label for="username"><?php echo _('Username:'); ?></label> 
<input type="text" name="username" id="username" autofocus required>
<label for="password"><?php echo _('Password:'); ?></label> 
<input type="password" name="password" id="password" required>

<?php
if ($this->param('can_create_root')) {
?>
<p>
<input type="checkbox" name="create_root" id="create_root" <?php echo $this->param('create_root_checked'); ?> >
<label for="create_root" class="inline"><?php echo _('Create root account'); ?></label>
<label for="root_password"><?php echo _('Enter root creation password:'); ?></label> 
<input type="password" name="root_password" id="root_password">
</p>
<?php
}
?>
<input type="submit">
</form>

</div>