<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormmater;
use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\EmailVerification;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function Register (Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'     => 'required|string|max:255',
                'email'    => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'whatsaap' => 'sometimes|string|max:20',
                'address' => 'sometimes|string',
                'role' => 'sometimes|string|max:10',
                'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'status_aktif' => 'sometimes',
                'fcm' => 'sometimes|nullable'
            ]);

            if ($validator->fails()) {
                return ResponseFormmater::error(null, $validator->errors()->first(), 400);
            }

            // simpan data user
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'whatsaap' => $request->whatsaap,
                'address' => $request->address,
                'role' => $request->role,
                'avatar' => $request->avatar,
                'status_aktif'=> $request->status_aktif,
                'fcm' => $request->fcm
            ]);

            // // generate OTP
            // $otp = rand(100000, 999999);

            // // kirim email verifikasi
            // Mail::to($request->email)->send(new OtpMail($otp));

            // // Simpan OTP ke database email_verifications
            // EmailVerification::updateOrCreate(
            //     ['email' => $request->email],
            //     ['otp' => $otp]
            // );

            // buat token untuk user
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return ResponseFormmater::success([
                'access_token' => $tokenResult,
                'token_type'   => 'Bearer',
                'user'         => $user,
                // 'otp'          => $otp // kalau di production sebaiknya jangan kirim OTP ke response
            ], 'Register Success, kode verifikasi telah dikirim ke email');

        } catch (Exception $error) {
            return ResponseFormmater::error([
                'message' => 'Register Failed',
                'error'   => $error->getMessage()
            ], 'Register Failed', 500);
        }
    }


    public function resetPasswordWithOtp(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'otp' => 'required',
            ]);

            // Step 1: Verify the OTP
            $verification = EmailVerification::where('email', $request->email)
                            ->where('otp', $request->otp)
                            ->first();

            if (!$verification) {
                return ResponseFormmater::error(null, 'Kode OTP salah atau email tidak ditemukan', 400);
            }

            // Check if OTP has expired (optional - add expiry time check if needed)
            if (now()->diffInMinutes($verification->created_at) > 10) {
                return ResponseFormmater::error(null, 'Kode OTP sudah kedaluwarsa', 400);
            }

            // Step 2: Find user with the email
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return ResponseFormmater::error(null, 'User tidak ditemukan', 404);
            }


            // Step 4: Delete the OTP after successful password reset
            $verification->delete();

            return ResponseFormmater::success([
                'message' => 'Silahkan melanjutkan Reset Password',
            ], 'OTP VALID');

        } catch (Exception $error) {
            return ResponseFormmater::error([
                'message' => 'OTP TIDAK VALID',
                'error' => $error->getMessage()
            ], 'OTP TIDAK VALID', 500);
        }
    }

    public function requestPasswordResetOtp(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
            ]);

            $email = $request->email;
            $user = User::where('email', $email)->first();

            if (!$user) {
                return ResponseFormmater::error(null, 'Email tidak terdaftar', 404);
            }

            // Generate OTP (6 digit random number)
            $otp = random_int(100000, 999999);

            // Save OTP to database (replace any existing OTP)
            EmailVerification::updateOrCreate(
                ['email' => $email],
                ['otp' => $otp, 'created_at' => now()]
            );

            // Send OTP email
            Mail::to($email)->send(new OtpMail($otp));

            return ResponseFormmater::success([
                'message' => 'Kode OTP telah dikirim ke email Anda',
            ], 'Permintaan Reset Password Berhasil');

        } catch (Exception $error) {
            return ResponseFormmater::error([
                'message' => 'Permintaan Reset Password Gagal',
                'error' => $error->getMessage()
            ], 'Permintaan Reset Password Gagal', 500);
        }
    }


    public function Login (Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $crendetials = request(['email', 'password']);

            if(!Auth::attempt($crendetials)) {
                return ResponseFormmater::error([
                    'message' => 'Unauthorized'
                ], 'Unauthorized Failed', 404);
            }

            $user = User::where('email', $request->email)->first();

            if(!Hash::check($request->password, $user->password, [])) {
                throw new Exception('password is incorrect');
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormmater::success([
                'access_token' =>  $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 'Login Success');
        }

        catch(Exception $error) {
            return ResponseFormmater::error([
                'message' => 'Login Failed',
                'error' => $error
            ], 'Login Failed', 404);
        }
    }


    public function Logout(Request $request)
    {
        try {
            // Revoke the token that was used to authenticate the current request
            $request->user()->currentAccessToken()->delete();

            return ResponseFormmater::success(null, 'Logout Success');
        } catch (Exception $error) {
            return ResponseFormmater::error([
                'message' => 'Logout Failed',
                'error'   => $error->getMessage()
            ], 'Logout Failed', 500);
        }
    }

    public function UpdateProfile(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $existingData = User::where('email', $request->email)->where('id', '!=', $id)->first();

        if($existingData) {
            return ResponseFormmater::error(
                null,
                'Email sudah dimiliki orang lain',
                505
            );
        }

        $data = $request->all();

        if($request->hasFile('avatar')) {

            if($request->file('avatar')->isValid()) {
                Storage::disk('upload')->delete($user->avatar);
                $avatar = $request->file('avatar');
                $extensions = $avatar->getClientOriginalExtension();
                $userAvatar = "user/".date('YmdHis').".".$extensions;
                $uploadPath = \env('UPLOAD_PATH'). "/user";
                $request->file('avatar')->move($uploadPath, $userAvatar);
                $data['avatar'] = $userAvatar;
            }
        }

        if($request->input('password')) {

            $data['password'] = Hash::make($data['password']);

        } else {

            $data = Arr::except($data,['password']);
        }


        $user->update($data);
        return ResponseFormmater::success(
            $user,
            'Data User berhasil diupdate'
        );
    }

}
