<!doctype html>
<html>
<head>
<title>
<?php echo $this->param('title'); ?>
</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/text.css">
<link rel="stylesheet" href="css/layout.css">
<link rel="stylesheet" href="css/elements.css">
<script src="js/nav.js"></script>
</head>
<body>
<?php require $this->param('header'); ?>

<?php require $this->param('content'); ?>

<?php require $this->param('footer'); ?>
</body>
</html>