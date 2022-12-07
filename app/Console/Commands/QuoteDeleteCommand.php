<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Quote;
use Illuminate\Support\Facades\DB;
use Validator;

class QuoteDeleteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quote:delete {quote_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete quote';

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
        $quoteId = $this->argument('quote_id');
        $validator = Validator::make(['quote_id' => $quoteId], [
            'quote_id' => 'required|int'
        ]);

        if ($validator->fails()) {
            echo $validator->errors();

            return;
        }

        try {
            $quote = Quote::find($quoteId);
            if (!empty($quote)) {
                DB::beginTransaction();
                $quote->delete();
                DB::commit();

                echo 'Quote deleted successfully.';
            } else {
                DB::rollBack();

                echo 'Technical error.';
            }
        } catch (\Exception $e) {
            DB::rollBack();

            echo 'Technical error.';
        }
    }
}
