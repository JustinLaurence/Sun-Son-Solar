<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'sku' => 'SSS-PANEL-400',
                'name' => '400W Monocrystalline Solar Panel',
                'category' => 'Solar Panels',
                'description' => 'A high-efficiency rooftop panel for dependable residential solar generation.',
                'price' => 12499.00,
                'image_url' => 'https://images.unsplash.com/photo-1509391366360-2e959784a276?auto=format&fit=crop&w=1000&q=85',
                'image_alt' => 'Rows of solar panels collecting sunlight',
                'is_available' => 1,
            ],
            [
                'sku' => 'SSS-PANEL-450',
                'name' => '450W Premium Solar Panel',
                'category' => 'Solar Panels',
                'description' => 'A high-output panel option for properties that need more generation from limited roof space.',
                'price' => 15999.00,
                'image_url' => 'https://images.unsplash.com/photo-1497435334941-8c899ee9e8e9?auto=format&fit=crop&w=1000&q=85',
                'image_alt' => 'Solar panels installed on a rooftop',
                'is_available' => 1,
            ],
            [
                'sku' => 'SSS-INV-5K',
                'name' => '5kW Hybrid Inverter',
                'category' => 'Inverters',
                'description' => 'A hybrid inverter designed to coordinate solar generation, household use, and battery storage.',
                'price' => 68500.00,
                'image_url' => 'https://images.unsplash.com/photo-1497440001374-f26997328c1b?auto=format&fit=crop&w=1000&q=85',
                'image_alt' => 'Solar energy equipment in a renewable energy installation',
                'is_available' => 1,
            ],
            [
                'sku' => 'SSS-BAT-5',
                'name' => '5kWh Home Battery',
                'category' => 'Battery Storage',
                'description' => 'Compact energy storage for using more of your solar power after sunset.',
                'price' => 118000.00,
                'image_url' => 'https://images.unsplash.com/photo-1497440001374-f26997328c1b?auto=format&fit=crop&w=1000&q=85',
                'image_alt' => 'Solar equipment at a renewable energy installation',
                'is_available' => 1,
            ],
            [
                'sku' => 'SSS-MOUNT-ROOF',
                'name' => 'Roof Mounting Kit',
                'category' => 'Mounting & Accessories',
                'description' => 'Weather-resistant mounting components for a secure rooftop solar installation.',
                'price' => 8900.00,
                'image_url' => 'https://images.unsplash.com/photo-1508514177221-188b1cf16e9d?auto=format&fit=crop&w=1000&q=85',
                'image_alt' => 'Solar array mounted across a rooftop',
                'is_available' => 1,
            ],
        ];

        foreach ($products as $product) {
            $existing = $this->db->table('products')->where('sku', $product['sku'])->get()->getRowArray();

            if ($existing) {
                continue;
            }

            $this->db->table('products')->insert($product);
        }
    }
}
