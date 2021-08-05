<?php
	session_start();
	session_unset();
	session_destroy();
	echo <<<"EOT"
		<!DOCTYPE html>
		<html>
			<script>
				window.location.replace("index.php");
			</script>
		</html>
EOT;
?>