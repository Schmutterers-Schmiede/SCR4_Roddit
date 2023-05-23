<?php

namespace Presentation\Controllers;

class Threads extends \Presentation\MVC\Controller {
  public function __construct(
    private \Application\ThreadByIdQuery $threadByIdQuery,
    private \Application\SignedInUserQuery $signedInUserQuery,
    private \Application\ThreadSearchQuery $threadSearchQuery,
    private \Application\CreateThreadCommand $createThreadCommand,
    private \Application\CreateEntryCommand $createEntryCommand
  ){}

  //go to detailed thread page that shows the entries
  public function GET_Thread() : \Presentation\MVC\ViewResult {
    $thread = $this->threadByIdQuery->execute($this->getParam('tid'));
    return new \Presentation\MVC\ViewResult('threadDetail', [
      'user' => $this->signedInUserQuery->execute(),
      'thread' => $thread
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

  public function GET_PostThread(): \Presentation\MVC\ViewResult {
    return new \Presentation\MVC\ViewResult('threadPost', [
      'user' => $this->signedInUserQuery->execute()
    ]);
  }

  public function POST_Create(): \Presentation\MVC\ActionResult {
    $userId = $this->signedInUserQuery->execute()->id;
    if($this->createThreadCommand->execute($userId, $this->getParam('title'))){
      //Creation successful -> go to home
      return $this->redirect('Home', 'Index');
    }
    //Creation unsuccessful -> reload Create Thread page with error
    return $this->view('threadPost', [
      'user' => $this->signedInUserQuery->execute(),      
      'errors' => ['This thread already exists']
    ]);
  }

  // public function GET_CreateEntry(): \Presentation\MVC\ViewResult {
  //   return new \Presentation\MVC\ViewResult('threadPost', [
  //     'user' => $this->signedInUserQuery->execute(),
  //     'threadId' => 
  //   ]);
  // }
  // public function POST_PostEntry(): \Presentation\MVC\ViewResult {
  //   $userId = $this->signedInUserQuery->execute()->id;
  //   $this->createEntryCommand->execute($userId)
    
  // }
}