<?php declare(strict_types=1);

/**
 * @file
 * Grafizzi\Graph\AbstractElement: a component of the Grafizzi library.
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

/**
 * Status: working, some possibly useless code. Handle error situations better.
 */
abstract class AbstractElement extends AbstractNamed implements ElementInterface {

  const DEPTH_INDENT = 2;

  /**
   * @var \Grafizzi\Graph\AttributeInterface[]
   */
  public array $fAttributes = [];

  /**
   * @var \Grafizzi\Graph\ElementInterface[] $fChildren
   */
  public array $fChildren = [];

  /**
   * The nesting level of the element.
   *
   * An unbound element, like the root graph, has depth 0.
   *
   * @var int
   */
  public int $fDepth = 0;

  /**
   * The parent element, when bound, or null otherwise.
   *
   * @var ?ElementInterface
   */
  public ?ElementInterface $fParent = NULL;

  /**
   * Possibly not needed with an efficient garbage collector, but might help in
   * case of dependency loops.
   *
   * XXX 20120512 check if really useful.
   */
  public function __destruct() {
    try {
      $name = $this->getName();
    } catch (AttributeNameException $e) {
      $name = 'unnamed';
    }
    $type = $this->getType();
    $this->logger->debug("Destroying $type $name");
    foreach ($this->fAttributes as &$attribute) {
      unset($attribute);
    }
    foreach ($this->fChildren as &$child) {
      unset($child);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function addChild(ElementInterface $child): ElementInterface {
    $name = $this->getName();
    $childName = $child->getName();
    $childType = $child->getType();
    $this->logger->debug("Adding child $childName, type $childType, to $name, depth {$this->fDepth}.");
    if (!in_array($childType, $this->getAllowedChildTypes())) {
      $message = "Invalid child type $childType for element $name.";
      $this->logger->error($message);
      throw new ChildTypeException($message);
    }
    $this->fChildren[$childName] = $child;
    $child->adjustDepth($this->fDepth + 1);
    $child->setParent($this);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function adjustDepth($extra = 0) {
    $this->logger->debug("Adjusting depth {$this->fDepth} by $extra.");
    $this->fDepth += $extra;
    /** @var AbstractElement $child */
    foreach ($this->fChildren as $child) {
      $child->adjustDepth($extra);
    }
    return $this->fDepth;
  }

  /**
   * Build the DOT string for this subtree.
   *
   * Ignores $directed.
   *
   * @param bool $directed
   *
   * @return string
   * @see NamedInterface::build()
   *
   */
  public function build(?bool $directed = NULL): string {
    $type = $this->getType();
    $name = $this->getName();
    $this->logger->debug("Building element $name.");
    $attributes = array_map(function (AttributeInterface $attribute) use (
      $directed
    ) {
      return $attribute->build($directed);
    }, $this->fAttributes);
    $name = $this->escape($name);
    $ret = str_repeat(' ', $this->fDepth * self::DEPTH_INDENT)
      . $this->buildAttributes($attributes, $type, $name)
      . ";\n";
    return $ret;
  }

  /**
   * @param array<string,string> $attributes
   * @param string $type
   * @param string $name
   *
   * @return string
   */
  protected function buildAttributes(
    array $attributes,
    string $type,
    string $name
  ): string {
    $ret = '';
    if (!empty($attributes)) {
      $builtAttributes = implode(', ', array_filter($attributes));
      if (!empty($builtAttributes)) {
        $prefix = '';
        if ($type) {
          $prefix .= "$type ";
        }
        if ($name) {
          $prefix .= "$name ";
        }
        if (empty($prefix)) {
          $prefix = ' ';
        }

        $ret .= "{$prefix}[ $builtAttributes ]";
      }
    }

    $ret .= ";\n";
    return $ret;
  }

  /**
   * @return array<string>
   */
  public static function getAllowedChildTypes(): array {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getAttributeByName($name): ?AttributeInterface {
    $ret = $this->fAttributes[$name] ?? NULL;
    $this->logger->debug("Getting attribute [$name]: " . print_r($ret,
        TRUE) . ".");
    return $ret;
  }

  /**
   * @param mixed $name
   *   Will be stringified.
   *
   * @return ?\Grafizzi\Graph\ElementInterface
   */
  public function getChildByName($name): ?ElementInterface {
    $ret = $this->fChildren[$name] ?? NULL;
    return $ret;
  }

  /**
   * {@inheritdoc}
   */
  public function getParent(): ?ElementInterface {
    return $this->fParent;
  }

  /**
   * {@inheritdoc}
   */
  public function getRoot(): ?ElementInterface {
    $current = $this;
    // Beware of priorities: do not remove parentheses.
    while (($parent = $current->getParent()) instanceof ElementInterface) {
      $current = $parent;
    }
    return $current;
  }

  /**
   * {@inheritdoc}
   */
  public function removeAttribute(AttributeInterface $attribute): void {
    $name = $attribute->getName();
    if (empty($name)) {
      $message = 'Trying to remove unnamed attribute.';
      $this->logger->warning($message);
      throw new AttributeNameException($message);
    }
    $this->removeAttributeByName($name);
  }

  /**
   * {@inheritdoc}
   */
  public function removeAttributeByName($name): void {
    $this->logger->debug("Removing attribute [$name].");
    unset($this->fAttributes[$name]);
  }

  /**
   * {@inheritdoc}
   */
  public function removeChild(ElementInterface $child): ?ElementInterface {
    $name = $child->getName();
    if (empty($name)) {
      $message = 'Trying to remove unnamed child.';
      $this->logger->warning($message);
      throw new ChildNameException($message);
    }
    $ret = $this->removeChildByName($name);
    return $ret;
  }

  /**
   * {@inheritdoc}
   */
  public function removeChildByName($name): ?ElementInterface {
    $this->logger->debug("Removing child [$name].");
    if (isset($this->fChildren[$name])) {
      $child = $this->fChildren[$name];
      $child->adjustDepth(-$this->fDepth - 1);
      unset($this->fChildren[$name]);
      $ret = $child;
    }
    else {
      $ret = NULL;
    }
    return $ret;
  }

  /**
   * {@inheritdoc}
   */
  public function setAttribute(AttributeInterface $attribute): void {
    $name = $attribute->getName();
    if (empty($name)) {
      $message = 'Trying to set unnamed attribute.';
      $this->logger->warning($message, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT));
      throw new ChildNameException($message);
    }
    $this->fAttributes[$name] = $attribute;
  }

  /**
   * @param array<\Grafizzi\Graph\AttributeInterface> $attributes
   *   An array of objects implementing AttributeInterface
   *
   * @return void
   */
  public function setAttributes(array $attributes): void {
    foreach ($attributes as $attribute) {
      if (!in_array('Grafizzi\\Graph\\AttributeInterface', class_implements($attribute))) {
        $message = 'Trying to set non-attribute as an attribute';
        $this->logger->warning($message);
        throw new AttributeNameException($message);
      }
      $this->setAttribute($attribute);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setParent(ElementInterface $parent): void {
    $this->fParent = $parent;
  }

}
