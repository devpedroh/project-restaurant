<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class Order extends Model
{

    public function create ($data) {

        $date = date('Y-m-d H:i:s');
        $createOrder = DB::table('orders')->insert([
            'name' => $data['name'],
            'payment_method' => $data['payment_method'],
            'payment_value' => $data['payment_value'],
            'obs' => $data['obs'],
            'created_at' => $date
        ]);

        if ($createOrder) {
            $order = DB::table('orders')->where([
                ['name', '=', $data['name']],
                ['status' , '=', 0],
                ['created_at', '=', $date]
            ])->first();

            if ($order) {
                for($i = 0; $i < COUNT($data['products']); $i++) {
                    $update = DB::table('products')->where('id', $data['products'][$i]['id'])->update([
                        'buys' => $this->getBuys( $data['products'][$i]['id']) + $data['products'][$i]['qtd']
                    ]);

                    $insert = DB::table('orders_items')->insert([
                        'order_id' => $order->id,
                        'product_id' => $data['products'][$i]['id'],
                        'qtd' => $data['products'][$i]['qtd'],
                        'vaun' => $data['products'][$i]['vaun']
                    ]);
                }
                return true;
            }

            return false;
        }

        return false;
    }

    private function getBuys ($id) {
        $data = DB::table('products')->where('id', $id)->first()->buys;

        return $data;
    }

    public function get($start, $limit, $search = null)
    {
        if ($search === '' || $search === null) {
            $data = DB::table('orders')->orderBy('created_at', 'desc')->limit($limit)->offset($start)->get();
        } else {
            $data = DB::table('orders')->where([
                ['status', '=', '0'],
            ])->where(function ($query) use ( $search) {
                $query->where(['id', '=', $search])->orWhere('name', 'like', '%' . $search . '%');
            })->orderBy('created_at', 'desc')->limit($limit)->offset($start)->get();
        }

        return $data;
    }

    public function count($search = null)
    {
        if (!$search) {
            $data = DB::table('orders')->count();
        } else {
            $data = DB::table('orders')->where([
                ['status', '=', '0'],
            ])->where(function ($query) use ( $search) {
                $query->where(['id', '=', $search])->orWhere('name', 'like', '%' . $search . '%');
            })->count();
        }

        return $data;
    }

    public function viewOrderItems ($id) {

        $data = DB::table('orders_items')->select('products.id', 'products.name', 'orders_items.qtd')
            ->join('products', 'products.id', '=', 'orders_items.product_id')->where('order_id', $id)->get();

        return $data;
    }

    public function end ($id) {

        $update = DB::table('orders')->where('id', $id)->update([
           'status' => 1,
           'updated_at' => date('Y-m-d H:i:s')
        ]);

        return $update;
    }

}
