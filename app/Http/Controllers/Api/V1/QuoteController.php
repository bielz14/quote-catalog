<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\BaseController as BaseController;
use App\Models\Quote;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\QuoteResource;
use Validator;
use Illuminate\Support\Facades\Auth;

class QuoteController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $quotes = Quote::all();

        return $this->sendResponse(QuoteResource::collection($quotes), 'Quotes retrieved successfully.');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $attributes = $request->all();

        $validator = Validator::make($attributes, [
            'title' => 'required|string|max:250|unique:quotes',
            'description' => 'required|string|max:3000|unique:quotes'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $quote = resolve(Quote::class);
        $quote->title = $attributes['title'];
        $quote->description = $attributes['description'];
        $quote->user_id = Auth::id();
        try {
            DB::beginTransaction();
            $quote->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->sendError('Creating Error.', $e->getMessage());
        }

        return $this->sendResponse(new QuoteResource($quote), 'Quote created successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Quote $quote
     * @return Response
     */
    public function update(Request $request, Quote $quote)
    {
        if (!empty($quote)) {
            $attributes = $request->all();

            $validator = Validator::make($attributes, [
                'title' => 'required|string|max:250|unique:quotes,title,' . $quote->id,
                'description' => 'required|string|max:3000|unique:quotes,description,' . $quote->id
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());
            }

            $quote->title = $attributes['title'];
            $quote->description = $attributes['description'];
            try {
                DB::beginTransaction();
                $quote->save();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();

                return $this->sendError('Creating Error.', $e->getMessage());
            }
        }

        return $this->sendResponse(new QuoteResource($quote), 'Quote updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Quote $quote
     * @return Response
     */
    public function destroy(Quote $quote)
    {
        if (!empty($quote)) {
            try {
                DB::beginTransaction();
                $quote->delete();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();

                return $this->sendError('Deleting Error.', $e->getMessage());
            }

            DB::commit();
        }

        return $this->sendResponse([], 'Quote deleted successfully.');
    }
}
