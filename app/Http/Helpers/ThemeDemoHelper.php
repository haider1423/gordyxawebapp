<?php

namespace App\Http\Helpers;

use App\Models\User\AffordableDeal;
use App\Models\User\Banner;
use App\Models\User\BannerSection;
use App\Models\User\BasicExtended;
use App\Models\User\BasicSetting;
use App\Models\User\Feature;
use App\Models\User\IntroPoint;
use App\Models\User\Language;
use App\Models\User\Pcategory;
use App\Models\User\Product;
use App\Models\User\ProductImage;
use App\Models\User\ProductInformation;
use App\Models\User\PsubCategory;
use App\Models\User\Slider;
use App\Models\User\Testimonial;
use App\Models\User\UserSectionHeading;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ThemeDemoHelper
{
    /**
     * Import Theme Styles Demo Data
     */
    public static function importThemeStyleData($userId, $languageId, $theme)
    {
        $languages = Language::where('user_id', $userId)->get();
        if ($languages->isEmpty()) {
            return false;
        }

        $themeStyleConfigs = self::getThemeStyleConfigs();
        $config = $themeStyleConfigs[$theme] ?? $themeStyleConfigs['fastfood'];

        // 1. Update Basic Settings for all user languages
        foreach ($languages as $lang) {
            $bs = BasicSetting::where('user_id', $userId)->where('language_id', $lang->id)->first();
            if (!$bs) {
                $bs = new BasicSetting();
                $bs->user_id = $userId;
                $bs->language_id = $lang->id;
            }

            $bs->theme = $theme;
            $bs->base_color = $config['base_color'] ?? 'D3A971';
            $bs->website_title = $config['website_title'] ?? 'Gordyx Food & Dining';
            $bs->hero_section_title = $config['hero_title'] ?? 'Delicious & Fresh Food Delivered';
            $bs->hero_section_subtitle = $config['hero_subtitle'] ?? 'Welcome to Our Store';
            $bs->hero_section_text = $config['hero_text'] ?? 'Experience exquisite flavors crafted with passion and top-tier quality ingredients.';
            $bs->hero_section_button_text = $config['hero_button_text'] ?? 'Explore Menu';
            $bs->hero_section_button_url = $config['hero_button_url'] ?? '#';
            $bs->hero_section_video_url = $config['hero_video_url'] ?? 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';

            $bs->intro_title = $config['intro_title'] ?? 'About Our Story & Craft';
            $bs->intro_subtitle = $config['intro_subtitle'] ?? 'Quality & Tradition';
            $bs->intro_text = $config['intro_text'] ?? 'We take pride in providing top notch products, ensuring every customer gets an exceptional experience.';
            $bs->intro_section_button_text = $config['intro_button_text'] ?? 'Learn More';
            $bs->intro_section_button_url = $config['intro_button_url'] ?? '#';

            if (!empty($config['intro_bg_image'])) {
                $bs->intro_bg_image = $config['intro_bg_image'];
            }
            if (!empty($config['feature_section_bg_image'])) {
                $bs->feature_section_bg_image = $config['feature_section_bg_image'];
            }
            if (!empty($config['special_section_bg_image'])) {
                $bs->special_section_bg_image = $config['special_section_bg_image'];
            }
            if (!empty($config['testimonial_bg_img'])) {
                $bs->testimonial_bg_img = $config['testimonial_bg_img'];
            }
            if (!empty($config['blog_section_bg_image'])) {
                $bs->blog_section_bg_image = $config['blog_section_bg_image'];
            }
            if (!empty($config['footer_section_bg_image'])) {
                $bs->footer_section_bg_image = $config['footer_section_bg_image'];
            }
            $bs->save();

            // 2. Update Basic Extended
            $be = BasicExtended::where('user_id', $userId)->where('language_id', $lang->id)->first();
            if (!$be) {
                $be = new BasicExtended();
                $be->user_id = $userId;
                $be->language_id = $lang->id;
            }
            $be->top_header_title = $config['top_header_title'] ?? 'Special Offer: Free delivery on orders over $30!';
            $be->top_header_text = $config['top_header_text'] ?? 'Order now & enjoy fresh delights!';
            $be->featured_category_section_title = $config['featured_category_title'] ?? 'Popular Categories';
            $be->featured_section_title = $config['featured_section_title'] ?? 'Special Features';
            $be->featured_section_subtitle = $config['featured_section_subtitle'] ?? 'Why Choose Us';
            $be->save();

            // 3. Update User Section Headings
            $ush = UserSectionHeading::where('user_id', $userId)->where('language_id', $lang->id)->first();
            if (!$ush) {
                $ush = new UserSectionHeading();
                $ush->user_id = $userId;
                $ush->language_id = $lang->id;
            }
            $ush->menu_title = $config['menu_title'] ?? 'Our Delicious Menu';
            $ush->menu_subtitle = $config['menu_subtitle'] ?? 'Handcrafted with Love';
            $ush->blog_title = $config['blog_title'] ?? 'Latest News & Articles';
            $ush->blog_subtitle = $config['blog_subtitle'] ?? 'From Our Kitchen';
            $ush->testimonial_title = $config['testimonial_title'] ?? 'What Customers Say';
            $ush->testimonial_subtitle = $config['testimonial_subtitle'] ?? 'Testimonials';
            $ush->team_title = $config['team_title'] ?? 'Meet Our Master Chefs';
            $ush->team_subtitle = $config['team_subtitle'] ?? 'Expert Team';
            $ush->faq_title = $config['faq_title'] ?? 'Frequently Asked Questions';
            $ush->faq_subtitle = $config['faq_subtitle'] ?? 'Got Questions?';
            $ush->save();
        }

        // 4. Sliders (for fastfood and general hero)
        Slider::where('user_id', $userId)->delete();
        $sliderList = $config['sliders'] ?? [
            [
                'title' => 'Taste the Real Fast Food Delight',
                'text' => 'Made from fresh ingredients with our signature gourmet seasoning.',
                'button_text' => 'Order Now',
                'button_url' => '#',
                'serial_number' => 1
            ],
            [
                'title' => 'Crispy, Cheesy & Flavor Packed',
                'text' => 'Hot off the grill and delivered to your doorstep in minutes.',
                'button_text' => 'View Specials',
                'button_url' => '#',
                'serial_number' => 2
            ]
        ];
        foreach ($languages as $lang) {
            foreach ($sliderList as $s) {
                Slider::create([
                    'user_id' => $userId,
                    'language_id' => $lang->id,
                    'title' => $s['title'],
                    'text' => $s['text'],
                    'button_text' => $s['button_text'],
                    'button_url' => $s['button_url'],
                    'serial_number' => $s['serial_number']
                ]);
            }
        }

        // 5. Intro Points
        IntroPoint::where('user_id', $userId)->delete();
        $introPoints = $config['intro_points'] ?? [
            ['title' => '100% Fresh & Organic Ingredients', 'serial_number' => 1],
            ['title' => 'Award-Winning Chefs & Master Recipes', 'serial_number' => 2],
            ['title' => 'Super Fast Express Delivery', 'serial_number' => 3],
            ['title' => 'Hygienic & Contactless Packaging', 'serial_number' => 4],
        ];
        foreach ($languages as $lang) {
            foreach ($introPoints as $ip) {
                IntroPoint::create([
                    'user_id' => $userId,
                    'language_id' => $lang->id,
                    'title' => $ip['title'],
                    'serial_number' => $ip['serial_number']
                ]);
            }
        }

        // 6. Features
        Feature::where('user_id', $userId)->delete();
        $features = $config['features'] ?? [
            ['title' => 'Fast Delivery', 'serial_number' => 1],
            ['title' => 'Fresh Food Quality', 'serial_number' => 2],
            ['title' => 'Best Price Guaranteed', 'serial_number' => 3],
            ['title' => '24/7 Dedicated Support', 'serial_number' => 4],
        ];
        foreach ($languages as $lang) {
            foreach ($features as $feat) {
                Feature::create([
                    'user_id' => $userId,
                    'language_id' => $lang->id,
                    'title' => $feat['title'],
                    'serial_number' => $feat['serial_number']
                ]);
            }
        }

        // 7. Testimonials
        Testimonial::where('user_id', $userId)->delete();
        $testimonials = $config['testimonials'] ?? [
            [
                'name' => 'Sarah Johnson',
                'rank' => 'Food Critic',
                'rating' => 5,
                'comment' => 'The taste and presentation exceeded all expectations! Truly a 5-star experience from start to finish.',
                'serial_number' => 1
            ],
            [
                'name' => 'Michael Chen',
                'rank' => 'Verified Customer',
                'rating' => 5,
                'comment' => 'Fast delivery, steaming hot food, and unforgettable flavors. This is hands down my favorite place to order!',
                'serial_number' => 2
            ],
            [
                'name' => 'Emily Davis',
                'rank' => 'Regular Diner',
                'rating' => 5,
                'comment' => 'Exceptional service and authentic flavors every single time. Highly recommended to everyone!',
                'serial_number' => 3
            ]
        ];
        foreach ($languages as $lang) {
            foreach ($testimonials as $t) {
                Testimonial::create([
                    'user_id' => $userId,
                    'language_id' => $lang->id,
                    'name' => $t['name'],
                    'rank' => $t['rank'],
                    'rating' => $t['rating'],
                    'comment' => $t['comment'],
                    'serial_number' => $t['serial_number']
                ]);
            }
        }

        // 8. Banners for seabbq, desifoodie, desices
        Banner::where('user_id', $userId)->delete();
        $banners = $config['banners'] ?? [
            [
                'title' => 'Special Weekend Deal',
                'subtitle' => 'Save up to 30% on signature items',
                'button_text' => 'Claim Deal',
                'button_url' => '#',
                'position' => 'left',
                'serial_number' => 1,
                'status' => 1
            ],
            [
                'title' => 'Chef Special Platter',
                'subtitle' => 'Freshly prepared for your satisfaction',
                'button_text' => 'Order Special',
                'button_url' => '#',
                'position' => 'right',
                'serial_number' => 2,
                'status' => 1
            ]
        ];
        foreach ($languages as $lang) {
            foreach ($banners as $b) {
                Banner::create([
                    'user_id' => $userId,
                    'language_id' => $lang->id,
                    'title' => $b['title'],
                    'subtitle' => $b['subtitle'],
                    'button_text' => $b['button_text'],
                    'button_url' => $b['button_url'],
                    'position' => $b['position'],
                    'serial_number' => $b['serial_number'],
                    'status' => $b['status']
                ]);
            }
        }

        return true;
    }

    /**
     * Import Product Categories Demo Data
     */
    public static function importProductCategoriesData($userId, $languageId, $theme)
    {
        $languages = Language::where('user_id', $userId)->get();
        if ($languages->isEmpty()) {
            return false;
        }

        $defaultLang = Language::where('user_id', $userId)->where('is_default', 1)->first() ?? $languages->first();

        // 1. Remove previous DEMO products & categories (safe: does NOT touch user's custom products!)
        $oldDemoProducts = Product::where('user_id', $userId)->where('is_demo', 1)->get();
        foreach ($oldDemoProducts as $prod) {
            ProductInformation::where('product_id', $prod->id)->delete();
            ProductImage::where('product_id', $prod->id)->delete();
            $prod->delete();
        }
        PsubCategory::where('user_id', $userId)->where('is_demo', 1)->delete();
        Pcategory::where('user_id', $userId)->where('is_demo', 1)->delete();

        // 2. Get theme-specific product catalog
        $themeCatalogs = self::getThemeProductCatalogs();
        $catalog = $themeCatalogs[$theme] ?? $themeCatalogs['fastfood'];

        // Get sample pre-bundled images
        $availableCategoryImages = self::getAvailableImages(public_path('assets/front/img/category'));
        $availableProductImages = self::getAvailableImages(public_path('assets/front/img/product/featured'));

        $catImageIdx = 0;
        $prodImageIdx = 0;

        foreach ($catalog as $catData) {
            $catImg = !empty($availableCategoryImages) ? $availableCategoryImages[$catImageIdx % count($availableCategoryImages)] : null;
            $catImageIdx++;

            // Create Category for default language first
            $category = Pcategory::create([
                'user_id' => $userId,
                'language_id' => $defaultLang->id,
                'name' => $catData['name'],
                'slug' => Str::slug($catData['name']) . '-' . rand(100, 999),
                'image' => $catImg,
                'status' => 1,
                'is_feature' => 1,
                'is_demo' => 1
            ]);

            // Create for other languages too
            foreach ($languages as $lang) {
                if ($lang->id != $defaultLang->id) {
                    Pcategory::create([
                        'user_id' => $userId,
                        'language_id' => $lang->id,
                        'name' => $catData['name'],
                        'slug' => Str::slug($catData['name']) . '-' . rand(100, 999),
                        'image' => $catImg,
                        'status' => 1,
                        'is_feature' => 1,
                        'is_demo' => 1
                    ]);
                }
            }

            // Create Products under this category
            if (!empty($catData['products'])) {
                foreach ($catData['products'] as $pData) {
                    $prodImg = !empty($availableProductImages) ? $availableProductImages[$prodImageIdx % count($availableProductImages)] : null;
                    $prodImageIdx++;

                    $product = Product::create([
                        'user_id' => $userId,
                        'feature_image' => $prodImg,
                        'current_price' => $pData['price'] ?? 15.00,
                        'previous_price' => $pData['prev_price'] ?? 20.00,
                        'rating' => 5,
                        'status' => 1,
                        'is_feature' => 1,
                        'is_demo' => 1
                    ]);

                    // Add product information for all user languages
                    foreach ($languages as $lang) {
                        ProductInformation::create([
                            'product_id' => $product->id,
                            'language_id' => $lang->id,
                            'user_id' => $userId,
                            'category_id' => $category->id,
                            'title' => $pData['title'],
                            'slug' => Str::slug($pData['title']) . '-' . rand(1000, 9999),
                            'summary' => $pData['summary'] ?? 'Freshly prepared with authentic ingredients and exquisite taste.',
                            'description' => '<p>' . ($pData['description'] ?? 'Enjoy our chef-crafted recipe prepared with the highest quality fresh ingredients, served with perfection.') . '</p>',
                            'meta_keywords' => Str::slug($pData['title'], ', '),
                            'meta_description' => $pData['summary'] ?? 'Freshly prepared delight.'
                        ]);
                    }
                }
            }
        }

        return true;
    }

    /**
     * Helper to read image filenames from a directory
     */
    private static function getAvailableImages($dir)
    {
        if (!File::exists($dir)) {
            return [];
        }
        $files = File::files($dir);
        $images = [];
        foreach ($files as $file) {
            $ext = strtolower($file->getExtension());
            if (in_array($ext, ['png', 'jpg', 'jpeg', 'webp'])) {
                $images[] = $file->getFilename();
            }
        }
        return $images;
    }

    /**
     * Theme Style Configurations for all 10 themes
     */
    private static function getThemeStyleConfigs()
    {
        return [
            'fastfood' => [
                'base_color' => 'FF324D',
                'website_title' => 'FastFood Express',
                'hero_title' => 'Crispy, Sizzling & Juicy Fast Food',
                'hero_subtitle' => 'Special Gourmet Deals',
                'hero_text' => 'Get freshly grilled burgers, crispy fried chicken, and golden fries delivered directly to your doorstep in minutes.',
                'hero_button_text' => 'Order Online',
                'hero_button_url' => '#',
                'intro_title' => 'Serving the Best Fast Food in Town',
                'intro_subtitle' => 'Our Passion for Flavor',
                'intro_text' => 'We combine signature spice blends, 100% prime beef, and artisan buns to create the ultimate comfort food experience.',
                'menu_title' => 'Fast Food Favorites',
                'menu_subtitle' => 'Top Picks for You',
                'blog_title' => 'Foodie Secrets & Updates',
                'testimonial_title' => 'Loved by Foodies',
            ],
            'bakery' => [
                'base_color' => 'E29B63',
                'website_title' => 'Sweet Crust Artisan Bakery',
                'hero_title' => 'Freshly Baked Artisan Breads & Pastries',
                'hero_subtitle' => 'Baked Fresh Every Morning',
                'hero_text' => 'From golden flaky croissants to handcrafted sourdough loaves and custom party cakes made with love.',
                'hero_button_text' => 'View Pastries',
                'hero_button_url' => '#',
                'intro_title' => 'Tradition in Every Single Crumb',
                'intro_subtitle' => 'Our Bakery Story',
                'intro_text' => 'Using traditional French baking techniques and wholesome organic flour, we bring you artisanal quality daily.',
                'menu_title' => 'Fresh Oven Bakes',
                'menu_subtitle' => 'Daily Specials',
                'blog_title' => 'Baking Tips & News',
                'testimonial_title' => 'Praise from Sweet Lovers',
            ],
            'pizza' => [
                'base_color' => 'D12525',
                'website_title' => 'Bella Napoli Pizzeria',
                'hero_title' => 'Authentic Wood-Fired Italian Pizzas',
                'hero_subtitle' => 'Crispy Crust & Melting Cheese',
                'hero_text' => 'Hand-tossed dough fermented for 48 hours, stone baked at 900°F with San Marzano tomatoes and fresh mozzarella.',
                'hero_button_text' => 'Choose Your Pizza',
                'hero_button_url' => '#',
                'intro_title' => 'Mastery in Italian Pizza Making',
                'intro_subtitle' => 'Real Italian Heritage',
                'intro_text' => 'Every pizza is crafted according to authentic Neapolitan traditions, baked over fragrant oak wood fire.',
                'menu_title' => 'Stone Baked Pizzas',
                'menu_subtitle' => 'Our Italian Menu',
                'blog_title' => 'Stories from the Oven',
                'testimonial_title' => 'What Pizza Fans Say',
            ],
            'coffee' => [
                'base_color' => 'B98053',
                'website_title' => 'Roast & Grind Coffee House',
                'hero_title' => 'Artisan Single-Origin Coffee & Brews',
                'hero_subtitle' => 'Awaken Your Senses',
                'hero_text' => 'Savor hand-roasted beans from Ethiopia, Colombia, and Guatemala brewed to perfection by certified baristas.',
                'hero_button_text' => 'Explore Brews',
                'hero_button_url' => '#',
                'intro_title' => 'The Art of Specialty Coffee',
                'intro_subtitle' => 'Direct Trade Beans',
                'intro_text' => 'We source ethically and roast locally in small batches to preserve delicate aroma profiles and tasting notes.',
                'menu_title' => 'Barista Specialties',
                'menu_subtitle' => 'Hot & Iced Coffee',
                'blog_title' => 'Coffee Roasting Guide',
                'testimonial_title' => 'Coffee Lovers Reviews',
            ],
            'medicine' => [
                'base_color' => '0284C7',
                'website_title' => 'MediCare Pharmacy & Health',
                'hero_title' => 'Your Trusted Healthcare & Wellness Partner',
                'hero_subtitle' => 'Fast Pharmacy Delivery',
                'hero_text' => 'Access authentic prescription medications, essential vitamins, first aid supplies, and wellness care products 24/7.',
                'hero_button_text' => 'Browse Pharmacy',
                'hero_button_url' => '#',
                'intro_title' => 'Dedicated to Your Health & Well-Being',
                'intro_subtitle' => 'Certified Pharmacists',
                'intro_text' => 'We provide 100% genuine pharmaceutical supplies with rapid, temperature-controlled doorstep delivery.',
                'menu_title' => 'Healthcare Essentials',
                'menu_subtitle' => 'Top Health Products',
                'blog_title' => 'Health & Wellness Tips',
                'testimonial_title' => 'Patient & Customer Reviews',
            ],
            'grocery' => [
                'base_color' => '55B325',
                'website_title' => 'GreenFarm Fresh Grocery',
                'hero_title' => 'Farm Fresh Groceries & Organic Produce',
                'hero_subtitle' => 'Delivered to Your Kitchen',
                'hero_text' => 'Shop organic fruits, crisp farm vegetables, dairy, pantry staples, and everyday essentials at market-best prices.',
                'hero_button_text' => 'Shop Groceries',
                'hero_button_url' => '#',
                'intro_title' => 'Straight from Local Organic Farms',
                'intro_subtitle' => 'Pure & Natural',
                'intro_text' => 'We partner directly with sustainable local farmers to guarantee uncompromised freshness and nutrition.',
                'menu_title' => 'Fresh Farm Aisles',
                'menu_subtitle' => 'Top Grocery Categories',
                'blog_title' => 'Healthy Living & Recipes',
                'testimonial_title' => 'Happy Shoppers',
            ],
            'beverage' => [
                'base_color' => 'FF6F00',
                'website_title' => 'PureSip Juices & Smoothies',
                'hero_title' => 'Cold-Pressed Juices & Artisan Drinks',
                'hero_subtitle' => '100% Pure & Natural',
                'hero_text' => 'Energize your day with vitamin-packed fruit smoothies, detox cold-pressed juices, and revitalizing iced coolers.',
                'hero_button_text' => 'View Drinks',
                'hero_button_url' => '#',
                'intro_title' => 'Bottled Freshness Without Preservatives',
                'intro_subtitle' => 'Zero Added Sugar',
                'intro_text' => 'Every bottle contains nutrient-dense raw fruits and superfoods pressed cold to preserve maximum vitality.',
                'menu_title' => 'Refreshing Beverages',
                'menu_subtitle' => 'Juices, Smoothies & Teas',
                'blog_title' => 'Detox & Health Guide',
                'testimonial_title' => 'Drinkers Experience',
            ],
            'seabbq' => [
                'base_color' => 'E54415',
                'website_title' => 'Ocean & Flame Sea BBQ',
                'hero_title' => 'Live Seafood Grill & Smoky BBQ Cuts',
                'hero_subtitle' => 'Grilled to Perfection',
                'hero_text' => 'Fresh ocean catches, smoked brisket, succulent BBQ ribs, and jumbo prawns grilled over aromatic wood charcoal.',
                'hero_button_text' => 'View BBQ Menu',
                'hero_button_url' => '#',
                'intro_title' => 'The Sizzle of Ocean & Smoke',
                'intro_subtitle' => 'Master of the Pit',
                'intro_text' => 'Our pitmasters season fresh lobster, red snapper, and prime ribs with secret rubs before slow-smoking over hickory.',
                'menu_title' => 'Seafood & BBQ Platters',
                'menu_subtitle' => 'Signature Specialties',
                'blog_title' => 'BBQ Pitmaster Journal',
                'testimonial_title' => 'Customer Feedback',
            ],
            'desifoodie' => [
                'base_color' => 'DC2626',
                'website_title' => 'Zaika Royal Desi Kitchen',
                'hero_title' => 'Authentic Desi Spices, Biryani & Karahi',
                'hero_subtitle' => 'Rich Royal Flavors',
                'hero_text' => 'Indulge in aromatic Dum Biryani, sizzling Seekh Kebabs, rich Mutton Karahi, and freshly baked Garlic Naan.',
                'hero_button_text' => 'Taste Desi Delights',
                'hero_button_url' => '#',
                'intro_title' => 'Culinary Heritage of the Subcontinent',
                'intro_subtitle' => 'Aromatic Perfection',
                'intro_text' => 'Centuries-old royal recipes prepared in traditional clay ovens and iron karahis using hand-ground spices.',
                'menu_title' => 'Royal Desi Dishes',
                'menu_subtitle' => 'Biryani, Karahi & BBQ',
                'blog_title' => 'Heritage & Spice Stories',
                'testimonial_title' => 'Desi Food Lovers',
            ],
            'desices' => [
                'base_color' => 'FF7243',
                'website_title' => 'Desi Frost Kulfi & Ice Treats',
                'hero_title' => 'Traditional Kulfi, Falooda & Gelato',
                'hero_subtitle' => 'Cool Down in Style',
                'hero_text' => 'Rich creamy Malai Kulfi, Royal Rabri Falooda, Belgian Chocolate Sundaes, and artisanal scoops made with fresh dairy.',
                'hero_button_text' => 'Explore Treats',
                'hero_button_url' => '#',
                'intro_title' => 'Pure Cream, Real Saffron & Pistachio',
                'intro_subtitle' => 'Sweet Indulgence',
                'intro_text' => 'Slow-cooked condensed milk infused with Kashmiri saffron, cardamom, and roasted pistachios for the richest dessert.',
                'menu_title' => 'Frozen Treats & Desserts',
                'menu_subtitle' => 'Ice Creams, Kulfi & Shakes',
                'blog_title' => 'Dessert Trends & Delights',
                'testimonial_title' => 'Sweet Reviews',
            ],
        ];
    }

    /**
     * Product Catalogs for all 10 themes
     */
    private static function getThemeProductCatalogs()
    {
        return [
            'fastfood' => [
                [
                    'name' => 'Burgers',
                    'products' => [
                        ['title' => 'Double Cheese Gourmet Burger', 'price' => 12.99, 'prev_price' => 15.99, 'summary' => 'Two juicy beef patties topped with melted cheddar, crisp lettuce, and secret sauce.'],
                        ['title' => 'Crispy Zinger Chicken Burger', 'price' => 9.99, 'prev_price' => 12.99, 'summary' => 'Golden fried spicy chicken breast with spicy mayo and shredded iceberg.'],
                        ['title' => 'Smoky BBQ Bacon Burger', 'price' => 13.50, 'prev_price' => 16.00, 'summary' => 'Grilled beef patty, crispy bacon strips, caramelized onions, and hickory BBQ sauce.']
                    ]
                ],
                [
                    'name' => 'Crispy Chicken & Wings',
                    'products' => [
                        ['title' => 'Golden Fried Chicken Bucket (6 Pcs)', 'price' => 16.99, 'prev_price' => 20.99, 'summary' => 'Tender chicken marinated in 11 herbs and spices, fried to a golden crunch.'],
                        ['title' => 'Spicy Buffalo Glazed Wings (10 Pcs)', 'price' => 11.99, 'prev_price' => 14.50, 'summary' => 'Crispy chicken wings tossed in tangy cayenne buffalo sauce with ranch dip.']
                    ]
                ],
                [
                    'name' => 'Loaded Fries & Sides',
                    'products' => [
                        ['title' => 'Cheesy Jalapeno Loaded Fries', 'price' => 6.99, 'prev_price' => 8.99, 'summary' => 'Crispy french fries drenched in hot cheese sauce, jalapeno slices, and bacon bits.'],
                        ['title' => 'Crispy Golden Onion Rings', 'price' => 5.49, 'prev_price' => 6.99, 'summary' => 'Thick cut sweet onions in beer batter, served with garlic aioli.']
                    ]
                ],
                [
                    'name' => 'Chilled Shakes & Drinks',
                    'products' => [
                        ['title' => 'Classic Chocolate Fudge Shake', 'price' => 4.99, 'prev_price' => 6.00, 'summary' => 'Rich Dutch chocolate blended with whole milk and topped with whipped cream.'],
                        ['title' => 'Iced Lemon Mint Soda', 'price' => 3.99, 'prev_price' => 5.00, 'summary' => 'Sparkling soda infused with fresh squeezed lemon juice and crushed mint.']
                    ]
                ]
            ],
            'bakery' => [
                [
                    'name' => 'Artisanal Breads',
                    'products' => [
                        ['title' => 'Traditional French Sourdough Loaf', 'price' => 7.50, 'prev_price' => 9.00, 'summary' => 'Crusty rustic bread made with wild yeast starter and slow-fermented for 24 hours.'],
                        ['title' => 'Italian Rosemary Herb Focaccia', 'price' => 6.99, 'prev_price' => 8.50, 'summary' => 'Soft olive oil bread topped with fresh rosemary sprigs and sea salt flakes.']
                    ]
                ],
                [
                    'name' => 'Pastries & Croissants',
                    'products' => [
                        ['title' => 'Butter Flaky French Croissant', 'price' => 4.25, 'prev_price' => 5.50, 'summary' => 'Laminated with pure European butter, baked golden with infinite flaky layers.'],
                        ['title' => 'Belgian Chocolate Pain au Chocolat', 'price' => 4.95, 'prev_price' => 6.00, 'summary' => 'Flaky pastry filled with two batons of rich dark Belgian chocolate.']
                    ]
                ],
                [
                    'name' => 'Celebration Cakes',
                    'products' => [
                        ['title' => 'Velvet Red Berry Layer Cake', 'price' => 38.00, 'prev_price' => 45.00, 'summary' => 'Moist red velvet sponge layered with smooth cream cheese frosting and fresh berries.'],
                        ['title' => 'Triple Dark Chocolate Truffle Cake', 'price' => 42.00, 'prev_price' => 50.00, 'summary' => 'Decadent chocolate sponge enveloped in dark chocolate ganache and cocoa nibs.']
                    ]
                ],
                [
                    'name' => 'Cookies & Macarons',
                    'products' => [
                        ['title' => 'Parisian Macarons Box (6 Pcs)', 'price' => 14.50, 'prev_price' => 18.00, 'summary' => 'Assorted almond meringue cookies filled with pistachio, raspberry, and salted caramel.'],
                        ['title' => 'Chunky Sea Salt Choco-Chip Cookie', 'price' => 3.50, 'prev_price' => 4.50, 'summary' => 'Chewy center, crisp edges, melted chocolate chunks and Maldon sea salt.']
                    ]
                ]
            ],
            'pizza' => [
                [
                    'name' => 'Classic Italian Pizzas',
                    'products' => [
                        ['title' => 'Margherita D.O.P Pizza', 'price' => 14.99, 'prev_price' => 18.00, 'summary' => 'San Marzano tomato sauce, fresh buffalo mozzarella, fresh basil, and extra virgin olive oil.'],
                        ['title' => 'Spicy Pepperoni & Jalapeno Pizza', 'price' => 16.99, 'prev_price' => 20.00, 'summary' => 'Loaded with premium cured pepperoni slices, spicy jalapenos, and melted mozzarella.']
                    ]
                ],
                [
                    'name' => 'Specialty Gourmet Pizzas',
                    'products' => [
                        ['title' => 'Truffle Mushroom & Prosciutto Pizza', 'price' => 19.50, 'prev_price' => 23.00, 'summary' => 'Wild forest mushrooms, white truffle oil, prosciutto di Parma, and shaved parmesan.'],
                        ['title' => 'Four Cheese Quattro Formaggi Pizza', 'price' => 17.50, 'prev_price' => 21.00, 'summary' => 'Gorgonzola, fontina, fresh mozzarella, and parmigiano reggiano on a light garlic base.']
                    ]
                ],
                [
                    'name' => 'Calzones & Rolls',
                    'products' => [
                        ['title' => 'Classic Italian Meatball Calzone', 'price' => 13.99, 'prev_price' => 16.50, 'summary' => 'Folded pizza pocket stuffed with beef meatballs, ricotta cheese, and marinara.'],
                        ['title' => 'Garlic Butter Parmesan Breadsticks', 'price' => 6.50, 'prev_price' => 8.00, 'summary' => 'Warm oven-baked dough sticks brushed with garlic herb butter and parmesan.']
                    ]
                ]
            ],
            'coffee' => [
                [
                    'name' => 'Espresso & Hot Brews',
                    'products' => [
                        ['title' => 'Signature Double Shot Espresso', 'price' => 3.75, 'prev_price' => 4.50, 'summary' => 'Intense and creamy with caramel crema, made from freshly ground Arabica beans.'],
                        ['title' => 'Velvet Flat White & Latte', 'price' => 5.25, 'prev_price' => 6.50, 'summary' => 'Silky micro-foam steamed milk poured over a rich ristretto espresso base.']
                    ]
                ],
                [
                    'name' => 'Cold Brew & Iced Coffee',
                    'products' => [
                        ['title' => 'Slow Brewed Nitro Cold Brew', 'price' => 5.95, 'prev_price' => 7.00, 'summary' => '18-hour cold water extraction infused with nitrogen for a velvety stout-like texture.'],
                        ['title' => 'Iced Salted Caramel Macchiato', 'price' => 5.75, 'prev_price' => 6.75, 'summary' => 'Vanilla-flavored milk with espresso drizzle, finished with buttery salted caramel.']
                    ]
                ],
                [
                    'name' => 'Beans & Grounds',
                    'products' => [
                        ['title' => 'Ethiopian Yirgacheffe Beans (250g)', 'price' => 16.00, 'prev_price' => 19.00, 'summary' => 'Floral jasmine notes with bright citrus bergamot undertones. Light-medium roast.'],
                        ['title' => 'Colombian Supremo Dark Roast (250g)', 'price' => 14.50, 'prev_price' => 17.50, 'summary' => 'Deep chocolate and roasted hazelnut profile with a full velvety body.']
                    ]
                ]
            ],
            'medicine' => [
                [
                    'name' => 'Prescription & Pain Relief',
                    'products' => [
                        ['title' => 'Extra Strength Pain Relief Tablets (500mg)', 'price' => 8.99, 'prev_price' => 11.00, 'summary' => 'Fast acting relief for headaches, body aches, and fever reduction.'],
                        ['title' => 'Anti-Allergy & Histamine Caplets', 'price' => 12.50, 'prev_price' => 15.00, 'summary' => 'Non-drowsy 24-hour relief from seasonal allergies, pollen, and dust reactions.']
                    ]
                ],
                [
                    'name' => 'Vitamins & Supplements',
                    'products' => [
                        ['title' => 'Vitamin C 1000mg + Zinc Complex', 'price' => 15.99, 'prev_price' => 19.99, 'summary' => 'Immune defense support capsules packed with citrus bioflavonoids and zinc.'],
                        ['title' => 'Omega-3 Wild Alaskan Fish Oil (1200mg)', 'price' => 22.00, 'prev_price' => 27.00, 'summary' => 'Purified molecularly distilled fish oil supporting heart, brain, and joint mobility.']
                    ]
                ],
                [
                    'name' => 'First Aid & Health Monitoring',
                    'products' => [
                        ['title' => 'Complete Family First Aid Kit (100 Pcs)', 'price' => 24.99, 'prev_price' => 30.00, 'summary' => 'Sterile bandages, antiseptic wipes, burn gel, medical tape, and precision tweezers.'],
                        ['title' => 'Digital Instant Read Forehead Thermometer', 'price' => 29.99, 'prev_price' => 38.00, 'summary' => 'Infrared non-contact precision temperature scanner with fever alert display.']
                    ]
                ]
            ],
            'grocery' => [
                [
                    'name' => 'Fresh Fruits & Vegetables',
                    'products' => [
                        ['title' => 'Organic Honeycrisp Red Apples (1kg)', 'price' => 4.99, 'prev_price' => 6.50, 'summary' => 'Crisp, sweet, and juicy orchard-fresh apples grown without synthetic pesticides.'],
                        ['title' => 'Fresh Farm Hydroponic Baby Spinach (250g)', 'price' => 3.49, 'prev_price' => 4.50, 'summary' => 'Tender pre-washed organic spinach leaves, perfect for fresh salads and green smoothies.']
                    ]
                ],
                [
                    'name' => 'Dairy, Eggs & Cheese',
                    'products' => [
                        ['title' => 'Organic Free-Range Brown Eggs (12 Pcs)', 'price' => 5.25, 'prev_price' => 6.50, 'summary' => 'Farm fresh large grade-A brown eggs from pasture-raised hens.'],
                        ['title' => 'Whole Organic Pure Milk (1 Gallon)', 'price' => 4.75, 'prev_price' => 5.99, 'summary' => 'Pasteurized whole milk rich in calcium and natural vitamin D3.']
                    ]
                ],
                [
                    'name' => 'Pantry & Grains',
                    'products' => [
                        ['title' => 'Extra Virgin Cold Pressed Olive Oil (500ml)', 'price' => 11.99, 'prev_price' => 14.50, 'summary' => 'Single-estate Mediterranean olive oil with a fruity aroma and peppery finish.'],
                        ['title' => 'Royal Long Grain Basmati Rice (5kg)', 'price' => 16.50, 'prev_price' => 20.00, 'summary' => 'Aged Himalayan long grain rice with delicate natural aroma.']
                    ]
                ]
            ],
            'beverage' => [
                [
                    'name' => 'Fresh Fruit Smoothies',
                    'products' => [
                        ['title' => 'Mango Passion Tropical Smoothie', 'price' => 6.50, 'prev_price' => 8.00, 'summary' => 'Alfonso mango chunks, passion fruit pulp, Greek yogurt, and raw honey.'],
                        ['title' => 'Berry Blast Antioxidant Smoothie', 'price' => 6.75, 'prev_price' => 8.25, 'summary' => 'Strawberries, blueberries, blackberries, and chia seeds blended with almond milk.']
                    ]
                ],
                [
                    'name' => 'Cold-Pressed Detox Juices',
                    'products' => [
                        ['title' => 'Green Goddess Detox Cold-Pressed Juice', 'price' => 7.25, 'prev_price' => 9.00, 'summary' => 'Celery, cucumber, green apple, kale, ginger root, and lemon.'],
                        ['title' => 'Golden Glow Citrus Turmeric Juice', 'price' => 6.95, 'prev_price' => 8.50, 'summary' => 'Fresh oranges, carrots, turmeric, and black pepper for maximum absorption.']
                    ]
                ],
                [
                    'name' => 'Artisan Iced Teas & Coolers',
                    'products' => [
                        ['title' => 'Peach Hibiscus Sparkling Iced Tea', 'price' => 4.95, 'prev_price' => 6.00, 'summary' => 'Brewed hibiscus herbal flowers infused with white peach puree and sparkling water.'],
                        ['title' => 'Fresh Mint & Cucumber Lime Cooler', 'price' => 4.50, 'prev_price' => 5.50, 'summary' => 'Crisp cucumber ribbons, garden mint leaves, and freshly squeezed key limes.']
                    ]
                ]
            ],
            'seabbq' => [
                [
                    'name' => 'Fresh Seafood Grill',
                    'products' => [
                        ['title' => 'Charcoal Grilled Jumbo Garlic Prawns (6 Pcs)', 'price' => 24.99, 'prev_price' => 29.99, 'summary' => 'Ocean caught prawns basted with herb garlic butter and grilled over live coals.'],
                        ['title' => 'Whole Red Snapper in Lemon Herb Marinade', 'price' => 28.50, 'prev_price' => 34.00, 'summary' => 'Fresh red snapper stuffed with fresh thyme, rosemary, and grilled to crispy perfection.']
                    ]
                ],
                [
                    'name' => 'Smoked & BBQ Meats',
                    'products' => [
                        ['title' => 'Smoked Hickory Beef Brisket Platter', 'price' => 26.00, 'prev_price' => 32.00, 'summary' => '12-hour slow smoked beef brisket with a dark peppery bark and tender smoke ring.'],
                        ['title' => 'Honey Glazed BBQ Baby Back Ribs (Full Rack)', 'price' => 29.99, 'prev_price' => 36.00, 'summary' => 'Fall-off-the-bone tender pork ribs glazed in our house honey bourbon BBQ sauce.']
                    ]
                ],
                [
                    'name' => 'BBQ Combos & Sides',
                    'products' => [
                        ['title' => 'Ocean & Turf Ultimate Combo Platter', 'price' => 45.00, 'prev_price' => 55.00, 'summary' => 'Grilled lobster tail, prime ribeye steak slice, grilled prawns, and roasted corn on the cob.'],
                        ['title' => 'Smoked Mac & Four Cheese Skillet', 'price' => 8.50, 'prev_price' => 10.50, 'summary' => 'Elbow pasta tossed in smoked gouda, cheddar, and gruyere with a toasted panko crust.']
                    ]
                ]
            ],
            'desifoodie' => [
                [
                    'name' => 'Royal Biryani & Rice',
                    'products' => [
                        ['title' => 'Hyderabadi Special Dum Mutton Biryani', 'price' => 18.99, 'prev_price' => 22.50, 'summary' => 'Fragrant basmati rice layered with tender mutton cuts, saffron, and aromatic spices.'],
                        ['title' => 'Lucknowi Chicken Biryani with Raita', 'price' => 15.99, 'prev_price' => 19.00, 'summary' => 'Subtly spiced chicken biryani cooked in sealed clay pots with saffron milk.']
                    ]
                ],
                [
                    'name' => 'Karahi & Handi Specials',
                    'products' => [
                        ['title' => 'Shinwari Mutton Karahi (Half Kg)', 'price' => 26.00, 'prev_price' => 31.00, 'summary' => 'Cooked in pure lamb fat with fresh tomatoes, green chilies, and black pepper in a cast iron wok.'],
                        ['title' => 'Butter Chicken Makhani Curry', 'price' => 16.50, 'prev_price' => 20.00, 'summary' => 'Tandoori roasted chicken morsels simmered in a velvety tomato and cashew butter gravy.']
                    ]
                ],
                [
                    'name' => 'Tandoori BBQ & Kebabs',
                    'products' => [
                        ['title' => 'Seekh Kebab Platter (4 Skewers)', 'price' => 14.50, 'prev_price' => 18.00, 'summary' => 'Minced beef blended with fresh herbs and spices, chargrilled on open tandoor skewers.'],
                        ['title' => 'Chicken Tikka Boti with Garlic Naan', 'price' => 15.00, 'prev_price' => 18.50, 'summary' => 'Boneless chicken cubes marinated in spicy yogurt and roasted in clay oven.']
                    ]
                ]
            ],
            'desices' => [
                [
                    'name' => 'Traditional Kulfi & Falooda',
                    'products' => [
                        ['title' => 'Royal Shahi Rabri Falooda', 'price' => 8.50, 'prev_price' => 10.50, 'summary' => 'Layers of rose syrup, basil seeds, cornstarch vermicelli, thick rabri, and malai kulfi.'],
                        ['title' => 'Saffron Pistachio Matka Kulfi (2 Pcs)', 'price' => 6.99, 'prev_price' => 8.50, 'summary' => 'Traditional slow-reduced milk kulfi infused with saffron and roasted pistachios in clay pots.']
                    ]
                ],
                [
                    'name' => 'Artisan Ice Creams & Gelato',
                    'products' => [
                        ['title' => 'Peshawari Roasted Pistachio Gelato', 'price' => 5.95, 'prev_price' => 7.50, 'summary' => 'Creamy slow-churned gelato packed with crushed green pistachios and cardamom.'],
                        ['title' => 'Belgian Chocolate & Brownie Overload Sundae', 'price' => 7.95, 'prev_price' => 9.50, 'summary' => 'Scoops of dark chocolate gelato, warm fudge brownie cubes, and melted chocolate syrup.']
                    ]
                ],
                [
                    'name' => 'Dessert Shakes & Lassi',
                    'products' => [
                        ['title' => 'Mango Kulfi Milkshake with Almonds', 'price' => 6.50, 'prev_price' => 8.00, 'summary' => 'Blended mango kulfi with chilled whole milk, topped with sliced almonds.'],
                        ['title' => 'Sweet Malai Lassi with Mawa Topping', 'price' => 5.50, 'prev_price' => 7.00, 'summary' => 'Thick creamy yogurt lassi served in traditional earthenware glasses.']
                    ]
                ]
            ]
        ];
    }
}
