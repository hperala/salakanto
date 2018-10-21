<div id="container">
<div id="banner">
<a href="."><?php echo _('Salakanto'); ?></a>
</div>

<div id="nav">
<ul id="main-links">
<li <?php echo $this->param('selected-names'); ?>><a href="names.php"><?php echo _('Names'); ?></a></li>
<li <?php echo $this->param('selected-translations'); ?>><a href="translations.php"><?php echo _('Translations'); ?></a></li>
<li <?php echo $this->param('selected-sources'); ?>><a href="sources.php"><?php echo _('Sources'); ?></a></li>
<li <?php echo $this->param('selected-help'); ?>><a href="help.php"><?php echo _('Help'); ?></a></li>
</ul>

<ul id="user">
<?php 
if ($this->param('is_logged_in')) { 
    echo '<li><a href="account.php">' 
         . $this->param('username') 
         . '</a></li>'
         . "\n"
         . '<li><a href="login.php?a=logout">' . _('Log out') . '</a></li>'
         . "\n";
} else {
    if ($this->param('allow_signup')) {
        echo '<li><a href="login.php?a=signup">' . _('Create account') . '</a></li>'
             . "\n";
    }
    echo '<li><a href="login.php">' . _('Log in') . '</a></li>'
         . "\n";
}
?>
</ul>
</div>