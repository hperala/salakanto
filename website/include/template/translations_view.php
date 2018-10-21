<?php
require_once 'include/template/Formatting.php';

function writeTranslationList($list, $isLoggedIn) {
    $editButtonTemplate = '<form method="GET" action="instance.php">'
                          . '<input type="hidden" name="a" value="edit">'
                          . '<input type="hidden" name="id" value="%d">'
                          . '<input type="submit" value="' . _('Edit') . '" class="inline-button"></form>';
    $rowTemplate = '<tr><td><a href="name.php?id=%d"><i class="el">%s</i></a> ‘%s’ <span class="ref">(%s)</span></td></tr>';
    $editableRowTemplate = '<tr><td><a href="name.php?id=%d"><i class="el">%s</i></a> ‘%s’ <span class="ref">(%s)</span></td><td class="edit-cell">%s</td></tr>';
    $insList = ''; 
    foreach ($list as $item) {
        if ($isLoggedIn) {
            $editButton = sprintf($editButtonTemplate,
                                  123);
            $insList .= sprintf($editableRowTemplate,
                                $item['id'],
                                $item['text'],
                                $item['transl'],
                                $item['ref'],
                                $editButton);
        } else {
            $insList .= sprintf($rowTemplate,
                                $item['id'],
                                $item['text'],
                                $item['transl'],
                                $item['ref']);
        }
        $insList .= "\n";
    }
    echo $insList;
}
?>
<h1><?php echo $this->param('heading'); ?></h1>

<div id="content">

<div id="paged-table">
<div class="pages-nav">
<?php echo Formatting::pageLinks($this->param('pagination')); ?>
</div>
<table>
<?php 
echo writeTranslationList($this->param('name_list'), 
                          $this->param('is_logged_in')); 
?>
</table>
<div class="pages-nav">
<?php echo Formatting::pageLinks($this->param('pagination')); ?>
</div>
</div>

</div>