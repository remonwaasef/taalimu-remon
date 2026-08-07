                        <div class="tab-pane fade <?php echo e($activeTab == 'general' ? 'show active' : ''); ?>" id="general" role="tabpanel" aria-labelledby="general-tab">
                            <form action="<?php echo e(route('center.settings.update', ['tenant' => $tenant->domain ?? 'center'])); ?>" method="POST" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <div class="row mb-4">
                                <!-- Logo -->
                                <div class="col-md-6 text-center border-end">
                                    <div class="position-relative d-inline-block">
                                        <div class="avatar-xl rounded-circle bg-light d-flex align-items-center justify-content-center text-primary fw-bold display-4 shadow-sm overflow-hidden" style="width: 100px; height: 100px; font-size: 2.5rem;">
                                            <?php if($tenant->logo): ?>
                                                <img src="<?php echo e(asset('storage/' . $tenant->logo)); ?>" class="w-100 h-100 object-fit-contain p-2">
                                            <?php else: ?>
                                                <?php echo e(substr($tenant->name, 0, 1)); ?>

                                            <?php endif; ?>
                                        </div>
                                        <label for="logo" class="position-absolute bottom-0 end-0 bg-white shadow-sm p-2 rounded-circle cursor-pointer border">
                                            <i class="fas fa-camera text-muted small"></i>
                                        </label>
                                        <input type="file" id="logo" name="logo" class="d-none" accept="image/*">
                                    </div>
                                    <p class="text-muted small mt-2 mb-0 fw-bold"><?php echo e(__('center::settings.general.logo')); ?></p>
                                </div>
                                <!-- Favicon -->
                                <div class="col-md-6 text-center">
                                    <div class="position-relative d-inline-block">
                                        <div class="avatar-lg rounded bg-light d-flex align-items-center justify-content-center text-primary shadow-sm overflow-hidden" style="width: 60px; height: 60px; margin-top: 20px;">
                                            <?php if($tenant->favicon): ?>
                                                <img src="<?php echo e(asset('storage/' . $tenant->favicon)); ?>" class="w-100 h-100 object-fit-contain p-2">
                                            <?php else: ?>
                                                <i class="fas fa-globe fs-2"></i>
                                            <?php endif; ?>
                                        </div>
                                        <label for="favicon" class="position-absolute bottom-0 end-0 bg-white shadow-sm p-2 rounded-circle cursor-pointer border">
                                            <i class="fas fa-camera text-muted x-small"></i>
                                        </label>
                                        <input type="file" id="favicon" name="favicon" class="d-none" accept="image/*">
                                    </div>
                                    <p class="text-muted small mt-2 mb-0 fw-bold"><?php echo e(__('center::settings.general.favicon')); ?></p>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.general.name')); ?></label>
                                    <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $tenant->name)); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.general.phone')); ?></label>
                                    <input type="tel" name="phone" class="form-control" value="<?php echo e(old('phone', $tenant->phone)); ?>" oninput="this.value = this.value.replace(/[^0-9\+\-\(\)\s]/g, '')" placeholder="01xxxxxxxxx">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.general.email')); ?></label>
                                    <input type="email" class="form-control bg-light" value="<?php echo e($tenant->email ?? ($tenant->users->first()?->email ?? 'N/A')); ?>" disabled>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.general.address')); ?></label>
                                    <input type="text" name="address" class="form-control" value="<?php echo e(old('address', $tenant->address)); ?>">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.general.description')); ?></label>
                                    <textarea name="description" class="form-control" rows="3"><?php echo e(old('description', $tenant->description)); ?></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-muted"><?php echo e(__('center::settings.general.timezone')); ?></label>
                                    <select name="timezone" class="form-select select2">
                                        <?php $__currentLoopData = timezone_identifiers_list(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timezone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($timezone); ?>" <?php echo e($tenant->timezone == $timezone ? 'selected' : ''); ?>>
                                                <?php echo e($timezone); ?> (<?php echo e(\Carbon\Carbon::now($timezone)->format('h:i A')); ?>)
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <small class="text-muted"><?php echo e(__('center::settings.general_timezone_help')); ?></small>
                                </div>

                                <!-- Social Media Links -->
                                <div class="col-12 mt-4">
                                    <h6 class="fw-bold text-primary mb-3"><i class="fas fa-share-alt me-2"></i><?php echo e(__('center::settings.general.social_links')); ?></h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white"><i class="fab fa-facebook text-primary"></i></span>
                                                <input type="url" name="facebook_url" class="form-control" value="<?php echo e(old('facebook_url', $tenant->facebook_url)); ?>" placeholder="<?php echo e(__('center::settings.general.facebook')); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white"><i class="fab fa-instagram text-danger"></i></span>
                                                <input type="url" name="instagram_url" class="form-control" value="<?php echo e(old('instagram_url', $tenant->instagram_url)); ?>" placeholder="<?php echo e(__('center::settings.general.instagram')); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white"><i class="fab fa-twitter text-info"></i></span>
                                                <input type="url" name="twitter_url" class="form-control" value="<?php echo e(old('twitter_url', $tenant->twitter_url)); ?>" placeholder="<?php echo e(__('center::settings.general.twitter')); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white"><i class="fab fa-youtube text-danger"></i></span>
                                                <input type="url" name="youtube_url" class="form-control" value="<?php echo e(old('youtube_url', $tenant->youtube_url)); ?>" placeholder="<?php echo e(__('center::settings.general.youtube')); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary px-5 shadow-sm rounded-pill">
                                        <i class="fas fa-save me-2"></i> <?php echo e(__('center::settings.general.save')); ?>

                                    </button>
                                </div>
                            </form>
                        </div> <!-- Closes general tab -->
<?php /**PATH D:\new project\antigravty\taalimu.com\taalimu.com\Modules/Center\resources/views/settings/partials/_tab-general.blade.php ENDPATH**/ ?>