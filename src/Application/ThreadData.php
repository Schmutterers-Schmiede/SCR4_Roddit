<?php
namespace Application;

readonly final class ThreadData {
  public function __construct(
    public string $userName,
    public string $id,
    public string $title,
    public \DateTime $timestamp,
    public array $entries
    ){
      $this->lastEntryUserName = $entries[0]->userName;
      $this->lastEntryTimestamp = $entries[0]->timestamp;
    }

    public string $lastEntryUserName;
    public \DateTime $lastEntryTimestamp;
    

}