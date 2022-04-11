<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class Product extends Model
{

    public function get($start, $limit, $search = null)
    {
        if ($search === '' || $search === null) {
            $data = DB::table('products')->orderBy('created_at', 'desc')->limit($limit)->offset($start)->get();
        } else {
            $data = DB::table('products')->where([
                ['id', '=', $search],
            ])->orWhere('name', 'like', '%' . $search . '%')->orderBy('created_at', 'desc')->limit($limit)->offset($start)->get();
        }

        return $data;
    }

    public function count($search = null)
    {
        if (!$search) {
            $data = DB::table('products')->count();
        } else {
            $data = DB::table('products')->where([
                ['id', '=', $search],
            ])->orWhere('name', 'like', '%' . $search . '%')->count();
        }

        return $data;
    }

    public function getTop () {

        $data = DB::table('products')->where([
            ['buys', '>', '0'],
        ])->orderBy('buys', 'desc')->limit(4)->get();

        return $data;
    }

}
