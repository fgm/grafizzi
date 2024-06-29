<?php declare(strict_types=1);

/**
 * @file
 * Grafizzi\Graph\AbstractNamed: a component of the Grafizzi library.
 *
 * (c) 2012-2024 Frédéric G. MARAND <fgm@osinet.fr>
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

use Pimple\Container;
use Psr\Log\LoggerInterface;

abstract class AbstractNamed implements NamedInterface {

  public string $fName = '';

  /**
   * A shortcut to the injected logger.
   *
   * @var \Psr\Log\LoggerInterface
   */
  public LoggerInterface $logger;

  /**
   * @var \Pimple\Container
   */
  protected Container $dic;

  function __construct(Container $dic) {
    $this->dic = $dic;
    $this->logger = &$dic['logger'];
  }

  /**
   * Will also handle numbers and booleans, and any object with a __toString().
   *
   * @param mixed $value
   * @param boolean $pseudoHtml
   *   Wrap GraphViz-style pseudo-HTML text.
   *
   * @return string
   */
  public static function escape($value, bool $pseudoHtml = FALSE): string {
    $keywords = [
      'digraph',
      'edge',
      'graph',
      'node',
      'strict',
      'subgraph',
    ];

    $wrapping = FALSE;

    // 1. Stringify anything.
    $s = static::stringify($value);
    $kwDetector = trim(strtolower($s));
    if ($kwDetector === '') {
      $s = 'false';
    }

    // 2. Handle keywords specifically.
    if (in_array($kwDetector, $keywords) || !self::validateId($kwDetector)) {
      $wrapping = 'dquote';
    }

    // 3. Wrap requested pseudo-html if it contains at least one terminated element.
    if ($pseudoHtml && (str_contains($s, '</') || str_contains($s, '/>'))) {
      $wrapping = 'html';
    }

    // 4. Normalize quotes and new lines
    if ($wrapping != 'html') {
      $s = str_replace(["\r\n", "\n", "\r", '"'],
        ['\n', '\n', '\n', '\"'], $s);
    }

    // 5. Wrap in double quotes if needed.
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
   * @see NamedInterface::getBuildName()
   */
  public function getBuildName(): string {
    return $this->escape($this->getName());
  }

  /**
   * @throws AttributeNameException
   * @see NamedInterface::getName()
   *
   */
  public function getName(): string {
    if ($this->fName === '') {
      $message = 'Getting name for unnamed object.';
      $this->logger->error($message);
      throw new AttributeNameException($message);
    }
    return $this->fName;
  }

  /**
   * @see NamedInterface::setName()
   */
  public function setName($name): void {
    $this->logger->debug($this->getType() . " attribute name set to $name.");
    $this->fName = $this->stringify($name);
  }

  /**
   * @param mixed $any
   *
   * @return string
   */
  public static function stringify(mixed $any): string {
    if (is_string($any)) {
      return $any;
    }
    if (is_object($any) && method_exists($any, '__toString')) {
      return $any->__toString();
    }
    if (is_bool($any)) {
      return $any ? 'true' : 'false';
    }
    return "$any";
  }

  /**
   * Helper for escape(). Validate non-quoted id.
   *
   * @see escape()
   */
  protected static function validateId(string $id): bool {
    $regex = '^([a-z_][a-z_0-9]*|-?(\.[0-9]+|[0-9]+(\.[0-9]*)?))$';
    return (bool) preg_match("/$regex/i", $id);
  }

}
