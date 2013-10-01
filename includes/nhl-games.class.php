<?php


class NHL_Games {

	public $teams 							= array();
	public $games_in_interval 				= array();
	public $games_per_teams_in_interval 	= array();
	public $games_per_players_in_interval 	= array();
	public $html;

	private $schedule 		= array();
	private $schedule_url 	= 'http://www.nhl.com/ice/schedulebyseason.htm?navid=nav-sch-sea';
	
	private static $instance;

	public function __construct()
	{
		//save it for a call from outside
		self::$instance =& $this;
	}

	/**
	 * return the current object outside this class
	 */
    public static function &get_instance()
    {
        return self::$instance;
    }

    /**
     * magic method : GET
     * @param varchar $property
     */
	public function __get($property) 
	{
	    if (property_exists($this, $property)) {
	    	return $this->$property;
	    }
    }

   	/**
     * magic method : SET
     * @param varchar $property
     * @param varchar $value
     */
    public function __set($property, $value) 
    {
    	if (property_exists($this, $property)) {
    		$this->$property = $value;
    	}
    }


    public function compute($min = null, $max = null)
    {
    	//set the schedules array
    	$this->loadSchedule();

    	//format minimum date
    	if($min != null) 
    		$min = strtotime($min . ' 00:00:00'); 
    	else 
    		$min = strtotime(date('Y-m-d') . ' 00:00:00');

    	//format maximum date
    	if($max != null) 
    		$max = strtotime($max . ' 23:59:59'); 
    	else 
    		$max = strtotime(date('Y-m-d') . ' 23:59:59');

    	//compute games in interval
    	$games_in_interval = array();
    	$this->games_per_teams_in_interval = nhl_teams_array();

    	foreach($this->schedule as $time=>$games) :
    		if($time >= $min && $time <= $max) 
    		{
    			array_push($games_in_interval, $games);
    			foreach($games as $i=>$game) :
    				$this->games_per_teams_in_interval[$game['home']]++;
    				$this->games_per_teams_in_interval[$game['visitor']]++;
    			endforeach;
    		}
    	endforeach;

    	$this->games_in_interval = $games_in_interval;

    	//return formated content if teams are set
    	if($this->teams != array()) 
    	{
    		$out = array();
    		foreach($this->teams as $team=>$players) :
    			foreach($players as $i=>$player) :
    				$out[$player . ' (' . strtoupper($team) . ')'] = $this->games_per_teams_in_interval[$team];
    			endforeach;
    		endforeach;
    		arsort($out);
    		$this->games_per_players_in_interval = $out;
    	}

    }

    private function loadSchedule()
    {
    	//get schedule from cache
    	$file = PATH_CACHE . '/schedule.html';
    	//cache is expired
    	if( !file_exists($file) || (filemtime($file) + CACHE_EXPIRE) < time())
    	{

	    	$content = $this->generateSchedule();
	    	
		//load from cache
		} else {
			$content = @file_get_contents($file);
			$content = unserialize($content);
		}

		//save to object
		$this->schedule = $content;
    }




    private function generateSchedule()
    {
    	$html = file_get_contents($this->schedule_url);

    	if( !$html || strlen($html) <= 0) {
    		throw new Exception('Invalid HTML content');
    	}

    	//clean HTML to exclude useless stuff and improve performance in creating DOM object
    	$html = substr($html, strpos($html, '<table class="data schedTbl">'), strlen($html));
    	$html = substr($html, 0, (strpos($html, '</table>') + 8));

    	preg_match_all('#<tr[^>]*>(.*?)</tr>#si' , $html, $lines);
    	$lines = $lines[0];

    	//remove the 3 first rows
    	$lines = array_slice($lines, 3);

    	//generate array
    	$schedule = array();
    	foreach($lines as $i=>$line) :
    		$game = array();
    		$dom = str_get_html($line);
    		$date = strtotime($dom->find('div.skedStartDateLocal',0)->plaintext);
    		if($dom->find('.skedStartDateSite')) {
    			$game['home'] = utf8_decode($dom->find('.teamName', 1)->plaintext);
    			$game['visitor'] = utf8_decode($dom->find('.teamName', 0)->plaintext);
    			$game['info'] = $game['visitor'] . ' vs ' . $game['home'];
    			$game['home'] = city_to_abbr($game['home']);
    			$game['visitor'] = city_to_abbr($game['visitor']);
    			$schedule[$date][] = $game;
    		}
    	endforeach;

    	//save to cahe
    	$file = PATH_CACHE . '/schedule.html';
    	
		if (!$handle = fopen($file, 'w+')) {
			throw new Exception('Cannot open file ' . $file);
		}
	    if (fwrite($handle, serialize($schedule)) === FALSE) {
	        throw new Exception("Cannot write to file ($file)");
	    }
		fclose($handle);
	

    	//output
    	return $schedule;

    }

}


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
