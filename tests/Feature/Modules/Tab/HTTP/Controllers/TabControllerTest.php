<?php

declare(strict_types=1);

use App\Modules\Tab\Models\Tab;
use App\Modules\User\Models\User;

it("has 'api', auth and 'verified' middleware applied", function () {
    $this->assertRouteHasMiddleware('tabs.show', ['api', 'auth:sanctum', 'verified']);
});

it("gets a logged in user's tab", function () {
    // Arrange
    $user = User::factory()->create();
    ['tab' => $tab] = Tab::factory()->prepareForUser($user);

    // Act
    $response = $this->actingAs($user, 'sanctum')->getJson(route('tabs.show', ['tab_id' => $tab->id]));

    // Assert
    $response->assertOk();
    expect($response->getOriginalContent()->id)->toEqual($tab->id);
});

it("doesn't get another user's tab", function () {
    // Arrange
    $userOne = User::factory()->create();
    ['tab' => $tab] = Tab::factory()->prepareForUser($userOne);

    $userTwo = User::factory()->create();

    // Act
    $response = $this->actingAs($userTwo, 'sanctum')->getJson(route('tabs.show', ['tab_id' => $tab->id]));

    // Assert
    $response->assertNotFound();
});
