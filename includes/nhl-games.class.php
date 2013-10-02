<?php


class NHL_Games
{

	public $teams 							= array();
	public $games_in_interval 				= array();
	public $games_per_teams_in_interval 	= array();
	public $games_per_players_in_interval 	= array();

	private $schedule 						= array();

	private static $instance;

	/**
	 * Class constructor
	*/
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

    /**
	 * Generate the requested array of games played / teams(or players) over a period of time
	 *
	 * @param $min DATE period start (YY-mm-dd)
	 * @param $max DATE period end (YY-mm-dd)
	*/
    public function compute($min = null, $max = null)
    {
    	//set the schedules array
    	$this->schedule = new NHL_Schedule();

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

    	foreach($this->schedule->games as $time=>$games) :
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

}
