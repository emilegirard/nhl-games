<?php

class NHL_Schedule {

	public $games = array();

	private $schedule_url 	    = 'http://www.nhl.com/ice/schedulebyseason.htm?navid=nav-sch-sea';
    private $use_cache          = true;
    private $cache_filename;

	/**
	 * Class constructor
	*/
	public function __construct()
	{
		$this->cache_filename = 'nhl-schedule-' . NHL_SCHEDULE_CUR_SEASON . '.html';
        $this->loadSchedule();

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
	 * load a schedule from cache, or if it is expired, generate a new one
	*/
	private function loadSchedule()
    {
    	//get schedule from cache
    	$file = NHL_SCHEDULE_PATH_CACHE . '/' . $this->cache_filename;

    	//cache is expired
    	if( !file_exists($file) || (filemtime($file) + NHL_SCHEDULE_CACHE_EXPIRE) < time() || $this->use_cache === false )
    	{
	    	$content = $this->generateSchedule();

		//load from cache
		} else
		{
			$content = @file_get_contents($file);
			$content = unserialize($content);
		}

		//save to object
		$this->games = $content;
    }

    /**
     * generate a schedule array by parsing the appropriate URL and extract content
     *
     * @return array games as an associative Array(time()=>games array());
    */
    private function generateSchedule()
    {
    	$html = file_get_contents($this->schedule_url);

    	if( !$html || strlen($html) <= 0)
    	{
    		throw new Exception('Invalid HTML content');
    	}

    	//clean HTML to exclude useless stuff and improve performance in creating DOM object
    	$html = substr($html, strpos($html, '<table class="data schedTbl">'), strlen($html));
    	$html = substr($html, 0, (strpos($html, '</table>') + 8));

    	preg_match_all('#<tr[^>]*>(.*?)</tr>#si' , $html, $lines);
    	$lines = $lines[0];
    	//generate array
    	$schedule = array();
    	foreach($lines as $i=>$line) :
    		$game = array();
    		$dom = str_get_html($line);
            if(is_object($dom))
            {
                $date = strtotime(@$dom->find('div.skedStartDateLocal',0)->plaintext);
                if(@$dom->find('.skedStartDateSite'))
                {
                    $game['home'] = utf8_decode($dom->find('.teamName', 1)->plaintext);
                    $game['visitor'] = utf8_decode($dom->find('.teamName', 0)->plaintext);
                    $game['info'] = $game['visitor'] . ' vs ' . $game['home'];
                    $game['home'] = city_to_abbr($game['home']);
                    $game['visitor'] = city_to_abbr($game['visitor']);
                    $schedule[$date][] = $game;
                }
            }
    	endforeach;

    	//save to cahe
    	$file = NHL_SCHEDULE_PATH_CACHE . '/' . $this->cache_filename;

		if (!$handle = fopen($file, 'w+'))
		{
			throw new Exception('Cannot open file ' . $file);
		}
	    if (fwrite($handle, serialize($schedule)) === FALSE)
	    {
	        throw new Exception("Cannot write to file ($file)");
	    }
		fclose($handle);

    	//output
    	return $schedule;
    }
}