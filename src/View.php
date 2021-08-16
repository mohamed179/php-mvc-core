<?php

namespace Mohamed179\Core;

class View
{
    private string $title;
    private string $layout;
    private string $view;
    private array $params;

    public function __construct(string $view, array $params = [])
    {
        $this->title = Application::$app->config['app_name'];
        $this->layout = Application::$app->config['main_layout'];
        $this->view = $view;
        $this->params = $params;
    }

    public function setLayout(string $layout)
    {
        $this->layout = $layout;
        return $this;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    private function loadLayout(): string
    {
        // start buffering output
        ob_start();

        // declearing the params as indevidual variables
        foreach ($this->params as $key => $value) {
            $$key = $value;
        }

        // load layout
        include_once Application::$ROOT_DIR.'/views/layouts/'.$this->layout.'.php';

        // get, clear and return buffered output
        return ob_get_clean();
    }

    private function loadView(): string
    {
        // start buffering output
        ob_start();

        // declearing the params as indevidual variables
        foreach ($this->params as $key => $value) {
            $$key = $value;
        }

        // load view
        include_once Application::$ROOT_DIR.'/views/'.$this->view.'.php';

        // get, clear and return buffered output
        return ob_get_clean();
    }

    public function __toString()
    {
        // load the view
        $viewContent = $this->loadView();

        // load the layout
        $layoutContent = $this->loadLayout();

        // setting title
        $layoutContent = str_replace('{{title}}', $this->title, $layoutContent);

        return str_replace('{{content}}', $viewContent, $layoutContent);
    }
}
