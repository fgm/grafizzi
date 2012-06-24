<?php
namespace Grafizzi\Graph\Filter;

use Grafizzi\Graph\Filter\FilterInterface;
use Grafizzi\Graph\Filter\AbstractFilter;

/**
 * A Grafizzi filter that also outputs its result to the passed-in string.
 *
 * Since it has to be passed by reference, the argument to use is like:
 * array('out' => &$some_string).
 */
class StringFilter extends AbstractFilter implements FilterInterface {

  /**
   * The reference to the variable in which to copy the result.
   *
   * @var string
   */
  public $string = null;

  /**
   * An optional callable to be invoked on the data.
   *
   * @var callable
   *   It receives the input and returns its after filtering.
   */
  public $callback = null;

  /**
   * @param array $args
   *   $args[0] is the only used element in this array.
   * @throws \InvalidArgumentException
   */
  public function __construct(array &$args = array()) {
    if (isset($args['out'])) {
      $this->string = &$args['out'];
    }
    if (isset($args['callback'])) {
      if (is_callable($args['callback'])) {
        $this->callback = $args['callback'];
      }
      else {
        throw new \InvalidArgumentException("Invalid callback passed to " . __CLASS__);
      }
    }
  }

  /**
   * @see \Grafizzi\Graph\Filter\FilterInterface::filter()
   */
  public function filter($input) {
    $ret = isset($this->callback)
      ? call_user_func($this->callback, $input)
      : $input;
    if (isset($this->string)) {
      $this->string = $ret;
    }
    return $ret;
  }
}
