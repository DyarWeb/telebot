<?php

namespace WeStacks\TeleBot\Laravel\Artisan;

use GuzzleHttp\Promise\Utils;
use Symfony\Component\Console\Helper\TableCell;
use WeStacks\TeleBot\Objects\BotCommand;

class CommandsCommand extends TeleBotCommand
{
    protected $signature = 'telebot:commands
        {bot? : The bot name defined in the config file.}
        {--A|all : Perform actions on all your bots.}
        {--S|setup : Declare your bot commands on Telegram servers. So the users can use autocompletion.}
        {--R|remove : Remove your already declared bot commands on Telegram servers.}
        {--I|info : Get the information about your current bot commands on Telegram servers.}';

    protected $description = 'Ease the Process of setting up and removing bot commands.';

    public function handle()
    {
        if (true !== ($error = $this->validOptions())) {
            $this->error($error);

            return 1;
        }

        $bots = $this->botsList();

        if ($this->option('setup')) {
            $this->setupCommands($bots);
        }

        if ($this->option('remove')) {
            $this->removeCommands($bots);
        }

        if ($this->option('info')) {
            $this->getCommands($bots);
        }

        return 0;
    }

    private function setupCommands($bots)
    {
        $promises = [];
        foreach ($bots as $bot) {
                $promises[] = $this->bot->bot($bot)
                    ->async()
                    ->exceptions()
                    ->setLocalCommands()
                    ->then(function ($result) use ($bot) {
                        $this->info("Success! Bot commands has been set for '{$bot}' bot!");
                        return $result;
                    })
                    ->otherwise(function ($e) use ($bot) {
                        $this->error("Error while setting bot commands for '{$bot}' bot: {$e->getMessage()}");
                        return $e;
                    });
        }
        Utils::all($promises)->wait();
    }

    private function removeCommands($bots)
    {
        $promises = [];
        foreach ($bots as $bot) {
                $promises[] = $this->bot->bot($bot)
                    ->async()
                    ->exceptions()
                    ->deleteLocalCommands()
                    ->then(function ($result) use ($bot) {
                        $this->info("Success! Bot commands has been deleted for '{$bot}' bot!");
                        return $result;
                    })
                    ->otherwise(function ($e) use ($bot) {
                        $this->error("Error while deleting bot commands for '{$bot}' bot: {$e->getMessage()}");
                        return $e;
                    });
        }
        Utils::all($promises)->wait();
    }

    private function getCommands($bots)
    {
        $this->alert('Bot Commands');

        $promises = [];
        foreach ($bots as $bot) {
            $promises[] = $this->bot->bot($bot)
                ->async()
                ->exceptions()
                ->getMyCommands()
                ->then(function (array $commands) use ($bot) {
                    $this->makeTable($commands, $bot);
                    return $commands;
                });
        }
        Utils::all($promises)->wait();
    }

    private function makeTable(array $commands, string $bot)
    {
        $rows = collect($commands)->map(function (BotCommand $command) {
            $key = '/'.$command->command;
            $value = $command->description;

            return compact('key', 'value');
        })->toArray();

        $this->table([
            [new TableCell('Bot: '.$bot, ['colspan' => 2])],
            ['Command', 'Description'],
        ], $rows);
    }
}
