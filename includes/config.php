<?php

ini_set('memory_limit','256M');
ini_set('max_execution_time', 9000); //300 seconds = 5 minutes

include('nhl-games.class.php');
include('simple-html-dom.class.php');

define('PATH', 			dirname(dirname (__FILE__)) );
define('PATH_CACHE', 	PATH . '/cache');
define('PATH_INC',		PATH . '/includes');
define('CACHE_EXPIRE',	3600);