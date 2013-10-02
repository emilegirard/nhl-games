<?php

include('includes/config.php');

$start 	= (isset($_GET['start'])) ? $_GET['start'] : date('Y-m-d');
$end 	= (isset($_GET['end'])) ? $_GET['end'] : date('Y-m-d', time() + 60*60*24*7);

$teams = array(
	'tb' => array('Steven Stamkos', 'Victor Hedman'),
	'la' => array('Anze Kopitar', 'Slava Voynov', 'Drew Doughty'),
	'phi' => array('Claude Giroux', 'Mark Streit'),
	'car' => array('Alexander Semin'),
	'edm' => array('Nail Yakupov', 'Jordan Eberle'),
	'col' => array('Gabriel Landeskog'),
	'mon' => array('Max Pacioretty', 'P.K. Subban', 'Brendan Gallagher', 'G- Carey Price'),
	'min' => array('Zach Parise', 'Nino Niederreiter'),
	'nyr' => array('Derek Stepan', 'Ryan McDonagh'),
	'pho' => array('Mike Ribeiro'),
	'nas' => array('Shea Weber'),
	'nyi' => array('Ryan Strome', 'Kyle Okposo'),
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

	    $('a.view').click(function() {
	    	$(this).parent().parent().find('.gamelist').css({display:'inline-block'});
	    	$(this).css({visibility:'hidden'});
	    	return false;
	    })
	  });
	</script>

</head>
<style type="text/css">
	body {
		background-color:#efefef;
		font-size:100%;
		font-family:'courier new', courier, arial;
		width:100%;
	}

	h1 {
		font-size:22pt;
		text-align:center;
		margin:20px 0 0 0;
		padding:0px;
	}

	h2 {
		font-size:16pt;
		text-align:center;
		margin:0 0 20px 0;
		padding:0px;
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
		ul.players li span.game {
			font-size:70%;
			background-color:#efefef;
			display:inline-block;
			padding:3px 6px;
			-moz-border-radius:5px;
			-webkit-border-radius:5px;
			-o-border-radius:5px;
			border-radius:5px;
			margin-right:8px;
			border:1px solid #ccc;
		}
		ul.players li.odd span.game {
			background-color:#fff;
			border:1px solid #ccc;
		}

		span.gamelist {
			display:none;
		}

	ul.players li .nb {
		float:right;
		font-weight:bold;
		}
		ul.players li .nb a.view {
			color:#ccc;
			margin:0 3px;
			position:relative;
			top:-5px;
			right:-5px;
		}

</style>
<body>
	<a href="https://github.com/emilegirard/nhl-games"><img style="position: absolute; top: 0; left: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_left_darkblue_121621.png" alt="Fork me on GitHub"></a>
	<form action="index.php" method="get">
		<h1>Nb of games played</h1>
		<h2>between <input name="start" class="datepicker" value="<?=$start;?>" /></span> and <input class="datepicker" name="end" value="<?=$end;?>" /> <input type="submit" value="GO &raquo;" /></h2>
	</form>

	<?php
	echo '<ul class="players">';

	$i=1; foreach($output->games_per_players_in_interval as $player=>$nb) :
		echo '<li class="' . (($i%2 === 0) ? 'odd' : '') . '">';
		echo '<span class="name">' . $player . '</span>';
		$team = strtolower(preg_replace('/.*\(([^\)]+)\).*/is', '\\1', $player));
		echo '<span class="gamelist">';
		_e_list_team_games($output->games_in_interval, $team);
		echo '</span>';
		echo '<span class="nb">' . $nb .'<a href="#" class="view">?</a></span>';
		echo '</li>';
		$i++;
	endforeach;

	echo '</ul>';
	?>

</body>
</html>