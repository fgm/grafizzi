<?php

namespace Grafizzi\Graph;

interface NamedInterface {
  public function build($directed = NULL);

  /**
   * The name of the function as used in the build process.
   *
   * May be different from the name used by other methods (clusters).
   */
  public function getBuildName();

  /**
   * The name of the object as used by all methods.
   *
   * @see getbuildName()
   */
  public function getName();

  /**
   * The name of the object type in GraphViz: attribute, node, edge, cluster...
   *
   * Sometimes used during building (graph, ...), sometimes not (attribute). May
   * vary per-instance, as graphs can have type graph or digraph depending on
   * their fDirected attribute.
   */
  public function getType();

  public function setName($name);
}
