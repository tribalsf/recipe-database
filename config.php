<?

define('G_PATH', '/var/www/tribal/');
define('LIB_PATHS', '/var/www/tribal/lib/,/var/www/tribal/klib/');
define('G_URL', 'http://tribal.ssugar.com/');

/* kdebug */
define('KDEBUG', true);
define('KDEBUG_SQL', true);
define('KDEBUG_SQL_HIGHLIGHT', true);
define('KDEBUG_HANDLER', true);

/* database */
define('DB_DATABASE', 'tribal');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '1s0b4r');

/* ignore past this line */
set_include_path(get_include_path().PATH_SEPARATOR.G_PATH);

if (defined('KDEBUG') && KDEBUG == true && php_sapi_name() != 'cli') {
	if (!defined('KDEBUG_JSON') || KDEBUG_JSON == false) {
		register_shutdown_function(array('kdebug', 'init'));
		if (defined('KDEBUG_HANDLER') && KDEBUG_HANDLER == true) {
			set_error_handler(array('kdebug', 'handler'), E_ALL);
		}
	}
}

function __autoload($class) {

	foreach (explode(',', LIB_PATHS) as $libdir) {
  	foreach (array('.class.php','.interface.php') as $file) {
			if ($libdir{0} == '/' && is_file($libdir.$class.$file)) {
				require_once $libdir.$class.$file;
				return true;
			}
			if (is_file(G_PATH.$libdir.$class.$file)) {
				require_once $libdir.$class.$file;
				return true;
			}
		}
	}

	return false;

}

function hpr() { return call_user_func_array(array('k','hpr'), func_get_args()); }
function cpr() { return call_user_func_array(array('k','cpr'), func_get_args()); }
function highlight() { return call_user_func_array(array('k','highlight'), func_get_args()); }
function xmlindent() { return call_user_func_array(array('k','xmlindent'), func_get_args()); }
