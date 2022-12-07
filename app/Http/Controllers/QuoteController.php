<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Quote;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Validator;

class QuoteController extends Controller
{
    const PAGINATION_PAGE_COUNT = 2;

    /**
     * @return View
     */
    public function index()
    {
        $quotes = Quote::paginate(self::PAGINATION_PAGE_COUNT);

        return view('quotes.list')->with('quotes', $quotes);
    }

    /**
     * @return View
     */
    public function create()
    {
        return view('forms.quote');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:250',
            'description' => 'required|string|max:3000'
        ]);

        $attributes = $request->all();
        $quote = resolve(Quote::class);
        $quote->title = $attributes['title'];
        $quote->description = $attributes['description'];
        $quote->user_id = User::first()->id;
        try {
            DB::beginTransaction();
            $quote->save();
            DB::commit();
            Session::put('success', 'Quote created successfully.');

            $lastPaginationPage = (int)ceil(Quote::count() / self::PAGINATION_PAGE_COUNT);
            return redirect(route('quote.list', ['page' => $lastPaginationPage]));

        } catch (\Exception $e) {
            DB::rollBack();

            Session::put('error', 'Technical error.');
        }

        return redirect(route('list'));
    }

    /**
     * @param Quote $quote
     * @return View
     */
    public function edit(Quote $quote)
    {
        return view('forms.quote')->with('quote', $quote);
    }

    /**
     * @param Request $request
     * @param Quote $quote
     * @return View
     */
    public function update(Request $request, Quote $quote)
    {
        if (!empty($quote)) {
            $this->validate($request, [
                'title' => 'required|string|max:250|unique:quotes,title,' . $quote->id,
                'description' => 'required|string|max:3000|unique:quotes,description,' . $quote->id
            ]);

            $attributes = $request->all();
            $quote->title = $attributes['title'];
            $quote->description = $attributes['description'];
            try {
                DB::beginTransaction();
                $quote->save();
                DB::commit();
                Session::put('success', 'Quote updated successfully.');

                return redirect(route('quote.list', ['page' => $request->get('page')]));

            } catch (\Exception $e) {
                DB::rollBack();

                Session::put('error', 'Technical error.');
            }
        } else {
            Session::put('error', 'Quote not found.');
        }

        return redirect(route('quote.list'));
    }

    public function destroy(Request $request, Quote $quote)
    {
        if (!empty($quote)) {
            try {
                DB::beginTransaction();
                $quote->delete();
                DB::commit();

                Session::put('success', 'Quote deleted successfully.');
            } catch (\Exception $e) {
                DB::rollBack();

                Session::put('error', 'Technical error.');
            }
        } else {
            Session::put('error', 'Quote not found.');
        }

        $routeParams = [];

        if (!empty($request->input('page'))) {
            $lastPaginationPage = (int)ceil(Quote::count() / self::PAGINATION_PAGE_COUNT);
            if ($request->input('page') == $lastPaginationPage + 1) {
                $page = $lastPaginationPage;
            } else {
                $page = $request->input('page');
            }

            $routeParams['page'] = $page;
        }

        return redirect(route('quote.list', $routeParams));
    }
}
