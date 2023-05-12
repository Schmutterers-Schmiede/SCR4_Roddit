<?php
// === register autoloader
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/src/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once($file);
    }
});

$sp = new ServiceProvider();
// === Application
$sp->register(\Application\SignedInUserQuery::class);
$sp->register(\Application\SignOutCommand::class);
$sp->register(\Application\SignInCommand::class);
$sp->register(\Application\ThreadsQuery::class);
$sp->register(\Application\ThreadByIdQuery::class);
// === Services
$sp->register(\Application\Services\AuthenticationService::class);
$sp->register(\Application\Interfaces\ThreadRepository::class, \Infrastructure\FakeRepository::class);
// === Infrastructure
$sp->register(\Infrastructure\Session::class, isSingleton: true);
$sp->register(\Infrastructure\FakeRepository::class, isSingleton: true);
$sp->register(\Application\Interfaces\UserRepository::class, \Infrastructure\FakeRepository::class);
$sp->register(\Application\Interfaces\Session::class, \Infrastructure\Session::class);
// === Presentation
$sp->register(\Presentation\MVC\MVC::class, function() {return new \Presentation\MVC\MVC(); }); //implicitly converted to string
$sp->register(\Presentation\Controllers\Home::class);
$sp->register(\Presentation\Controllers\User::class);
$sp->register(\Presentation\Controllers\ThreadDetail::class);
//(new \Presentation\MVC\MVC())->handleRequest($sp);
$sp->resolve(\Presentation\MVC\MVC::class)->handleRequest($sp);

