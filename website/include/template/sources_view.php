<?php
require_once 'include/template/Formatting.php';

function writeSources($sources) {
    $rowTemplate = '<tr><td><a href="source.php?id=%d">%s</a></td></tr>';
    $rowEditTemplate = '<tr><td><a href="source.php?id=%d">%s</a></td><td class="edit-cell">%s</td><td class="edit-cell">%s</td></tr>';
    $editButtonTemplate = '<form method="GET" action="source.php"><input type="hidden" name="a" value="edit"><input type="hidden" name="id" value="%d"><input type="submit" value="' . _('Edit') . '" class="inline-button"></form>';
    $delButtonTemplate = '<form method="GET" action="source.php"><input type="hidden" name="a" value="delete"><input type="hidden" name="id" value="%d"><input type="submit" value="' . _('Delete') . '" class="inline-button"></form>';

    foreach ($sources as $source) {
        if ($source['has_edit_button']) {
            $editButton = vsprintf($editButtonTemplate, $source['id']);
            $delButton = vsprintf($delButtonTemplate, $source['id']);
            echo sprintf($rowEditTemplate,
                         $source['id'],
                         $source['text'],
                         $editButton,
                         $delButton);
        } else {
            echo sprintf($rowTemplate,
                         $source['id'],
                         $source['text']);
        }
    }
}
?>
<h1><?php echo $this->param('heading'); ?></h1>

<div id="content">

<div id="paged-table">
<div class="pages-nav">
<?php echo Formatting::pageLinks($this->param('pagination')); ?>
</div>
<table>
<?php writeSources($this->param('source_list')); ?>
</table>
<div class="pages-nav">
<?php echo Formatting::pageLinks($this->param('pagination')); ?>
</div>
</div>

</div>