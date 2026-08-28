{{-- Footer — Redesigned Reference --}}
<footer class="footer">
  <div class="container footer-grid">
    <div class="footer-brand">
      <a class="brand" href="{{ route('home') }}"><span class="brand-mark">+</span><span>Taalimu</span></a>
      <p>نظام تشغيل متكامل لإدارة المراكز والمؤسسات التعليمية.</p>
    </div>
    <div><h4>المنتج</h4><a href="#features">المميزات</a><a href="#product">المنصة</a><a href="#pricing">الأسعار</a></div>
    <div><h4>المساعدة</h4><a href="#faq">الأسئلة الشائعة</a><a href="#">تواصل معنا</a><a href="#">الدعم</a></div>
    <div><h4>الشركة</h4><a href="#">من نحن</a><a href="#">المدونة</a><a href="#">تحديثات المنتج</a></div>
    <div class="newsletter">
      <h4>اشترك في التحديثات</h4>
      <p>نصائح وأخبار تساعدك على إدارة مركزك بشكل أفضل.</p>
      <form class="newsletter-form" action="#" method="POST">
        @csrf
        <input type="email" placeholder="بريدك الإلكتروني" aria-label="بريدك الإلكتروني" required>
        <button class="btn btn-primary" type="submit">اشتراك</button>
      </form>
    </div>
  </div>
  <div class="container copyright">© {{ date('Y') }} Taalimu — جميع الحقوق محفوظة</div>
</footer>
