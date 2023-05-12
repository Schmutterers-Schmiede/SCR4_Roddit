<?php

namespace Presentation\Controllers;

class Threads extends \Presentation\MVC\Controller {
  public function __construct(
    private \Application\ThreadByIdQuery $threadByIdQuery,
    private \Application\SignedInUserQuery $signedInUserQuery,
    private \Application\ThreadSearchQuery $threadSearchQuery
  ){}

  //go to detailed thread page that shows the entries
  public function GET_Thread() : \Presentation\MVC\ViewResult {
    return new \Presentation\MVC\ViewResult('threadDetail', [
      'user' => $this->signedInUserQuery->execute(),
      'selectedThread' => $this->threadByIdQuery->execute($this->tryGetParam('tid', $value))
    ]);
  }

  public function GET_Search() : \Presentation\MVC\ViewResult {
    return $this->view('threadSearch', [
      'user' => $this->signedInUserQuery->execute(),
      'filter' => $this->tryGetParam('f', $value) ? $value : '',
      'threads' => $this->tryGetParam('f', $value) ? $this->threadSearchQuery->execute($value) : null,
      'context' => $this->getRequestUri()
    ]);
  }
}