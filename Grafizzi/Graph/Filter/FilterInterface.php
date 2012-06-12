<?php
namespace Grafizzi\Graph\Filter;

/**
 * Any filter must accept an input and output 0 to 2 "streams".
 */
interface FilterInterface {

  public function __construct(array &$args = array());

  /**
   *
   * @param string $input
   *
   * @return array
   *   - 'data': the data output of the filter, normally to be used as the
   *     input to the next chained filter.
   *   - 'info': the info output of the filter, possibly used for error
   *     reporting.
   */
  public function filter($input);
}
