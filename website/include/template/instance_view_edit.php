<h1><?php echo $this->param('heading'); ?></h1>

<div id="content">

<form action="<?php echo $this->param('form_handler'); ?>" method="post">

<label for="text"><?php echo _('The form:'); ?></label>
<input type="text" name="text" value="<?php echo $this->param('item')->text(); ?>"><br>
<div class="field-desc"><?php echo _('Single form only, no extra notes.'); ?></div>
<?php echo $this->fieldError('text'); ?>

<label for="source_id"><?php echo _('Source:'); ?></label>
<select name="source_id">
<?php
foreach ($this->param('sources') as $option) {
    $sel = $option['id'] == $this->param('item')->sourceId()
           ? 'selected="selected"'
           : '';
    echo sprintf("<option value='%s' %s>%s</option>\n", 
                 $option['id'],
                 $sel,
                 $option['text']);
}
?>
</select>
<div class="field-desc"><?php echo _('The document where this form appears.'); ?></div>
<?php echo $this->fieldError('source_id'); ?>

<label for="source_elaboration"><?php echo _('Source details:'); ?></label>
<input type="text" name="source_elaboration" value="<?php echo $this->param('item')->sourceElaboration(); ?>"><br>
<div class="field-desc"><?php echo _('Details of the source, if the selected label is very generic.'); ?></div>
<?php echo $this->fieldError('source_elaboration'); ?>

<label for="ref"><?php echo _('Reference:'); ?></label>
<input type="text" name="ref" value="<?php echo $this->param('item')->ref(); ?>"><br>
<div class="field-desc"><?php echo _('Page reference.'); ?></div>
<?php echo $this->fieldError('ref'); ?>

<label for="note"><?php echo _('Note:'); ?></label>
<input type="text" name="note" value="<?php echo $this->param('item')->note(); ?>"><br>
<div class="field-desc"><?php echo _('A note concerning this particular instance.'); ?></div>
<?php echo $this->fieldError('note'); ?>

<label for="translation"><?php echo _('Translation:'); ?></label>
<input type="text" name="translation" value="<?php echo $this->param('item')->translation(); ?>"><br>
<div class="field-desc"><?php echo _('Leave empty if no explicit translation is given for this instance.'); ?></div>
<?php echo $this->fieldError('translation'); ?>

<label for="etymology"><?php echo _('Etymology:'); ?></label>
<textarea rows="7" cols="50" name="etymology" placeholder="<?php echo _('e.g. &lt; [w]form[/w] ‘transl.’ ([r]ROOT-[/r])'); ?>">
<?php echo $this->param('item')->etymology(); ?>
</textarea><br>
<div class="field-desc"><?php echo _('A summary of etymological notes associated with this instance. Markup allowed.'); ?></div>
<?php echo $this->fieldError('etymology'); ?>

<input type="hidden" name="name_id" value="<?php echo $this->param('name_id'); ?>">
<input type="submit">
</form>

</div>