<?php

namespace Nicklasos\Router;

class App
{
    private $routes = [];
    private $notFoundCallback;
    private $getRoute = '/';
    private $cntGetRoute = 0;
    private $foundRoute = false;

    public function __construct()
    {
        if (isset($_GET['route'])) {
            $_GET['route'] = preg_replace('/[^-a-zA-Z0-9_\/]/', '', $_GET['route']);
            $this->getRoute = array_filter(explode('/', $_GET['route']));
            $this->cntGetRoute = count($this->getRoute);
        }
    }

    public function run()
    {
        foreach ($this->routes as $route => $callback) {

            if ($route == '/' && $this->cntGetRoute == 0) {
                $this->foundRoute = true;
                echo $callback();
                break;
            }

            $route = explode('/', $route);

            if (count($route) != $this->cntGetRoute) {
                continue;
            }

            if ($this->searchRoute($route)) {
                $this->foundRoute = true;
                echo $callback();
            }
        }

        if ($this->foundRoute === false) {
            $notFoundCallback = $this->notFoundCallback;
            echo $notFoundCallback();
        }
    }

    private function searchRoute($route)
    {
        for($i = 0; $i <= $this->cntGetRoute; $i++) {
            if (
                isset($route[$i][0]) &&
                $route[$i][0] == ':'
            ) {
                $_GET[str_replace(':', '', $route[$i])] = $this->getRoute[$i];
            } else if (
                isset($this->getRoute[$i]) &&
                isset($route[$i]) &&
                $this->getRoute[$i] != $route[$i]
            ) {
                return false;
            }
        }

        return true;
    }

    public function get($route, $callback)
    {
        $this->routes[$route] = $callback;
    }

    public function notFound($callback)
    {
        $this->notFoundCallback = $callback;
    }
    
    public function isAjax()
    {
        return
            isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            $_SERVER['HTTP_X_REQUESTED_WITH'] &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}
