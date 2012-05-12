<?php

namespace Grafizzi\Graph;

interface NamedInterface {
  public function build();
  public function getName();

  /**
   * The name of the object type in GraphViz: attribute, node, edge, cluster...
   *
   * Sometimes used during building (node, ...), sometimes not (attribute).
   */
  public static function getType();

  public function setName($name);
}
