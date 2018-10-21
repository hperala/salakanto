<h1><?php echo $this->param('heading'); ?></h1>

<div id="content">

<form action="<?php echo $this->param('form_handler'); ?>" method="post">

<label for="abbreviation"><?php echo _('Abbreviation:'); ?></label>
<input type="text" name="abbreviation" value="<?php echo $this->param('item')->abbreviation(); ?>"><br>
<div class="field-desc"><?php echo _('Max. 50 characters.'); ?></div>
<?php echo $this->fieldError('abbreviation'); ?>

<label for="year"><?php echo _('Year:'); ?></label>
<input type="text" name="year" value="<?php echo $this->param('item')->year(); ?>"><br>
<div class="field-desc"><?php echo _('Approximate year of writing. Numbers only. Used for sorting purposes.'); ?></div>
<?php echo $this->fieldError('year'); ?>

<label for="text"><?php echo _('Text:'); ?></label>
<textarea rows="10" cols="50" name="text">
<?php echo $this->param('item')->text(); ?>
</textarea>
<div class="field-desc"><?php echo _('Enough information to identify the document, e.g. full name, where published.'); ?></div>
<?php echo $this->fieldError('text'); ?>

<input type="submit">

</form>

</div>