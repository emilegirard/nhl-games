<?php


class NHL_Games
{

	public $teams 							= array();
	public $games_in_interval 				= array();
	public $games_per_teams_in_interval 	= array();
	public $games_per_players_in_interval 	= array();
    public $Schedule;
    public $cache_path                      = '';
    public $cache_expire                    = '';

	private $schedule 						= array();
    private $start;
    private $end;
    private $use_cache                      = true;

	private static $instance;

	/**
	 * Class constructor
	*/
	public function __construct()
	{
		//save it for a call from outside
		self::$instance =& $this;

        $this->start = date('Y-m-d');
        $this->end = date('Y-m-d', time() + 60*60*24*6);

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
    public function compute()
    {
    	//set the schedules array
    	$this->schedule = new NHL_Schedule();
        $this->schedule->init();

        //set the cache bool
        $this->schedule->use_cache = $this->use_cache;
        if($this->cache_path != '') $this->schedule->cache_path = $this->cache_path;
        if($this->cache_expire != '') $this->schedule->cache_expire = $this->cache_expire;

        $this->schedule->init();
        $this->Schedule = $this->schedule;

    	//format minimum date
    	$min = strtotime($this->start . ' 00:00:00');

    	//format maximum date
    	$max = strtotime($this->end . ' 23:59:59');

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
