<?php

namespace Application;

class ThreadByIdQuery
{
    public function __construct(
        private Interfaces\ThreadRepository $threadRepository
    ) {
    }

    public function execute(int $id): \Application\ThreadData
    {
      $thread = $this->threadRepository->getThreadById($id);
      $entries = $thread->getEntries();      
      foreach($entries as $e){
        $resEntries[] = new \Application\EntryData($e->getUserName(), $e->getDateTime(), $e->getText());
      }
      $res = new \Application\ThreadData($thread->getUserName() ,$thread->getId() ,$thread->getTitle() ,$thread->getDateTime() ,$resEntries);
      return $res;
    }
}