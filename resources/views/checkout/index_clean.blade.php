@extends('layouts.app')
@section('title', 'চেকআউট - Bazario')
@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-4">
    <p class="text-center text-sm text-gray-500 mb-4">অর্ডার করতে আপনার তথ্য দিন</p>

    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form" novalidate
          x-data="{
            division: '',
            district: '',
            thana: '',
            method: 'cod',
            isGatewayConfigured: {{ empty(env('SSLCZ_STORE_ID')) ? 'false' : 'true' }},
            selectMethod(m) {
                this.method = m;
                if (!this.isGatewayConfigured && (m === 'bkash' || m === 'nagad')) {
                    // Show warning, then switch back to cod after 2 seconds
                    setTimeout(() => { if(this.method === m) this.method = 'cod'; }, 2000);
                }
            },
            coupon: '{{ session('coupon_code', '') }}', 
            applied: {{ session('promo_discount', 0) > 0 ? 'true' : 'false' }}, 
            message: '', 
            loading: false,
            discount: {{ session('promo_discount', 0) }},
            subtotal: {{ $subtotal }},
            ship            divisions: ['ঢাকা', 'চট্টগ্রাম', 'রাজশাহী', 'খুলনা', 'বরিশাল', 'সিলেট', 'রংপুর', 'ময়মনসিংহ'],
            districts: {
                'ঢাকা': ['ঢাকা', 'গাজীপুর', 'নারায়ণগঞ্জ', 'নরসিংদী', 'ফরিদপুর', 'রাজবাড়ী', 'গোপালগঞ্জ', 'মাদারীপুর', 'শরীয়তপুর', 'মানিকগঞ্জ', 'মুন্সিগঞ্জ', 'কিশোরগঞ্জ', 'টাঙ্গাইল'],
                'চট্টগ্রাম': ['চট্টগ্রাম', 'কুমিল্লা', 'নোয়াখালী', 'ফেনী', 'ব্রাহ্মণবাড়িয়া', 'চাঁদপুর', 'লক্ষ্মীপুর', 'কক্সবাজার', 'খাগড়াছড়ি', 'রাঙ্গামাটি', 'বান্দরবান'],
                'সিলেট': ['সিলেট', 'হবিগঞ্জ', 'مৌলভীবাজার', 'সুনামগঞ্জ'],
                'রাজশাহী': ['রাজশাহী', 'বগুড়া', 'পাবনা', 'সিরাজগঞ্জ', 'নাটোর', 'নওগাঁ', 'জয়পুরহাট', 'চাঁপাইনবাবগঞ্জ'],
                'খুলনা': ['খুলনা', 'যশোর', 'ঝিনাইদহ', 'কুষ্টিয়া', 'মাগুরা', 'মেহেরপুর', 'নড়াইল', 'সাতক্ষীরা', 'বাগেরহাট', 'চুয়াডাঙ্গা'],
                'বরিশাল': ['বরিশাল', 'ভোলা', 'পটুয়াখালী', 'পিরোজপুর', 'বরগুনা', 'ঝালকাঠি'],
                'রংপুর': ['রংপুর', 'দিনাজপুর', 'গাইবান্ধা', 'কুড়িগ্রাম', 'নীলফামারী', 'লালমনিরহাট', 'ঠাকুরগাঁও', 'পঞ্চগড়'],
                'ময়মনসিংহ': ['ময়মনসিংহ', 'জামালপুর', 'নেত্রকোণা', 'শেরপুর']
            },
            thanas: {
                // ঢাকা বিভাগ
                'ঢাকা': ['আদাবর', 'আজিমপুর', 'বাড্ডা', 'বংশাল', 'ক্যান্টনমেন্ট', 'চকবাজার', 'দারুস সালাম', 'ডেমরা', 'ধানমন্ডি', 'দোহার', 'গেন্ডারিয়া', 'গুলশান', 'হাজারীবাগ', 'জুরাইন', 'কদমতলী', 'কামরাঙ্গীরচর', 'কেরানীগঞ্জ', 'খিলক্ষেত', 'খিলগাঁও', 'কলাবাগান', 'কোতোয়ালি', 'লালবাগ', 'মিরপুর', 'মোহাম্মদপুর', 'মতিঝিল', 'নবাবগঞ্জ', 'নিউমার্কেট', 'পল্লবী', 'রমনা', 'সাভার', 'শাহবাগ', 'শ্যামপুর', 'শ্যামলী', 'সূত্রাপুর', 'তেজগাঁও', 'তুরাগ', 'উত্তরা', 'ওয়ারী'],
                'গাজীপুর': ['গাজীপুর সদর', 'কালীগঞ্জ', 'কাপাসিয়া', 'শ্রীপুর', 'কালিয়াকৈর'],
                'নারায়ণগঞ্জ': ['নারায়ণগঞ্জ সদর', 'বন্দর', 'আড়াইহাজার', 'রূপগঞ্জ', 'সোনারগাঁ', 'ফতুল্লা', 'সিদ্ধিরগঞ্জ'],
                'নরসিংদী': ['নরসিংদী সদর', 'বেলাবো', 'মনোহরদী', 'পলাশ', 'রায়পুরা', 'শিবপুর'],
                'ফরিদপুর': ['ফরিদপুর সদর', 'বোয়ালমারী', 'আলফাডাঙ্গা', 'মধুখালী', 'নগরকান্দা', 'সালথা', 'চরভদ্রাসন', 'সদরপুর', 'ভাঙ্গা'],
                'রাজবাড়ী': ['রাজবাড়ী সদর', 'গোয়ালন্দ', 'পাংশা', 'বালিয়াকান্দি', 'কালুখালী'],
                'গোপালগঞ্জ': ['গোপালগঞ্জ সদর', 'কাশিয়ানী', 'কোটালিপাড়া', 'মুকসুদপুর', 'টুঙ্গিপাড়া'],
                'মাদারীপুর': ['মাদারীপুর সদর', 'শিবচর', 'কালকিনি', 'রাজৈর'],
                'শরীয়তপুর': ['শরীয়তপুর সদর', 'নড়িয়া', 'জাজিরা', 'গোসাইরহাট', 'ভেদরগঞ্জ', 'ডামুড্যা'],
                'মানিকগঞ্জ': ['মানিকগঞ্জ সদর', 'সিংগাইর', 'শিবালয়', 'সাটুরিয়া', 'হরিরামপুর', 'ঘিওরা', 'দৌলতপুর'],
                'মুন্সিগঞ্জ': ['মুন্সিগঞ্জ সদর', 'লৌহজং', 'গজারিয়া', 'সিরাজদিখান', 'টংগিবাড়ী', 'শ্রীনগর'],
                'কিশোরগঞ্জ': ['কিশোরগঞ্জ সদর', 'হোসেনপুর', 'করিমগঞ্জ', 'তাড়াইল', 'পাকুন্দিয়া', 'কটিয়াদী', 'কুলিয়ারচর', 'ভৈরব', 'নিকলী', 'বাজিতপুর', 'ইটনা', 'মিঠামইন', 'অষ্টগ্রাম'],
                'টাঙ্গাইল': ['টাঙ্গাইল সদর', 'বাসাইল', 'কালিহাতী', 'ঘাটাইল', 'মির্জাপুর', 'নাগরপুর', 'মধুপুর', 'সখিপুর', 'দেলদুয়ার', 'ধনবাড়ী', 'গোপালপুর', 'ভূয়াপুর'],
                
                // চট্টগ্রাম বিভাগ
                'চট্টগ্রাম': ['চট্টগ্রাম সদর', 'আকবারশাহ', 'বাকলিয়া', 'বায়েজিদ', 'বন্দর', 'চান্দগাঁও', 'চকোরিয়া', 'ডবলমুরিং', 'ইপিজেড', 'হাটহাজারী', 'খুলশী', 'কোতোয়ালি', 'পাহাড়তলী', 'পাঁচলাইশ', 'পতেঙ্গা', 'রাউজান', 'সীতাকুন্ড', 'আনোয়ারা', 'বাঁশখালী', 'বোয়ালখালী', 'চন্দনাইশ', 'ফটিকছড়ি', 'লোহাগাড়া', 'মিরসরাই', 'পটিয়া', 'রাঙ্গুনিয়া', 'সন্দীপ', 'সাতকানিয়া'],
                'কুমিল্লা': ['কুমিল্লা সদর', 'বরুড়া', 'ব্রাহ্মণপাড়া', 'বুড়িচং', 'চান্দিনা', 'চৌদ্দগ্রাম', 'দাউদকান্দি', 'দেবিদ্বার', 'হোমনা', 'লাকসাম', 'মুরাদনগর', 'নাঙ্গলকোট', 'তিতাস', 'মেঘনা', 'মনোহরগঞ্জ'],
                'নোয়াখালী': ['নোয়াখালী সদর', 'বেগমগঞ্জ', 'চাটখিল', 'কোম্পানীগঞ্জ', 'হাতিয়া', 'সেনবাগ', 'সোনাইমুড়ী', 'সুবর্ণচর', 'কবিরহাট'],
                'ফেনী': ['ফেনী সদর', 'দাগনভূঞা', 'ছাগলনাইয়া', 'পরশুরাম', 'ফুলগাজী', 'সোনাগাজী'],
                'ব্রাহ্মণবাড়িয়া': ['ব্রাহ্মণবাড়িয়া সদর', 'কসবা', 'নবীনগর', 'সরাইল', 'নাসিরনগর', 'বাঞ্ছারামপুর', 'আশুগঞ্জ', 'আখাউড়া', 'বিজয়নগর'],
                'চাঁদপুর': ['চাঁদপুর সদর', 'হাইমচর', 'কচুয়া', 'ফরিদগঞ্জ', 'মতলব উত্তর', 'মতলব দক্ষিণ', 'হাজীগঞ্জ', 'শাহরাস্তি'],
                'লক্ষ্মীপুর': ['লক্ষ্মীপুর সদর', 'রায়পুর', 'রামগঞ্জ', 'রামগতি', 'কমলনগর'],
                'কক্সবাজার': ['কক্সবাজার সদর', 'চকোরিয়া', 'কুতুবদিয়া', 'মহেশখালী', 'পেকুয়া', 'রামু', 'টেকনাফ', 'উখিয়া'],
                'খাগড়াছড়ি': ['খাগড়াছড়ি সদর', 'দীঘিনালা', 'পানছড়ি', 'লক্ষ্মীছড়ি', 'মহালছড়ি', 'মানিকছড়ি', 'মাটিরাঙ্গা', 'রামগড়'],
                'রাঙ্গামাটি': ['রাঙ্গামাটি সদর', 'কাউখালী', 'কাপ্তাই', 'জুরাছড়ি', 'নানিয়ারচর', 'বরকল', 'বাঘাইছড়ি', 'বিলাইছড়ি', 'রাজস্থলী', 'লংগদু'],
                'বান্দরবান': ['বান্দরবান সদর', 'আলিকদম', 'থানচি', 'নাইক্ষ্যংছড়ি', 'রুমা', 'রোয়াংছড়ি', 'লামা'],
                
                // সিলেট বিভাগ
                'সিলেট': ['সিলেট সদর', 'বালাগঞ্জ', 'বিশ্বনাথ', 'কোম্পানিগঞ্জ', 'ফেঞ্চুগঞ্জ', 'গোলাপগঞ্জ', 'গোয়াইনঘাট', 'জকিগঞ্জ', 'কানাইঘাট', 'ওসমানীনগর', 'দক্ষিণ সুরমা', 'বিয়ানীবাজার'],
                'হবিগঞ্জ': ['হবিগঞ্জ সদর', 'নবীগঞ্জ', 'লাখাই', 'বানিয়াচং', 'আজমিরীগঞ্জ', 'চুনারুঘাট', 'বাহুবল', 'মাধবপুর'],
                'মৌলভীবাজার': ['মৌলভীবাজার সদর', 'বড়লেখা', 'জুড়ী', 'কুলাউড়া', 'রাজনগর', 'কমলগঞ্জ', 'শ্রীমঙ্গল'],
                'সুনামগঞ্জ': ['সুনামগঞ্জ সদর', 'দক্ষিণ সুনামগঞ্জ', 'বিশ্বম্ভরপুর', 'ছাতক', 'জগন্নাথপুর', 'তাহিরপুর', 'ধর্মপাশা', 'জামালগঞ্জ', 'শাল্লা', 'দিরাই', 'দোয়ারা বাজার'],
                
                // রাজশাহী বিভাগ
                'রাজশাহী': ['রাজশাহী সদর', 'বাঘা', 'বাগমারা', 'চারঘাট', 'দুর্গাপুর', 'গোদাগাড়ী', 'মোহনপুর', 'পবা', 'পুঠিয়া', 'তানোর'],
                'بগুড়া': ['বগুড়া সদর', 'আদমদীঘি', 'ধুনট', 'দুপচাঁচিয়া', 'গাবতলী', 'কাহালু', 'নন্দীগ্রাম', 'সারিয়াকান্দি', 'শাজাহানপুর', 'শেরপুর', 'শিবগঞ্জ', 'সোনাতলা'],
                'পাবনা': ['পাবনা সদর', 'আটঘরিয়া', 'ঈশ্বরদী', 'চাটমোহর', 'সাঁথিয়া', 'সুজানগর', 'ফরিদপুর', 'বেড়া', 'ভাঙ্গুড়া'],
                'সিরাজগঞ্জ': ['সিরাজগঞ্জ সদর', 'বেলকুচি', 'কামারখন্দ', 'কাজীপুর', 'রায়গঞ্জ', 'শাহজাদপুর', 'উল্লাপাড়া', 'চৌহালী', 'তাড়াশ'],
                'নাটোর': ['নাটোর সদর', 'বাগাতিপাড়া', 'বড়াইগ্রাম', 'গুরুদাসপুর', 'লালপুর', 'সিংড়া', 'নলডাঙ্গা'],
                'নওগাঁ': ['নওগাঁ সদর', 'নিয়ামতপুর', 'পোরশা', 'সাপাহার', 'ধামইরহাট', 'বদলগাছী', 'আত্রাই', 'রানীনগর', 'মহাদেবপুর', 'পত্নীতলা', 'মান্দা'],
                'জয়পুরহাট': ['জয়পুরহাট সদর', 'আক্কেলপুর', 'কালাই', 'ক্ষেতলাল', 'পাঁচবিবি'],
                'চাঁপাইনবাবগঞ্জ': ['চাঁপাইনবাবগঞ্জ সদর', 'গোমস্তাপুর', 'নাচোল', 'ভোলাহাট', 'শিবগঞ্জ'],
                
                // খুলনা বিভাগ
                'খুলনা': ['খুলনা সদর', 'কয়রা', 'ডুমুরিয়া', 'তেরখাদা', 'দাকোপ', 'দিঘলিয়া', 'পাইকগাছা', 'ফুলতলা', 'বটিয়াঘাটা'],
                'যশোর': ['যশোর সদর', 'অভয়নগর', 'কেশবপুর', 'চৌগাছা', 'ঝিকরগাছা', 'বাঘেরপাড়া', 'মণিরামপুর', 'শার্শা'],
                'ঝিনাইদহ': ['ঝিনাইদহ সদর', 'কালীগঞ্জ', 'কোটচাঁদপুর', 'মহেশপুর', 'শৈলকুপা', 'হরিণাকুণ্ডু'],
                'কুষ্টিয়া': ['কুষ্টিয়া সদর', 'কুমারখালী', 'খোকসা', 'দৌলতপুর', 'ভেড়ামারা', 'মিরপুর'],
                'মাগুরা': ['মাগুরা সদর', 'শ্রীপুর', 'শালিখা', 'মহম্মদপুর'],
                'মেহেরপুর': ['মেহেরপুর সদর', 'গাংনী', 'মুজিবনগর'],
                'নড়াইল': ['নড়াইল সদর', 'লোহাগড়া', 'কালিয়া'],
                'সাতক্ষীরা': ['সাতক্ষীরা সদর', 'আশাশুনি', 'দেবহাটা', 'কলারোয়া', 'কালীগঞ্জ', 'শ্যামনগর', 'তালা'],
                'বাগেরহাট': ['বাগেরহাট সদর', 'ফকিরহাট', 'মোল্লাহাট', 'চিতলমারী', 'কচুয়া', 'শরনখোলা', 'মোড়লগঞ্জ', 'মোংলা', 'রামপাল'],
                'চুয়াডাঙ্গা': ['চুয়াডাঙ্গা সদর', 'আলমডাঙ্গা', 'দামুড়হুদা', 'জীবননগর'],
                
                // বরিশাল বিভাগ
                'বরিশাল': ['বরিশাল সদর', 'আগৈলঝাড়া', 'বাকেরগঞ্জ', 'বানারীপাড়া', 'বাবুগঞ্জ', 'গৌরনদী', 'হিজলা', 'মেহেন্দিগঞ্জ', 'মুলাদী', 'উজিরপুর'],
                'ভোলা': ['ভোলা সদর', 'বোরহানউদ্দিন', 'চরফ্যাশন', 'দৌলতখান', 'লালমোহন', 'মনপুরা', 'তজুমদ্দিন'],
                'পটুয়াখালী': ['পটুয়াখালী সদর', 'বাউফল', 'দশমিনা', 'গলাচিপা', 'কলাপাড়া', 'মির্জাগঞ্জ', 'দুমকী', 'রাঙ্গাবালী'],
                'পিরোজপুর': ['পিরোজপুর সদর', 'কাউখালী', 'নাজিরপুর', 'ভাণ্ডারিয়া', 'মঠবাড়িয়া', 'নেছারাবাদ', 'ইন্দুরকানী'],
                'বরগুনা': ['বরগুনা সদর', 'আমতলী', 'বামনা', 'বেতাগী', 'পাথরঘাটা', 'তালতলী'],
                'ঝালকাঠি': ['ঝালকাঠি সদর', 'কাঠালিয়া', 'নলছিটি', 'রাজাপুর'],
                
                // রংপুর বিভাগ
                'রংপুর': ['রংপুর সদর', 'বদরগঞ্জ', 'গঙ্গাচড়া', 'কাউনিয়া', 'মিঠাপুকুর', 'পীরগঞ্জ', 'পীরগাছা', 'তারাগঞ্জ'],
                'দিনাজপুর': ['দিনাজপুর সদর', 'বিরামপুর', 'বীরগঞ্জ', 'বোচাগঞ্জ', 'ফুলবাড়ী', 'ঘোড়াঘাট', 'হাকিমপুর', 'কাহারোল', 'খানসামা', 'নবাবগঞ্জ', 'পাবর্তীপুর', 'চিরিরবন্দর'],
                'গাইবান্ধা': ['গাইবান্ধা সদর', 'ফুলছড়ি', 'গোবিন্দগঞ্জ', 'পলাশবাড়ী', 'সাদুল্লাপুর', 'সাঘাটা', 'সুন্দরগঞ্জ'],
                'কুড়িগ্রাম': ['কুড়িগ্রাম সদর', 'উলিপুর', 'চিলমারী', 'রৌমারী', 'রাজিবপুর', 'ভুরুঙ্গামারী', 'নাগেশ্বরী', 'ফুলবাড়ী', 'রাজারহাট'],
                'নীলফামারী': ['নীলফামারী সদর', 'ডোমার', 'জলঢাকা', 'কিশোরগঞ্জ', 'সৈয়দপুর', 'ডিমলা'],
                'লালমনিরহাট': ['লালমনিরহাট সদর', 'কালীগঞ্জ', 'আদিতমারী', 'হাতীবান্ধা', 'পাটগ্রাম'],
                'ঠাকুরগাঁও': ['ঠাকুরগাঁও সদর', 'পীরগঞ্জ', 'বালিয়াডাঙ্গী', 'রানীশংকৈল', 'হরিপুর'],
                'পঞ্চগড়': ['পঞ্চগড় সদর', 'তেতুলিয়া', 'দেবীগঞ্জ', 'আটোয়ারী', 'বোদা'],
                
                // ময়মনসিংহ বিভাগ
                'ময়মনসিংহ': ['ময়মনসিংহ সদর', 'ভালুকা', 'ধোবাউড়া', 'ফুলবাড়ীয়া', 'গফরগাঁও', 'গৌরীপুর', 'হালুয়াঘাট', 'ঈশ্বরগঞ্জ', 'মুক্তাগাছা', 'নান্দাইল', 'ফুলপুর', 'তারাকান্দা', 'ত্রিশাল'],
                'জামালপুর': ['জামালপুর সদর', 'বকশীগঞ্জ', 'দেওয়ানগঞ্জ', 'ইসলামপুর', 'মাদারগঞ্জ', 'মেলান্দহ', 'সরিষাবাড়ী'],
                'নেত্রকোণা': ['নেত্রকোণা সদর', 'আটপাড়া', 'বারহাট্টা', 'দুর্গাপুর', 'খালিয়াজুড়ী', 'কলমাকান্দা', 'কেন্দুয়া', 'মদন', 'মোহনগঞ্জ', 'পূর্বধলা'],
                'শেরপুর': ['শেরপুর সদর', 'নালিতাবাড়ী', 'শ্রীবরদী', 'ঝিনাইগাতী', 'নকলা']
            },
            getDistricts() {
                return this.districts[this.division] || [];
            },
            getThanas() {
                return this.thanas[this.district] || [];
            }
        }">��মলনগর'],
                'কক্সবাজার': ['কক্সবাজার সদর', 'চকোরিয়া', 'কুতুবদিয়া', 'মহেশখালী', 'পেকুয়া', 'রামু', 'টেকনাফ', 'উখিয়া'],
                'খাগড়াছড়ি': ['খাগড়াছড়ি সদর', 'দীঘিনালা', 'পানছড়ি', 'লক্ষ্মীছড়ি', 'মহালছড়ি', 'মানিকছড়ি', 'মাটিরাঙ্গা', 'রামগড়'],
                'রাঙ্গামাতি': ['রাঙ্গামাতি সদর', 'কাউখালী', 'কাপ্তাই', 'কানিয়া', 'জুরাছড়ি', 'নানিয়ারচর', 'বরকল', 'বাঘাইছড়ি', 'বিলাইছড়ি', 'রাজস্থলী'],
                'বান্দরবান': ['বান্দরবান সদর', 'আলিকদম', 'থানচি', 'নাইক্ষ্যংছড়ি', 'রুমা', 'রোয়াংছড়ি', 'লামা'],
                'সিলেট': ['সিলেট সদর', 'বালাগঞ্জ', 'বিশ্বনাথ', 'কোম্পানিগঞ্জ', 'ফেঞ্চুগঞ্জ', 'গোলাপগঞ্জ', 'গোয়াইনঘাট', 'জকিগঞ্জ', 'কানাইঘাট', 'ওসমানীনগর', 'দক্ষিণ সুরমা'],
                'হবিগঞ্জ': ['হবিগঞ্জ সদর', 'নবীগঞ্জ', 'লাখাই', 'বানিয়াচং', 'আজমিরীগঞ্জ', 'চুনারুঘাট', 'বাহুবল', 'মাধবপুর'],
                'মৌলভীবাজার': ['মৌলভীবাজার সদর', 'বড়লেখা', 'জুড়ী', 'কুলাউড়া', 'রাজনগর', 'কমলগঞ্জ', 'শ্রীমঙ্গল'],
                'সুনামগঞ্জ': ['সুনামগঞ্জ সদর', 'দক্ষিণ সুনামগঞ্জ', 'বিশ্বম্ভরপুর', 'ছাতক', 'জগন্নাতপুর', 'তাহirপুর', 'ধর্মপাশা', 'জামালগঞ্জ', 'শাল্লা', 'দিরাই', 'দোয়ারা বাজার'],
                'রাজশাহী': ['রাজশাহী সদর', 'বাঘা', 'বাগমারা', 'চারঘাট', 'দুর্গাপুর', 'গোদাগাড়ী', 'মোহনপুর', 'পবা', 'পুঠিয়া', 'তানোর'],
                'বগুড়া': ['বগুড়া সদর', 'আদমদীঘি', 'ধুনট', 'দুপচাঁচিয়া', 'গাবতলী', 'কাহালু', 'নন্দীগ্রাম', 'সারিয়াকান্দি', 'সদর', 'শাজাহানপুর', 'শেরপুর', 'শিবগঞ্জ', 'সোনাতলা'],
                'পাবনা': ['পাবনা সদর', 'আটঘরিয়া', 'ঈশ্বরদী', 'চাটমোহর', 'শাহজাদপুর', 'বেড়া', 'সাঁথিয়া', 'সুজানগর', 'ফরিদপুর'],
                'সিরাজগঞ্জ': ['সিরাজগঞ্জ সদর', 'বেলকুচি', 'কামারখন্দ', 'কাজীপুর', 'রায়গঞ্জ', 'শাহজাদপুর', 'উল্লাপাড়া', 'চৌহালী', 'তাড়াশ'],
                'নড়াইল': ['নড়াইল সদর', 'লোহাগড়া', 'কালিয়া'],
                'সাতক্ষীরা': ['সাতক্ষীরা সদর', 'আশাশুনি', 'দেবহাটা', 'কলারোয়া', 'কালীগঞ্জ', 'শ্যামনগর', 'তালা'],
                'বাগেরহাট': ['বাগেরহাট সদর', 'ফকিরহাট', 'মোল্লাহাট', 'চিতলমারী', 'কচুয়া', 'শরনখোলা', 'মোড়লগঞ্জ', 'মংলা', 'রামপাল'],
                'চুয়াডাঙ্গা': ['চুয়াডাঙ্গা সদর', 'আলমডাঙ্গা', 'দামুড়হুদা', 'জীবননগর'],
                'মেহেরপুর': ['মেহেরপুর সদর', 'গাংনী', 'মুজিবনগর'],
                'বরিশাল': ['বরিশাল সদর', 'আগৈলঝাড়া', 'বাকেরগঞ্জ', 'বানারীপাড়া', 'বাবুগঞ্জ', 'গৌরনদী', 'হিজলা', 'মেহেন্দিগঞ্জ', 'মুলাদী', 'উজিরপুর'],
                'ভোলা': ['ভোলা সদর', 'বোরহানউদ্দিন', 'চরফ্যাশন', 'দৌলতখান', 'লালমোহন', 'মনপুরা', 'তজুমদ্দিন'],
                'পটুয়াখালী': ['পটুয়াখালী সদর', 'বাউফল', 'দশমিনা', 'গলাচিপা', 'কলাপাড়া', 'মির্জাগঞ্জ', 'দুমকী', 'রাঙ্গাবালী'],
                'পিরোজপুর': ['পিরোজপুর সদর', 'কাউখালী', 'নাজিরপুর', 'ভাণ্ডারিয়া', 'মঠবাড়িয়া', 'নেছারাবাদ', 'ইন্দুরকানী'],
                'বরগুনা': ['বরগুনা সদর', 'আমতলী', 'বামনা', 'বেতাগী', 'পাথরঘাটা', 'তালতলী'],
                'ঝালকাঠি': ['ঝালকাঠি সদর', 'কাঠালিয়া', 'নলছিটি', 'রাজাপুর'],
                'রংপুর': ['রংপুর সদর', 'বদরগঞ্জ', 'গঙ্গাচড়া', 'কাউনিয়া', 'মিঠাপুকুর', 'পীরগঞ্জ', 'পীরগাছা', 'তারাগঞ্জ'],
                'দিনাজপুর': ['দিনাজপুর সদর', 'বিরামপুর', 'বীরগঞ্জ', 'বোচাগঞ্জ', 'ফুলবাড়ী', 'ঘোড়াঘাট', 'হাকিমপুর', 'কাহারোল', 'খানসামা', 'নবাবগঞ্জ', 'পাবর্তীপুর', 'চিরিরবন্দর'],
                'গাইবান্ধা': ['গাইবান্ধা সদর', 'ফুলছড়ি', 'গোবিন্দগঞ্জ', 'পলাশবাড়ী', 'সাদুল্লাপুর', 'সাঘাটা', 'সুন্দরগঞ্জ'],
                'কুড়িগ্রাম': ['কুড়িগ্রাম সদর', 'শামপুর', 'উলিপুর', 'চিলমারী', 'রৌমারী', 'রাজিবপুর', 'ভুরুঙ্গামারী', 'নাগেশ্বরী', 'ফুলবাড়ী'],
                'নীলফামারী': ['নীলফামারী সদর', 'ডোমার', 'জলঢাকা', 'কিশোরগঞ্জ', 'সৈয়দপুর', 'ডিমলা'],
                'লালমনিরহাট': ['লালমনিরহাট সদর', 'কালীগঞ্জ', 'আদিতমারী', 'হাতীবান্ধা', 'পাটগ্রাম'],
                'ঠাকুরগাঁও': ['ঠাকুরগাঁও সদর', 'পীরগঞ্জ', 'বালিয়াডাঙ্গী', 'রানীশংকৈল', 'হরিপুর'],
                'পঞ্চগড়': ['পঞ্চগড় সদর', 'তেতুলিয়া', 'দেবীগঞ্জ', 'আটোয়ারী', 'বোদা'],
                'ময়মনসিংহ': ['ময়মনসিংহ সদর', 'ভালুকা', 'ধোবাউড়া', 'ফুলবাড়ীয়া', 'গফরগাঁও', 'গৌরীপুর', 'হালুয়াঘাট', 'ঈশ্বরগঞ্জ', 'মুক্তাগাছা', 'নান্দাইল', 'ফুলপুর', 'তারাকান্দা', 'ত্রিশাল'],
                'জামালপুর': ['জামালপুর সদর', 'বকশীগঞ্জ', 'দেওয়ানগঞ্জ', 'ইসলামপুর', 'মাদারগঞ্জ', 'মেলান্দহ', 'সরিষাবাড়ী'],
                'নেত্রকোণা': ['নেত্রকোণা সদর', 'আটপাড়া', 'বারহাট্টা', 'দুর্গাপুর', 'খালিয়াজুড়ী', 'কলমাকান্দা', 'কেন্দুয়া', 'মদন', 'মোহনগঞ্জ', 'পূর্বধলা'],
                'শেরপুর': ['শেরপুর সদর', 'নালিতাবাড়ী', 'শ্রীবরদী', 'ঝিনাইগাতী', 'নকলা']
            },
            getDistricts() {
                return this.districts[this.division] || [];
            },
            getThanas() {
                return this.thanas[this.district] || [];
            }
          }">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Delivery Info -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h2 class="font-bold text-gray-900 text-base mb-5 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#FF6A00] text-white text-xs font-bold flex items-center justify-center">১</span>
                        ডেলিভারি তথ্য
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-1.5 block">পূর্ণ নাম *</label>
                            <input type="text" name="name" value="{{ optional(auth()->user())->name ?? old('name') }}" required class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none @error('name') border-red-400 @enderror" placeholder="আপনার নাম লিখুন">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-1.5 block">ফোন নম্বর *</label>
                            <input type="tel" name="phone" value="{{ optional(auth()->user())->phone ?? old('phone') }}" 
                                required placeholder="01XXXXXXXXX" maxlength="11"
                                oninput="const b = {'০':'0','১':'1','২':'2','৩':'3','৪':'4','৫':'5','৬':'6','৭':'7','৮':'8','৯':'9'}; this.value = this.value.split('').map(c => b[c] || (/[0-9]/.test(c) ? c : '')).join('')"
                                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none @error('phone') border-red-400 @enderror">
                            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Division -->
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-1.5 block">বিভাগ *</label>
                            <select name="division" x-model="division" @change="district = ''; thana = ''" required class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none bg-white">
                                <option value="">--- বিভাগ বেছে নিন ---</option>
                                <template x-for="div in divisions" :key="div">
                                    <option :value="div" x-text="div"></option>
                                </template>
                            </select>
                        </div>

                        <!-- District -->
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-1.5 block">জেলা *</label>
                            <select name="district" x-model="district" @change="thana = ''" required :disabled="!division" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none bg-white disabled:bg-gray-100 disabled:text-gray-400">
                                <option value="">--- জেলা বেছে নিন ---</option>
                                <template x-for="dist in getDistricts()" :key="dist">
                                    <option :value="dist" x-text="dist"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Thana/Upazila -->
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-1.5 block">থানা / উপজেলা</label>
                            <select name="thana" x-model="thana" :disabled="!district" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none bg-white disabled:bg-gray-100 disabled:text-gray-400">
                                <option value="">--- থানা বেছে নিন ---</option>
                                <template x-for="t in getThanas()" :key="t">
                                    <option :value="t" x-text="t"></option>
                                </template>
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="text-sm font-medium text-gray-700 mb-1.5 block">পূর্ণ ঠিকানা *</label>
                            <textarea name="address" required rows="3" placeholder="বাড়ি নং, রোড, এলাকা..." class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none resize-none @error('address') border-red-400 @enderror">{{ optional(auth()->user())->address ?? old('address') }}</textarea>
                            @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h2 class="font-bold text-gray-900 text-base mb-5 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-[#FF6A00] text-white text-xs font-bold flex items-center justify-center">২</span>
                        পেমেন্ট পদ্ধতি
                    </h2>

                    <div class="grid grid-cols-3 gap-3">
                        <label @click="selectMethod('cod')" class="relative flex flex-col items-center gap-2 p-3 rounded-xl border-2 cursor-pointer transition-all duration-200 hover:border-orange-200"
                            :class="method === 'cod' ? 'border-[#FF6A00] bg-orange-50' : 'border-gray-100 bg-white'">
                            <input type="radio" name="payment_method" value="cod" :checked="method === 'cod'" class="sr-only">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center" :class="method === 'cod' ? 'bg-[#FF6A00] text-white' : 'bg-gray-100 text-gray-400'">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <span class="text-[10px] sm:text-xs font-bold text-center leading-tight" :class="method === 'cod' ? 'text-orange-700' : 'text-gray-500'">ক্যাশ অন ডেলিভারি</span>
                        </label>

                        <label @click="selectMethod('bkash')" class="relative flex flex-col items-center gap-2 p-3 rounded-xl border-2 cursor-pointer transition-all duration-200 hover:border-pink-200"
                            :class="method === 'bkash' ? 'border-pink-500 bg-pink-50' : 'border-gray-100 bg-white'">
                            <input type="radio" name="payment_method" value="bkash" :checked="method === 'bkash'" class="sr-only">
                            <img src="https://www.logo.wine/a/logo/BKash/BKash-Icon-Logo.wine.svg" class="w-10 h-10 object-contain" alt="bKash">
                            <span class="text-[10px] sm:text-xs font-bold" :class="method === 'bkash' ? 'text-pink-700' : 'text-gray-500'">বিকাশ</span>
                        </label>

                        <label @click="selectMethod('nagad')" class="relative flex flex-col items-center gap-2 p-3 rounded-xl border-2 cursor-pointer transition-all duration-200 hover:border-red-200"
                            :class="method === 'nagad' ? 'border-red-500 bg-red-50' : 'border-gray-100 bg-white'">
                            <input type="radio" name="payment_method" value="nagad" :checked="method === 'nagad'" class="sr-only">
                            <img src="https://download.logo.wine/logo/Nagad/Nagad-Vertical-Logo.wine.png" class="w-10 h-10 object-contain" alt="Nagad">
                            <span class="text-[10px] sm:text-xs font-bold" :class="method === 'nagad' ? 'text-red-700' : 'text-gray-500'">নগদ</span>
                        </label>
                    </div>

                    <!-- Payment Instructions -->
                    <template x-if="(method === 'bkash' || method === 'nagad') && !isGatewayConfigured">
                        <div x-transition class="mt-6 p-5 rounded-2xl bg-red-50 border border-red-100 shadow-sm animate-bounce-subtle">
                            <div class="flex items-center flex-col text-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-red-900 text-sm mb-1 uppercase tracking-tight">পেমেন্ট গেটওয়ে বর্তমানে বন্ধ আছে</h3>
                                    <p class="text-red-700 text-[11px] leading-relaxed">
                                        দুঃখিত, বিকাশ এবং নগদ পেমেন্ট এখন বন্ধ রয়েছে। ৩ সেকেন্ড পর আপনাকে স্বয়ংক্রিয়ভাবে <span class="font-bold underline">ক্যাশ অন ডেলিভারি</span> মোডে নিয়ে যাওয়া হবে।
                                    </p>
                                </div>
                                <button @click="method = 'cod'" type="button" class="mt-2 text-[10px] font-bold bg-white text-red-600 px-4 py-2 rounded-lg border border-red-200 hover:bg-red-50 transition">
                                    এখনই ক্যাশ অন ডেলিভারি সিলেক্ট করুন
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-lg p-6 sticky top-24">
                    <h2 class="font-bold text-gray-900 text-base mb-5">অর্ডার সারসংক্ষেপ</h2>
                    
                    <div class="space-y-4 mb-6 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($cartItems as $item)
                        <div class="flex gap-3">
                            <div class="relative flex-shrink-0">
                                <img src="{{ $item['product']->thumbnail_url }}" class="w-14 h-14 rounded-xl object-cover border border-gray-50" alt="{{ $item['product']->name }}">
                                <span class="absolute -top-2 -right-2 bg-gray-900 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center">{{ $item['quantity'] }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs font-bold text-gray-800 truncate mb-1">{{ $item['product']->name }}</h4>
                                <p class="text-[10px] text-gray-500 line-through">Tk{{ number_format($item['product']->price * 1.2) }}</p>
                                <p class="text-xs font-black text-[#FF6A00]">Tk{{ number_format($item['product']->effective_price) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Coupon Code -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <div class="flex gap-2">
                            <input type="text" x-model="coupon" :disabled="applied" placeholder="কুপন কোড (যদি থাকে)" 
                                class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-orange-400 focus:outline-none disabled:bg-gray-100">
                            
                            <button type="button" x-show="!applied" @click="
                                    if(!coupon) return;
                                    loading = true;
                                    fetch('/apply-coupon', {
                                        method: 'POST',
                                        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content},
                                        body: JSON.stringify({coupon_code: coupon})
                                    }).then(r => r.json()).then(d => {
                                        loading = false;
                                        message = d.message;
                                        if(d.success) {
                                            applied = true;
                                            discount = d.discount;
                                            subtotal = d.subtotal;
                                            shipping = d.shipping;
                                            total = d.total;
                                            document.getElementById('display-discount-row').classList.remove('hidden');
                                            document.getElementById('display-discount').innerText = '-Tk' + d.discount.toLocaleString();
                                            document.getElementById('display-shipping').innerText = (d.shipping == 0 ? 'ফ্রি ডেলিভারি' : 'Tk' + d.shipping.toLocaleString());
                                            if(d.shipping == 0) document.getElementById('display-shipping').classList.add('text-green-600', 'font-semibold');
                                            document.getElementById('display-total').innerText = 'Tk' + d.total.toLocaleString();
                                        }
                                    });
                                "
                                :disabled="loading"
                                class="bg-[#FF6A00] text-white px-4 py-2.5 rounded-xl text-xs font-bold hover:bg-[#FF7A1A] transition disabled:opacity-50"
                            >
                                অ্যাপ্লাই
                            </button>

                            <button type="button" x-show="applied" @click="
                                    loading = true;
                                    fetch('/remove-coupon', {
                                        method: 'POST',
                                        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content}
                                    }).then(r => r.json()).then(d => {
                                        loading = false;
                                        applied = false;
                                        coupon = '';
                                        message = d.message;
                                        discount = 0;
                                        subtotal = d.subtotal;
                                        shipping = d.shipping;
                                        total = d.total;
                                        document.getElementById('display-discount-row').classList.add('hidden');
                                        document.getElementById('display-shipping').innerText = 'Tk' + d.shipping.toLocaleString();
                                        document.getElementById('display-shipping').classList.remove('text-green-600', 'font-semibold');
                                        document.getElementById('display-total').innerText = 'Tk' + d.total.toLocaleString();
                                    });
                                "
                                class="bg-red-50 text-red-500 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-red-100 transition flex-shrink-0 border border-red-100"
                            >
                                মুছে ফেলুন
                            </button>
                        </div>
                        <p class="text-xs mt-1.5" :class="applied ? 'text-green-600' : 'text-red-500'" x-show="message" x-text="message"></p>
                    </div>

                    <hr class="border-gray-100 mb-4">
                    <div class="space-y-2.5">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>সাবটোটাল</span>
                            <span>Tk{{ number_format($subtotal) }}</span>
                        </div>
                        
                        <div id="display-discount-row" class="flex justify-between text-sm text-green-600 {{ session('promo_discount') > 0 ? '' : 'hidden' }}">
                            <span>ডিসকাউন্ট</span>
                            <span id="display-discount">-Tk{{ number_format(session('promo_discount', 0)) }}</span>
                        </div>

                        <div class="flex justify-between text-sm text-gray-600">
                            <span>ডেলিভারি চার্জ</span>
                            <span id="display-shipping" class="{{ $shipping == 0 ? 'text-green-600 font-semibold' : '' }}">
                                @if($shipping == 0) ফ্রি ডেলিভারি @else Tk{{ number_format($shipping) }} @endif
                            </span>
                        </div>
                        <hr class="border-gray-100">
                        <div class="flex justify-between font-black text-lg">
                            <span>মোট</span>
                            <span id="display-total" class="text-[#FF6A00]">Tk{{ number_format($total) }}</span>
                        </div>
                    </div>

                    <button type="submit" 
                        class="w-full py-4 rounded-2xl text-sm font-black flex items-center justify-center gap-2 mt-6 transition-all duration-300"
                        :disabled="(method === 'bkash' || method === 'nagad') && !isGatewayConfigured"
                        :class="(method === 'bkash' || method === 'nagad') && !isGatewayConfigured ? 'bg-orange-100 text-orange-300 cursor-not-allowed shadow-none' : 'bg-[#FF6A00] text-white hover:bg-[#FF7A1A] shadow-xl'"
                    >
                        <span x-text="(method === 'bkash' || method === 'nagad') && !isGatewayConfigured ? 'পেমেন্ট বন্ধ আছে' : 'অর্ডার সম্পন্ন করুন'"></span>
                    </button>
                    <p class="text-xs text-gray-400 text-center mt-3">অর্ডার বাটনে ক্লিক করলে আপনি শর্তাবলীতে সম্মত হচ্ছেন।</p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        let isValid = true;
        let firstError = null;
        const requiredFields = this.querySelectorAll('[required]');
        
        // Clear previous errors
        this.querySelectorAll('.error-msg').forEach(el => el.remove());
        this.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500', 'ring-2', 'ring-red-100'));

        requiredFields.forEach(field => {
            const val = field.value.trim();
            let isFieldValid = true;
            let errorMessage = '';

            if (!val) {
                isFieldValid = false;
                if (field.name === 'name') errorMessage = 'অনুগ্রহ করে আপনার পূর্ণ নাম লিখুন';
                else if (field.name === 'phone') errorMessage = 'ফোন নম্বর প্রদান করা আবশ্যক';
                else if (field.name === 'division') errorMessage = 'বিভাগ নির্বাচন করুন';
                else if (field.name === 'district') errorMessage = 'জেলা নির্বাচন করুন';
                else if (field.name === 'address') errorMessage = 'অনুগ্রহ করে আপনার ঠিকানা লিখুন';
            } else if (field.name === 'phone') {
                const bdPhoneRegex = /^01[3-9]\d{8}$/;
                if (!bdPhoneRegex.test(val)) {
                    isFieldValid = false;
                    errorMessage = 'সঠিক ফোন নম্বর দিন (১১ ডিজিট, উদাহরণ: 01XXXXXXXXX)';
                }
            }

            if (!isFieldValid) {
                isValid = false;
                
                // Add error styling
                field.classList.add('border-red-500', 'ring-2', 'ring-red-100');
                
                // Add error message
                const msg = document.createElement('p');
                msg.className = 'text-red-500 text-xs mt-1.5 font-medium error-msg ml-1 flex items-center gap-1';
                msg.innerHTML = `<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg> ${errorMessage}`;
                field.parentNode.appendChild(msg);
                
                if (!firstError) firstError = field;
            }
        });

        if (!isValid) {
            e.preventDefault();
            if (firstError) {
                const yOffset = -120; // Extra offset for sticky header
                const y = firstError.getBoundingClientRect().top + window.pageYOffset + yOffset;
                window.scrollTo({top: y, behavior: 'smooth'});
                firstError.focus();
            }
        }
    });

    // Remove red border on input
    document.querySelectorAll('#checkout-form [required]').forEach(field => {
        field.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('border-red-500', 'ring-2', 'ring-red-100');
                const errorMsg = this.parentNode.querySelector('.error-msg');
                if (errorMsg) errorMsg.remove();
            }
        });
        field.addEventListener('change', function() {
            if (this.value.trim()) {
                this.classList.remove('border-red-500', 'ring-2', 'ring-red-100');
                const errorMsg = this.parentNode.querySelector('.error-msg');
                if (errorMsg) errorMsg.remove();
            }
        });
    });
</script>

@if(isset($siteSettings) && $siteSettings->facebook_pixel_id)
<script>
    fbq('track', 'InitiateCheckout', {
        value: {{ $total }},
        currency: 'BDT',
        num_items: {{ count($cartItems) }}
    });
</script>
@endif
@endpush
