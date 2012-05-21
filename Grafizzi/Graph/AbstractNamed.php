<?php
namespace Grafizzi\Graph;

use Monolog\Logger;

abstract class AbstractNamed implements NamedInterface {

  public $fName;

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
    $this->setName($dic['name']);
  }

  /**
   * @see NamedInterface::getBuildName()
   */
  public function getBuildName() {
    return $this->getName();
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
