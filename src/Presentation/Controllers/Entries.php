<?php

namespace Presentation\Controllers;
use Presentation\MVC\ViewResult;

class Entries extends \Presentation\MVC\Controller {
  public function __construct(    
    private \Application\SignedInUserQuery $signedInUserQuery,
    private \Application\CreateEntryCommand $createEntryCommand,
    private \Application\LatestEntryQuery $latestEntryQuery,
    private \Application\ThreadByIdQuery $threadByIdQuery,
    private \Application\ThreadsQuery $threadsQuery,
    private \Application\DeleteEntryCommand $deleteEntryCommand
  ){}

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
    if(strlen($text) === 0){
      //ERROR: no text entered
      return $this->view('entryPost', [
        'user' => $this->signedInUserQuery->execute(),
        'threadId' => $threadId,
        'latestEntry' => $this->latestEntryQuery->execute(),
        'errors' => ['No text entered']
      ]);
    }
    else if($userId !== (int)$this->getParam('userId')){
      //invalid user id
      return new \Presentation\MVC\ViewResult('home', [
        'user' => $this->signedInUserQuery->execute(),
        'threads' => $this->threadsQuery->execute(),
        'latestEntry' => $this->latestEntryQuery->execute(),
        'errors' => ['Action denied']
      ]);
    }            
    //creation successful         
    $this->createEntryCommand->execute($userId, $threadId, $text);
    $thread = $this->threadByIdQuery->execute($threadId);
    return new ViewResult('threadDetail', [          
      'user' => $this->signedInUserQuery->execute(),          
      'title' => $thread->title,
      'entries' => $thread->entries,
      'threadId' => $thread->id,
      'latestEntry' => $this->latestEntryQuery->execute(),          
    ]);        
  }

  public function POST_DeleteEntry(): \Presentation\MVC\ViewResult{
    if($this->signedInUserQuery->execute()->userName === $this->getParam('author')){
      //authorization successful
      $this->deleteEntryCommand->execute((int)$this->getParam('entryId'));
      $selectedThread = $this->threadByIdQuery->execute($this->getParam('threadId'));
      return new \Presentation\MVC\ViewResult('threadDetail', [
        'user' => $this->signedInUserQuery->execute(),
        'title' => $selectedThread->title,
        'entries' => $selectedThread->entries,
        'threadId' => $this->getParam('threadId'),
        'latestEntry' => $this->latestEntryQuery->execute()
      ]);
    }
    //hacker attack
    return new \Presentation\MVC\ViewResult('home', [
      'user' => $this->signedInUserQuery->execute(),
      'threads' => $this->threadsQuery->execute(),
      'latestEntry' => $this->latestEntryQuery->execute(),
      'errors' => ['Action denied.']
    ]);
  }
    

  
}