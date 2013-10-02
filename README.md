# NHL GAMES COUNTER #

Return the number of games a team (or a list of players) will play between two dates.

## How it works ##

First, you create your associative array with you teams (and players) :

	$teams = array(
		'mon' => array('P.K. Subban', 'Carey Price'),
		'bos' => array('Patrice Bergeron')
	);

Then you set the time range

	$start = '2013-10-01';
	$end   = '2013-10-07';

Finally you instantiate the class to fetch the number of games your players will play during this period of time :

	$output = new NHL_Games();
	$output->teams = $teams;
	$output->compute( $start, $end );

	//display results
	print_r( $output->games_per_players_in_interval );

## Working Demo ##

See http://nhloracle.com/nhl-games/ for a working example.