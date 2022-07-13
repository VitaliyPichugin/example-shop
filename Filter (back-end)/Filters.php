<?php

namespace App\Repositories\Filter;

use App\Http\Resources\DataCollection;
use App\Models\Category;
use App\Models\ProductField;
use App\Repositories\Main\Helper;

class Filters extends Helper
{
    private $categoryId = 0;

    const TME = 18;

    const FIELD = 'description_lv';

    public function __construct(
        private $filter,
        private $filterParam,
        private $filterProduct,
    ) {
    }

    public function generateFilters()
    {
        $description_lang = self::FIELD;
        $indexed_product_ids = $this->filterProduct::distinct()->pluck('product_id');
        $not_indexed_product_ids = ProductField::query()
            ->where('vendor_id', self::TME)
            ->where($description_lang, 'like', '%&lt;br&gt;Manufacturer%')
            ->pluck('product_id');
        $data_ids = $not_indexed_product_ids->diff($indexed_product_ids)->chunk(100);
        foreach ($data_ids as $ids) {
            ProductField::query()
                ->whereIn('product_id', $ids)
                ->with('product')
                ->where($description_lang, 'like', '%&lt;br&gt;Manufacturer%')
                ->where('vendor_id', self::TME)
                ->chunk(
                    50,
                    function ($data) use ($description_lang) {
                        $data->each(
                            function ($item) use ($description_lang) {
                                $category_id = $item->product->category_id;
                                $res = $this->getParameters($item, $description_lang);
                                $product_id = $item->product_id;
                                foreach ($res as $key => $param) {
                                    if ($param != '' && $key != '' && strlen($param) < 100 && strlen($key) < 100) {
                                        $filter = $this->filter::query()->firstOrCreate(
                                            [
                                                'title' => strip_tags($key)
                                            ],
                                        );
                                        $filter_params = $this->filterParam::query()->firstOrCreate(
                                            [
                                                'name' => strip_tags($param),
                                                'category_id' => $category_id,
                                            ],
                                            ['filter_id' => $filter->id,]
                                        );
                                        $this->filterProduct::query()->firstOrCreate(
                                            [
                                                'product_id' => $product_id,
                                                'filter_param_id' => $filter_params->id,
                                                'category_id' => $category_id,
                                            ],
                                        );
                                    }
                                }
                            }
                        );
                    }
                );
        }
    }

    public function updateFiltersByParams($request)
    {
        $data = [];
        $params = [];
        $this->categoryId = $request->id;
        $filters = $this->filterParam::query()
            ->where('category_id', $this->categoryId)
            ->with('filter');
        $res = $filters->get();
        $filters_group = null;

        if (isset($request->group_filters)) {
            $filters_group = $this->decodeFilterParams($request->group_filters);
            $data_params = $this->getQtyParams($filters_group);
        } else {
            $data_params = $this->getQtyParams();
        }

        if (is_array($filters_group)) {
            $filters_group_res = array_merge(...$filters_group);
        } else {
            $filters_group_res[] = $filters_group;
        }

        foreach ($res as $val) {
            $cnt = $data_params[$val['id']] ?? 0;
            $params[$val['filter_id']][] = collect(
                [
                    'filter_id' => $val['filter_id'],
                    'name' => $val['name'],
                    'id' => $val['id'],
                    'checked' => in_array($val['id'], $filters_group_res) && $cnt > 0 ?? false,
                    'cnt_prod' => $cnt,
                ]
            );
            $param_collection = collect($params[$val['filter_id']]);
            $data[$val['filter_id']] = collect(
                [
                    'id' => $val['filter_id'],
                    'params' => $param_collection
                        ->sortByDesc('cnt_prod')
                        ->sortByDesc('checked'),
                    'title' => $val['filter']['title'],
                    'cnt_in_case' => $param_collection->sum('cnt_prod'),
                    'checked' => $param_collection->where('checked', true)->count()
                ]
            );
        }
        $data = collect($data)
            ->sortByDesc('cnt_in_case')
            ->sortByDesc('checked');

        $cnt_cases = $data->count();

        $data->map(function ($item) use ($cnt_cases) {
            $item['cases'] = $cnt_cases;
            return $item;
        });

        if (!isset($request->all_filters)) {
            $data = $data->take(12);
        }

        return new DataCollection($data);
    }

    public function getFiltersByCategory($request)
    {
        $data = [];
        $params = [];
        $filters_group = null;
        $this->categoryId = $this->getId($request->id);
        $filters = $this->filterParam::query()
            ->where('category_id', $this->categoryId)
            ->with('filter');

        $res = $filters->get();

        if (isset($request->group_filters)) {
            $filters_group = $this->decodeFilterParams($request->group_filters);
            $data_params = $this->getQtyParams($filters_group);
        } else {
            $data_params = $this->getQtyParams();
        }

        if (is_array($filters_group)) {
            $filters_group_res = array_merge(...$filters_group);
        } else {
            $filters_group_res[] = $filters_group;
        }

        foreach ($res as $val) {
            $cnt = $data_params[$val['id']] ?? 0;
            $params[$val['filter_id']][] = collect(
                [
                    'filter_id' => $val['filter_id'],
                    'name' => $val['name'],
                    'id' => $val['id'],
                    'checked' => in_array($val['id'], $filters_group_res) && $cnt > 0 ?? false,
                    'cnt_prod' => $cnt,
                ]
            );
            $param_collection = collect($params[$val['filter_id']]);
            $data[$val['filter_id']] = collect(
                [
                    'id' => $val['filter_id'],
                    'params' => $param_collection
                        ->sortByDesc('cnt_prod')
                        ->sortByDesc('checked'),
                    'title' => $val['filter']['title'],
                    'cnt_in_case' => $param_collection->sum('cnt_prod'),
                    'checked' => $param_collection->where('checked', true)->count()
                ]
            );
        }
        $data = collect($data)
            ->sortByDesc('cnt_in_case')
            ->sortByDesc('checked');

        $cnt_cases = $data->count();

        $data->map(function ($item) use ($cnt_cases) {
            $item['cases'] = $cnt_cases;
            return $item;
        });

        if (!isset($request->all_filters)) {
            $data = $data->take(12);
        }

        return new DataCollection($data);
    }

    private function decodeFilterParams($str)
    {
        $res = $str;
        if (stristr($str, ',') !== false || stristr($str, '|') !== false) {
            $res = [];
            $data = explode('|', $str);
            foreach ($data as $val) {
                $res[] = explode(',', $val);
            }
        }
        return $res;
    }

    private function builderFilterParams($groups, &$builder)
    {
        $res = $groups;
        $product_ids = [];
        if (is_string($res)) {
            $params = $this->filterProduct::query()
                ->where('filter_param_id', $res)
                ->pluck('product_id')
                ->unique();
            $builder->whereIn('product_id', $params);
            $this->additionalBuilderParams($builder, $res);
        } else {
            foreach ($res as $ids) {
                $product_ids[] = $this->filterProduct::query()
                    ->whereIn('filter_param_id', $ids)
                    ->pluck('product_id')
                    ->toArray();
            }
            $builder->whereIn('product_id', array_unique(array_merge(...array_values($product_ids))));
            if (count($res) === 1) {
                $this->additionalBuilderParams($builder, $res[0]);
            }
        }
    }

    private function additionalBuilderParams(&$builder, $id,)
    {
        $filter_id = $this->filterParam::whereId($id)->pluck('filter_id');
        if ($filter_id != 'undefined' && $filter_id) {
            $builder->orWhereIn(
                'filter_param_id',
                $this->filterParam::query()
                    ->where('filter_id', $filter_id)
                    ->where('category_id', $this->categoryId)
                    ->pluck('id')
            );
        }
    }

    private function getQtyParams($groups = false)
    {
        $builder = $this->filterProduct::query()
            ->selectRaw('filter_param_id, COUNT(*) AS cnt_prod')
            ->where('category_id', $this->categoryId);
        if ($groups) {
            $this->builderFilterParams($groups, $builder);
        }
        $builder->groupBy('filter_param_id');
        return $builder->pluck('cnt_prod', 'filter_param_id');
    }

    public function clearFilters()
    {
        $this->filter::query()->truncate();
        $this->filterParam::query()->truncate();
        $this->filterProduct::query()->truncate();
    }

    private function getParameters($data, $description_lang)
    {
        $data_description = array_slice(explode('<br>', htmlspecialchars_decode($data[$description_lang])), 2);
        $res = [];
        foreach ($data_description as $item) {
            $data_str = explode(':', $item);
            if (count($data_str) === 2) {
                $key = trim($data_str[0]);
                if ($key != 'Manufacturer') {
                    $res[trim($key)] = trim($data_str[1]);
                }
            }
        }
        return $res;
    }

    private function getCategoryIds($id)
    {
        $category = Category::where('category_id', $id)->first();
        return $category->getDescendants($category);
    }

}
