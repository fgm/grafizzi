<?php
namespace Grafizzi\Graph;

use Monolog\Logger;
use Grafizzi\Graph\NamedInterface;
use Grafizzi\Graph\AttributeNameException;

abstract class AbstractLoggable implements NamedInterface {

  public $fName;

  /**
   * @var Logger
   */
  protected $logger;

  /**
   * @var \Pimple
   */
  protected $dic;

  function __construct(\Pimple $dic) {
    $this->dic = $dic;
    $this->logger = $dic['logger'];
  }

  /**
   * @see NamedInterface::getName()
   *
   * @throws AttributeNameException
   */
  public function getName() {
    if (!isset($this->fName)) {
      throw new AttributeNameException('Getting name for unnamed object.');
    }
    return $this->fName;
  }

  /**
   * @see NamedInterface::setName()
   */
  public function setName($name) {
    $this->fName = $name;
  }
}
