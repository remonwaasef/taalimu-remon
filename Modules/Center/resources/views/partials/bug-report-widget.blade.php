{{-- Bug Report Floating Widget (Beta Feedback System) --}}
{{-- Uses dom-to-image-more for auto-screenshots (proper Arabic font support) --}}
{{-- Also supports paste/drag/upload as manual alternatives --}}

<!-- dom-to-image-more: Handles Arabic fonts correctly by embedding them as base64 -->
<script src="https://cdn.jsdelivr.net/npm/dom-to-image-more@3/dist/dom-to-image-more.min.js"></script>

@include('center::partials.bug_report._styles')

{{-- Floating Button --}}
<button type="button" class="bug-report-fab" onclick="openBugReportModal()" id="bugReportFab">
    🐛
    <span class="fab-tooltip">{{ __('center::bug_report.report_bug') }}</span>
</button>

{{-- Bug Report Modal --}}
<div class="modal fade" id="bugReportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            {{-- Header --}}
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-bug me-2"></i>{{ __('center::bug_report.report_bug') }}
                </h5>
                <button type="button" class="btn-close" onclick="closeBugReportModal()" aria-label="Close">&times;</button>
            </div>

            {{-- Form Body --}}
            <div class="modal-body" id="bugReportFormBody">
                <form id="bugReportForm" enctype="multipart/form-data">
                    @csrf

                    {{-- Category --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small">{{ __('center::bug_report.report_category') }}</label>
                        <select name="category" class="form-select" required id="bugCategory">
                            <option value="bug">🐛 {{ __('center::bug_report.category_bug') }}</option>
                            <option value="suggestion">💡 {{ __('center::bug_report.category_suggestion') }}</option>
                            <option value="ui_issue">🎨 {{ __('center::bug_report.category_ui_issue') }}</option>
                            <option value="performance">⚡ {{ __('center::bug_report.category_performance') }}</option>
                            <option value="other">📝 {{ __('center::bug_report.category_other') }}</option>
                        </select>
                    </div>

                    {{-- Title --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small">{{ __('center::bug_report.report_title') }}</label>
                        <input type="text" name="title" class="form-control" placeholder="{{ __('center::bug_report.report_title_placeholder') }}" required maxlength="255" id="bugTitle">
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small">{{ __('center::bug_report.report_description') }}</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="{{ __('center::bug_report.report_description_placeholder') }}" required maxlength="5000" id="bugDescription"></textarea>
                    </div>

                    {{-- Auto Screenshot Box --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small">
                            <i class="fas fa-camera me-1 text-success"></i>
                            @if(app()->getLocale() == 'ar') لقطة الشاشة
                            @elseif(app()->getLocale() == 'fr') Capture d'écran
                            @else Screenshot @endif
                        </label>
                        <div class="screenshot-box" id="screenshotBox">
                            {{-- Loading state --}}
                            <div id="screenshotLoading" class="screenshot-loading">
                                <div><i class="fas fa-spinner fa-spin"></i></div>
                                <small>
                                    @if(app()->getLocale() == 'ar') جاري التقاط الشاشة...
                                    @elseif(app()->getLocale() == 'fr') Capture en cours...
                                    @else Capturing screenshot... @endif
                                </small>
                            </div>
                            {{-- Image preview --}}
                            <img id="screenshotPreview" class="screenshot-img d-none" src="" alt="Screenshot">
                            {{-- Action buttons --}}
                            <div class="screenshot-actions d-none" id="screenshotActions">
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeScreenshot()">
                                    <i class="fas fa-trash me-1"></i>
                                    @if(app()->getLocale() == 'ar') حذف @elseif(app()->getLocale() == 'fr') Supprimer @else Remove @endif
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('bugScreenshot').click()">
                                    <i class="fas fa-exchange-alt me-1"></i>
                                    @if(app()->getLocale() == 'ar') تغيير @elseif(app()->getLocale() == 'fr') Changer @else Change @endif
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm" onclick="retakeScreenshot()">
                                    <i class="fas fa-redo me-1"></i>
                                    @if(app()->getLocale() == 'ar') إعادة التقاط @elseif(app()->getLocale() == 'fr') Reprendre @else Retake @endif
                                </button>
                            </div>
                        </div>
                        <input type="file" name="screenshot" class="d-none" accept="image/*" id="bugScreenshot">
                    </div>

                    {{-- Auto-info Notice --}}
                    <div class="bug-info-badge mb-3">
                        <i class="fas fa-info-circle me-1"></i>
                        {{ __('center::bug_report.auto_info_notice') }}
                    </div>

                    {{-- Hidden Fields --}}
                    <input type="hidden" name="page_url" id="bugPageUrl">
                    <input type="hidden" name="browser_info" id="bugBrowserInfo">
                    <input type="hidden" name="auto_screenshot" id="autoScreenshotValue">

                    {{-- Submit --}}
                    <button type="submit" class="btn btn-success w-100 rounded-pill py-2 fw-bold" id="bugSubmitBtn">
                        <i class="fas fa-paper-plane me-2"></i>{{ __('center::bug_report.submit_report') }}
                    </button>
                </form>
            </div>

            {{-- Success Body --}}
            <div class="modal-body d-none" id="bugReportSuccessBody">
                <div class="bug-report-success">
                    <div class="success-icon">✅</div>
                    <h4 class="fw-bold mt-3">{{ __('center::bug_report.thank_you_title') }}</h4>
                    <p class="text-muted">{{ __('center::bug_report.thank_you_message') }}</p>
                    <button type="button" class="btn btn-outline-success rounded-pill px-4 mt-2" onclick="closeBugReportModal()">
                        {{ __('center::bug_report.close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@include('center::partials.bug_report._scripts')
