<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user()->load(['defaultAddress', 'addresses']);

        if (
            !$user->defaultAddress &&
            (
                !empty($user->address) ||
                !empty($user->city) ||
                !empty($user->postal_code)
            )
        ) {
            $user->addresses()->create([
                'full_name'   => $user->name,
                'phone'       => $user->phone,
                'address'     => $user->address,
                'city'        => $user->city,
                'district'    => null,
                'ward'        => null,
                'postal_code' => $user->postal_code,
                'is_default'  => 1,
            ]);

            $user->load(['defaultAddress', 'addresses']);
        }

        $recentOrders = $user->orders()->latest()->take(5)->get();
        $totalOrders = $user->orders()->count();
        $completedOrders = $user->orders()->where('status', 'completed')->count();
        $pendingOrders = $user->orders()->whereIn('status', ['pending', 'confirmed'])->count();
        $totalSpent = $user->orders()->where('payment_status', 'paid')->sum('grand_total');

        $defaultAddress = $user->defaultAddress;

        return view('frontend.profile.edit', compact(
            'user',
            'recentOrders',
            'totalOrders',
            'completedOrders',
            'pendingOrders',
            'totalSpent',
            'defaultAddress'
        ));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'phone'         => ['nullable', 'string', 'max:20'],
            'gender'        => ['nullable', 'in:male,female,other'],
            'date_of_birth' => ['nullable', 'date'],
            'address'       => ['nullable', 'string'],
            'city'          => ['nullable', 'string', 'max:100'],
            'district'      => ['nullable', 'string', 'max:100'],
            'ward'          => ['nullable', 'string', 'max:100'],
            'postal_code'   => ['nullable', 'string', 'max:20'],
        ]);

        $user->update([
            'name'          => $validated['name'],
            'phone'         => $validated['phone'] ?? null,
            'gender'        => $validated['gender'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
        ]);

        $addressData = [
            'full_name'   => $validated['name'],
            'phone'       => $validated['phone'] ?? null,
            'address'     => $validated['address'] ?? null,
            'city'        => $validated['city'] ?? null,
            'district'    => $validated['district'] ?? null,
            'ward'        => $validated['ward'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
        ];

        $defaultAddress = $user->addresses()->where('is_default', 1)->first();

        if ($defaultAddress) {
            $defaultAddress->update($addressData);
        } else {
            $user->addresses()->create(array_merge($addressData, [
                'is_default' => 1,
            ]));
        }

        return back()->with('success', 'Cập nhật thông tin thành công.');
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'Mật khẩu hiện tại không đúng.',
            ]);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success_password', 'Đổi mật khẩu thành công.');
    }

    public function updateAvatar(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);

        if (!empty($user->avatar)) {
            $oldPath = public_path($user->avatar);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        $file = $request->file('avatar');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        if (!is_dir(public_path('avatars'))) {
            mkdir(public_path('avatars'), 0777, true);
        }

        $file->move(public_path('avatars'), $filename);

        $user->update([
            'avatar' => 'avatars/' . $filename,
        ]);

        return back()->with('success', 'Cập nhật ảnh đại diện thành công.');
    }

    public function updateDefaultAddress(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'full_name'   => ['required', 'string', 'max:255'],
            'phone'       => ['required', 'string', 'max:20'],
            'address'     => ['required', 'string'],
            'city'        => ['nullable', 'string', 'max:100'],
            'district'    => ['nullable', 'string', 'max:100'],
            'ward'        => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
        ]);

        $currentDefault = $user->addresses()->where('is_default', 1)->first();

        if ($currentDefault) {
            $currentDefault->update($validated);
        } else {
            $user->addresses()->create(array_merge($validated, [
                'is_default' => 1,
            ]));
        }

        return back()->with('success_address', 'Cập nhật địa chỉ mặc định thành công.');
    }
}