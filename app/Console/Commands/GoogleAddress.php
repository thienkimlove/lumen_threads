<?php

namespace App\Console\Commands;

use App\Engine\TestThread;
use Illuminate\Console\Command;

class GoogleAddress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:address';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
          $districts = app('db')->table('address_codes as t1')
              ->join('address_codes as t2', 't1.parent_id', '=', 't2.id')
            ->selectRaw('t1.address_name as district_name, t2.address_name as province_name, t1.id as district_id, "address_codes" as db_name')
            ->whereNotNull('t1.parent_id')
            ->whereNull('t2.parent_id')
            ->get();

          $stacks = [];

        foreach ($districts as $district) {
            $stacks[] = new TestThread($district);
        }

        foreach ($stacks as $t) {
            $t->start(PTHREADS_INHERIT_NONE);
        }

    }
}
