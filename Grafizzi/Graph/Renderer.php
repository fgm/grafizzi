<?php
namespace Grafizzi\Graph;

use Grafizzi\Graph\Filter\AbstractFilter;
use Grafizzi\Graph\GraphVizWrapper;
use Pimple;

/**
 */
class Renderer {

  /**
   * The dependency injection container.
   *
   * @var Pimple
   */
  public $dic;

  /**
   * The channel between filters.
   *
   * @var string
   */
  public $pipe = null;

  /**
   * Helper to enumerate GraphViz format filters on the current system.
   *
   * @throws \ErrorException
   *
   * @return array
   *   An array of format names or false if dot cannot be run.
   */
  public static function getFormats(Pimple $dic) {
    $dotCommand = 'dot -Tinvalid';
    $useExceptions = !empty($dic['use_exceptions']);
    $descriptorSpec = array(
        0 => array('pipe', 'r'),
        1 => array('pipe', 'w'),
        2 => array('pipe', 'w'),
    );

    $process = proc_open($dotCommand, $descriptorSpec, $pipes, NULL, NULL);
    if (!is_resource($process)) {
      if ($useExceptions) {
        throw new \ErrorException('GraphViz "dot" command could not be run.', EER);
      }
      else {
        $formats = array();
      }
    }

    fclose($pipes[0]);
    fclose($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    proc_close($process);

    $sts = preg_match('/(.+):( .* )*/', $stderr, $matches);
    if (!$sts || count($matches) != 3) {
      if ($useExceptions) {
        throw new \ErrorException('GraphViz did not return a usable formats list.');
      }
      else {
        $formats = array();
      }
    }
    else {
      $formats = explode(' ', trim($matches[2]));
    }

    return $formats;
  }

  /**
   * Constructor.
   *
   * @param Pimple $dic
   * @param array $filters
   *   An array of filters and their options, to apply in a chain.
   */
  public function __construct(Pimple $dic) {
    $this->dic = $dic;
  }

  /**
   * Magic method: apply filter methods by simple name.
   *
   * @see Grafizzi\Graph\Filter\AbstractFilter::create()
   *
   * @param string $name
   *   The simple name for a filter. Will be converted to an actual class name.
   * @param array $args
   *   An array of arguments to pass to the filter method.
   *
   * @throws \DomainException
   *   Throws exception if the filter name does not convert to a usable filter
   *   class.
   *
   * @return \Grafizzi\Graph\Renderer
   */
  public function __call($name, $args) {

    $filter = isset($args[0])
      ? AbstractFilter::create($name, $args[0])
      : AbstractFilter::create($name);
    if ($filter) {
      $this->pipe = $filter->filter($this->pipe);
    }
    else {
      throw new \DomainException('Filter not found: ' . $name);
    }
    return $this;
  }
}
