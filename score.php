<?php

	$db_host = 'mysql.squabbler.net';
	$db_user = 'storyboa';
	$db_pass = 'jungle';
	$db_name = 'main';
	$table = 'flashmines';
	$connection = mysql_connect($db_host, $db_user, $db_pass) or DIE('Error Connecting To Database.');
	mysql_select_db($db_name);
	
	function getHighScores() {
		$query = "SELECT * FROM `flashmines` WHERE `id` <= '5';";
		$highscores = mysql_query($query);
		if (!$highscores) {
		    die('Invalid query: ' . mysql_error());
		}
		
		$obj = array();
		
		while ($row = mysql_fetch_array($highscores)) {
			$array = array (
					'rank' => $row[0],
					'score' => $row[1],
					'player' => $row[2]
				);
			$obj[ $row[0] ] = $array;
		}
		
		echo ( json_encode($obj) );
	}
	
	function saveHighScore() {
		
		// Check for valid high score submitted
		$player = $_REQUEST['p'];
		$score = (int) $_REQUEST['s'];
		if ($score > 8998) return;
		
		// Query current high scores
		$q0 = "SELECT * FROM `flashmines` WHERE `id` <= '5';";
		$r0 = mysql_query($q0);
		$obj = array();
		while ($row = mysql_fetch_array($r0)) {
			$array = array (
					'rank' => $row[0],
					'score' => $row[1],
					'player' => $row[2]
				);
			$obj[ $row[0] ] = $array;
		}
		
		// Compare submitted score with results
		if ($score > $obj[1]['score']) {
			$new_rank = 1;
			$q1 = "INSERT INTO `flashmines` (`id`,`score`,`player`) VALUES ('1','" . $score . "','" . $player . "');";
			$q2 = "UPDATE `flashmines` SET `id`='2' WHERE `id` = '1' LIMIT 1";
			$q3 = "UPDATE `flashmines` SET `id`='3' WHERE `id` = '2' LIMIT 1";
			$q4 = "UPDATE `flashmines` SET `id`='4' WHERE `id` = '3' LIMIT 1";
			$q5 = "UPDATE `flashmines` SET `id`='5' WHERE `id` = '4' LIMIT 1";
			$q6 = "DELETE FROM `flashmines` WHERE `id` = '5' LIMIT 1;";
		} else if ($score > $obj[2]['score']) {
			echo("new score is rank 2");
			$new_rank = 2;
			$q1 = "INSERT INTO `flashmines` (`id`,`score`,`player`) VALUES ('2','" . $score . "','" . $player . "');";
			$q2 = "UPDATE `flashmines` SET `id`='3' WHERE `id` = '2' LIMIT 1";
			$q3 = "UPDATE `flashmines` SET `id`='4' WHERE `id` = '3' LIMIT 1";
			$q4 = "UPDATE `flashmines` SET `id`='5' WHERE `id` = '4' LIMIT 1";
			$q5 = "DELETE FROM `flashmines` WHERE `id` = '5' LIMIT 1;";
		} else if ($score > $obj[3]['score']) {
			echo("new score is rank 3");
			$new_rank = 3;
			$q1 = "INSERT INTO `flashmines` (`id`,`score`,`player`) VALUES ('3','" . $score . "','" . $player . "');";
			$q2 = "UPDATE `flashmines` SET `id`='4' WHERE `id` = '3' LIMIT 1";
			$q3 = "UPDATE `flashmines` SET `id`='5' WHERE `id` = '4' LIMIT 1";
			$q4 = "DELETE FROM `flashmines` WHERE `id` = '5' LIMIT 1;";
		} else if ($score > $obj[4]['score']) {
			echo("new score is rank 4");
			$new_rank = 4;
			$q1 = "INSERT INTO `flashmines` (`id`,`score`,`player`) VALUES ('4','" . $score . "','" . $player . "');";
			$q2 = "UPDATE `flashmines` SET `id`='5' WHERE `id` = '4' LIMIT 1";
			$q3 = "DELETE FROM `flashmines` WHERE `id` = '5' LIMIT 1;";
		} else if ($score > $obj[5]['score']) {
			echo("new score is rank 5");
			$new_rank = 5;
			$q1 = "INSERT INTO `flashmines` (`id`,`score`,`player`) VALUES ('5','" . $score . "','" . $player . "');";
			$q2 = "DELETE FROM `flashmines` WHERE `id` = '5' LIMIT 1;";
		} else {
			return;
		}
		
		// Insert new high scores back into database
		if ($q6) $r6 = mysql_query($q6);
		if ($q5) $r5 = mysql_query($q5);
		if ($q4) $r4 = mysql_query($q4);
		if ($q3) $r3 = mysql_query($q3);
		if ($q2) $r2 = mysql_query($q2);
		if ($q1) $r1 = mysql_query($q1);
		
		// Query new high scores and return result
		$qf = "SELECT * FROM `flashmines` WHERE `id` <= '5';";
		$rf = mysql_query($qf);
		$objN = array();
		while ($rowN = mysql_fetch_array($rf)) {
			$arrayN = array (
					'rank' => $rowN[0],
					'score' => $rowN[1],
					'player' => $rowN[2]
				);
			$objN[ $rowN[0] ] = $arrayN;
		}
		echo ( json_encode($objN) );
	}
	
	if ($_REQUEST['f']) {
		if ($_REQUEST['f'] == 'get') {
			getHighScores();
		} else if ($_REQUEST['f'] == 'save') {
			saveHighScore();
		} else {
			echo("Invalid or Null Function Called!");
		}
	}
	
?>