<?php
namespace App;
#- use to render view.
class View
{
  public $main;
  private $_layout;

  function __construct($main)
  {
    $this->main = str_replace('controller', '', strtolower($main));
    $this->_layout = true;
  }

  private function _require(string $page, string $folder = 'layout')
  {
    $path = $_SERVER['DOCUMENT_ROOT'] . "/templates/{$folder}/{$page}.php";
    $path = str_replace('app\\\\', DIRECTORY_SEPARATOR, $path);
    require_once $path;
  }

  public function render($pages)
  {
    if ($this->_layout) {
      if (is_string($pages)) {
        $this->_require('header.html');
        $this->_require('_alert.html');
        $this->_require('_notice.html');
        $this->_require('_popup.html');
        $this->_require('_navbar.html');
        $this->_require($pages, $this->main);
        $this->_require('_footer.html');
        $this->_require('bottom.html');
      } else if (is_array($pages)) {
        $this->_require('header.html');
        $this->_require('_alert.html');
        $this->_require('_notice.html');
        $this->_require('_popup.html');
        $this->_require('_navbar.html');
        foreach ($pages as $page) {
          $this->_require($page, $this->main);
        }
        $this->_require('_footer.html');
        $this->_require('bottom.html');
      }
    } else {
      if (is_string($pages)) {
        $this->_require('header.html');
        $this->_require('_alert.html');
        $this->_require('_notice.html');
        $this->_require('_popup.html');
        $this->_require($pages, $this->main);
        $this->_require('bottom.html');
      } else if (is_array($pages)) {
        $this->_require('header.html');
        $this->_require('_alert.html');
        $this->_require('_notice.html');
        $this->_require('_popup.html');
        foreach ($pages as $page) {
          $this->_require($page, $this->main);
        }
        $this->_require('bottom.html');
      }
    }
  }

  public static function assets_include()
  {
    $base = $_SERVER['DOCUMENT_ROOT'];
    $css = array_slice(scandir($base . '/assets/css/'), 2);
    $js = array_slice(scandir($base . '/assets/js/'), 2);

    foreach ($css as $i) {
      echo '<link rel="stylesheet" type="text/css" href="' . BASE_URL . 'assets/css/' . $i . '">';
    }
    foreach ($js as $i) {
      echo '<script type="text/javascript" src="' . BASE_URL . 'assets/js/' . $i . '"></script>';
    }
  }

  public static function partial($main, $pages)
  {
    $path = $_SERVER['DOCUMENT_ROOT'];
    if (is_array($pages)) {
      foreach ($pages as $page) {
        require_once $path . "/templates/{$main}/{$page}.php";
      }
    } else {
      require_once $path . "/templates/{$main}/{$pages}.php";
    }
  }

  public function put_layout(bool $layout)
  {
    $this->_layout = $layout;
    return $this;
  }

  public function assign(string $param, $value)
  {
    $GLOBALS[$param] = $value;
    return $this;
  }

  public function put_flash(bool $status = true, $value)
  {
    if ($status)
      $_SESSION['popup'] = ['status' => 1, 'info' => $value];
    else 
      $_SESSION['popup'] = ['status' => 0, 'info' => $value];
    return $this;
  }

  public function redirect(string $uri)
  {
    header("location: {$uri}");
    exit;
  }

  public function return(string $type = 'default', array $data)
  {
    switch ($type) {
      case 'default':
        print_r(
          $data
        );
        break;
      case 'json':
        print_r(
          json_encode($data)
        );
        break;
    }
  }
}