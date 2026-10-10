<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ApiAuthController extends Controller
{
    /**
     * ثبت نام کاربر جدید و صدور توکن API
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        // ایجاد توکن اختصاصی Sanctum برای کاربر
        // createtokenابزار لاراول سنکتامه این خط یه توکن متنی جدید برای کاربر میسازه
        // از این به بعد فرانت این توکن رو ذخیره میکنه و در تمام درخواست های بعدی میفرسته تا شناسایی بشه
        $token = $user->createToken('auth_token')->plainTextToken;

        // بجای ریدایرکت به صفحه وب پاسخ  تو قالب json به فرانت برمیگرده
        // access_token همون کلید ورود به برنامه
        // 'token_type'   => 'Bearer' نوع توکن که استانداردش بیر هست 
        // 'user'         => $user, اطلاعات کاربر ثبت نام شده
        return response()->json([
            'message'      => 'ثبت‌نام با موفقیت انجام شد.',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user,
        ], 201);
    }

    /**
     * ورود کاربر و صدور توکن API
     */
    public function login(LoginRequest $request)
    {
        $datavalidated = $request->validated();

        // بررسی وجود کاربر و صحت رمز عبور
        $user = User::where('email', $datavalidated['email'])->first();

        // دو شرط مهمو چک میکنه
        // کاربر اصن پیداشد؟
        // پسورد وارد شده با پسورد هش شده تو دیتابیس مطابقت دارد؟
        if (! $user || ! Hash::check($datavalidated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['اطلاعات وارد شده با اطلاعات ما مطابقت ندارد.'],
            ]);
        }

        // اگه درست بود یه توکن سنکتام جدید برای ورود کاربر بساز
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'      => 'ورود با موفقیت انجام شد.',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user,
        ]);
    }

    /**
     * خروج کاربر و ابطال توکن فعلی
     */
    public function logout(Request $request)
    {
        // dd('reached apiauthcontroller');
        // حذف توکنی که کاربر با آن درخواست داده 
        // بجای پاک کردن سشن مرورگر توکنی که کاربر باهاش لاگین کرده رو از جدول  personal-acess-tokens
        // تو دیتابییس حذف میکنه تا با این توکن دیگه نتونه به api دسترسی داشته باشه
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'با موفقیت از حساب کاربری خارج شدید.',
        ]);
    }

    /**
     * دریافت مشخصات کاربر جاری (احراز هویت شده)
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
    // یه متد api  که فرانت اند هروقت خواست بدونه چه کسی لاگین کرده
    // به این ادرس درخواست میزنه و اطلاعات کاربر درخواست کننده روبصورت جیسون میگیره
}