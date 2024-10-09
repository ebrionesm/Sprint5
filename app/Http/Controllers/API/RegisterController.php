<?php
     
namespace App\Http\Controllers\API;
     
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Player;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
     
class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nickname' => 
                [
                    'nullable', 'unique:players',/*Rule::unique('players')->where(fn (Builder $query) => $query->where('nickname', 'Anónimo')),*/ 'max:25'
                ],
            'email' => 
                [
                    'required',
                    'unique:players',
                    //Rule::unique('players')->ignore($player->id),
                ],

            //'role'=>'required|string',
            'password' => 'required',
            /*'c_password' => 'required|same:password',*/
        ]);
     
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $request->merge(['nickname' => $request->nickname ?? 'Anonymous']);
        $request->merge(['role' => 'player']);
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $player = Player::create($input);
        //$player->assignRole($request->input('role', 'api'));
        $player->assignRole($player->role);
        $player->save();
        $success['token'] =  $player->createToken('dicegame')->accessToken;
        $success['name'] =  $player->nickname;
   
        return $this->sendResponse($success, 'Player registered successfully.');
    }
     
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $player = Auth::user(); 
            $success['token'] =  $player->createToken('dicegame')-> accessToken; 
            $success['name'] =  $player->nickname;
   
            return $this->sendResponse($success, 'Player login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }
}