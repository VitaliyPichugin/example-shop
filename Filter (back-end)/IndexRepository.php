<?php

namespace App\Repositories\Filter;

use App\Models\Filter;
use App\Models\FilterParam;
use App\Models\FilterProduct;

class IndexRepository
{
    /**
     * @var Filters
     */
    public $filter;

    public function __construct()
    {
        $this->filter = new Filters(Filter::class, FilterParam::class, FilterProduct::class);
    }
}
