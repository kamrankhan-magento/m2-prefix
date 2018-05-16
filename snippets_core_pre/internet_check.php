<?php

ini_set('default_socket_timeout', 20);//20 seconds
var_dump(file_get_contents('http://httpbin.org/ip'));