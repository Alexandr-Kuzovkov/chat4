<?php if(session_status() !== PHP_SESSION_ACTIVE ) session_start();
require_once(__DIR__ .'/../include/common.inc.php');

$pages = 'pages/';
$scripts = 'scripts/';
$root_dir = ''; /*если скрипт не в корневом каталоге сервера*/

$routes = [
    '/login' => $pages . 'login.php',
    '/logout' => $scripts . 'logout.php',
    '/info' => $pages . 'info.php',
    '404' => $pages . '404.php',
    '/file' => $scripts . 'download_file.php',
    '/file-del' => $scripts . 'delete_file.php',
    '/upload' => $scripts . 'upload.php',
    '/room/:room' => $pages . 'index.php',

];


//array_map(function ($key){return substr($key, 0, strpos($key, ':'));}, array_filter(array_keys($routes), function($key) {return strpos($key, ':') !== false;}))
if (isset($_SERVER['REQUEST_URI'])){

    $real_uri = $_SERVER['REQUEST_URI'];

    if (($p = strpos($real_uri, '?')) === false){
        $uri = substr($real_uri, 0);
    }else{
        $uri = substr($real_uri, 0, strpos($real_uri, '?') );
    }


    /*учет случая когда скрипт не в корневом каталоге сервера*/
    if (strlen($root_dir) && strpos($uri, $root_dir) === 0){
        $uri = substr($uri, strlen($root_dir));
    }

    if (!Auth::isAuth() && $uri != '/login' && $uri != '/auth') { header('Location: /login'); exit(); }
    if (isset($routes[$uri])){
        if(is_array($routes[$uri])){
            if (isset($routes[$uri][1]) && is_array($routes[$uri][1]))
                    foreach($routes[$uri][1] as $key => $val)
                        $_GET[$key] = $val;
            $require = '../' . $routes[$uri][0];
        }else{
            $require = '../' . $routes[$uri];
        }
    }
    else if (strpos($uri, '/room/') === 0){
        $require = '../' . $routes['/room/:room'];
    } else{
        $require = '../' . $routes['404'];
    }


    require_once ($require);

}else{
    echo 'Access not allow';
}