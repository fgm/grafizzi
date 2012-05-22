<?php

namespace Grafizzi\Graph;

class Cluster extends Subgraph {

  public function getBuildName() {
    $name = $this->getName();
    $ret = $this->escape("cluster_$name");
    return $ret;
  }
}
