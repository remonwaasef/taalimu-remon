{{-- Portals Section matching C:\Users\new\Downloads\Taalimu_Landing_Page_HTML_CSS_Fixed --}}
<section id="portals" class="portals">
  <div class="container">
    <p class="section-kicker">بوابات مخصصة لكل مستخدم</p>
    <div class="portal-grid">
      <article class="portal-card parent">
        <div class="portal-content">
          <span class="icon">👥</span>
          <h3>بوابة ولي الأمر</h3>
          <p>تابع تقدم ابنك بكل سهولة</p>
          <ul>
            <li>متابعة الحضور والغياب</li>
            <li>الاطلاع على الدرجات</li>
            <li>التواصل مع المدرسة والمعلمين</li>
            <li>استلام الإشعارات والتنبيهات</li>
          </ul>
          <a href="{{ route('login.portal') }}">دخول ولي الأمر ←</a>
        </div>
      </article>

      <article class="portal-card student">
        <div class="portal-content">
          <span class="icon">▣</span>
          <h3>بوابة الطالب</h3>
          <p>كل دراستك في مكان واحد</p>
          <ul>
            <li>عرض الجدول الدراسي</li>
            <li>الحضور والغياب</li>
            <li>الاطلاع على الدرجات والتقييمات</li>
            <li>التواصل مع المعلمين</li>
          </ul>
          <a href="{{ route('login.portal') }}">دخول الطالب ←</a>
        </div>
      </article>

      <article class="portal-card teacher">
        <div class="portal-content">
          <span class="icon">🎓</span>
          <h3>بوابة المدرس</h3>
          <p>كل ما تحتاجه لإدارة حصصك</p>
          <ul>
            <li>تسجيل الحضور والغياب</li>
            <li>إدارة الدرجات والتقييمات</li>
            <li>رفع المواد والملفات</li>
            <li>التواصل مع الطلاب وأولياء الأمور</li>
          </ul>
          <a href="{{ route('login.portal') }}">دخول المدرس ←</a>
        </div>
      </article>
    </div>
  </div>
</section>