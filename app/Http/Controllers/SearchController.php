<?php

namespace App\Http\Controllers;

use App\Repositories\SearchRepository;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(
        private SearchRepository $searchRepo,
    ) {}

    public function index(Request $request)
    {
        $q = $request->input('q');

        if (! $q || trim($q) === '') {
            return view('search.results', [
                'query' => $q,
                'campaigns' => collect(),
                'blogs' => collect(),
                'events' => collect(),
                'total' => 0,
            ]);
        }

        $q = trim($q);

        $results = $this->searchRepo->globalSearch($q);
        $total = $results['campaigns']->count() + $results['blogs']->count() + $results['events']->count();

        return view('search.results', [
            'q' => $q,
            'campaigns' => $results['campaigns'],
            'blogs' => $results['blogs'],
            'events' => $results['events'],
            'total' => $total,
        ]);
    }
}
