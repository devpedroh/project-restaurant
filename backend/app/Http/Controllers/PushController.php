<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Auth;
use App\User;

class PushController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function saveToken(Request $request)
    {
        $data = $request->all();
        $user = $request->user();

        $update = DB::table('users')->where([
            ['id', '=', $user->id],
        ])->update([
            'device_token' => $data['device_token']
        ]);

        return 200;
    }

    public function disableToken(Request $request)
    {
        $data = $request->all();
        $user = $request->user();

        $update = DB::table('users')->where([
            ['id', '=', $user->id],
        ])->update([
            'device_token' => null
        ]);

        return 200;
    }


    /**
     * Write code on Method
     *
     * @return response()
     */
    public function sendNotification(Request $request)
    {

        $firebaseToken = User::where('user_role', 'Cozinheiro')->pluck('device_token')->all();
        if (!$firebaseToken) {
            return [
                'status' => false,
                'errors' => 'Token invalido',
            ];
        } else {
            $SERVER_API_KEY = 'AAAAx9ZEuyU:APA91bEXE5immVcegbQg_eAiJs4ciP84S-piP7IHsgTLbWlGwGxz0Y6td6tTBwTOhZ-8hR2n4DR7U_j7MQW2EcN5uTUtIlBkE0s2tR5CSmZKQ6Bw1hli2Sle9zaCmQBbU4juEdVUyVLd';

            $data = [
                "registration_ids" => $firebaseToken,
                "notification" => [
                    "title" => 'Novo pedido!',
                    "body" => 'Um novo pedido acaba de ser feito! Favor, verifique...',
                    "content_available" => true,
                    "priority" => "high",
                ]
            ];
            $dataString = json_encode($data);

            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            curl_setopt($ch, CURLOPT_FAILONERROR, true);

            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                $error_msg = curl_error($ch);
            }
            curl_close($ch);

            if (isset($error_msg)) {
                return [
                    'status' => true,
                    'errors' => $error_msg,
                    'string' => $dataString
                ];
            } else {
                return [
                    'status' => true,
                    'errors' => 'Notificação enviada!',
                    'string' => $dataString
                ];
            }
        }

        return [
            'status' => false,
        ];
    }
}
