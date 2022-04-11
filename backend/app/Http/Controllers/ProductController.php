<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;

class ProductController extends Controller
{
    private $products;

    public function __construct () {
        $this->products = new Product();
    }

    public function get (Request $req) {
        $data = $req->all();

        $start = (($req->currentPage - 1) * $req->pageSize > 0 ? (($req->currentPage - 1) * $req->pageSize) : 0);
        $limit = ($req->pageSize ? $req->pageSize : 10);
        $search = $req->search;

        return [
            'status' => true,
            'data' => $this->products->get($start, $limit, $search),
            'total' => $this->products->count($search),
        ];
    }

    public function getTop () {
        return [
            'status' => true,
            'data' => $this->products->getTop(),
        ];
    }

}
