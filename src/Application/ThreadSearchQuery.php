<?php

namespace Application;

class ThreadSearchQuery
{
    public function __construct(
        private Interfaces\ThreadRepository $threadRepository,
        private Services\AuthenticationService $authenticationService
    ) {
    }

    public function execute(string $filter): array
    {
        $res = [];
        foreach ($this->threadRepository->getThreadsForFilter($filter) as $thread) {
          foreach($thread->getEntries() as $e) {
            $resEntries[] = new \Application\EntryData($e->getUserName(), $e->getTimeStamp(), $e->getText());
          }
            $res[] = new \Application\ThreadData( $thread->getUserName(), 
                                                  $thread->getId(),
                                                  $thread->getTitle(),
                                                  $thread->getTimeStamp(),
                                                  $resEntries,
                                                  $this->authenticationService->getUserId() === $thread->getId());
        }
        return $res;
    }
}
