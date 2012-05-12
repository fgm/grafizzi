<?php
namespace Grafizzi\Graph;

interface GraphInterface extends ElementInterface {
  public function getDirected();
  public function render();
  public function setDirected($directed);
}
