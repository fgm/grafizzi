<?php declare(strict_types=1);

/**
 * @file
 * Grafizzi\Graph\Attribute: a component of the Grafizzi library.
 *
 * (c) 2012-2022 Frédéric G. MARAND <fgm@osinet.fr>
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

/**
 * An Element attribute.
 */
class Attribute extends AbstractNamed implements AttributeInterface {

  /**
   * A hash of default values for allowed attributes.
   *
   * If it is empty, any attribute is allowed.
   *
   * @var array<string,mixed>
   */
  public static $fDefaults = [];

  /**
   * @var mixed
   */
  public $fValue;

  /**
   * @param \Pimple\Container $dic
   * @param string $name
   * @param mixed $value
   *
   * @see AttributeInterface::__construct()
   */
  public function __construct(
    Container $dic,
    string $name,
    $value = NULL
  ) {
    parent::__construct($dic);
    $this->setName($name);
    $this->setValue($value);
  }

  /**
   * @param boolean $directed
   *   Needed for signature conformity,but actually ignored.
   *
   * @return string
   * @todo FIXME escape name, value more carefully
   *
   */
  public function build(?bool $directed = NULL): string {
    $name = $this->getName();
    $this->logger->debug("Building attribute " . $name);
    $value = $this->getValue();
    if ('title' == $name && empty($value)) {
      $ret = '';
    }
    else {
      $escape = in_array($name, ['label', 'headlabel', 'taillabel']);
      $ret = "$name=" . self::escape($value ?? '', $escape);
    }
    return $ret;
  }

  /**
   * @return array<string>
   *
   * @see AttributeInterface::getAllowedNames()
   */
  public static function getAllowedNames(): array {
    $ret = array_keys(self::$fDefaults);
    return $ret;
  }

  /**
   * {@inheritdoc}
   *
   * @return mixed
   */
  public static function getDefaultValue(string $name) {
    $ret = self::$fDefaults[$name] ?? NULL;
    return $ret;
  }

  public function getType(): string {
    return 'attribute';
  }

  /**
   * @return mixed
   */
  public function getValue() {
    return $this->fValue;
  }

  /**
   * In addition to basic behavior, validate name.
   *
   * @throws AttributeNameException
   * @see AbstractNamed::setName()
   *
   */
  public function setName($name): void {
    if (!empty(self::$fDefaults) && !array_key_exists($name,
        self::$fDefaults)) {
      $message = "Invalid attribute $name.";
      $this->logger->error($message);
      throw new AttributeNameException($message);
    }
    parent::setName($name);
  }

  /**
   * Note: a null default value will work too: if $value is not null, the
   * default is ignored, and if value is null, isset() fails, and fValue is set
   * to $value, i.e. null too.
   *
   * @see AttributeInterface::setValue()
   */
  public function setValue($value = NULL) {
    $this->logger->debug("{$this->fName}->value = "
      . print_r($value, TRUE) . ".");
    if (!isset($value) && isset(self::$fDefaults[$this->fName])) {
      $value = self::$fDefaults[$this->fName];
    }
    $this->fValue = $value;
  }

}
