<?php

namespace App\Command;

use App\OmdbApi;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImdbCommand extends Command
{
    private $omdbApi;

    public function __construct(OmdbApi $omdbApi)
    {
        parent::__construct('app:imdb');

        $this->omdbApi = $omdbApi;
    }

    protected function configure(): void
    {
        $this
            ->setName('app:imdb')
            ->setDescription('My description')
            ->addArgument('keyword', InputArgument::OPTIONAL, 'Provide the keyword you are looking for.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $keyword = $input->getArgument('keyword');

        //$answer = $io->choice('Which kind of media would you display?', ['game', 'movie', 'serie']);
        //dump($answer);

        if (!$keyword) {
            $keyword = $io->ask('Please provide the keyword for IMDB search?', 'Sky', function($answer) {
                $answer = strtolower($answer);
                $blackListWords = ['shit', 'fuck'];

                if (in_array($answer, $blackListWords)) {
                    throw new \InvalidArgumentException('the keyword '.$answer.' is not allowed, please try again.');
                }

                return $answer;
            });
        }

        $io->title('You are looking for movies containing "'.$keyword.'"');

        $movies = $this->omdbApi->requestAllBySearch($keyword);
        $io->section(sprintf('%d movies found for the keyword "%s"', $movies['totalResults'], $keyword));
        // https://www.imdb.com/title/tt1411250/

        $rows = [];
        foreach ($movies['Search'] as $movie) {
            $rows[] = [$movie['Title'], $movie['Year'], 'https://www.imdb.com/title/'.$movie['imdbID'].'/', $movie['Type']];
        }

        //$io->horizontalTable(['TITLE', 'RELEASED YEAR', 'MOVIE URL', 'TYPE'], $rows);
        $io->table(['TITLE', 'RELEASED YEAR', 'MOVIE URL', 'TYPE'], $rows);
        //dump($movies);

        return Command::SUCCESS;
    }
}
