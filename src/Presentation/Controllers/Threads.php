<?php

namespace Presentation\Controllers;

class books extends \Presentation\MVC\Controller {
  public function __construct(
    private \Application\ThreadsQuery $threadsQuery
  ){}
}