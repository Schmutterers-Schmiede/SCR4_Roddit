<?php

namespace Application\Interfaces;

interface EntryRepository {
  public function createEntry(int $threadId, int $userId, string $text);
  public function getLatestEntry();
  public function deleteEntry(int $entryId);
}