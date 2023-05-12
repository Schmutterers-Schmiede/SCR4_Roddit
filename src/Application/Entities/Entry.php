<?php
namespace Application\Entities;

class Entry {
  public function __construct(
    private int $id,
    private int $ThreadId,
    private string $userName,    
    private \DateTime $dateTime,
    private string $text
    ){}

    public function getId(): int {
      return $this->id;
    }
    public function getUserName(): string {
      return $this->userName;
    }
    
    public function getDateTime(): \DateTime {
      return $this->dateTime;
    }

    public function getText(): string {
      return $this->text;
    }    

}