<?php

namespace Application;

class LatestEntryQuery
{
    public function __construct(
        private Interfaces\ThreadRepository $threadRepository
    ) {
    }

    public function execute(): ?\Application\EntryInfoData
    {
      
      $resEntryData = $this->threadRepository->getLatestEntry();
      if($resEntryData != null)
        $res = new \Application\EntryInfoData($resEntryData->getThreadTitle(), $resEntryData->getUserName(), $resEntryData->getTimeStamp());
      else
        $res = null;
      
        return $res;
    }
}