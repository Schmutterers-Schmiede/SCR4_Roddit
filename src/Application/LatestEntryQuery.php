<?php

namespace Application;

class LatestEntryQuery
{
    public function __construct(
        private Interfaces\ThreadRepository $threadRepository
    ) {
    }

    public function execute(): \Application\EntryInfoData
    {
      
      $resEntryData = $this->threadRepository->getLatestEntry();
      $res = new \Application\EntryInfoData($resEntryData->getThreadTitle(), $resEntryData->getUserName(), $resEntryData->getTimeStamp());
      return $res;
    }
}