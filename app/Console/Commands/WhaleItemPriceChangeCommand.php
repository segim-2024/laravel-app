<?php

namespace App\Console\Commands;

use App\Models\WhaleCart;
use App\Models\WhaleItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class WhaleItemPriceChangeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:whale-item-price-change-command';

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
        $items = [
            ['it_id' => '1575002624', 'it_price' => 30000],
            ['it_id' => '1575011807', 'it_price' => 30000],
            ['it_id' => '1575011662', 'it_price' => 30000],
            ['it_id' => '1575011848', 'it_price' => 30000],
            ['it_id' => '1575011933', 'it_price' => 30000],
            ['it_id' => '1575011966', 'it_price' => 30000],
            ['it_id' => '1626309852', 'it_price' => 30000],
            ['it_id' => '1626309935', 'it_price' => 30000],
            ['it_id' => '1626309999', 'it_price' => 30000],
            ['it_id' => '1626310061', 'it_price' => 30000],
            ['it_id' => '1626310091', 'it_price' => 30000],
            ['it_id' => '1626310143', 'it_price' => 30000],
            ['it_id' => '1575012253', 'it_price' => 30000],
            ['it_id' => '1575012903', 'it_price' => 30000],
            ['it_id' => '1575012956', 'it_price' => 30000],
            ['it_id' => '1575012992', 'it_price' => 30000],
            ['it_id' => '1575013032', 'it_price' => 30000],
            ['it_id' => '1575013081', 'it_price' => 30000],
            ['it_id' => '1575086857', 'it_price' => 30000],
            ['it_id' => '1575086907', 'it_price' => 30000],
            ['it_id' => '1575086952', 'it_price' => 30000],
            ['it_id' => '1575086992', 'it_price' => 30000],
            ['it_id' => '1575087031', 'it_price' => 30000],
            ['it_id' => '1575087072', 'it_price' => 30000],
            ['it_id' => '1575087308', 'it_price' => 30000],
            ['it_id' => '1575087398', 'it_price' => 30000],
            ['it_id' => '1575087439', 'it_price' => 30000],
            ['it_id' => '1575087500', 'it_price' => 30000],
            ['it_id' => '1575087569', 'it_price' => 30000],
            ['it_id' => '1575088083', 'it_price' => 30000],
            ['it_id' => '1575088145', 'it_price' => 30000],
            ['it_id' => '1575088187', 'it_price' => 30000],
            ['it_id' => '1575088218', 'it_price' => 30000],
            ['it_id' => '1575088249', 'it_price' => 30000],
            ['it_id' => '1575088277', 'it_price' => 30000],
            ['it_id' => '1575088307', 'it_price' => 30000],
            ['it_id' => '1575088357', 'it_price' => 30000],
            ['it_id' => '1575088390', 'it_price' => 30000],
            ['it_id' => '1575088418', 'it_price' => 30000],
            ['it_id' => '1575088453', 'it_price' => 30000],
            ['it_id' => '1575088533', 'it_price' => 30000],
            ['it_id' => '1575088562', 'it_price' => 30000],
            ['it_id' => '1626310171', 'it_price' => 30000],
            ['it_id' => '1626310299', 'it_price' => 30000],
            ['it_id' => '1626310335', 'it_price' => 30000],
            ['it_id' => '1626310361', 'it_price' => 30000],
            ['it_id' => '1626310554', 'it_price' => 30000],
            ['it_id' => '1626310585', 'it_price' => 30000],
            ['it_id' => '1626310614', 'it_price' => 30000],
            ['it_id' => '1626310653', 'it_price' => 30000],
            ['it_id' => '1626310710', 'it_price' => 30000],
            ['it_id' => '1626310738', 'it_price' => 30000],
            ['it_id' => '1626310781', 'it_price' => 30000],
            ['it_id' => '1626310811', 'it_price' => 30000],
            ['it_id' => '1626310850', 'it_price' => 30000],
            ['it_id' => '1626310891', 'it_price' => 30000],
            ['it_id' => '1626310923', 'it_price' => 30000],
            ['it_id' => '1626310961', 'it_price' => 30000],
            ['it_id' => '1626311000', 'it_price' => 30000],
            ['it_id' => '1626311029', 'it_price' => 30000],
            ['it_id' => '1626311093', 'it_price' => 30000],
            ['it_id' => '1626311127', 'it_price' => 30000],
            ['it_id' => '1626311158', 'it_price' => 30000],
            ['it_id' => '1626311191', 'it_price' => 30000],
            ['it_id' => '1626311325', 'it_price' => 30000],
            ['it_id' => '1626311367', 'it_price' => 30000],
            ['it_id' => '1626311406', 'it_price' => 30000],
            ['it_id' => '1626311476', 'it_price' => 30000],
            ['it_id' => '1626312005', 'it_price' => 30000],
            ['it_id' => '1626312040', 'it_price' => 30000],
            ['it_id' => '1626312095', 'it_price' => 30000],
            ['it_id' => '1626312126', 'it_price' => 30000],
            ['it_id' => '1646618311', 'it_price' => 42000],
            ['it_id' => '1646877802', 'it_price' => 42000],
            ['it_id' => '1663120223', 'it_price' => 42000],
            ['it_id' => '1675400822', 'it_price' => 42000],
        ];

        try {
            foreach ($items as $item) {
                $item = WhaleItem::where('it_id', '=', $item['it_id'])->first();
                if (! $item) {
                    dd($item);
                }
            }

            $this->info('check finished');

            foreach ($items as $item) {
                WhaleItem::where('it_id', '=', $item['it_id'])->update([
                    'it_price' => $item['it_price'],
                    'it_cust_price' => $item['it_price'],
                ]);

                WhaleCart::where('it_id', '=', $item['it_id'])
                    ->where('ct_status', '=', '쇼핑')
                    ->update(['ct_price' => $item['it_price']]);
            }

        } catch (\Exception $exception) {
            Log::error($exception);
            $this->error('failed');
            return COMMAND::FAILURE;
        }

        $this->info('changed complete');
        return COMMAND::SUCCESS;
    }
}
