<?php

namespace Database\Seeders;

use App\Models\InstagramUsername;
use App\Models\Setting;
use App\Models\SmmCategory;
use App\Models\SmmProvider;
use App\Models\SmmService;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin Account
        $admin = User::updateOrCreate(
            ['email' => 'admin@instazone.com'],
            [
                'name' => 'مدير النظام | Admin',
                'password' => Hash::make('admin123456'),
                'is_admin' => true,
                'balance' => 500.00,
                'phone' => '+9647700000000',
                'telegram' => '@InstaAdmin',
            ]
        );

        // 2. Demo User Account
        $user = User::updateOrCreate(
            ['email' => 'user@instazone.com'],
            [
                'name' => 'محمد العراقي',
                'password' => Hash::make('user123456'),
                'is_admin' => false,
                'balance' => 120.00,
                'phone' => '+9647800000000',
                'telegram' => '@Mohammad_iq',
            ]
        );

        // 3. Platform Settings
        $settings = [
            'site_name' => 'انستازون | InstaZone',
            'usdt_trc20_address' => 'TYDzsYUEWzK1oP4vB7g8W8c4BvHnK7f8zM',
            'usdt_bep20_address' => '0x71C67Ed37037C0d7Bf3014B1F20606B5e4492A72',
            'telegram_support' => '@InstaZone_Support',
            'whatsapp_support' => '+9647700000000',
            'notice_banner' => '✨ أهلاً بكم في انستازون - المتجر الأول لبيع اليوزرات القديمة والرباعية وزيادة المتابعين مع الدفع المشفر USDT!',
        ];

        foreach ($settings as $key => $val) {
            Setting::set($key, $val);
        }

        // 4. Sample SMM Provider
        $provider = SmmProvider::updateOrCreate(
            ['name' => 'SMM Global Server API v2'],
            [
                'api_url' => 'https://justexpan.com/api/v2',
                'api_key' => 'demokey_88921bdfc919248a8',
                'balance' => 74.50,
                'currency' => 'USD',
                'status' => true,
            ]
        );

        // 5. SMM Categories & Services
        $catFollowers = SmmCategory::create([
            'name_ar' => 'متابعين انستقرام (ضمان حقيقي)',
            'name_en' => 'Instagram Followers (Guaranteed)',
            'icon' => 'fa-users',
            'sort_order' => 1,
        ]);

        $catLikes = SmmCategory::create([
            'name_ar' => 'لايكات وتفاعل انستقرام',
            'name_en' => 'Instagram Likes & Engagement',
            'icon' => 'fa-heart',
            'sort_order' => 2,
        ]);

        $catReels = SmmCategory::create([
            'name_ar' => 'مشاهدات ريلز وإكسبلور',
            'name_en' => 'Reels & Video Views (Explore)',
            'icon' => 'fa-play-circle',
            'sort_order' => 3,
        ]);

        $catComments = SmmCategory::create([
            'name_ar' => 'تعليقات مخصصة وعربية',
            'name_en' => 'Custom & Arabic Comments',
            'icon' => 'fa-comment',
            'sort_order' => 4,
        ]);

        // Services
        SmmService::create([
            'category_id' => $catFollowers->id,
            'name_ar' => 'متابعين انستقرام حقيقيين عرب وخليجيين [ضمان 30 يوم / سرعة 5K يومياً]',
            'name_en' => 'Real Arab/Gulf Followers [30 Days Refill]',
            'price_per_1k' => 2.5000,
            'min_quantity' => 100,
            'max_quantity' => 20000,
            'execution_type' => 'manual',
            'description' => 'متابعين حقيقيين متفاعلين مع الحساب، نسبة نقص شبه معدومة مع تعويض تلقائي لمدة 30 يوم.',
        ]);

        SmmService::create([
            'category_id' => $catFollowers->id,
            'name_ar' => 'متابعين انستقرام مكس سريع جداً [بدون نقص / سرعة فورية]',
            'name_en' => 'Instagram Mixed Followers [Instant High Speed]',
            'price_per_1k' => 1.2000,
            'min_quantity' => 50,
            'max_quantity' => 100000,
            'execution_type' => 'api',
            'provider_id' => $provider->id,
            'provider_service_id' => '1024',
            'description' => 'بدء فوري خلال 1-5 دقائق. سرعة إرسال تصل إلى 50,000 في اليوم.',
        ]);

        SmmService::create([
            'category_id' => $catLikes->id,
            'name_ar' => 'لايكات انستقرام حقيقية مع زيادة وصول إكسبلور [سرعة فائقة]',
            'name_en' => 'HQ Instagram Likes + Explore Reach',
            'price_per_1k' => 0.4500,
            'min_quantity' => 50,
            'max_quantity' => 50000,
            'execution_type' => 'api',
            'provider_id' => $provider->id,
            'provider_service_id' => '2048',
            'description' => 'لايكات من حسابات حقيقية بصور وبوستات، تدعم رفع الريتش والظهور في صفحة Explore.',
        ]);

        SmmService::create([
            'category_id' => $catReels->id,
            'name_ar' => 'مشاهدات ريلز انستقرام إكسبلور + حفظ المنشور [سرعة 1M يومياً]',
            'name_en' => 'Instagram Reels Views + Saves [Fast 1M/day]',
            'price_per_1k' => 0.1500,
            'min_quantity' => 500,
            'max_quantity' => 5000000,
            'execution_type' => 'api',
            'provider_id' => $provider->id,
            'provider_service_id' => '3012',
            'description' => 'أرخص وأسرع خدمة مشاهدات ريلز للمساعدة في تصدر الترند والإكسبلور فوراً.',
        ]);

        SmmService::create([
            'category_id' => $catComments->id,
            'name_ar' => 'تعليقات انستقرام عربية إيجابية مخصصة [تكتب التعليقات بنفسك]',
            'name_en' => 'Custom Arabic Comments',
            'price_per_1k' => 6.0000,
            'min_quantity' => 10,
            'max_quantity' => 1000,
            'execution_type' => 'manual',
            'description' => 'تعليقات من حسابات عربية حقيقية 100%، يمكنك كتابة نصوص التعليقات التي تفضلها.',
        ]);

        // 6. Instagram Usernames
        $usernames = [
            [
                'username' => 'x_99',
                'type' => 'quad',
                'followers_count' => 14500,
                'creation_year' => '2014',
                'price' => 180.00,
                'description' => 'يوزر رباعي فخم ونادر جداً، الحساب أنشئ في 2014 ويحتوي على 14.5K متابع حقيقي، يشمل الإيميل الأساسي (OGE) مع كامل رسائل الترحيب الأصلية.',
                'status' => 'available',
                'delivery_info' => "معلومات الحساب المسلم:\n- الإيميل الأساسي: x_99_main@gmail.com\n- كلمة سر الإيميل: Pass@12399x\n- يوزر انستقرام: @x_99\n- باسورد انستقرام: InstaSecret#99x\n- كود 2FA الاحتياطي: 8841-9921-3412",
            ],
            [
                'username' => 'k8_m',
                'type' => 'quad',
                'followers_count' => 6200,
                'creation_year' => '2015',
                'price' => 125.00,
                'description' => 'يوزر رباعي مميز متناسق وخفيف النطق، نظيف وخالٍ من أي حظر أو مخالفات، تسليم فوري مع إيميله الأساسي.',
                'status' => 'available',
                'delivery_info' => "بيانات الحساب:\n- الإيميل: k8m_acc@outlook.com\n- الباسورد: Outlook#K8m2026\n- باسورد انستا: K8m#Pass99",
            ],
            [
                'username' => 'r.7.r',
                'type' => 'tri_semi',
                'followers_count' => 8900,
                'creation_year' => '2013',
                'price' => 240.00,
                'description' => 'شبه ثلاثي متكرر بنقاط فخمة جداً @r.7.r، حساب قديم ونادر جداً، ساسبند محمي وتسليم إيميل أول.',
                'status' => 'available',
                'delivery_info' => "بيانات التسليم:\n- الإيميل: r7r_original@gmail.com\n- الباسورد: Pass#R7R_2026\n- كود النسخ الاحتياطي: 4421-8891-2319",
            ],
            [
                'username' => 'vintage_iq',
                'type' => 'vintage',
                'followers_count' => 3100,
                'creation_year' => '2012',
                'price' => 85.00,
                'description' => 'حساب انستقرام عتيق تأسيس 2012، يحتوي على بوستات قديمة وأقدمية عالية في خوارزميات انستقرام تفيدك بالحماية من البلاغات.',
                'status' => 'available',
                'delivery_info' => "بيانات الحساب:\n- الإيميل: vintage_iq12@yahoo.com\n- الباسورد: YahooVintage2012!",
            ],
            [
                'username' => 'royal_hub',
                'type' => 'verified',
                'followers_count' => 84000,
                'creation_year' => '2016',
                'price' => 890.00,
                'description' => 'حساب موثق بالعلامة الزرقاء الرسمية (Verified Badge) مع 84K متابع، متاح تغيير الاسم والمجال، تسليم رسمي مضمون 100%.',
                'status' => 'available',
                'delivery_info' => "بيانات الحساب الموثق:\n- الإيميل الرسمي: royalhub_official@gmail.com\n- الباسورد: Royal#Secured2026\n- 2FA Key: JBSWY3DPEHPK3PXP",
            ],
            [
                'username' => '7_vx',
                'type' => 'quad',
                'followers_count' => 11200,
                'creation_year' => '2015',
                'price' => 150.00,
                'description' => 'يوزر رباعي بدايته رقم ونهايته حروف فخمة، حساب نشط ونظيف مع إيميل جيميل أساسي.',
                'status' => 'available',
                'delivery_info' => "بيانات الحساب:\n- الإيميل: vx7_original@gmail.com\n- الباسورد: VX#7_Secure123",
            ],
            [
                'username' => 'vip_dxb',
                'type' => 'special',
                'followers_count' => 38000,
                'creation_year' => '2017',
                'price' => 310.00,
                'description' => 'يوزر تجاري فخم مناسب للمشاريع والبراندات في دبي والخليج، متابعين متفاعلين ونشاط عالي.',
                'status' => 'sold',
                'delivery_info' => "تم البيع مسبقاً وتسليمه للعميل.",
            ],
        ];

        foreach ($usernames as $u) {
            InstagramUsername::updateOrCreate(['username' => $u['username']], $u);
        }

        // 7. Engagement Tasks
        $tasks = [
            [
                'title' => 'وضع لايك على بوست العروض الحصرية',
                'type' => 'like_post',
                'target_url' => 'https://www.instagram.com/p/DAq_X8tI_test1',
                'points_reward' => 50,
                'max_completions' => 1000,
                'instructions' => 'قم بالدخول إلى رابط البوست واضغط زر الإعجاب (Like) ثم عد هنا واضغط تأكيد الإكمال.',
                'is_daily' => true,
                'status' => true,
            ],
            [
                'title' => 'مشاهدة ولايك على ريلز تريند انستقرام',
                'type' => 'like_reel',
                'target_url' => 'https://www.instagram.com/reel/C89testReel_2',
                'points_reward' => 75,
                'max_completions' => 500,
                'instructions' => 'شاهد فيديو الريلز لمدة 10 ثوانٍ على الأقل وضع لايك لدعم الوصول.',
                'is_daily' => true,
                'status' => true,
            ],
            [
                'title' => 'متابعة حساب المتجر الرسمي على انستقرام',
                'type' => 'follow_account',
                'target_url' => 'https://www.instagram.com/instazone_official',
                'points_reward' => 150,
                'max_completions' => 2000,
                'instructions' => 'اضغط على زر Follow لمتابعة حسابنا الرسمي للبقاء على اطلاع بكل جديد.',
                'is_daily' => true,
                'status' => true,
            ],
            [
                'title' => 'كتابة تعليق إيجابي على أحدث بوست',
                'type' => 'comment',
                'target_url' => 'https://www.instagram.com/p/DAq_X8tI_test3',
                'points_reward' => 100,
                'max_completions' => 300,
                'instructions' => 'اكتب تعليقاً لطيفاً من كلمتين أو أكثر عن تجربة استخدامك.',
                'is_daily' => true,
                'status' => true,
            ],
            [
                'title' => 'مشاهدة ستوري اليوم والتصويت على الاستطلاع',
                'type' => 'story_view',
                'target_url' => 'https://www.instagram.com/stories/instazone_official',
                'points_reward' => 60,
                'max_completions' => 1500,
                'instructions' => 'افتح الستوري وصوت في الاستطلاع اليومي لدعم الحساب.',
                'is_daily' => true,
                'status' => true,
            ],
        ];

        foreach ($tasks as $t) {
            \App\Models\Task::updateOrCreate(['title' => $t['title']], $t);
        }

        // Give demo user points for immediate testing
        if ($user) {
            $user->points = 1250;
            $user->save();
        }
    }
}
