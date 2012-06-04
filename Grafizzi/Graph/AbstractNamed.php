<?php

/**
 * @file
 * Grafizzi\Graph\AbstractNamed: a component of the Grafizzi library.
 *
 * (c) 2012 Frédéric G. MARAND <fgm@osinet.fr>
 *
 * Grafizzi is free software: you can redistribute it and/or modify it under the
 * terms of the GNU Lesser General Public License as published by the Free
 * Software Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * Grafizzi is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE.  See the GNU Lesser General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with Grafizzi, in the COPYING.LESSER.txt file.  If not, see
 * <http://www.gnu.org/licenses/>
 */
namespace Grafizzi\Graph;

abstract class AbstractNamed implements NamedInterface {

  public $fName = null;

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
      $wrapping = 'dquote';
    } elseif (!isset($string)) {
      $s = 'false';
    } elseif (is_bool($string)) {
      $s = $string ? 'true' : 'false';
    } else {
      $s = (string) $string;
      if (!self::validateId($s)) {
        $wrapping = 'dquote';
      }
    }

    // 2. Wrap requested pseudo-html if it contains at least one terminated element.
    if ($pseudoHtml && (strpos($s, '</') !== false || strpos($s, '/>') !== false)) {
      $wrapping = 'html';
    }

    // 3. Normalize quotes and new lines
    if ($wrapping != 'html') {
      $s = str_replace(array("\r\n", "\n", "\r", '"'),
                       array('\n',   '\n', '\n', '\"'), $s);
    }

    // 4. Wrap in double quotes if needed.
    switch ($wrapping) {
      case 'dquote':
        $s = '"' . $s . '"';
        break;

      case 'html':
        $s = "<$s>";
        break;

      default:
        // Do not wrap.
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
