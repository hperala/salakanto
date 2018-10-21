<h1><?php echo $this->param('heading'); ?></h1>

<div id="content">

<?php echo $this->param('message'); ?>

<h2><?php echo _('Import data'); ?></h2>

<form action="admin.php" method="POST" enctype="multipart/form-data">
<input type="hidden" name="a" value="import">
<label for="file"><?php echo _('Filename:'); ?></label>
<input type="file" name="file" id="file"><br>

<input type="submit" name="submit">
</form>

<h2><?php echo _('Users'); ?></h2>

<form action="admin.php" method="POST">
<input type="hidden" name="a" value="editusers">
<table>
<tr><th><?php echo _('Username'); ?></th><th><?php echo _('Created'); ?></th><th><?php echo _('Enabled'); ?></th><th><?php echo _('Delete'); ?></th></tr>
<?php
$userTemplate = '<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>';
foreach ($this->param('users') as $user) {
    $formattedDate = $user['date'];
    $enabledCheckbox = '<input type="checkbox" name="enabled_users[]" value="123">';
    $deleteCheckbox = '<input type="checkbox" name="users_to_delete[]" value="123">';
    echo sprintf($userTemplate,
                 $user['text'],
                 $formattedDate,
                 $enabledCheckbox,
                 $deleteCheckbox);
    echo "\n";    
}
?>
</table>
<input type="submit" name="submit">
</form>
</div>