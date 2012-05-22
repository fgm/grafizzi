<?php
namespace Grafizzi\Graph;

class Graph extends AbstractElement implements GraphInterface {

  /**
   * Helper to simplify construction of strict graphs.
   *
   * @return array
   */
  public static function strict() {
    return array('strict' => true);
  }

  /**
   * Generate non-strict graphs by default
   *
   * @var boolean
   */
  public $fStrict = false;

  /**
   * Generate digraphs by default.
   */
  public $fDirected = true;

  public function __construct(\Pimple $dic, $name = 'G', array $attributes = array()) {
    if (!isset($dic['directed'])) {
      $dic['directed'] = true;
    }
    parent::__construct($dic);
    $this->setName($name);
    if (!empty($attributes)) {
      if (isset($attributes['strict'])) {
        $this->fStrict = $attributes['strict'];
        unset($attributes['strict']);
      }
      $this->setAttributes($attributes);
    }
  }

  public function build($directed = NULL) {
    // Allow overriding the build directed attribute.
    if (isset($directed)) {
      $savedDirected = $this->getDirected();
      $this->setDirected($directed);
    }
    $actualDirected = $this->getDirected();
    $type = $this->getType();
    $buildName = $this->getBuildName();
    $elementIndent = str_repeat(' ', $this->fDepth * self::DEPTH_INDENT);
    $childIndent = str_repeat(' ', ($this->fDepth + 1) * self::DEPTH_INDENT);

    $strict = $this->fStrict ? 'strict ' : '';
    $ret = "$elementIndent$strict$type $buildName {\n";

    foreach ($this->fAttributes as $attribute) {
      $ret .= $childIndent . $attribute->build($directed) . ";\n";
    }
    if (count($this->fAttributes)) {
      $ret .= "\n";
    }

    foreach ($this->fChildren as $child) {
      $ret .= $child->build($actualDirected);
    }
    $ret .= "$elementIndent} /* /$type $buildName */\n";

    // Restore the directed attribute if it was changed for build.
    if (isset($directed)) {
      $this->setDirected($savedDirected);
    }
    return $ret;
  }

  public static function getAllowedChildTypes() {
    $ret = array(
      'cluster',
      'edge',
      'multiedge', // Grafizzi extension
      'node',
      'subgraph',
    );
    return $ret;
  }

  public function getDirected() {
    $ret = $this->fDirected;
    return $ret;
  }

  public function getType() {
    $ret = $this->getDirected() ? 'digraph' : 'graph';
    return $ret;
  }

  public function setDirected($directed) {
    $this->fDirected = $directed;
  }
}
