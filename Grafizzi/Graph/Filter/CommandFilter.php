<?php
namespace Grafizzi\Graph\Filter;
use Grafizzi\Graph\Filter\AbstractFilter;

/**
 * Filters implemented as an executable file
 */
abstract class AbstractCommandFilter extends AbstractFilter {

  public static $commandName;

  public function run() {
    $descriptorSpec = array(
      0 => array('pipe', 'r'),
      1 => array('pipe', 'w'),
      2 => array('pipe', 'w'),
    );
    $pipes = array();

    $process = proc_open(self::$commandName, $descriptorSpec, $pipes, NULL, NULL);
    if (!is_resource($process)) {
      throw new \ErrorException('"' . self::$commandName . '" command could not be run.');
    }

    fclose($pipes[0]);
    $ret = array(
      'data' => stream_get_contents($pipes[1]),
      'info' => stream_get_contents($pipes[2]),
    );
    fclose($pipes[1]);
    fclose($pipes[2]);
    proc_close($process);

    return $ret;
  }
}
