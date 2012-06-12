<?php
namespace Grafizzi\Graph\Filter;

use Grafizzi\Graph\Filter\FilterInterface;
use Grafizzi\Graph\Filter\AbstractFilter;

/**
 * A trivial filter example that drops anything it receives.
 */
class SinkFilter extends AbstractFilter implements FilterInterface {

  /**
   * @see \Grafizzi\Graph\Filter\FilterInterface::filter()
   */
  public function filter($input) {
    return null;
  }
}
