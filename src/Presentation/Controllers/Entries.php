<?php

namespace Presentation\Controllers;
use Presentation\MVC\ViewResult;

class Entries extends \Presentation\MVC\Controller {
  public function __construct(    
    private \Application\SignedInUserQuery $signedInUserQuery,
    private \Application\CreateEntryCommand $createEntryCommand,
    private \Application\LatestEntryQuery $latestEntryQuery,
    private \Application\ThreadByIdQuery $threadByIdQuery
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