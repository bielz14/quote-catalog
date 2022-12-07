<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use App\Models\Quote;
use Illuminate\Support\Facades\DB;
use Validator;

class QuoteCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quote:create {title} {description}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create quote';

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
        $input['title'] = $this->argument('title');
        $input['description'] = $this->argument('description');
        $validator = Validator::make($input, [
            'title' => 'required|string|max:250|unique:quotes',
            'description' => 'required|string|max:3000|unique:quotes'
        ]);

        if ($validator->fails()) {
            echo $validator->errors();

            return;
        }

        $quote = resolve(Quote::class);
        $quote->title = $input['title'];
        $quote->description = $input['description'];
        $quote->user_id = User::first()->id;
        try {
            DB::beginTransaction();
            $quote->save();
            DB::commit();

            echo 'Quote created successfully.';

        } catch (\Exception $e) {
            DB::rollBack();

            echo 'Technical error.';
        }
    }
}
