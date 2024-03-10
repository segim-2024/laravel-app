<?php

namespace App\Console\Commands;

use App\Models\Cart;
use App\Models\Item;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SegimItemPriceChangeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:segim-item-price-change-command';

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
            ['it_id' => 'sg00000090', 'it_price' => 5000],
            ['it_id' => 'sg00000091', 'it_price' => 5000],
            ['it_id' => 'sg00000092', 'it_price' => 5000],
            ['it_id' => 'sg00000093', 'it_price' => 5000],
            ['it_id' => 'sg00000094', 'it_price' => 5000],
            ['it_id' => 'sg00000095', 'it_price' => 5000],
            ['it_id' => 'sg00000096', 'it_price' => 5000],
            ['it_id' => 'sg00000097', 'it_price' => 5000],
            ['it_id' => 'sg00000098', 'it_price' => 5000],
            ['it_id' => 'sg00000099', 'it_price' => 5000],
            ['it_id' => 'sg00000100', 'it_price' => 5000],
            ['it_id' => 'sg00000101', 'it_price' => 5000],
            ['it_id' => 'sg00000102', 'it_price' => 5000],
            ['it_id' => 'sg00000103', 'it_price' => 5000],
            ['it_id' => 'sg00000104', 'it_price' => 5000],
            ['it_id' => 'sg00000105', 'it_price' => 5000],
            ['it_id' => 'sg00000106', 'it_price' => 5000],
            ['it_id' => 'sg00000107', 'it_price' => 5000],
            ['it_id' => 'sg00000108', 'it_price' => 5000],
            ['it_id' => 'sg00000109', 'it_price' => 5000],
            ['it_id' => 'sg00000110', 'it_price' => 5000],
            ['it_id' => 'sg00000111', 'it_price' => 5000],
            ['it_id' => 'sg00000112', 'it_price' => 5000],
            ['it_id' => 'sg00000113', 'it_price' => 5000],
            ['it_id' => 'sg00000114', 'it_price' => 5000],
            ['it_id' => 'sg00000115', 'it_price' => 5000],
            ['it_id' => 'sg00000116', 'it_price' => 5000],
            ['it_id' => 'sg00000117', 'it_price' => 5000],
            ['it_id' => 'sg00000082', 'it_price' => 5000],
            ['it_id' => 'sg00000083', 'it_price' => 5000],
            ['it_id' => 'sg00000084', 'it_price' => 5000],
            ['it_id' => 'sg00000085', 'it_price' => 5000],
            ['it_id' => 'sg00000086', 'it_price' => 5000],
            ['it_id' => 'sg00000087', 'it_price' => 5000],
            ['it_id' => 'sg00000088', 'it_price' => 5000],
            ['it_id' => 'sg00000089', 'it_price' => 5000],
            ['it_id' => 'sg00000118', 'it_price' => 25000],
            ['it_id' => 'sg00000119', 'it_price' => 25000],
            ['it_id' => 'sg00000120', 'it_price' => 25000],
            ['it_id' => 'sg00000121', 'it_price' => 25000],
            ['it_id' => 'sg00000122', 'it_price' => 25000],
            ['it_id' => 'sg00000123', 'it_price' => 25000],
            ['it_id' => 'sg00000124', 'it_price' => 26000],
            ['it_id' => 'sg00000125', 'it_price' => 26000],
            ['it_id' => 'sg00000126', 'it_price' => 26000],
            ['it_id' => 'sg00000127', 'it_price' => 26000],
            ['it_id' => 'sg00000128', 'it_price' => 26000],
            ['it_id' => 'sg00000129', 'it_price' => 26000],
            ['it_id' => 'sg00000130', 'it_price' => 26000],
            ['it_id' => 'sg00000131', 'it_price' => 26000],
            ['it_id' => 'sg00000132', 'it_price' => 26000],
            ['it_id' => 'sg00000133', 'it_price' => 26000],
            ['it_id' => 'sg00000134', 'it_price' => 26000],
            ['it_id' => 'sg00000135', 'it_price' => 26000],
            ['it_id' => 'sg00000136', 'it_price' => 26000],
            ['it_id' => 'sg00000137', 'it_price' => 26000],
            ['it_id' => 'sg00000138', 'it_price' => 26000],
            ['it_id' => 'sg00000139', 'it_price' => 26000],
            ['it_id' => 'sg00000140', 'it_price' => 26000],
            ['it_id' => 'sg00000141', 'it_price' => 26000],
            ['it_id' => 'sg00000142', 'it_price' => 26000],
            ['it_id' => 'sg00000143', 'it_price' => 26000],
            ['it_id' => 'sg00000144', 'it_price' => 26000],
            ['it_id' => 'sg00000145', 'it_price' => 26000],
            ['it_id' => 'sg00000146', 'it_price' => 26000],
            ['it_id' => 'sg00000147', 'it_price' => 26000],
            ['it_id' => '1581491281', 'it_price' => 29000],
            ['it_id' => '1581492079', 'it_price' => 31000],
            ['it_id' => '1581492193', 'it_price' => 35000],
            ['it_id' => '1581492338', 'it_price' => 29000],
            ['it_id' => '1581492597', 'it_price' => 31000],
            ['it_id' => '1581492666', 'it_price' => 35000],
            ['it_id' => '1581492922', 'it_price' => 29000],
            ['it_id' => '1581493028', 'it_price' => 31000],
            ['it_id' => '1581493093', 'it_price' => 35000],
            ['it_id' => 'sg00000064', 'it_price' => 26000],
            ['it_id' => 'sg00000065', 'it_price' => 26000],
            ['it_id' => 'sg00000066', 'it_price' => 26000],
            ['it_id' => 'sg00000067', 'it_price' => 26000],
            ['it_id' => 'sg00000068', 'it_price' => 26000],
            ['it_id' => 'sg00000069', 'it_price' => 26000],
            ['it_id' => 'sg00000070', 'it_price' => 26000],
            ['it_id' => 'sg00000071', 'it_price' => 26000],
            ['it_id' => 'sg00000072', 'it_price' => 26000],
            ['it_id' => 'sg00000073', 'it_price' => 26000],
            ['it_id' => 'sg00000074', 'it_price' => 26000],
            ['it_id' => 'sg00000075', 'it_price' => 26000],
            ['it_id' => '1563864343', 'it_price' => 31000],
            ['it_id' => '1563864824', 'it_price' => 31000],
            ['it_id' => '1563865102', 'it_price' => 31000],
            ['it_id' => '1560410872', 'it_price' => 31000],
            ['it_id' => '1563861677', 'it_price' => 31000],
            ['it_id' => '1560417768', 'it_price' => 31000],
            ['it_id' => '1560424455', 'it_price' => 31000],
            ['it_id' => '1560424638', 'it_price' => 31000],
            ['it_id' => '1560424922', 'it_price' => 31000],
            ['it_id' => '1560425172', 'it_price' => 31000],
            ['it_id' => '1563865276', 'it_price' => 31000],
            ['it_id' => '1563865497', 'it_price' => 31000],
            ['it_id' => '1566376277', 'it_price' => 31000],
            ['it_id' => '1566376610', 'it_price' => 31000],
            ['it_id' => '1566376709', 'it_price' => 31000],
            ['it_id' => '1566376765', 'it_price' => 31000],
            ['it_id' => '1566376814', 'it_price' => 31000],
            ['it_id' => '1566376861', 'it_price' => 31000],
            ['it_id' => '1619405591', 'it_price' => 33000],
            ['it_id' => '1618195405', 'it_price' => 33000],
            ['it_id' => '1619405462', 'it_price' => 29000],
            ['it_id' => '1619405533', 'it_price' => 29000],
            ['it_id' => '1610509852', 'it_price' => 29000],
            ['it_id' => '1610427480', 'it_price' => 29000],
            ['it_id' => '1610427690', 'it_price' => 29000],
            ['it_id' => '1610430446', 'it_price' => 29000],
            ['it_id' => '1610509976', 'it_price' => 29000],
            ['it_id' => '1610510027', 'it_price' => 29000],
            ['it_id' => '1610510074', 'it_price' => 33000],
            ['it_id' => '1610510312', 'it_price' => 33000],
            ['it_id' => '1610510365', 'it_price' => 33000],
            ['it_id' => '1610591888', 'it_price' => 33000],
            ['it_id' => '1610592016', 'it_price' => 33000],
            ['it_id' => '1610592159', 'it_price' => 33000],
            ['it_id' => '1610592231', 'it_price' => 39000],
            ['it_id' => '1610592458', 'it_price' => 39000],
            ['it_id' => '1610592524', 'it_price' => 39000],
            ['it_id' => '1616377390', 'it_price' => 29000],
            ['it_id' => '1616377479', 'it_price' => 29000],
            ['it_id' => '1616377533', 'it_price' => 33000],
            ['it_id' => '1621471215', 'it_price' => 39000],
            ['it_id' => '1624511655', 'it_price' => 33000],
            ['it_id' => '1637214998', 'it_price' => 29000],
            ['it_id' => '1625734225', 'it_price' => 33000],
            ['it_id' => '1632876076', 'it_price' => 33000],
            ['it_id' => '1635129240', 'it_price' => 33000],
            ['it_id' => '1635129380', 'it_price' => 33000],
            ['it_id' => '1637808638', 'it_price' => 39000],
            ['it_id' => '1640074592', 'it_price' => 33000],
            ['it_id' => '1670809292', 'it_price' => 33000],
            ['it_id' => '1640756642', 'it_price' => 12000],
            ['it_id' => '1640756965', 'it_price' => 12000],
            ['it_id' => '1640757247', 'it_price' => 12000],
            ['it_id' => '1640757394', 'it_price' => 12000],
            ['it_id' => '1639379433', 'it_price' => 33000],
            ['it_id' => '1640757461', 'it_price' => 12000],
            ['it_id' => '1648174119', 'it_price' => 33000],
            ['it_id' => '1623995939', 'it_price' => 29000],
            ['it_id' => '1623995846', 'it_price' => 29000],
            ['it_id' => '1629523346', 'it_price' => 29000],
            ['it_id' => '1629528153', 'it_price' => 29000],
            ['it_id' => '1629528358', 'it_price' => 29000],
            ['it_id' => '1629528495', 'it_price' => 29000],
            ['it_id' => '1617607178', 'it_price' => 29000],
            ['it_id' => '1620629179', 'it_price' => 29000],
            ['it_id' => '1610592583', 'it_price' => 29000],
            ['it_id' => '1610592773', 'it_price' => 29000],
            ['it_id' => '1613976168', 'it_price' => 29000],
            ['it_id' => '1623029457', 'it_price' => 29000],
            ['it_id' => '1645423226', 'it_price' => 33000],
            ['it_id' => '1645423158', 'it_price' => 33000],
            ['it_id' => '1651210858', 'it_price' => 33000],
            ['it_id' => '1654149496', 'it_price' => 33000],
            ['it_id' => '1657861384', 'it_price' => 33000],
            ['it_id' => '1663305426', 'it_price' => 33000],
            ['it_id' => '1646190090', 'it_price' => 42000],
            ['it_id' => '1646184767', 'it_price' => 42000],
            ['it_id' => '1656653382', 'it_price' => 42000],
            ['it_id' => '1669095908', 'it_price' => 42000],
        ];

        try {
            foreach ($items as $item) {
                $targetItem = Item::where('it_id', '=', $item['it_id'])->first();
                if (! $targetItem) {
                    dd($item);
                }
            }

            $this->info('check finished');

            foreach ($items as $item) {
                Item::where('it_id', '=', $item['it_id'])->update([
                    'it_price' => $item['it_price'],
                    'it_cust_price' => $item['it_price'],
                ]);

                Cart::where('it_id', '=', $item['it_id'])
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
