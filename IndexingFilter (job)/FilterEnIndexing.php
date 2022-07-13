<?php

namespace App\Http\Controllers\Job;

use App\Models\Filter;
use App\Models\FilterParam;
use App\Models\FilterProduct;
use App\Jobs\IndexingEnFilter;
use App\Repositories\Filter\Filters;

class FilterEnIndexing
{
    public function index()
    {
        IndexingEnFilter::dispatch(
            new Filters(
                Filter::class,
                FilterParam::class,
                FilterProduct::class
            )
        );
    }
}
