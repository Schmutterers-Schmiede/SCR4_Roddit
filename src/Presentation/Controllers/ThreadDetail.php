<?php

namespace Presentation\Controllers;

class ThreadDetail extends \Presentation\MVC\Controller {
  public function __construct(
    private \Application\ThreadByIdQuery $threadByIdQuery,
    private \Application\SignedInUserQuery $signedInUserQuery
  ){}

  //go to detailed thread page that shows the entries
  public function GET_Thread() : \Presentation\MVC\ViewResult {
    return new \Presentation\MVC\ViewResult('threadDetail', [
      'user' => $this->signedInUserQuery->execute(),
      'selectedThread' => $this->threadByIdQuery->execute($this->tryGetParam('tid', $value))
    ]);
  }
}