<?php
namespace Application\Entities;

class Thread {
  public function __construct(
    private int $id,
    private string $userName,
    private string $title,
    private \DateTime $dateTime,
    private array $entries
    ){}

    public function getId(): int {
      return $this->id;
    }
    public function getUserName(): string {
      return $this->userName;
    }
    public function getTitle(): string {
      return $this->title;
    }
    public function getDateTime(): \DateTime{
      return $this->dateTime;
    }
    public function getEntries(): array {
      return $this->entries;
    }
    
    

}