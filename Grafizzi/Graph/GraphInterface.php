<?php
namespace Grafizzi\Graph;

interface GraphInterface extends ElementInterface {
  public function getDirected();
  // Not yet
  // public function render();
  public function setDirected($directed);
}
