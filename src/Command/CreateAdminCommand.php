<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Creates an admin user with simple credentials',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Check if admin already exists
        $existingAdmin = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['email' => 'admin@admin.com']);

        if ($existingAdmin) {
            $io->warning('Admin user already exists!');
            $io->info('Email: admin@admin.com');
            $io->info('Password: admin');
            return Command::SUCCESS;
        }

        // Create new admin user
        $user = new User();
        $user->setEmail('admin@admin.com');
        $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        // Hash the password
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'admin');
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success('Admin user created successfully!');
        $io->table(
            ['Field', 'Value'],
            [
                ['Email', 'admin@admin.com'],
                ['Password', 'admin'],
                ['Roles', 'ROLE_ADMIN, ROLE_USER'],
            ]
        );

        $io->note('You can now login at: /login');

        return Command::SUCCESS;
    }
}
