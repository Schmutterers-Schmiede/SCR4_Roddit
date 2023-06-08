<?php

namespace Application;

class ThreadSearchQuery
{
    public function __construct(
        private Interfaces\ThreadRepository $threadRepository,
        private \Application\SignedInUserQuery $signedInUserQuery
    ) {
    }

    public function execute(string $filter): array
    {
        $res = [];
        foreach ($this->threadRepository->getThreadsForFilter($filter) as $thread) {
          $resEntries = [];
          foreach($thread->getEntries() as $e) {
            $entryDeletable = $this->signedInUserQuery->execute()->userName === $e->getUserName();
            $resEntries[] = new \Application\EntryData($e->getId(), $e->getUserName(), $e->getTimeStamp(), $e->getText(), $entryDeletable);
          }
          $user = $this->signedInUserQuery->execute();
          $threadDeletable = $user !== null && $user->userName === $thread->getUserName();
          $res[] = new \Application\ThreadData( $thread->getUserName(), 
                                                $thread->getId(),
                                                $thread->getTitle(),
                                                $thread->getTimeStamp(),
                                                $resEntries,
                                                $threadDeletable);
        }
        return $res;
    }
}
