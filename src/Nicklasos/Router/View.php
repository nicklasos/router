<?php

namespace Nicklasos\Router;

class View
{
    private $layout = false;
    private $viewsPath;

    function setLayout($layout)
    {
        $this->layout = $layout;
    }

    function render($view, $data = array())
    {
        if ($this->isAjax()) {
            $this->layout = false;
        }

        if ($this->layout) {
            $path = $this->viewsPath . '/' . $this->layout . '.php';
            $this->layout = false;
        } else {
            $path = $this->viewsPath . '/' . $view . '.php';
        }

        extract($data);

        if (file_exists($path)) {
            ob_start();
            include($path);
            $view = ob_get_contents();
            ob_get_clean();
            return $view;
        } else {
            throw new \Exception('File ' . $path . ' not found');
        }
    }

    public function setViewsPath($path)
    {
        $this->viewsPath = $path;
    }
    
    private function isAjax()
    {
        return
            isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            $_SERVER['HTTP_X_REQUESTED_WITH'] &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}
