<?php
require_once 'include/template/Formatting.php';

function writeNames($names) {
    $rowTemplate = '<tr><td><a href="name.php?id=%d"><i class="el">%s</i></a></td><td>%s</td><td>%s</td></tr>';
    $rowEditTemplate = '<tr><td><a href="name.php?id=%d"><i class="el">%s</i></a></td><td>%s</td><td>%s</td><td class="edit-cell">%s</td><td class="edit-cell">%s</td></tr>';
    $editButtonTemplate = '<form method="GET" action="name.php"><input type="hidden" name="a" value="edit"><input type="hidden" name="id" value="%d"><input type="submit" value="' . _('Edit') . '" class="inline-button"></form>';
    $delButtonTemplate = '<form method="GET" action="name.php"><input type="hidden" name="a" value="delete"><input type="hidden" name="id" value="%d"><input type="submit" value="' . _('Delete') . '" class="inline-button"></form>';

    foreach ($names as $name) {
        if ($name['has_edit_button']) {
            $editButton = vsprintf($editButtonTemplate, $name['id']);
            $delButton = vsprintf($delButtonTemplate, $name['id']);
            echo sprintf($rowEditTemplate, 
                         $name['id'],
                         $name['text'],
                         $name['translations'],
                         $name['notes'],
                         $editButton,
                         $delButton);
        } else {
            echo sprintf($rowTemplate,
                         $name['id'],
                         $name['text'],
                         $name['translations'],
                         $name['notes']);
        }
        echo "\n";
    }
}
?>
<h1><?php echo $this->param('heading'); ?></h1>

<div id="content">

<?php 
if ($this->param('is_logged_in')) {
    echo '<a href="name.php?a=edit" class="nav-link">' . _('Add') . '</a>';
}
?>
<div id="paged-table">
<div class="pages-nav">
<?php echo Formatting::pageLinks($this->param('pagination')); ?>
</div>
<table>
<?php
if ($this->param('is_logged_in')) {
    echo '<tr><th>' . _('Name') . '</th><th>' . _('Translations') . '</th><th>' . _('Etymological notes') . '</th><th class="button-col">&nbsp;</th><th class="button-col">&nbsp;</th></tr>';
} else {
    echo '<tr><th>' . _('Name') . '</th><th>' . _('Translations') . '</th><th>' . _('Etymological notes') . '</th></tr>';
}
echo "\n";
?>
<?php writeNames($this->param('name_list')); ?>
</table>
<div class="pages-nav">
<?php echo Formatting::pageLinks($this->param('pagination')); ?>
</div>
</div>

</div>