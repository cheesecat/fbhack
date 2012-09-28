<?php echo "<?xml version='1.0' encoding='utf-8' ?>"; ?>
<Response>
	<Say><?php echo urldecode($_GET['name']); ?> got totally drunk and he needs a lift home. </Say>
	<Pause length="1"/>
	<Say voice='woman'>Please pick him up from <?php echo urldecode($_GET['location']); ?> or else he will call your mother.</Say>
</Response>
