<?php

declare(strict_types=1);

namespace App\Modules\User\Presentation\HTTP\Controllers\Auth;

use App\Modules\Core\HTTP\Controllers\Controller;
use App\Modules\User\Domain\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

final class RegistrationController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->string('password')->toString()),
        ]);

        event(new Registered($user));

        return response()->json(['message' => 'Successfully registered new user'], 201);
    }
}
