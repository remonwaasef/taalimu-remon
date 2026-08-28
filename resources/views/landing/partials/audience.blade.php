{{-- Portals Section matching user reference specification --}}
<section id="portals" class="portals">
  <div class="container">
    <p class="section-kicker">بوابات مخصصة لكل مستخدم</p>
    <div class="portal-grid">
      <article class="portal-card parent">
        <img src="{{ asset('images/portals/parent_v2.png') }}" class="card-img" alt="بوابة ولي الأمر" loading="lazy">
        <div class="portal-content">
          <div>
            <span class="icon">👥</span>
            <h3>بوابة ولي الأمر</h3>
            <p>تابع تقدم ابنك بكل سهولة</p>
            <ul>
              <li>متابعة الحضور والغياب</li>
              <li>الاطلاع على الدرجات</li>
              <li>التواصل مع المدرسة والمعلمين</li>
              <li>استلام الإشعارات والتنبيهات</li>
            </ul>
          </div>
          <a href="{{ route('login.portal') }}">دخول ولي الأمر ←</a>
        </div>
      </article>

      <article class="portal-card student">
        <img src="{{ asset('images/portals/student_v2.png') }}" class="card-img" alt="بوابة الطالب" loading="lazy">
        <div class="portal-content">
          <div>
            <span class="icon">▣</span>
            <h3>بوابة الطالب</h3>
            <p>كل دراستك في مكان واحد</p>
            <ul>
              <li>عرض الجدول الدراسي</li>
              <li>الحضور والغياب</li>
              <li>الاطلاع على الدرجات والتقييمات</li>
              <li>التواصل مع المعلمين</li>
            </ul>
          </div>
          <a href="{{ route('login.portal') }}">دخول الطالب ←</a>
        </div>
      </article>

      <article class="portal-card teacher">
        <img src="{{ asset('images/portals/teacher_v2.png') }}" class="card-img" alt="بوابة المدرس" loading="lazy">
        <div class="portal-content">
          <div>
            <span class="icon">🎓</span>
            <h3>بوابة المدرس</h3>
            <p>كل ما تحتاجه لإدارة حصصك</p>
            <ul>
              <li>تسجيل الحضور والغياب</li>
              <li>إدارة الدرجات والتقييمات</li>
              <li>رفع المواد والملفات</li>
              <li>التواصل مع الطلاب وأولياء الأمور</li>
            </ul>
          </div>
          <a href="{{ route('login.portal') }}">دخول المدرس ←</a>
        </div>
      </article>
    </div>
  </div>
</section>