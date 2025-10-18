<?php

namespace App\Console\Commands;

use App\Models\Enums\UserRole;
use App\Models\User;
use Illuminate\Console\Command;

class MakeAdmin extends Command
{
    protected $signature = 'make:admin {email}';

    protected $description = 'Promote a user to admin role';

    public function handle(): int
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error('User not found.');

            return self::FAILURE;
        }

        $user->update(['role' => UserRole::Admin]);
        $this->info("{$user->email} promoted to admin.");

        return self::SUCCESS;
    }
}
