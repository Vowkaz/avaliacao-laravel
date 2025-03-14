<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('alias')->nullable()->after('name');
        });

        $permissions = [
            'gestao-usuarios' => 'Gestão de usuários',
            'gestao-produtos' => 'Gestão de produtos',
            'gestao-categorias' => 'Gestão de categorias',
            'gestao-marcas' => 'Gestão de marcas',
        ];

        foreach ($permissions as $name => $permission) {
            Permission::firstOrCreate(['name' => $name, 'alias' => $permission]);
        }

        $admin = Role::firstOrCreate(['name' => User::ROLE_ADMIN]);
        $admin->givePermissionTo('gestao-usuarios');

        Role::firstOrCreate(['name' => User::ROLE_COMMON]);

        User::all()->each(function (User $user) {
            $user->assignRole($user->role);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::whereIn('name', [
            'gestao-usuarios',
            'gestao-produtos',
            'gestao-categorias',
            'gestao-marcas',
        ])->delete();

        Role::whereIn('name', [User::ROLE_ADMIN, User::ROLE_COMMON])->delete();

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('alias');
        });
    }
};
