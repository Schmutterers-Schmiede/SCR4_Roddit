<?php

namespace Presentation\Controllers;
use Presentation\MVC\ViewResult;

class Threads extends \Presentation\MVC\Controller {
  public function __construct(
    private \Application\ThreadByIdQuery $threadByIdQuery,
    private \Application\SignedInUserQuery $signedInUserQuery,
    private \Application\ThreadSearchQuery $threadSearchQuery,
    private \Application\CreateThreadCommand $createThreadCommand,
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
    $userId = $this->signedInUserQuery->execute()->id;
    if($this->createThreadCommand->execute($userId, $this->getParam('title'))){
      //Creation successful -> go to home
      return $this->redirect('Home', 'Index');
    }
    //Creation unsuccessful -> reload Create Thread page with error
    return $this->view('threadPost', [
      'user' => $this->signedInUserQuery->execute(),      
      'errors' => ['This thread already exists'],
      'latestEntry' => $this->latestEntryQuery->execute()
    ]);
  }

  public function GET_PostEntry(): \Presentation\MVC\ViewResult {
    return new \Presentation\MVC\ViewResult('entryPost', [
      'user' => $this->signedInUserQuery->execute(),
      'threadId' => $this->getParam('threadId'),
      'latestEntry' => $this->latestEntryQuery->execute()
    ]);
  }

  public function POST_CreateEntry(): \Presentation\MVC\ViewResult {
    $threadId = (int)$this->getParam('threadId');
    $text = $this->getParam('text');
    $userId = $this->signedInUserQuery->execute()->id;
    if(strlen($this->getParam('text')) === 0){
      //ERROR: no text entered
      return $this->view('threadPost', [
        'user' => $this->signedInUserQuery->execute(),
        'threadId' => $threadId,
        'latestEntry' => $this->latestEntryQuery->execute(),
        'errors' => 'No text entered'
      ]);
    }
    else{
      if($this->createEntryCommand->execute($userId, $threadId, $text)){
        //creation successful         
        $thread = $this->threadByIdQuery->execute($threadId);
        return new ViewResult('threadDetail', [          
          'user' => $this->signedInUserQuery->execute(),          
          'title' => $thread->title,
          'entries' => $thread->entries,
          'threadId' => $thread->id,
          'latestEntry' => $this->latestEntryQuery->execute(),          
        ]);
      }
      else{
        //hacker attack
        return new ViewResult('threadPost', [
          'user' => $this->signedInUserQuery->execute(),
          'threadId' => $this->getParam('threadId'),
          'latestEntry' => $this->latestEntryQuery->execute(),
          'errors' => 'something went wrong'
        ]);
      }
    }            
  }
}