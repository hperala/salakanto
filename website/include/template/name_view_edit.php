<h1><?php echo $this->param('heading'); ?></h1>

<div id="content">

<form action="<?php echo $this->param('form_handler'); ?>" method="post">

<label for="title"><?php echo _('Title:'); ?></label>
<input type="text" name="title" value="<?php echo $this->param('item')->title(); ?>"><br>
<div class="field-desc"><?php echo _('A typical, well known form of the name.'); ?></div>
<?php echo $this->fieldError('title'); ?>

<label for="title"><?php echo _('Description:'); ?></label>
<input type="text" name="text" value="<?php echo $this->param('item')->text(); ?>"><br>
<div class="field-desc"><?php echo _('Full description of the scope of this entry, if needed.'); ?></div>
<?php echo $this->fieldError('text'); ?>

<label for="title"><?php echo _('Indexes covered:'); ?></label>
<input type="text" name="index_coverage" value="<?php echo $this->param('item')->indexCoverage(); ?>"><br>
<div class="field-desc"><?php echo _('Sources that have been covered by checking every occurrence of the name given in the index of that source. A list of abbreviations.'); ?></div>
<?php echo $this->fieldError('index_coverage'); ?>

<label for="other_coverage"><?php echo _('Other covered sources:'); ?></label>
<input type="text" name="other_coverage" value="<?php echo $this->param('item')->otherCoverage(); ?>"><br>
<div class="field-desc"><?php echo _('Information about other sources that have been processed using some method.'); ?></div>
<?php echo $this->fieldError('other_coverage'); ?>

<label for="secondary"><?php echo _('Secondary sources:'); ?></label>
<textarea rows="7" cols="50" name="secondary">
<?php echo $this->param('item')->secondary(); ?>
</textarea><br>
<div class="field-desc"><?php echo _('Annotated bibliography of material relevant to this name. Markup allowed, enclose text in [p][/p].'); ?></div>
<?php echo $this->fieldError('secondary'); ?>

<label for="notes"><?php echo _('Notes:'); ?></label>
<textarea rows="7" cols="50" name="notes">
<?php echo $this->param('item')->notes(); ?>
</textarea><br>
<div class="field-desc"><?php echo _('Other information about this name. For example: first appearance, normalization of spelling in some source. Markup allowed, enclose text in [p][/p].'); ?></div>
<?php echo $this->fieldError('notes'); ?>

<input type="submit" value="Send">
</form>

<h2><?php echo _('Instances:'); ?></h2>

<a href="instance.php?a=edit" class="nav-link"><?php echo _('Add'); ?></a>

<table>
<tr><th><?php echo _('Instance'); ?></th><th class="button-col">&nbsp;</th><th class="button-col">&nbsp;</th></tr>
<?php
$instanceTemplate = '<tr><td><a href="instance.php?id=%s">%s</a></td><td>'
                    . '<form method="GET" action="instance.php"><input type="hidden" name="a" value="edit"><input type="hidden" name="id" value="%s"><input type="submit" value="' . _('Edit') . '" class="inline-button"></form>'
                    . '</td><td>'
                    . '<form method="GET" action="instance.php"><input type="hidden" name="a" value="delete"><input type="hidden" name="id" value="%s"><input type="submit" value="' . _('Delete') . '" class="inline-button"></form>'
                    . '</td></tr>';
foreach ($this->param('instance_list') as $instance) {
    echo sprintf($instanceTemplate,
                 $instance['id'], 
                 $instance['text'], 
                 $instance['id'],
                 $instance['id']);    
}
?>
</table>
</div>