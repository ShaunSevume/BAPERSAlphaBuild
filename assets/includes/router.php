<?php

class Router {

    public $views;
    public $url;
    public $path;
    public $breadcrumbs;
    public $args;
    public $title;

    public function __construct($views) {
        if (isset($_GET['logout'])) {
            session_destroy();
            header('Location: ' . WEB_CONFIG['home'] . 'login.php');
        }
        $this->views = $views;
        $this->url = '404';
        $v = (isset($_GET['v']) ? $_GET['v'] : 'dashboard');

        foreach ($this->views as $path => $val) {
            if ($path == $v) {
                $this->args = (isset($val['args']) && isset($_GET[$val['args']]) ? $_GET[$val['args']] : null);
                $this->url = $path;
                $this->path = $path . '/' . $path;
                $this->breadcrumbs = $this->title = $val['title'];
                if (isset($_GET['s']) && isset($val['subs'])) {
                    $s = $_GET['s'];
                    foreach ($val['subs'] as $spath => $sval) {
                        if ($spath == $s && !(isset($sval['args']) && !isset($_GET[$sval['args']]))) {
                            $this->args = (isset($sval['args']) && isset($_GET[$sval['args']]) ? $_GET[$sval['args']] : null);
                            $this->url = $spath;
                            $this->path = $path . '/' . $spath;
                            $this->title .= ' > ' . $sval['title'];
                            $this->breadcrumbs .= '<i class="feather icon-chevron-right"></i>' . $sval['title'];
                            break;
                        }
                    }
                }
                break;
            }
        }
    }

    // Render view
    public function renderView($data = null) {
        include WEB_CONFIG['root'] . 'views/' . $this->path . '.php';
    }

    // Render menu
    public function renderComponent($com, $data = null) {
        include WEB_CONFIG['root'] . 'views/_components/' . $com . '.php';
    }

    // change url
    public function changeView($v) {
        echo '<script type="text/javascript">window.location.replace("'. WEB_CONFIG['home'] .'index.php?v='.$v.'")</script>';
    }

}

?>