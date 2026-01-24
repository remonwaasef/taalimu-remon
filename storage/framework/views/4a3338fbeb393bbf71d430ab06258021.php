<footer class="bg-secondary border-t border-border/50" style="padding: 2rem 0;">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Main Footer Content -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; margin-bottom: 1.5rem;">
            <!-- Brand Column -->
            <div>
                <a href="<?php echo e(route('home')); ?>" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                    <div class="w-8 h-8 rounded-lg gradient-hero flex items-center justify-center">
                        <span class="text-primary-foreground font-bold"><?php echo e(substr(\App\Models\SiteSetting::get('site_name', config('app.name')), 0, 1)); ?></span>
                    </div>
                    <span style="font-weight: bold; font-size: 1.125rem; color: white;"><?php echo e(\App\Models\SiteSetting::get('site_name', config('app.name'))); ?></span>
                </a>
                <p style="color: rgba(255, 255, 255, 0.7); font-size: 0.875rem;">
                    <?php echo e(\App\Models\SiteSetting::get('site_description', __('landing.hero.subtitle'))); ?>

                </p>
            </div>

            <!-- Product Links -->
            <div>
                <h5 style="font-weight: 600; color: white; font-size: 0.875rem; margin-bottom: 0.75rem;"><?php echo e(__('landing.footer.product.title')); ?></h5>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="margin-bottom: 0.5rem;">
                        <a href="#features" style="color: rgba(255, 255, 255, 0.7); font-size: 0.75rem; text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='rgb(0, 255, 255)'" onmouseout="this.style.color='rgba(255, 255, 255, 0.7)'">
                            <?php echo e(__('landing.footer.product.features')); ?>

                        </a>
                    </li>
                    <li style="margin-bottom: 0.5rem;">
                        <a href="#pricing" style="color: rgba(255, 255, 255, 0.7); font-size: 0.75rem; text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='rgb(0, 255, 255)'" onmouseout="this.style.color='rgba(255, 255, 255, 0.7)'">
                            <?php echo e(__('landing.footer.product.pricing')); ?>

                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Company Links -->
            <div>
                <h5 style="font-weight: 600; color: white; font-size: 0.875rem; margin-bottom: 0.75rem;"><?php echo e(__('landing.footer.company.title')); ?></h5>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="margin-bottom: 0.5rem;">
                        <a href="#" style="color: rgba(255, 255, 255, 0.7); font-size: 0.75rem; text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='rgb(0, 255, 255)'" onmouseout="this.style.color='rgba(255, 255, 255, 0.7)'">
                            <?php echo e(__('landing.footer.company.about')); ?>

                        </a>
                    </li>
                    <li style="margin-bottom: 0.5rem;">
                        <a href="#" style="color: rgba(255, 255, 255, 0.7); font-size: 0.75rem; text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='rgb(0, 255, 255)'" onmouseout="this.style.color='rgba(255, 255, 255, 0.7)'">
                            <?php echo e(__('landing.footer.company.contact')); ?>

                        </a>
                    </li>
                </ul>
            </div>

            <!-- Legal Links -->
            <div>
                <h5 style="font-weight: 600; color: white; font-size: 0.875rem; margin-bottom: 0.75rem;"><?php echo e(__('landing.footer.legal.title')); ?></h5>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="margin-bottom: 0.5rem;">
                        <a href="<?php echo e(route('privacy')); ?>" style="color: rgba(255, 255, 255, 0.7); font-size: 0.75rem; text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='rgb(0, 255, 255)'" onmouseout="this.style.color='rgba(255, 255, 255, 0.7)'">
                            <?php echo e(__('landing.footer.legal.privacy')); ?>

                        </a>
                    </li>
                    <li style="margin-bottom: 0.5rem;">
                        <a href="<?php echo e(route('terms')); ?>" style="color: rgba(255, 255, 255, 0.7); font-size: 0.75rem; text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='rgb(0, 255, 255)'" onmouseout="this.style.color='rgba(255, 255, 255, 0.7)'">
                            <?php echo e(__('landing.footer.legal.terms')); ?>

                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div style="border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 1rem;">
            <div style="display: flex; flex-direction: column; align-items: center; justify-content: space-between; gap: 0.75rem; font-size: 0.75rem; color: rgba(255, 255, 255, 0.5);">
                <p style="margin: 0;"><?php echo e(__('landing.footer.copyright')); ?></p>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <a href="<?php echo e(route('cookies')); ?>" style="color: rgba(255, 255, 255, 0.5); text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='rgb(0, 255, 255)'" onmouseout="this.style.color='rgba(255, 255, 255, 0.5)'"><?php echo e(__('landing.footer.legal.cookie')); ?></a>
                    <button onclick="openCookieSettings()" style="color: rgba(255, 255, 255, 0.5); background: none; border: none; cursor: pointer; text-decoration: none; transition: color 0.3s; font-size: 0.75rem;" onmouseover="this.style.color='rgb(0, 255, 255)'" onmouseout="this.style.color='rgba(255, 255, 255, 0.5)'"><?php echo e(__('gdpr.banner.settings')); ?></button>
                    <a href="#" style="color: rgba(255, 255, 255, 0.5); text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='rgb(0, 255, 255)'" onmouseout="this.style.color='rgba(255, 255, 255, 0.5)'"><?php echo e(__('landing.footer.social.twitter')); ?></a>
                    <a href="#" style="color: rgba(255, 255, 255, 0.5); text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='rgb(0, 255, 255)'" onmouseout="this.style.color='rgba(255, 255, 255, 0.5)'"><?php echo e(__('landing.footer.social.linkedin')); ?></a>
                    <a href="#" style="color: rgba(255, 255, 255, 0.5); text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='rgb(0, 255, 255)'" onmouseout="this.style.color='rgba(255, 255, 255, 0.5)'"><?php echo e(__('landing.footer.social.facebook')); ?></a>
                </div>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH D:\new project\antigravty\edu\edu\resources\views/landing/partials/footer.blade.php ENDPATH**/ ?>