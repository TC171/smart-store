<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ShipperSeeder extends Seeder
{
    public function run(): void
    {
        $shippers = [
            [
                'name'   => 'Nguyễn Văn Giao',
                'email'  => 'shipper1@smartstore.com',
                'phone'  => '0901234001',
            ],
            [
                'name'   => 'Trần Thị Hàng',
                'email'  => 'shipper2@smartstore.com',
                'phone'  => '0901234002',
            ],
            [
                'name'   => 'Lê Minh Tốc',
                'email'  => 'shipper3@smartstore.com',
                'phone'  => '0901234003',
            ],
        ];

        foreach ($shippers as $data) {
            User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['name'],
                    'phone'    => $data['phone'],
                    'password' => Hash::make('password'),
                    'role'     => 'shipper',
                    'status'   => 1,
                ]
            );
        }

        $this->command->info('✅ Đã tạo ' . count($shippers) . ' shipper mẫu. Mật khẩu: password');
    }
}
