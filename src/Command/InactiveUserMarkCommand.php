<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'user:mark:inactive',
    description: 'Set inactivity property for users',
    aliases: ['user:mark:inactive'],
    hidden: false
)]
class InactiveUserMarkCommand extends Command
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    public function configure()
    {
        $this->setHelp('This command allows you to mark inactive users');
        $this->addArgument('days', InputArgument::OPTIONAL, 'Inactive day count in days', "30");
    }

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager, string $name = null)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $days = $input->getArgument('days');
        if (!ctype_digit($days)) {
            $output->writeln('Allowed only integer values!');
            return Command::INVALID;
        }
        $output->writeln('Marking inactive users...');
        $users = $this->userRepository->getUsersWhichExceedLoginTime(intval($days));

        foreach ($users as $user) {
            $user->setIsActive(false);
        }

        $this->entityManager->flush();

        $output->writeln('Successfully marked inactive users');
        return Command::SUCCESS;
    }
}