<?php
namespace Application;

readonly final class ThreadData {
  public function __construct(
    public string $userName,
    public string $id,
    public string $title,
    public \DateTime $dateTime,
    public array $entries
    ){
      $this->lastEntryUserName = $entries[0]->userName;
      $this->lastEntryDateTime = $entries[0]->dateTime;
    }

    public string $lastEntryUserName;
    public \DateTime $lastEntryDateTime;
    

}