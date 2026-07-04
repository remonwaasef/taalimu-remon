<script>
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab');
    if (tab) {
        let tabEl;
        if (tab === 'coupons') {
            tabEl = document.querySelector('#coupons-tab');
        } else if (tab === 'features') {
            tabEl = document.querySelector('#features-tab');
        } else if (tab === 'plans') {
            tabEl = document.querySelector('#plans-tab');
        }
        
        if (tabEl) {
            const bootstrapTab = new bootstrap.Tab(tabEl);
            bootstrapTab.show();
        }
    }

    @if(isset($errors) && $errors->any())
        var addCouponModal = new bootstrap.Modal(document.getElementById('addCouponModal'));
        addCouponModal.show();
        
        // Also switch to coupon tab
        var couponTab = new bootstrap.Tab(document.querySelector('#coupons-tab'));
        couponTab.show();
    @endif
});
</script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
