<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Word;

class ImportWords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-words';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = storage_path('app\public\words_dictionary.json');

        if (!file_exists($filePath)) {
            $this->error('O arquivo words_dictionary.json não foi encontrado.');
            return;
        }

        $json = file_get_contents($filePath);
        $words = json_decode($json, true);

        foreach ($words as $word => $meaning) {
            Word::updateOrCreate(['word' => $word]);
        }

        $this->info('Words importadas com sucesso!');
    }
}
