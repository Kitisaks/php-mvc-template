<?php
class Plug{

  function __construct(){
    $this->view = new View();
  }

  public function loadmodule($main){
    $path = "../controller/".$main."_controller.php";
    
    if(file_exists($path)){
      require_once $path;
      $name = $main."controller";
      $this->controller = new $name;
    }
  }



}