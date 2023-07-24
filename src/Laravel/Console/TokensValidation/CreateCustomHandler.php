<?php

namespace HichemtabTech\TokensValidation\Laravel\Console\TokensValidation;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CreateCustomHandler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tokens-validation:handler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a TokensValidation handler to override the defaults';

    private const lists = [
        "Authentication" => [
            "AuthTokenCookiesHandler",
            "AuthTokenGenerator"
        ],
        "Confirmation" => [
            "ConfirmationCodeGenerator",
            "ConfirmationUrlBuilder",
            "UserIdEncrypter"
        ],
        "Invitation" => [
            "InvitationTokenGenerator",
            "InvitationUrlBuilder"
        ]
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $keys = array_keys(self::lists);
        $keys = array_map(function($key) {
            return strlen($key);
        }, $keys);
        $key = max($keys);
        $n = $key + 1;

        $options = [];

        // Build the option array without titles
        foreach (self::lists as $group => $items) {
            foreach ($items as $item) {
                $s = $group;
                $s .= str_repeat(' ', $n - strlen($group));
                $s .= ' -  ' . $item;
                $options[] = $s;
            }
        }

        // Ask the user to choose an option
        $choice = $this->choice('Choose what class you want to override:', $options);
        $parts = explode('-', $choice);
        $head = trim($parts[0]);
        $choice = trim($parts[1]);

        $name = $this->ask('name of the class ?', $choice);
        $stub = File::get(__DIR__."./stubs/$head/$choice.stub");
        $stub = str_replace("//TO*DO", "//TODO", $stub);
        $stub = str_replace("class $choice extends", "class $name extends", $stub);


        /** @noinspection PhpUndefinedFunctionInspection */
        $path = app_path('Actions/TokensValidation/' . $head . '/' . $name . '.php');
        File::ensureDirectoryExists(dirname($path));
        File::put($path, $stub);

        $this->line("Now, follow these instructions:");
        $this->line("1. Go to the config file named tokensvalidation.php");
        $this->line("2. change the value of the key '".($head == 'Authentication' ? 'AuthTokens' : $head.'Token').".$choice' to 'App\Actions\TokensValidation\\$head\\$name::class'");
        $this->line("3. Clear config cache.");
        return CommandAlias::SUCCESS;
    }
}