<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;


class AuthController extends Controller
{

    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:100',
            'email' => 'required|unique:users|max:255',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        $name = $request->input("name");
        $email = $request->input("email");
        $password = $request->input("password");
        $address = $request->input("address");
        $no_hp = $request->input("no_hp");
        $role = $request->input("role");

        $hashPwd = Hash::make($password);
        // $md5 = md5($password);
        // $hashPwd = $this->ppnEncrypt($md5, "fkRm2204RPl2017bsMlhwSdCPtAaMIin");

        $data = [
            "name" => $name,
            "email" => $email,
            "password" => $hashPwd,
            "role" => $role,
            "address" => $address,
            "no_hp" => $no_hp
        ];


        if (UserModel::create($data)) {
            $out = [
                "message" => "register_success",
                "code"    => 201,
            ];
        } else {
            $out = [
                "message" => "failed_register",
                "code"   => 404,
            ];
        }

        return response()->json($out, $out['code']);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);

        $email = $request->input("email");
        $password = $request->input("password");

        $user = UserModel::where("email", $email)->first();

        if (!$user) {
            $out = [
                "message" => "login_failed",
                "code"    => 401,
                "result"  => [
                      "user" => [
                        "name" => null,
                        "role" => null,
                    ],
                    "token" => null,
                ]
            ];
            return response()->json($out, $out['code']);
        }

        // alternatif jika enkripsi boleh dijadikan algoritma
        // $md5Decrypt = $this->ppnDecrypt($user->password, "fkRm2204RPl2017bsMlhwSdCPtAaMIin");
          
        // if (md5($password) == $md5Decrypt) {
        //     $newtoken  = $this->generateRandomString();

        //     $user->update([
        //         'token' => $newtoken
        //     ]);

        //     $out = [
        //         "message" => "login_success",
        //         "code"    => 200,
        //         "result"  => [
        //             "user" => [
        //                 "id" => $user->id,
        //                 "name" => $user->name,
        //                 "email" => $user->email,
        //                 "address" => $user->address,
        //                 "no_hp" => $user->no_hp,
        //                 "role" => $user->role,
        //                 "token" => $user->token
        //             ],
        //             "token" => $newtoken,
        //         ]
        //     ];
        // } else {
        //     $out = [
        //         "message" => "login_failed",
        //         "code"    => 401,
        //         "result"  => [
        //             "user" => [
        //                 "id" => null,
        //                 "name" => null,
        //                 "email" => null,
        //                 "password" => null,
        //                 "address" => null,
        //                 "no_hp" => null,
        //                 "role" => null,
        //             ],
        //             "token" => null,
        //         ]
        //     ];
        // }

        if (Hash::check($password, $user->password)) {
            $newtoken  = $this->generateRandomString();

            $user->update([
                'token' => $newtoken
            ]);

            $out = [
                "message" => "login_success",
                "code"    => 200,
                "result"  => [
                    "user" => [
                        "id" => $user->id,
                        "name" => $user->name,
                        "email" => $user->email,
                        "password" => $user->password,
                        "address" => $user->address,
                        "no_hp" => $user->no_hp,
                        "role" => $user->role,
                        "token" => $user->token
                    ],
                    "token" => $newtoken,
                ]
            ];
        } else {
            $out = [
                "message" => "login_failed",
                "code"    => 401,
                "result"  => [
                    "user" => [
                        "id" => null,
                        "name" => null,
                        "email" => null,
                        "password" => null,
                        "address" => null,
                        "no_hp" => null,
                        "role" => null,
                    ],
                    "token" => null,
                ]
            ];
        }

        return response()->json($out, $out['code']);
    }

    function generateRandomString($length = 32)
    {
        $karakkter = '012345678dssd9abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $panjang_karakter = strlen($karakkter);
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= $karakkter[rand(0, $panjang_karakter - 1)];
        }
        return $str;
    } 
    
    function ppnEncrypt( $data , $key )
    {
        $method = 'aes-256-gcm';
        $key = base64_decode( $key );
        $iv = openssl_random_pseudo_bytes( openssl_cipher_iv_length( $method ) );
        $tag = ""; // openssl_encrypt will fill this
        $result = openssl_encrypt( $data , $method , $key , OPENSSL_RAW_DATA , $iv , $tag , "" , 16 );
        return base64_encode( $iv.$tag.$result );
    }

    function ppnDecrypt( $data , $key )
    {
        $method = 'aes-256-gcm';
        $data = base64_decode( $data );
        $key = base64_decode( $key );
        $ivLength = openssl_cipher_iv_length( $method );
        $iv = substr( $data , 0 , $ivLength );
        $tag = substr( $data , $ivLength , 16 );
        $text = substr( $data , $ivLength+16 );
        return openssl_decrypt( $text , $method , $key , OPENSSL_RAW_DATA , $iv , $tag );
    }

    function testing() {
        $md5 = md5("iniPassword");
        $md5Encrypted = ppnEncrypt($md5, "awokkowwokkowgowlaskdfjkeiowdlsk");
        echo $md5Encrypted.'</br>';
        
        $md5Decrypt = ppnDecrypt($md5Encrypted, "awokkowwokkowgowlaskdfjkeiowdlsk");
        echo $md5Decrypt.'</br>';
        
        if ($md5Decrypt = "iniPassword") {
            echo "login sukses";
        } else {
            echo "login gagal";
        }
    }

    function makeHashPswd($password){
        $hashPwd = Hash::make($password);
        echo $hashPwd;
    }
}
