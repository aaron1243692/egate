<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        $columns = Schema::getColumnListing('users');

        $expectedColumns = ['id', 'username', 'email', 'password', 'created_at', 'updated_at'];
        sort($columns);
        $sortedExpected = $expectedColumns;
        sort($sortedExpected);

        if ($columns === $sortedExpected) {
            return;
        }

        $existingUsers = DB::table('users')->orderBy('id')->get();
        $usedUsernames = [];

        Schema::create('users_rbac_tmp', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });

        foreach ($existingUsers as $user) {
            $candidate = property_exists($user, 'username') && filled($user->username)
                ? $user->username
                : (property_exists($user, 'name') && filled($user->name)
                    ? $user->name
                    : Str::before((string) $user->email, '@'));

            $username = $this->makeUniqueUsername((string) $candidate, $usedUsernames);

            DB::table('users_rbac_tmp')->insert([
                'id' => $user->id,
                'username' => $username,
                'email' => $user->email,
                'password' => $user->password,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]);
        }

        Schema::drop('users');
        Schema::rename('users_rbac_tmp', 'users');
    }

    public function down(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        $existingUsers = DB::table('users')->orderBy('id')->get();

        Schema::create('users_legacy_tmp', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
        });

        foreach ($existingUsers as $user) {
            DB::table('users_legacy_tmp')->insert([
                'id' => $user->id,
                'name' => $user->username,
                'email' => $user->email,
                'email_verified_at' => null,
                'password' => $user->password,
                'remember_token' => null,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]);
        }

        Schema::drop('users');
        Schema::rename('users_legacy_tmp', 'users');
    }

    private function makeUniqueUsername(string $value, array &$usedUsernames): string
    {
        $base = Str::of($value)->lower()->snake('');
        $base = preg_replace('/[^a-z0-9_]+/', '', (string) $base) ?: 'user';
        $username = $base;
        $suffix = 1;

        while (in_array($username, $usedUsernames, true) || DB::table('users_rbac_tmp')->where('username', $username)->exists()) {
            $username = "{$base}{$suffix}";
            $suffix++;
        }

        $usedUsernames[] = $username;

        return $username;
    }
};
