<?php

namespace Presentation\Controllers;
use Presentation\MVC\ViewResult;

class Threads extends \Presentation\MVC\Controller {
  public function __construct(    
    private \Application\ThreadsQuery $threadsQuery,
    private \Application\ThreadByIdQuery $threadByIdQuery,
    private \Application\SignedInUserQuery $signedInUserQuery,
    private \Application\ThreadSearchQuery $threadSearchQuery,
    private \Application\CreateThreadCommand $createThreadCommand,
    private \Application\DeleteThreadCommand $deleteThreadCommand,
    private \Application\CreateEntryCommand $createEntryCommand,
    private \Application\LatestEntryQuery $latestEntryQuery
  ){}

  //go to detailed thread page that shows the entries
  public function GET_Thread() : \Presentation\MVC\ViewResult {
    $selectedThread = $this->threadByIdQuery->execute($this->getParam('tid'));
    return new \Presentation\MVC\ViewResult('threadDetail', [
      'user' => $this->signedInUserQuery->execute(),
      'title' => $selectedThread->title,
      'entries' => $selectedThread->entries,
      'threadId' => $this->getParam('tid'),
      'latestEntry' => $this->latestEntryQuery->execute()
    ]);
  }

  public function GET_Search() : \Presentation\MVC\ViewResult {
    return $this->view('threadSearch', [
      'user' => $this->signedInUserQuery->execute(),
      'filter' => $this->tryGetParam('f', $value) ? $value : '',
      'threads' => $this->tryGetParam('f', $value) ? $this->threadSearchQuery->execute($value) : null,
      'context' => $this->getRequestUri(),
      'latestEntry' => $this->latestEntryQuery->execute()
    ]);
  }

  public function GET_PostThread(): \Presentation\MVC\ViewResult {
    return new \Presentation\MVC\ViewResult('threadPost', [
      'user' => $this->signedInUserQuery->execute(),
      'latestEntry' => $this->latestEntryQuery->execute()
    ]);
  }

  public function POST_CreateThread(): \Presentation\MVC\ActionResult {
    $userId = $this->getParam('userId');
    $title = $this->getParam('title');
    if(strlen($title) === 0){
      return $this->view('threadPost', [
        'user' => $this->signedInUserQuery->execute(),      
        'errors' => ['No Title entered.'],
        'latestEntry' => $this->latestEntryQuery->execute()
      ]);
    }
    if($this->signedInUserQuery->execute() === null){
      return new \Presentation\MVC\ViewResult('home', [
        'user' => $this->signedInUserQuery->execute(),
        'threads' => $this->threadsQuery->execute(),
        'latestEntry' => $this->latestEntryQuery->execute(),
        'errors' => ['Action denied']
      ]);
    }
    if($this->createThreadCommand->execute($userId, $title) === 0){
      //Creation successful -> go to home
      return $this->redirect('Home', 'Index');
    }
    else{
      //Creation unsuccessful -> reload Create Thread page with error
      return $this->view('threadPost', [
        'user' => $this->signedInUserQuery->execute(),      
        'errors' => ['A thread with this name already exists'],
        'latestEntry' => $this->latestEntryQuery->execute()
      ]);
    }
  }

  public function POST_DeleteThread(): \Presentation\MVC\ViewResult{
    if($this->signedInUserQuery->execute()->userName === $this->getParam('author')){
      //authorization successful
      $this->deleteThreadCommand->execute($this->getParam('threadId'));
      return new \Presentation\MVC\ViewResult('home', [
        'user' => $this->signedInUserQuery->execute(),
        'threads' => $this->threadsQuery->execute(),
        'latestEntry' => $this->latestEntryQuery->execute()
      ]);
    }
    //hacker attack
    return new \Presentation\MVC\ViewResult('home', [
      'user' => $this->signedInUserQuery->execute(),
      'threads' => $this->threadsQuery->execute(),
      'latestEntry' => $this->latestEntryQuery->execute(),
      'errors' => 'You are not authorized to delete this sübröddit'
    ]);
  }

  public function GET_PostEntry(): \Presentation\MVC\ViewResult {
    return new \Presentation\MVC\ViewResult('entryPost', [
      'user' => $this->signedInUserQuery->execute(),
      'threadId' => $this->getParam('threadId'),
      'latestEntry' => $this->latestEntryQuery->execute()
    ]);
  }

  
}