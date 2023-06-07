<?php

namespace Application;
use Application\Services\AuthenticationService;

class ThreadByIdQuery
{
    public function __construct(
        private Interfaces\ThreadRepository $threadRepository,
        private Services\AuthenticationService $authenticationService
    ) {
    }

    public function execute(int $id): \Application\ThreadData
    {
      $resEntries = [];
      $thread = $this->threadRepository->getThreadById($id);
      $entries = $thread->getEntries();      
      foreach($entries as $e){
        $resEntries[] = new \Application\EntryData($e->getUserName(), $e->getTimeStamp(), $e->getText());
      }
      $res = new \Application\ThreadData( $thread->getUserName(), 
                                          $thread->getId(), 
                                          $thread->getTitle(),
                                          $thread->getTimeStamp(),
                                          $resEntries, 
                                          $this->authenticationService->getUserId() === $thread->getId());
      return $res;
    }
}