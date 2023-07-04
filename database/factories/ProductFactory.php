<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class; // 連結的模組
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->randomDigit, // 自動產生亂數
            'title' => '測試產品',
            'content' => $this->faker->word, // 自動產生文字(請參考Faker文件)
            'price' => $this->faker->numberBetween(100,1000), // 數字來自100~1000
            'quantity' => $this->faker->numberBetween(10,100), // 數字來自10~100
        ];
    }

    public function less() // 建立一個less狀態的產品，會使數量剩1
    {
        return $this->state(function(array $attributes){
            return [
                'quantity' => 1
            ];
        });
    }
}
