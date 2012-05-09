<?php
namespace OSInet\Graph;

use OSInet\Graph\ElementInterface;

interface GraphInterface extends ElementInterface {
  public function getDirected();
  public function setDirected($directed);

  public function build();
  public function render();
}
