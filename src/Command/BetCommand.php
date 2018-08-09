<?php

namespace FFormula\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;

use FFormula\BF\Bet;

class BetCommand extends Command
{
    private $bet;

    public function __construct (Bet $bet)
    {
        parent::__construct();
        $this->bet = $bet;
    }

    public function configure()
    {
        $this -> setName("bet")
              -> setDescription("Some Bet Command")
              -> addArgument("type", InputArgument::REQUIRED, "Type of sport");
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $logger = new ConsoleLogger($output);
        $type = $input -> getArgument("type");

        $this -> bet -> setLogger ($logger);
        $res = $this -> bet -> getAllEventTypes($type);

        $output -> writeln ("Type: $res");
    }

}