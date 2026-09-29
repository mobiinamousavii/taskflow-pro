 @extends('layouts.app')

 @section('title', 'TaskFlow | مدیریت هوشمند پروژه‌ها و تسک‌ها')

 @push('styles')
 <style>
     /* Hero Section */
     .hero-section {
         padding: 90px 20px 60px;
         text-align: center;
         max-width: 900px;
         margin: 0 auto;
     }

     .hero-badge {
         display: inline-block;
         padding: 6px 16px;
         background: #e0e7ff;
         color: #4338ca;
         border-radius: 20px;
         font-size: 0.85rem;
         font-weight: 700;
         margin-bottom: 20px;
     }

     .hero-title {
         font-size: 2.8rem;
         font-weight: 900;
         color: #0f172a;
         line-height: 1.3;
         margin-bottom: 20px;
     }

     .hero-title span {
         color: #6366f1;
     }

     .hero-subtitle {
         font-size: 1.15rem;
         color: #64748b;
         line-height: 1.7;
         margin-bottom: 36px;
     }

     .hero-buttons {
         display: flex;
         justify-content: center;
         gap: 16px;
         margin-bottom: 50px;
     }

     .hero-buttons .btn {
         padding: 12px 28px;
         font-size: 1rem;
     }

     /* بخش ویژگی‌ها (Features) شبیه Jira و ClickUp */
     .features-section {
         max-width: 1100px;
         margin: 40px auto 80px;
         padding: 0 20px;
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
         gap: 24px;
     }

     .feature-card {
         background: #ffffff;
         border: 1px solid #e2e8f0;
         border-radius: 16px;
         padding: 32px 24px;
         transition: all 0.3s ease;
     }

     .feature-card:hover {
         border-color: #6366f1;
         box-shadow: 0 12px 24px -6px rgba(99, 102, 241, 0.12);
         transform: translateY(-4px);
     }

     .feature-icon {
         width: 48px;
         height: 48px;
         background: #f1f5f9;
         border-radius: 12px;
         display: flex;
         align-items: center;
         justify-content: center;
         color: #6366f1;
         margin-bottom: 20px;
     }

     .feature-card h3 {
         font-size: 1.2rem;
         font-weight: 700;
         color: #1e293b;
         margin-bottom: 10px;
     }

     .feature-card p {
         font-size: 0.92rem;
         color: #64748b;
         line-height: 1.6;
     }

     footer {
         text-align: center;
         padding: 30px;
         border-top: 1px solid #e2e8f0;
         color: #94a3b8;
         font-size: 0.85rem;
         background: #ffffff;
     }
 </style>
 @endpush

 @section('content')

 <!-- بخش اصلی معرفی (Hero) -->
 <section class="hero-section">
     <span class="hero-badge">یک برنامه برای جایگزینی همه آنها</span>

     <h1 class="hero-title">
         تمام پروژه‌ها و تسک‌های تیم، <br>
         <span>در یک فضای کاری متمرکز</span>
     </h1>

     <p class="hero-subtitle">
         مدیریت آسان کارهای تیمی با بورد کانبان، تعریف ورک‌استیس‌ها و پیگیری پیشرفت پروژه‌ها .
     </p>
 </section>

 <!-- بخش امکانات کلیدی -->
 <section id="features" class="features-section">

     <!-- کارت ۱ -->
     <div class="feature-card">
         <div class="feature-icon">
             <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 01-2-2h-2a2 2 0 01-2 2"></path>
             </svg>
         </div>
         <h3>بوردهای کانبان (Kanban)</h3>
         <p>تسک‌های پروژه خود را در ستون‌های «در صف انجام»، «در حال انجام» و «انجام‌شده» جابه‌جا و مدیریت کنید.</p>
     </div>

     <!-- کارت ۲ -->
     <div class="feature-card">
         <div class="feature-icon">
             <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
             </svg>
         </div>
         <h3>ورک‌استیس‌های مجزا</h3>
         <p>برای هر تیم یا کلاینت یک ورک‌استیس جداگانه بسازید و سطوح دسترسی اعضا را به راحتی تعیین کنید.</p>
     </div>

     <!-- کارت ۳ -->
     <div class="feature-card">
         <div class="feature-icon">
             <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
             </svg>
         </div>
         <h3>زمان‌بندی و اولویت‌بندی</h3>
         <p>برای تسک‌ها ددلاین (تاریخ تحویل) و میزان اولویت تعیین کنید تا هیچ کاری از قلم نیفتد.</p>
     </div>

 </section>

 <footer>
     توسعه داده شده با لاراول &copy; {{ date('Y') }} TaskFlow
 </footer>

 @endsection