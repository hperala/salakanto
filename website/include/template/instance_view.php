<h1><?php echo $this->param('heading'); ?></h1>

<div id="content">

<?php
if ($this->param('is_logged_in')) {
    $editLink = '<div><a href="instance.php?a=edit&amp;id=%s" class="nav-link">' . _('Edit') . '</a></div>';
    echo sprintf($editLink,
                 $this->param('item')->idHtml());
} 
?>

<table>
<tr><td><?php echo _('The form:'); ?></td>
<td><?php echo $this->param('item')->textHtml(); ?></td></tr>
<tr><td><?php echo _('Source:'); ?></td>
<td><?php echo $this->param('item')->sourceAbbreviation(); ?></td></tr>
<tr><td><?php echo _('Source details:'); ?></td>
<td><?php echo $this->param('item')->sourceElaborationHtml(); ?></td></tr>
<tr><td><?php echo _('Reference:'); ?></td>
<td><?php echo $this->param('item')->refHtml(); ?></td></tr>
<tr><td><?php echo _('Note:'); ?></td>
<td><?php echo $this->param('item')->noteHtml(); ?></td></tr>
<tr><td><?php echo _('Translation:'); ?></td>
<td><?php echo $this->param('item')->translationHtml(); ?></td></tr>
<tr><td><?php echo _('Etymology:'); ?></td>
<td><?php echo $this->param('item')->etymologyHtml(); ?></td></tr>
</table>

</div>