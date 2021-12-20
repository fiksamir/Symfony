<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:fibonacci',
    description: 'Show fibonacci digits',
)]
class FibonacciCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = (int)$input->getArgument('arg1');
        $option = $input->getOption('option');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($option) {
            $io->note(sprintf('You passed an option: %s', $option));
        }

//        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        while ($arg1 < PHP_INT_MAX) {
            if ($arg1 === 0) {
                $arg1 += rand(1, 5);
                continue;
            }
            $fib = $this->fibonacci($arg1);
            $io->text('Position in Fibonacci Sequence is: ' . $arg1);
            $io->text('Value is: ' . $fib);
            sleep(rand(5, 10));
            $arg1 += $fib + rand(1, 5);
        }

        return Command::SUCCESS;
    }

    private function fibonacci($num)
    {
        if ($num < 1) {
            return false;
        }
        if ($num <= 2) {
            return ($num - 1);
        }
        $pre_pre = 0;
        $current = 1;
        for ($i = 3; $i <= $num; $i++) {
            $pre = $current;
            $current = $pre + $pre_pre;
            $pre_pre = $pre;
        }
        return $current;
    }
}
