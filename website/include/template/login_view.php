<h1><?php echo $this->param('heading'); ?></h1>

<div id="content">

<form method="post" action="login.php">

<?php
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

<input type="submit">
</form>

</div>