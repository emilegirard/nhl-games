<?php

//extand memory limit
ini_set('memory_limit','256M');

//load functions
include('functions.php');

//load classes
if(!class_exists('NHL_Games'))
	include('nhl_games.class.php');

if(!class_exists('NHL_Schedule'))
	include('nhl_schedule.class.php');

if(!class_exists('simple_html_dom_node'))
	include('simple-html-dom.class.php');

//define constants
define('NHL_GAMES_PATH', 			dirname(dirname (__FILE__)) );
define('NHL_GAMES_PATH_CACHE', 		NHL_GAMES_PATH . '/cache');
define('NHL_GAMES_PATH_INC',		NHL_GAMES_PATH . '/includes');

define('NHL_SCHEDULE_PATH_CACHE', 		dirname(dirname (__FILE__)) . '/cache');
define('NHL_SCHEDULE_CUR_SEASON', 		'2013-14');
define('NHL_SCHEDULE_CACHE_EXPIRE',		365*3600); //1 year