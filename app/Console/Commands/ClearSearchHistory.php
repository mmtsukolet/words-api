<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\PreviousSearches;

class ClearSearchHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:searches';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all history searches';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Query all
        PreviousSearches::query()->update(['is_display' => 0]);
        $this->info('DONE and Successful.');
    }
}
