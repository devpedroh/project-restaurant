<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;

class OrderController extends Controller
{
    private $orders;

    public function __construct () {
        $this->orders = new Order();
    }

    public function create (Request $req) {
        $data = $req->all();

        if ($this->orders->create($data)) {
            return [
                'message' => 'Pedido finalizado com sucesso!',
                'status' => 200,
            ];
        }

        return [
            'message' => 'Não foi possível inserir seu pedido!',
            'status' => 500,
        ];
    }

    public function get (Request $req) {

        $start = (($req->currentPage - 1) * $req->pageSize > 0 ? (($req->currentPage - 1) * $req->pageSize) : 0);
        $limit = ($req->pageSize ? $req->pageSize : 10);
        $search = $req->search;

        return [
            'status' => true,
            'data' => $this->orders->get($start, $limit, $search),
            'total' => $this->orders->count($search),
        ];
    }

    public function view (Request $req) {

        $order = DB::table('orders')->where('id', $req->id)->first();

        if ($order) {
            return [
                'order' => $order,
                'order_items' => $this->orders->viewOrderItems($req->id),
                'status' => 200
            ];
        }

        return [
            'message' => 'Pedido não encontrado!',
            'status' => 500
        ];

    }

    public function end (Request $req) {

        $order = DB::table('orders')->where([
            ['id', '=', $req->id],
            ['status', '=', '0'],
        ])->first();

        if ($order) {
            if ($this->orders->end($req->id)) {
                return [
                    'message' => 'Pedido finalizado com sucesso!',
                    'status' => 200
                ];
            } else {
                return [
                    'message' => 'Erro ao finalizar pedido!',
                    'status' => 500
                ];
            }
        }

        return [
            'message' => 'Pedido não encontrado!',
            'status' => 500
        ];

    }

}
