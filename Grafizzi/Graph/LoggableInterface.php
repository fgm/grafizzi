<?php
namespace Grafizzi\Graph;

use Grafizzi\Graph\NamedInterface;

interface LoggableInterface extends NamedInterface {
  public function __construct(\Pimple $dic);
}
