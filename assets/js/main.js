/**
 * Main JavaScript for Ethio Farmers Market
 * Handles Sidebar, Toasts, and Global Interactions
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Sidebar Toggle Logic
    const sidebar = document.getElementById('mainSidebar');
    const content = document.getElementById('mainContent');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const topNavbar = document.getElementById('topNavbar');

    if (sidebarToggle && sidebar && content && topNavbar) {
        sidebarToggle.addEventListener('click', () => {
            if (window.innerWidth <= 992) {
                sidebar.classList.toggle('mobile-active');
            } else {
                sidebar.classList.toggle('collapsed');
                content.classList.toggle('expanded');
                topNavbar.classList.toggle('expanded');
                // Save state to local storage (only for desktop)
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            }
        });

        // Close sidebar when clicking a link on mobile
        sidebar.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 992) {
                    sidebar.classList.remove('mobile-active');
                }
            });
        });

        // Initialize desktop state from local storage
        if (window.innerWidth > 992 && localStorage.getItem('sidebarCollapsed') === 'true') {
            sidebar.classList.add('collapsed');
            content.classList.add('expanded');
            topNavbar.classList.add('expanded');
        }
    }

    // 2. Global Add to Cart (AJAX)
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.id;
            
            // Add loading state
            const originalContent = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            this.disabled = true;

            fetch(BASE_URL + 'ajax/add-to-cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'product_id=' + productId
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message);
                    
                    // Update cart count badge
                    const cartBtn = document.querySelector('.cart-btn');
                    if (cartBtn) {
                        let badge = cartBtn.querySelector('.cart-count');
                        if (!badge) {
                            badge = document.createElement('span');
                            badge.className = 'cart-count';
                            badge.style.cssText = 'position: absolute; top: -10px; right: -10px; background: var(--accent); color: white; border-radius: 50%; padding: 2px 7px; font-size: 0.7rem;';
                            cartBtn.appendChild(badge);
                        }
                        badge.innerText = data.cart_count;
                        badge.style.display = 'block';
                    }
                } else {
                    showToast(data.message || 'Please login first.', 'error');
                    if (data.message === 'Please login first.') {
                        setTimeout(() => window.location.href = BASE_URL + 'public/login.php', 1500);
                    }
                }
            })
            .catch(err => {
                console.error(err);
                showToast('Something went wrong.', 'error');
            })
            .finally(() => {
                this.innerHTML = originalContent;
                this.disabled = false;
            });
        });
    });

    // 3. Newsletter Subscription (AJAX)
    const newsletterForm = document.getElementById('newsletter-form');
    const newsletterStatus = document.getElementById('newsletter-status');

    if (newsletterForm) {
        newsletterForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(newsletterForm);
            const submitBtn = newsletterForm.querySelector('button');
            const originalBtnText = submitBtn.innerText;

            submitBtn.disabled = true;
            submitBtn.innerText = 'Subscribing...';

            fetch(BASE_URL + 'ajax/subscribe.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    newsletterForm.reset();
                    if (newsletterStatus) {
                        newsletterStatus.style.color = '#4ade80';
                        newsletterStatus.innerText = data.message;
                    }
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(err => {
                console.error(err);
                showToast('Subscription failed. Please try again.', 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerText = originalBtnText;
            });
        });
    }
});

/**
 * Premium Toast Notification System
 * @param {string} message 
 * @param {string} type 'success' | 'error'
 */
function showToast(message, type = 'success') {
    // Remove existing toasts
    const existing = document.querySelectorAll('.toast-notification');
    existing.forEach(t => t.remove());

    const toast = document.createElement('div');
    toast.className = `toast-notification glass ${type}`;
    toast.style.cssText = `
        position: fixed;
        bottom: 30px;
        right: 30px;
        padding: 1.2rem 2rem;
        background: ${type === 'success' ? 'var(--primary)' : 'var(--accent)'};
        color: white;
        z-index: 9999;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 600;
        animation: toastSlideIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    `;
    
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    toast.innerHTML = `<i class="fas ${icon}"></i> ${message}`;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(50px)';
        toast.style.transition = 'all 0.5s ease';
        setTimeout(() => toast.remove(), 500);
    }, 4000);
}

// Add Toast Animation to Head
const toastStyle = document.createElement('style');
toastStyle.innerHTML = `
    @keyframes toastSlideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
`;
document.head.appendChild(toastStyle);
