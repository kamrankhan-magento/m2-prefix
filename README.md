# Magneto 2 snippets

An easier way to run magneto snippets, without writing your own controllers.

## How to use

* Simplest approach is to include clone it in your project `pub` folder
like `git clone git@github.com:zainengineer/m2_prefix.git pub/zain_custom`
* Include the `auto_include.php` in your `index.php` like `require_once __DIR__ . '/zain_custom/auto_include.php';`
* Copy snippets examples from `snippet_sample` into `snippets` and modify them or create new
* Run the snippet like `http://magento2.docker/?op=temp` it will execute snippet in `snippets/temp.php`

## Bonus

* To generate logger and other tools visit https://zainengineer.github.io/m2_prefix/