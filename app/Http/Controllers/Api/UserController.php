<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;

use App\Models\ClientAnswers;
use App\Models\QuestionType;
use Illuminate\Support\Facades\File;
use Image;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;

class UserController extends Controller
{
    public function register(Request $request)
    {
        if (!empty($request->all())) {
            $input = $request->all();
            $rules = [
                'first_name' => ['required'],
                'sur_name' => ['required'],
                'birth_date' => ['required'],
                'gender' => ['required'],
                'email' => ['required', 'email', 'max:50', 'unique:users,email'],
                'phone_number' => ['required'],
                'street' => ['required'],
                'zipcode' => ['required'],
                'location' => ['required'],
                'country' => ['required'],
                'newsletter' => ['required'],
                'terms' => ['required'],
                'data_protection' => ['required'],
                'password' => ['required', 'string', 'min:6'],
                'profile_image' => ['required', 'mimes:jpeg,jpg,png,gif', 'max:10000'],
            ];
            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 0,
                    'message' => $validator->errors()->first(),
                    'data' => [],
                ]);
            } else {
                $user = User::create([
                    'first_name' => $input['first_name'],
                    'sur_name' => $input['sur_name'],
                    'birth_date' => $input['birth_date'],
                    'gender' => $input['gender'],
                    'email' => $input['email'],
                    'phone_number' => $input['phone_number'],
                    'street' => $input['street'],
                    'zipcode' => $input['zipcode'],
                    'location' => $input['location'],
                    'country' => $input['country'],
                    'newsletter' => $input['newsletter'],
                    'terms' => $input['terms'],
                    'data_protection' => $input['data_protection'],
                    'password' => bcrypt($input['password']),
                ]);
                if ($request->hasFile('profile_image')) {
                    $img = $request->file('profile_image');
                    $filename = time() . '.' . $img->getClientOriginalExtension();
                    $path = public_path('uploads/profile_pictures/' . $user['id']);
                    File::makeDirectory($path, $mode = 0777, true, true);
                    Image::make($img->getRealPath())->resize(300, 300)->save($path . '/' . $filename);
                    User::where('id', $user['id'])->update(['profile_pic' => $filename]);
                }
                return response()->json([
                    'status' => 1,
                    'message' => 'User successfully registered',
                    'data' => $user['id'],
                ]);
            }
        } else {
            return response()->json([
                'status' => 0,
                'message' => "Something Went Wrong.",
                'data' => "",
            ]);
        }
    }

    public function login(Request $request)
    {
        if (!empty($request->all())) {
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 0,
                    'message' => $validator->errors()->first(),
                    'data' => [],
                ]);
            }

            if (!Auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
                return response()->json([
                    'status' => 0,
                    'message' => "Invalid Credentials",
                    'data' => [],
                ]);
            } else {
                $checkUser = User::where('id', Auth::user()->id)->first();
                $checkUser['profile_pic'] = asset('uploads/profile_pictures/' . Auth::user()->id) . '/' . $checkUser['profile_pic'];
                $checkUser['accessToken'] = Auth()->user()->createToken('authToken')->accessToken;
                return response()->json([
                    'status' => 1,
                    'message' => "You have successfully logged in.",
                    'data' => $checkUser,
                ]);
            }
        } else {
            return response()->json([
                'status' => 0,
                'message' => "Something Went Wrong.",
                'data' => "",
            ]);
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user()->token();
        $tokens = $user->tokens->pluck('id');
        Token::whereIn('id', $tokens)
            ->update(['revoked' => true]);

        RefreshToken::whereIn('access_token_id', $tokens)->update(['revoked' => true]);
        return response()->json([
            'status' => 1,
            'message' => "Logout Successfully.",
            'data' => "",
        ]);
    }

    public function info(Request $request)
    {
        $user_info = User::where('email', $request->email)->get();

        return response()->json($user_info);
    }
}