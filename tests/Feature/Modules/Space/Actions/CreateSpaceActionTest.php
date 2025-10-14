<?php

declare(strict_types=1);

use App\Modules\Space\Actions\CreateSpaceAction;
use App\Modules\Space\DTOs\CreateSpaceDto;
use App\Modules\Space\Events\SpaceCreated;
use App\Modules\Space\Exceptions\DuplicateUserSpaceNameException;
use App\Modules\Space\Models\Space;
use App\Modules\User\Models\User;
use Illuminate\Support\Facades\Event;

it('creates a new user space', function () {
    // Arrange
    $user = User::factory()->create();
    $payload = ['name' => 'My New Space'];

    // Act
    $newSpace = app(CreateSpaceAction::class)->run($user, CreateSpaceDto::fromArray($payload));

    // Assert
    expect($newSpace->name)->toBe($payload['name'])
        ->and(Space::all())->toHaveCount(1)->first()->name->toBe($payload['name']);
});

it('dispatches a SpaceCreated event upon creating a new space', function () {
    // Arrange
    Event::fake();
    $user = User::factory()->create();

    // Act
    app(CreateSpaceAction::class)->run($user, CreateSpaceDto::fromArray(['name' => 'Space name']));

    // Assert
    Event::assertDispatched(SpaceCreated::class);
});

it('disallows creation of duplicate spaces, no events dispatched', function () {
    // Arrange
    $user = User::factory()->create();
    $payload = ['name' => 'My New Space'];
    Space::factory()->for($user)->create($payload);
    Event::fake();

    // Act & Assert
    expect(fn () => app(CreateSpaceAction::class)->run($user, CreateSpaceDto::fromArray($payload)))
        ->toThrow(DuplicateUserSpaceNameException::class);

    Event::assertNothingDispatched();
});
