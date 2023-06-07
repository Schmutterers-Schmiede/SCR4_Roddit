<?php
namespace Application;

readonly final class ThreadData {
  public function __construct(
    public string $userName,
    public string $id,
    public string $title,
    public \DateTime $timestamp,
    public array $entries,
    public bool $deletable
    ){
      if(count($entries) !== 0){
        $this->lastEntryUserName = $entries[0]->userName;
        $this->lastEntryTimestamp = $entries[0]->timestamp;
      }
      $this->entryCount = count($entries);
    }

    public string $lastEntryUserName;
    public \DateTime $lastEntryTimestamp;
    public int $entryCount;
    

}