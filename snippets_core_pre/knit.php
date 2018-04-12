<?php
//https://github.com/kint-php/kint
Kint::dump($GLOBALS, $_SERVER);
d($GLOBALS, $_SERVER);

d([]);

//string, int etc also show backtrace
Kint::dump(1);
d(1);

//text only
//~s(1);

//basic
//s(1);