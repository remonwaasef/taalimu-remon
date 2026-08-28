{{-- Footer Section matching user reference specification --}}
<footer id="about" class="footer">
  <div class="container footer-grid">
    <div>
      <a class="brand" href="{{ route('home') }}"><span class="brand-mark">✦</span><span>Taalimu</span></a>
      <p>منصة متكاملة لإدارة المؤسسات التعليمية، تجمع كل ما تحتاجه في منصة واحدة.</p>
      <div class="socials">
        <span><i class="fab fa-facebook-f"></i></span>
        <span><i class="fab fa-instagram"></i></span>
        <span><i class="fab fa-youtube"></i></span>
        <span><i class="fab fa-linkedin-in"></i></span>
      </div>
    </div>
    <div>
      <h4>الشركة</h4>
      <a href="#portals">من نحن</a>
      <a href="#">وظائف</a>
      <a href="#">تواصل معنا</a>
    </div>
    <div>
      <h4>المنتج</h4>
      <a href="#capabilities">المميزات</a>
      <a href="#capabilities">الأسعار</a>
      <a href="#">التحديثات</a>
    </div>
    <div>
      <h4>الموارد</h4>
      <a href="#">المدونة</a>
      <a href="#">الأدلة</a>
      <a href="#">الأسئلة الشائعة</a>
    </div>
    <div>
      <h4>اشترك في نشرتنا البريدية</h4>
      <p>احصل على آخر التحديثات والنصائح الخاصة بإدارة المؤسسات التعليمية.</p>
      <form action="#" method="POST">
        @csrf
        <input type="email" placeholder="أدخل بريدك الإلكتروني" required>
        <button type="submit" class="btn btn-primary">اشترك الآن</button>
      </form>
    </div>
  </div>
  <div class="container copyright">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
      <span>© {{ date('Y') }} Taalimu — جميع الحقوق محفوظة</span>
      <div style="display:flex; gap:15px;">
        <a href="{{ route('privacy') }}" style="color:inherit; font-size:inherit;">سياسة الخصوصية</a>
        <a href="{{ route('terms') }}" style="color:inherit; font-size:inherit;">الشروط والأحكام</a>
      </div>
    </div>
  </div>
</footer>
