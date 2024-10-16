<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeleteTokenFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:deletefile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'deletefile token';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (Storage::disk('local')->exists('tokens/swapToken.txt')) {
            Storage::disk('local')->delete('tokens/swapToken.txt');  
        // $this->info('sucess');
         }
        // else{
        //      $this->info('faile');
        // }
      return 0;
    
    }
}
