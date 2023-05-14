<?php
namespace Application\Entities;

class Entry {
  public function __construct(
    private int $id,
    private int $ThreadId,
    private string $userName,    
    private \DateTime $timeStamp,
    private string $text
    ){}

    public function getId(): int {
      return $this->id;
    }
    public function getUserName(): string {
      return $this->userName;
    }
    
    public function getTimeStamp(): \DateTime {
      return $this->timeStamp;
    }

    public function getText(): string {
      return $this->text;
    }    

}