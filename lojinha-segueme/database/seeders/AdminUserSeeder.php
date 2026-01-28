<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verifica se já existe um usuário admin
        $adminExists = User::where('is_admin', true)->exists();

        if (!$adminExists) {
            User::create([
                'name' => 'Administrador',
                'email' => 'admin@lojinha-segueme.com',
                'password' => Hash::make('admin123'),
                'is_approved' => true,
                'is_admin' => true,
                'approved_at' => now(),
                'email_verified_at' => now(),
            ]);

            $this->command->info('✓ Usuário administrador criado com sucesso!');
            $this->command->info('  Email: admin@lojinha-segueme.com');
            $this->command->info('  Senha: admin123');
            $this->command->warn('  ⚠ IMPORTANTE: Altere a senha após o primeiro acesso!');
        } else {
            $this->command->info('Usuário administrador já existe.');
        }
    }
}
