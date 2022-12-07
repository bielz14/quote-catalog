<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Quote;
use Illuminate\Support\Facades\DB;
use Validator;

class QuoteUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quote:update {title} {description} {quote_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update quote';

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

        $quote = Quote::find($quoteId);
        if (!empty($quote)) {
            $input['title'] = $this->argument('title');
            $input['description'] = $this->argument('description');
            $validator = Validator::make($input, [
                'title' => 'required|string|max:250|unique:quotes,title,' . $quote->id,
                'description' => 'required|string|max:3000|unique:quotes,description,' . $quote->id
            ]);

            if ($validator->fails()) {
                echo $validator->errors();

                return;
            }

            $quote->title = $input['title'];
            $quote->description = $input['description'];
            try {
                DB::beginTransaction();
                $quote->save();
                DB::commit();

                echo 'Quote updated successfully.';

            } catch (\Exception $e) {
                DB::rollBack();

                echo 'Technical error.';
            }
        } else {
            echo 'Quote not found.';
        }
    }
}
