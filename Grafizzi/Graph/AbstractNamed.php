<?php
namespace Grafizzi\Graph;

abstract class AbstractNamed implements NamedInterface {

  public $fName = NULL;

  /**
   * A shortcut to the injected logger.
   *
   * @var Logger
   */
  public $logger;

  /**
   * @var \Pimple
   */
  protected $dic;

  function __construct(\Pimple $dic) {
    $this->dic = $dic;
    $this->logger = &$dic['logger'];
  }

  /**
   * Will also handle numbers and booleans, and any object with a __toString().
   *
   * @param string $string
   *   The string to escape.
   * @param boolean $pseudoHtml
   *   Wrap GraphViz-style pseudo-HTML text.
   *
   * @return string
   */
  public static function escape($string, $pseudoHtml = false) {
    $keywords = array(
      'digraph',
      'edge',
      'graph',
      'node',
      'strict',
      'subgraph',
    );

    $wrapping = false;

    // 1. Handle keywords specifically, convert anything else to string.
    $s = trim(strtolower($string));
    if (in_array($s, $keywords)) {
      $wrapping = true;
    } elseif (!isset($string)) {
      $s = 'false';
    } elseif (is_bool($string)) {
      $s = $string ? 'true' : 'false';
    } else {
      $s = (string) $string;
      if (!self::validateId($s)) {
        $wrapping = true;
      }
    }

    // 2. Wrap requested pseudo-html if it contains at least one terminated element.
    if ($pseudoHtml && (strpos($s, '</') !== false || strpos($s, '/>') !== false)) {
      $s = "<$s>";
      $wrapping = true;
    }

    // 3. Normalize quotes and new lines
    $s = str_replace(array("\r\n", "\n", "\r", '"'),
                     array('\n',   '\n', '\n', '\"'), $s);

    // 4. Wrap in double quotes if needed.
    if ($wrapping) {
      $s = '"' . $s . '"';
    }

    return $s;
  }

  /**
   * Helper for escape(). Validate non-quoted id.
   *
   * @see escape()
   *
   * @param string $id
   *
   * @return boolean
   */
  protected static function validateId($id) {
    $regex = '^([a-z_][a-z_0-9]*|-?(\.[0-9]+|[0-9]+(\.[0-9]*)?))$';
    $ret = preg_match("/$regex/i", $id) ? true : false;
    return $ret;
  }

  /**
   * @see NamedInterface::getBuildName()
   */
  public function getBuildName() {
    return $this->escape($this->getName());
  }

  /**
   * @see NamedInterface::getName()
   *
   * @throws AttributeNameException
   */
  public function getName() {
    if (!isset($this->fName)) {
      $message = 'Getting name for unnamed object.';
      $this->logger->err($message);
      throw new AttributeNameException($message);
    }
    return $this->fName;
  }

  /**
   * @see NamedInterface::setName()
   */
  public function setName($name) {
    $this->logger->debug($this->getType() . " attribute name set to $name.");
    $this->fName = $name;
  }
}
