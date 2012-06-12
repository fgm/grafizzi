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
   * Apply the rendering pipeline to the raw GraphViz source.
   *
   * @param string $raw
   * @return string
   */
  public function render($raw) {
    $ret = array_reduce($this->filters, function ($data, AbstractFilter $filter) {
      $ret = $filter($data);
      return $ret;
    }, $raw);

    return $ret;
  }

  public function __call($name, $args) {
    // echo "In ". __METHOD__ . "(" . print_r($args, true) . ")\n";
    // Will throw DomainException in case of error.
    array_unshift($args, $name);
    $filter = call_user_func_array(array(__NAMESPACE__ . '\\Filter\\AbstractFilter', 'create'), $args);
    // echo "Filter: " . var_export($filter, true) . "\n";
    if ($filter) {
      $this->pipe = $filter->filter($this->pipe);
    }
    return $this;
  }
}
