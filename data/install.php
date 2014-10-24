<?php
	// Get access to the username password and connectionstring.
	install();

	function install () {

		// path to dumped sql file.
		$filePath = "test_db.sql";
		$db_name = 'TestGallery';

		// Connect to create db.
		$pdo = new PDO('mysql:host=localhost', 'root', 'root');


		$contentString = "";
		$handle = fopen(realpath($filePath), "r");

		while($line = fgets($handle)) {

			$contentString .= $line; 
		}

		fclose($handle);

		// preg_replace("PhotoGallery", "TestGallery", $contentString);
		$stringReplace = str_replace('PhotoGallery', 'TestGallery', $contentString);
		echo('<pre>');
		var_dump($stringReplace);
		echo('</pre>');

		// Create all db content.
		$result = $pdo->exec($stringReplace);
		var_dump($result); 
		die('Please delete install.php!');
	}
