<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
           
                'title' => 'من نحن',
                'page_content' => 'من نحن',
                'seo_title' => 'عنوان',
                'seo_link'=>'http',
                'seo_desc'=>"this is description",
                'tags'=>'about us',
                'user_id'=>1
                
        ];
   
          
        
    }
}