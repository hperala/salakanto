<h1><?php echo $this->param('heading'); ?></h1>

<div id="content">

<?php
function formatSource($id, $text, $elaboration) {
    $str = sprintf('<a href="source.php?id=%s">%s</a>',
                   $id,
                   $text);
    if ($elaboration) {
        $str = sprintf('%s [%s]', 
                       $str, 
                       $elaboration);
    }
    return $str;
}


if ($this->param('is_logged_in')) {
    $editLink = '<div><a href="name.php?a=edit&amp;id=%s" class="nav-link">' . _('Edit') . '</a></div>' . "\n";
    echo sprintf($editLink,
                 $this->param('item')->idHtml());
}

if ($this->param('text')
    || $this->param('index_coverage')
    || $this->param('other_coverage')) {
    echo "<table class=\"nameinfo\">\n";
    $rowTemplate = "<tr><th>%s</th><td>%s</td></tr>\n";
    if ($this->param('text')) {
        echo sprintf($rowTemplate, 
                     _('Description:'), 
                     $this->param('text'));
    }
    if ($this->param('index_coverage')) {
        echo sprintf($rowTemplate,
                     _('The following sources have been covered by checking every reference in the index:'),
                     $this->param('index_coverage'));
    }
    if ($this->param('other_coverage')) {
        echo sprintf($rowTemplate,
                     _('The following sources have been covered by other means:'),
                     $this->param('other_coverage'));
    }
    echo "</table>\n";
}

?>

<h2><?php echo _('Forms'); ?></h2>
<span id="button-short" class="nav-link-sel">
<?php echo _('Short list'); ?>
</span>
<a href="javascript:showLong();" id="button-long" class="nav-link">
<?php echo _('Full details'); ?>
</a>

<div id="forms-short">
<?php
$formattedForms = array();
foreach ($this->param('distinct_forms') as $form) {
    $formattedForms[] = '<i class="el">' . $form . '</i>';
}
echo "<p>" . implode(', ', $formattedForms) . "</p>\n"; 
?>
</div>
<div id="forms-long" style="display: none">
<?php 
foreach ($this->param('forms_by_source') as $source) {
    echo '<p>';
    $formattedItems = array();
    foreach ($source['items'] as $item) {
        $formattedItem = '<i class="el">' . $item['text'] . '</i>';
        if ($item['note']) {
            $formattedItem .= ' <small class="note">[' . $item['note'] . ']</small>';
        } 
        $formattedItem .= ' (' . $item['ref'] . ')';
        $formattedItems[] = $formattedItem; 
    }
    echo implode(', ', $formattedItems); 
    echo ' — ' . formatSource($source['source_id'], $source['source_text'], 
                              $source['source_elaboration']);
    echo "</p>\n";
}
?>
</div>

<h2><?php echo _('Translations and etymological notes'); ?></h2>

<?php 
echo "<div class=\"translations\">\n";
foreach ($this->param('etyms') as $etym) {
    echo '<p>';
    echo '<i class="el">' . $etym['text'] . '</i>';
    if ($etym['translation']) {
        echo ' ‘' . $etym['translation'] . '’'; 
    }
    if ($etym['etymology']) {
        echo ': ' . $etym['etymology'];
    }
    echo ' — <a href="source.php?id=' . $etym['source_id'] . '">'
         . $etym['source_text'] . '</a>';
    if ($etym['source_elaboration']) {
        echo ' [' . $etym['source_elaboration'] . ']';
    }
    echo ' (' . $etym['ref'] . ')';
    echo "</p>\n";
}
echo "</div>\n";

if ($this->param('notes')) {
    echo '<h2>' . _('Notes') . '</h2>' . "\n";
    echo $this->param('notes') . "\n";
}

if ($this->param('secondary')) {
    echo '<h2>' . _('Secondary sources') . '</h2>' . "\n";
    echo $this->param('secondary') . "\n";
}
?>
</div>