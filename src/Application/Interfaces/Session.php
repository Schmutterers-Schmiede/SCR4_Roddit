<?php

namespace Application\Interfaces;

Interface Session {
  public function get(string $key) : mixed;
  public function put(string $key, mixed $value) : void;
  public function delete(string $key) : void;
}