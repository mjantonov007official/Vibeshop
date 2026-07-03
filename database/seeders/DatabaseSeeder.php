<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@threadlab.studio'],
            [
                'name' => 'ThreadLab Admin',
                'password' => 'password',
                'role' => 'admin',
                'email_verified_at' => Carbon::now(),
            ],
        );

        User::query()->updateOrCreate(
            ['email' => 'customer@threadlab.studio'],
            [
                'name' => 'ThreadLab Customer',
                'password' => 'password',
                'role' => 'customer',
                'email_verified_at' => Carbon::now(),
            ],
        );

        $products = [
            [
                'name' => 'Essential Black Tee',
                'slug' => 'essential-black-tee',
                'sku' => 'TL-001-B',
                'description' => 'Premium cotton t-shirt designed for everyday comfort and style. Reconstructed silhouette with dropped shoulders and reinforced technical seams.',
                'price' => 699,
                'compare_at_price' => 1200,
                'category' => 'Basic',
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAj4PnVBOKJZJoLcx8QJTvxJZiXjqaWyNAb6NRH-CvKnxdaS0Jf37dlOhpCyh1WR-I5OQXaSdLP-16201n_7TrnmlbzPDUvczACB4mm6gWiCOX6bOOWQnCDlRyT5tlrwfKPhSpO3RsqzYyZd_1c3ZhwuIYep5cGfxvlZ3REfaxmPY4kHD-BxJexNN9bIt6FDzfOxG6GYFvnXdxflwEQYkUgIeKkY9PWA1zC_xOLEO4BgblQ92-Dkj1Db7f5eooT7lEqKutRYc3W6ho',
                'gallery' => [
                    'https://lh3.googleusercontent.com/aida-public/AB6AXuBaysYXlmBblQhbmjyYDR9-UZlaIIQeAP6GxqA4pgHitivFmL5UsKOsSjsfgRyl8gLmITTgtPNFaFkXlNPgR7J7Efh7zqq22lzlCw-26xipJhe-nEwpvHCWz_ctFp7dsBx4X2YAGnoniz5zigRGcn-tS6k0bRjxVNrbZf1Y7U1NO0BfrBdCUPCq0yRqEvB_uEIh8m783GKulKEOTSuax0q2yPiLTBeHArEgxxfMEE14653A7ycHmtSIly5nybqDBQgwOC03xfjoH2E',
                    'https://lh3.googleusercontent.com/aida-public/AB6AXuCqsdzoOHdo1PvQe_yOTmU8gDXi7kkeWgwuBOcbTyblSTpl95cd1Fzg49nNb17IJTNGVv7lwYOL2mkQ8v3hska9gYJJ-2ptvucS4zl0EKahc1DSqGCxC29eei-dOWiRn0ITi7Syxh1aM2g_KzFIGrt_EtTHKMXvAiQ-j65soApK7E94jZhMiqfJIXLqY7S9V_LKEasRSkET4f3q6BeoP1y_Q0EfzMtKh4u9iHBiCSxuuhXDLNRDbjmeDjedPjGJ6Zlvs69U4gqwApA',
                    'https://lh3.googleusercontent.com/aida-public/AB6AXuAYw2Eq2az-5YpmVklYYgG0YbZmVtxva4ERtyfFEyEgsimKUgjFosu-P84YbEr8CylR2sSwPkZlmmrOqap8ItrFrD5p5cMEfUW1Gh4Ejr8BqjlRt9U-kafZt4Q2XRTfafRdRuo2Kf6ZqDg2Zz6JUQfKz8ddX53pLTBqZ8ail14Xrh-us46CU37cjqIMSTh2F7qiml9togO9vr_kxeuwSr1_ublwgtIzkqRi3pw3lF1Uq4FFZ5jygPu3c2wsTZzUwl5D-DoNZc7N-4Q',
                ],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'stock' => 40,
                'is_featured' => false,
            ],
            [
                'name' => 'Classic White Tee',
                'slug' => 'classic-white-tee',
                'sku' => 'TL-002-W',
                'description' => 'Clean heavyweight white tee with a structured fit and minimal finish.',
                'price' => 849,
                'category' => 'Basic',
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBC4I5a96dLapKGACArZ-M_4D8dP_DPfx1KvaHn6qmcrF0R5za4qFUDZyESq1HCUeXF_Am95SCiIxMlA9WYM_qKAn5FXo4_fbXhi_Td0Xxa2xRaKW72QkKvQnkQ1LC7JUOrhE9k05LCl3ozm_uVzMWYRLMcrUcq3TTJ51eVj2IxdmSBhTk0jP_znU5xmGF3CJ_lr0tWLcUDn2QjkZ6X__dW7Fph7xobFBHqHxkhGj_h9KxtUbFCV6CetrBzAULvK6-6TQq89wybZO8',
                'gallery' => [],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'stock' => 32,
            ],
            [
                'name' => 'Earth Tone Tee',
                'slug' => 'earth-tone-tee',
                'sku' => 'TL-003-E',
                'description' => 'Muted earth tone cotton tee for relaxed everyday rotation.',
                'price' => 1099,
                'category' => 'Minimal',
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuB6nZ-rDWJyEx7eMk9CvW3cRybBvwe8y0fIuQz2o0iIv3GGTva7_w28fhTTUQCNYSe9JT0MEoo55x-3Tni4mQIHAKMop1qICe02b6syYSs-v8RIo2uyhm6Q_eFPGBzJ5nsgfa23vtNvOZJLPvjW38dpupg9zoe3RAlnNDN7SAXVX2P84QElAbVqGyhWnAwvoD5CQ8b7k0tFfCtvxGr1Voelwo-3c95KdM305FoBc7HLmpGtD-OrQ2SNb4C19xCqa_yS5KnZPS73JUQ',
                'gallery' => [],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'stock' => 24,
            ],
            [
                'name' => 'Oversized Street Tee',
                'slug' => 'oversized-street-tee',
                'sku' => 'TL-004-OS',
                'description' => 'Oversized streetwear tee with a dropped-shoulder profile.',
                'price' => 1299,
                'category' => 'Oversized',
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDm5ugfeo33UUfjNpzqVBIIWgmTZ9NnfHFJE_1dJ6Z7eYCAK2ndKoXMM-xM6yyj6HGo32LPR-ObTbQIdl0OQkyW4aGYh4jAn3ctTjDxiEsrrX1TeX6Uy900agi6q53QtcM4gJ8DMT8j8Q2m1Pv3IHwcF9VAlhDz8iTJxxg6psTFjp_b2Ah4E32mLTPNlV8dBSX_JOMvOoxz3EZaXtrOe8HrII-NMHtYzkVclESNeGpwWgyeNwYUeiTjQSEQXQ6uKk_6Pfn0KKgtvwo',
                'gallery' => [],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'stock' => 18,
            ],
            [
                'name' => 'Minimal Logo Tee',
                'slug' => 'minimal-logo-tee',
                'sku' => 'TL-005-M',
                'description' => 'Signature minimalist logo tee crafted from 240GSM premium cotton.',
                'price' => 949,
                'category' => 'Minimal',
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAmhZrHsF9AM5XF32dhzr7RURiKTVJJnpVuV2XDK-aBcPerIltnSQpCsfqWZAvmEQz305yeHVS34rTy_LT24qiPv_f9x2YmVjYqZlyrK3d9a_dCeU9N-aikdOAR7czpjhwdAhgnNjtfu_nVFVzMUK6PEdNujVgUS0pGyblD-uedfQK_ZhrzTcIo-R_Uu5Ce84kNHL9irC5UvfjpPmgN78tTmuehEULmoHZ7Vpzs3q4QJ-czp8FJCkagWBEeDGDPmgrhkSm2EHrnmYs',
                'gallery' => [],
                'sizes' => ['S', 'M', 'L', 'XL'],
                'stock' => 15,
                'is_featured' => true,
            ],
            [
                'name' => 'Oversized Canvas Tote',
                'slug' => 'oversized-canvas-tote',
                'sku' => 'TL-006-TOTE',
                'description' => 'Heavy-duty canvas tote in onyx with minimal detailing.',
                'price' => 1750,
                'category' => 'Accessories',
                'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCSgauD9kkXcYE1eTny5qqX1GBieIXQasF00L1NxiRmI9MdclKcCj5oxlNprSdy8gtXZHXR32yX8dBJHd1WgNE54BHudWMbr1cMREC83o5uvk1NdW0IabU6RiVYHtnkx4ik4fcU4tKQuvn28PJzsWednZEU_y80P6R1YytBgvXTcecEucBvJENronotG3SaP8o9NkiQNrmhWcjb2KRCnk3WPTET1b5G3F4CYJsIrc7ev-Dxop93ELHDAJUWnNtGI9oLPn9zgCdyT-A',
                'gallery' => [],
                'sizes' => ['Onyx'],
                'stock' => 20,
            ],
        ];

        foreach ($products as $product) {
            Product::query()->updateOrCreate(
                ['slug' => $product['slug']],
                $product,
            );
        }
    }
}
