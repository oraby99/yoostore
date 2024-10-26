<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use App\Models\PaymentStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderStatus::insert([
            ['name' => 'Pending'],
            ['name' => 'Delivered'],
            ['name' => 'Cancelled'],
        ]);

        PaymentStatus::insert([
            ['name' => 'Pending'],
            ['name' => 'Paid'],
            ['name' => 'Cancelled'],
        ]);
    }
}
