<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Config;  
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use App\Models\User;

class VkController extends Controller
{
    public function index(Request $request)
    {
        $uuid = 'secretuniqstring';
        $appId = 51901854;
        $redirectUri = urlencode('http://localhost/checkVk');
        $redirect_state = 'nonce';

        $redirect = 'https://id.vk.com/auth?'."uuid=${uuid}&app_id=${appId}&response_type=silent_token&redirect_uri=${redirectUri}&redirect_state=${redirect_state}";
        
        return redirect($redirect);
    }

    public function check(Request $request)
    {
        
        $payload = json_decode($request->input('payload'));
        if ($payload->uuid !== 'secretuniqstring') {
            abort(403);
        }
        $url = 'https://api.vk.com/method/auth.exchangeSilentAuthToken?v=5.131&token='.$payload->token.'&access_token='.config('services.vk.service_token').'&uuid='.$payload->uuid;
        $res = Http::get($url)->object();
        if (isset($res->error)) {
            return redirect('vk-auth');
        }
        $user_info = Http::get('https://api.vk.com/method/account.getProfileInfo?v=5.131&access_token='.$res->response->access_token)->object();
        $user_vk_id = $user_info->response->id;
        if ($user = User::firstWhere('vk_id', $user_vk_id)) {
            Auth::login($user);
            return redirect(route('dashboard', absolute: false));
        }

        $user = User::create([
            'name' =>  $user_info->response->first_name.' '. $user_info->response->last_name,
            'email' => $user_vk_id.'@vk.com',
            'vk_id' => $user_vk_id
        ]);

        event(new Registered($user));

        Auth::login($user);
        return redirect(route('dashboard', absolute: false));

    }


}
