<?php

if( !function_exists('city_to_abbr'))
{
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
		if($team == 'florida') $ext = 'flo';
		if($team == 'san jose') $ext = 'sj';
		if($team == 'tampa bay') $ext = 'tb';
		if($team == 'phoenix') $ext = 'phx';
		return $ext;
	}
}


if( !function_exists('nhl_teams'))
{
	function nhl_teams()
	{
		return array(
			'ana', 'bos', 'buf', 'car','cgy', 'chi', 'col', 'clb', 'dal', 'det', 'edm', 'flo', 'la', 'min', 'mon',
			'nas', 'njd', 'nyi', 'nyr', 'ott', 'phi', 'phx', 'pit', 'sj', 'stl', 'tb', 'tor', 'van', 'was', 'win'
		);
	}
}

if( !function_exists('nhl_teams_array'))
{
	function nhl_teams_array()
	{
		$out = array();
		foreach(nhl_teams() as $team) {
			$out[$team] = 0;
		}
		return $out;
	}
}

if( !function_exists('list_team_games'))
{
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
}

if( !function_exists('_e_list_team_games'))
{
	function _e_list_team_games($games, $team)
	{
		$arr = list_team_games($games, $team);
		foreach($arr as $i=>$game) :
			echo '<span class="game">' . $game . '</span>';
		endforeach;
	}
}