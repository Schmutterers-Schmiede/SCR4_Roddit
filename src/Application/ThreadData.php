<?php
namespace Application;

readonly final class ThreadData {
  public function __construct(
    public string $userName,
    public string $title,
    public string $dateTime,
    public array $entries
    ){
      $this->lastEntryUserName = $entries[0]->getUserName();
      $this->lastEntryDateTime = $entries[0]->getDateTime();
    }

    public string $lastEntryUserName;
    public string $lastEntryDateTime;
    

}