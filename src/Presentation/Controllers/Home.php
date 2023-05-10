<?php

namespace Presentation\Controllers;

class Home {
  public function __construct(
    private \Application\SignedInUserQuery $signedInUserQuery,
    private \Application\ThreadsQuery $threadsQuery
  ){}
  public function GET_Index() : \Presentation\MVC\ViewResult {
    return new \Presentation\MVC\ViewResult('home', [
      'user' => $this->signedInUserQuery->execute(),
      'threads' => $this->threadsQuery->execute()
    ]);
  }
}