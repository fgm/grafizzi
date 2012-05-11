<?php

namespace Grafizzi\Graph;

interface NamedInterface {
  public function build();
  public function getName();
  public function setName($name);
}
