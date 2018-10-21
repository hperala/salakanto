<h1><?php echo $this->param('heading'); ?></h1>

<div id="content">

<?php
if ($this->param('is_logged_in')) {
    $editLink = '<div><a href="source.php?a=edit&amp;id=%s" class="nav-link">' . _('Edit') . '</a></div>';
    echo sprintf($editLink, 
                 $this->param('item')->idHtml());
}
?>
<p>
<?php echo $this->param('item')->abbreviationHtml(); ?>:
<?php echo $this->param('item')->textHtml(); ?>
</p>

</div>