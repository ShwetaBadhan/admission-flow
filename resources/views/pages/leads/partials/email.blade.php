 <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
            <h5 class="mb-1">Email & SMS</h5>
            <div class="d-inline-flex align-items-center">
                <a href="javascript:void(0);" class="link-primary fw-medium" data-bs-toggle="modal"
                    data-bs-target="#send_message_modal">
                    <i class="ti ti-circle-plus me-1"></i>Send Message
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Message History -->
            <div class="card border mb-0">
                <div class="card-body pb-0">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <h6 class="mb-1">Message History</h6>
                                <p>View all emails and SMS sent to this lead.</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        @forelse($lead->communications->whereIn('type', ['email', 'sms'])->take(10) as $comm)
                            <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-md bg-{{ $comm->type == 'email' ? 'info' : 'success' }} rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="ti ti-{{ $comm->type == 'email' ? 'mail' : 'message' }} text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <h6 class="fw-semibold mb-0">{{ ucfirst($comm->type) }}</h6>
                                        <small class="text-muted">{{ $comm->created_at->format('d M Y, h:i A') }}</small>
                                    </div>
                                    @if($comm->type == 'email' && $comm->subject)
                                        <p class="mb-1 text-muted small">Subject: {{ $comm->subject }}</p>
                                    @endif
                                    <p class="mb-0">{{ Str::limit($comm->content, 150) }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center py-4 mb-0">No messages sent yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Send Message Modal (Email/SMS) -->
<div class="modal fade" id="send_message_modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="messageForm" action="{{ route('leads.send-message', $lead) }}" method="POST">
                @csrf
                <!-- Single hidden field for message type -->
                <input type="hidden" name="message_type" id="message_type" value="email">
                
                <div class="modal-body">
                    <!-- Tabs -->
                    <ul class="nav nav-pills d-flex mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link btn w-100 active" 
                                    data-bs-toggle="pill" 
                                    data-bs-target="#pills-home" 
                                    type="button" 
                                    role="tab" 
                                    aria-selected="true"
                                    onclick="switchTab('email')">
                                <i class="ti ti-mail me-1"></i> Email
                            </button>
                        </li>
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link btn w-100" 
                                    data-bs-toggle="pill" 
                                    data-bs-target="#pills-profile" 
                                    type="button" 
                                    role="tab" 
                                    aria-selected="false"
                                    onclick="switchTab('sms')">
                                <i class="ti ti-message me-1"></i> SMS
                            </button>
                        </li>
                    </ul>
                    
                    <div class="tab-content">
                        <!-- Email Tab -->
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel">
                            <div class="mb-3">
                                <label class="form-label">To <span class="text-danger">*</span></label>
                                <input type="email" name="email_to" id="email_to" class="form-control" 
                                       value="{{ $lead->email }}" 
                                       placeholder="Enter email address" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Subject <span class="text-danger">*</span></label>
                                <input type="text" name="subject" id="subject" class="form-control" 
                                       placeholder="Enter subject" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Message <span class="text-danger">*</span></label>
                                <textarea name="email_content" id="email_content" class="form-control email-content" rows="6" 
                                          placeholder="Type your email message..."></textarea>
                            </div>
                        </div>
                        
                        <!-- SMS Tab -->
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel">
                            <div class="mb-3">
                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" name="sms_to" id="sms_to" class="form-control" 
                                       value="{{ $lead->mobile }}" 
                                       placeholder="Enter phone number">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Message (Max 160 characters) <span class="text-danger">*</span></label>
                                <textarea name="sms_content" id="sms_content" class="form-control sms-content" rows="4" 
                                          maxlength="160" placeholder="Type your SMS message..."></textarea>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <small class="text-muted">
                                        <span class="char-count">0</span>/160 characters
                                    </small>
                                    <small class="text-muted">
                                        <span class="sms-count">0</span> SMS
                                    </small>
                                </div>
                            </div>
                            <div class="alert alert-warning small mb-0">
                                <i class="ti ti-info-circle me-1"></i>
                                <strong>Note:</strong> SMS functionality requires backend configuration with an SMS provider (Twilio, MSG91, etc.)
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-send me-1"></i> 
                        <span class="send-btn-text">Send Email</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Send Message Modal -->
@push('scripts')
<script>
// Global function to switch tabs
function switchTab(type) {
    document.getElementById('message_type').value = type;
    
    const sendBtnText = document.querySelector('.send-btn-text');
    if (sendBtnText) {
        sendBtnText.textContent = type === 'email' ? 'Send Email' : 'Send SMS';
    }
    
    // Remove required from all fields
    document.querySelectorAll('#email_to, #subject, #email_content, #sms_to, #sms_content').forEach(el => {
        el.removeAttribute('required');
    });
    
    // Add required only to active tab fields
    if (type === 'email') {
        document.getElementById('email_to').setAttribute('required', 'required');
        document.getElementById('subject').setAttribute('required', 'required');
        document.getElementById('email_content').setAttribute('required', 'required');
    } else {
        document.getElementById('sms_to').setAttribute('required', 'required');
        document.getElementById('sms_content').setAttribute('required', 'required');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const smsTextarea = document.querySelector('.sms-content');
    const charCount = document.querySelector('.char-count');
    const smsCount = document.querySelector('.sms-count');
    
    // SMS character counter
    if (smsTextarea && charCount) {
        smsTextarea.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = length;
            
            // Calculate number of SMS (160 chars per SMS)
            const smsNumber = Math.ceil(length / 160) || 0;
            smsCount.textContent = smsNumber;
            
            if (length > 160) {
                charCount.classList.add('text-danger');
                smsCount.classList.add('text-danger');
            } else {
                charCount.classList.remove('text-danger');
                smsCount.classList.remove('text-danger');
            }
        });
    }
    
    // Form submission handler
    const form = document.getElementById('messageForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const messageType = document.getElementById('message_type').value;
            
            // Create hidden inputs with standard names for the controller
            if (messageType === 'email') {
                // Create 'to' field
                let toInput = document.createElement('input');
                toInput.type = 'hidden';
                toInput.name = 'to';
                toInput.value = document.getElementById('email_to').value;
                this.appendChild(toInput);
                
                // Create 'content' field
                let contentInput = document.createElement('input');
                contentInput.type = 'hidden';
                contentInput.name = 'content';
                contentInput.value = document.getElementById('email_content').value;
                this.appendChild(contentInput);
                
            } else {
                // Create 'to' field for SMS
                let toInput = document.createElement('input');
                toInput.type = 'hidden';
                toInput.name = 'to';
                toInput.value = document.getElementById('sms_to').value;
                this.appendChild(toInput);
                
                // Create 'content' field for SMS
                let contentInput = document.createElement('input');
                contentInput.type = 'hidden';
                contentInput.name = 'content';
                contentInput.value = document.getElementById('sms_content').value;
                this.appendChild(contentInput);
            }
        });
    }
    
    // Initialize with email tab
    switchTab('email');
});
</script>
@endpush