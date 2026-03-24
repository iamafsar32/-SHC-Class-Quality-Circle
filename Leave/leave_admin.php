<style>
/* Advanced Toast UI */
.advanced-toast {
    position: fixed;
    top: 25px;
    right: 25px;
    background: white;
    padding: 15px 25px;
    border-radius: 8px;
    border-left: 6px solid #001f3f; /* Your Theme Color */
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    display: flex;
    flex-direction: column;
    z-index: 9999;
    animation: slideInLeft 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    overflow: hidden;
    min-width: 300px;
}

.advanced-toast.hide {
    animation: fadeOut 0.5s forwards;
}

.toast-content {
    display: flex;
    align-items: center;
}

.toast-icon {
    background: #001f3f;
    color: white;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
}

.toast-details {
    display: flex;
    flex-direction: column;
}

.toast-title {
    font-weight: 800;
    color: #001f3f;
    font-size: 16px;
}

.toast-msg {
    color: #666;
    font-size: 13px;
}

/* Progress Bar Animation */
.toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    width: 100%;
    background: #001f3f;
    animation: progress 4s linear forwards;
}

@keyframes slideInLeft {
    0% { transform: translateX(110%); }
    100% { transform: translateX(0); }
}

@keyframes fadeOut {
    to { transform: translateX(110%); opacity: 0; }
}

@keyframes progress {
    100% { width: 0%; }
}

/* Responsive adjustment for Mobile */
@media (max-width: 768px) {
    .advanced-toast {
        top: 10px;
        right: 10px;
        left: 10px;
        min-width: auto;
    }
}


</style>



<?php if(isset($_GET['success'])): ?>
    <div id="success-toast" class="advanced-toast">
        <div class="toast-content">
            <div class="toast-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <div class="toast-details">
                <span class="toast-title">Success</span>
                <span class="toast-msg">Database & files updated successfully!</span>
            </div>
        </div>
        <div class="toast-progress"></div>
    </div>

    <script>
        setTimeout(() => {
            const toast = document.getElementById('success-toast');
            if(toast) {
                toast.classList.add('hide');
                // Remove from DOM after animation finishes
                setTimeout(() => toast.remove(), 500);
            }
        }, 4000);
    </script>
<?php endif; ?>