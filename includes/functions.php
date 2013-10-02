<?php


function city_to_abbr($team)
{
	$team = strtolower($team);
	$ext = substr($team,0,3);

	//handle exceptions
	if($team == 'los angeles') $ext = 'la';
	if($team == 'columbus') $ext = 'clb';
	if($team == 'calgary') $ext = 'cgy';
	if($team == 'montréal' || $team == 'montreal') $ext = 'mon';
	if($team == 'st. louis' ) $ext = 'stl';
	if($team == 'new jersey' ) $ext = 'njd';
	if($team == 'ny islanders') $ext = 'nyi';
	if($team == 'ny rangers') $ext = 'nyr';
	if($team == 'ny rangers') $ext = 'nyr';
	if($team == 'florida') $ext = 'fla';
	if($team == 'san jose') $ext = 'sj';
	if($team == 'tampa bay') $ext = 'tb';
	return $ext;
}

function nhl_teams()
{
	return array(
		'ana', 'bos', 'buf', 'car','cgy', 'chi', 'col', 'clb', 'dal', 'det', 'edm', 'fla', 'la', 'min', 'mon',
		'nas', 'njd', 'nyi', 'nyr', 'ott', 'phi', 'pho', 'pit', 'sj', 'stl', 'tb', 'tor', 'van', 'was', 'win'
	);
}

function nhl_teams_array()
{
	$out = array();
	foreach(nhl_teams() as $team) {
		$out[$team] = 0;
	}
	return $out;
}

function list_team_games($games, $team)
{
	$out = array();
	foreach($games as $i=>$list) {
		foreach($list as $i=>$game) {
			if($game['visitor'] == $team)
				$out[] = '@ '.$game['home'];
			else if($game['home'] == $team)
				$out[] = 'vs '.$game['visitor'];
		}
	}
	return $out;
}

function _e_list_team_games($games, $team)
{
	$arr = list_team_games($games, $team);
	foreach($arr as $i=>$game) :
		echo '<span class="game">' . $game . '</span>';
	endforeach;
}