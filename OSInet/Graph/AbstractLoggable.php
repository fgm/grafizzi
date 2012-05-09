<?php
namespace OSInet\Graph;

use Monolog\Logger;

class AbstractLoggable {

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
}
