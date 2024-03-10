<?php

namespace App\Console\Commands;

use App\Models\Item;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SegimItemSoldoutChangeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:segim-item-soldout-change-command';

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
        try {
            $ids = ['sg00000001','sg00000002','sg00000003','sg00000004','sg00000005','sg00000006','sg00000007','sg00000008','sg00000009','1535361671','1535361886','1535361910','1535361943','1535361968','1535362024','sg00000076','sg00000077','sg00000078','sg00000079','sg00000080','sg00000081'];
            Item::whereIn('it_id', $ids)
                ->update([
                    'it_soldout' => 1
                ]);

        } catch (\Exception $exception) {
            Log::error($exception);
            $this->error('failed');
            return COMMAND::FAILURE;
        }

        $this->info('changed complete');
        return COMMAND::SUCCESS;
    }
}
