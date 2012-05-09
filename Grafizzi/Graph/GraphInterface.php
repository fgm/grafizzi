<?php
namespace Grafizzi\Graph;

use Grafizzi\Graph\ElementInterface;

interface GraphInterface extends ElementInterface {
  public function getDirected();
  public function render();
  public function setDirected($directed);
}
