<?php

include('includes/config.php');

$start = (isset($_GET['start'])) ? $_GET['start'] : date('Y-m-d');
$end = (isset($_GET['end'])) ? $_GET['end'] : date('Y-m-d', time() + 60*60*24*7);


$teams = array(
	'tb' => array('Steven Stamkos', 'Victor Hedman'),
	'la' => array('Anze Kopitar', 'Slava Voynov', 'Drew Doughty'), 
	'phi' => array('Claude Giroux'),
	'car' => array('Alexander Semin'),
	'edm' => array('Nail Yakupov', 'Jordan Eberle'),
	'col' => array('Gabriel Landeskog'),
	'mon' => array('Max Pacioretty', 'P.K. Subban', 'Brendan Gallagher', 'G- Carey Price'),
	'min' => array('Zach Parise', 'Nino Niederreiter'),
	'nyr' => array('Derek Stepan', 'Ryan McDonagh'),
	'pho' => array('Mike Ribeiro'),
	'nas' => array('Shea Weber'),
	'nyi' => array('Mark Streit', 'Ryan Strome', 'Kyle Okposo'),
	'buf' => array('G- Ryan Miller'),
	'ott' => array('G- Craig Anderson'),
	'bos' => array('Dougie Hamilton', 'Milan Lucic'),
	'fla' => array('Dmitry Kulikov', 'Alexander Barkov'),
	'clb' => array('Ryan Murray'),
	'chi' => array('Tuevo Teravainen')
	);


$output = new NHL_Games();
$output->teams = $teams;
$output->compute($start, $end);

?>
<html>
<head>
	<title>NB of games played in interval</title>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
	<link rel="stylesheet" href='assets/css/dark-hive/jquery-ui-1.10.3.custom.min.css' type='text/css' media='all' />


	
	<script>
	  $(function() {
	    $( ".datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });
	  });
	</script>

</head>
<style type="text/css">
	body {
		background-color:#efefef;
		font-size:120%;
		font-family:'courier new', courier, arial;
		width:100%;
	}

	h1 {
		font-size:16pt;
		text-align:center;
		margin:20px 0;
		padding:20px;
	}

	ul.players {
		display:block;
		list-style-type:none;
		width:40%;
		margin:0 auto 50px auto;
	}

	ul.players li {
		display:block;
		background-color:fff;
		margin:5px 0;
		padding:5px;
		}
		ul.players li.odd {
			background-color:#efefef;
		}

	ul.players li .nb {
		float:right;
		font-weight:bold;
	}
</style>
<body>

	<form action="index.php" method="get">
		<h1>Nb of games played between <input name="start" class="datepicker" value="<?=$start;?>" /></span> and <input class="datepicker" name="end" value="<?=$end;?>" /> <input type="submit" value="GO" /></h1>
	</form>

	<?php
	echo '<ul class="players">';

	$i=1; foreach($output->games_per_players_in_interval as $player=>$nb) :
		echo '<li class="' . (($i%2 === 0) ? 'odd' : '') . '">';
		echo '<span class="name">' . $player . '</span>';
		echo '<span class="nb">' . $nb .'</span>';
		echo '</li>';
		$i++;
	endforeach;

	echo '</ul>';
	?>

</body>
</html>