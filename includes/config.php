<?php

//extand memory limit
ini_set('memory_limit','256M');

//load functions
include('functions.php');

//load classes
include('nhl-games.class.php');
include('nhl_schedule.class.php');
include('simple-html-dom.class.php');

//define constants
define('PATH', 			dirname(dirname (__FILE__)) );
define('PATH_CACHE', 	PATH . '/cache');
define('PATH_INC',		PATH . '/includes');
define('CACHE_EXPIRE',	3600);