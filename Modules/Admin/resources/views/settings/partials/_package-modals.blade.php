<!-- Add Package Modal -->
<div class="modal fade" id="addPackageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header">
                <h5 class="fw-bold">{{ __('admin.new_package') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.settings.packages.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">الاسم (AR)</label>
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Name (EN)</label>
                        <input type="text" class="form-control" name="name_en">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('admin.slug') }}</label>
                        <input type="text" class="form-control" name="slug" placeholder="{{ __('admin.slug_placeholder') }}">
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">{{ __('admin.price_monthly') }}</label>
                            <input type="number" step="0.01" class="form-control" name="price">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">{{ __('admin.duration_days') }}</label>
                            <input type="number" class="form-control" name="duration_in_days" value="30">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary rounded-pill px-4">{{ __('admin.create') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

