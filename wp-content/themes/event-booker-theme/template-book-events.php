<?php 
/*
Template Name: Book Events
*/
?>

<!DOCTYPE HTML>

<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Book Events</title>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	
    <div id='calendar'></div>
	<div id="modalWindow" class="yvg-modal">
		<div class="modal-content">
			<span class="close">&times;</span>
			<div id="modalWindowContent"></div>
		</div>
	</div>
    <?php wp_footer(); ?>
</body>

</html>