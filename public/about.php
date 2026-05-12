<?php
// public/about.php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';
?>

<div style="max-width: 1200px; margin: 0 auto; padding: 4rem 2rem;" class="animate-fade-up">
    <!-- Hero Section -->
    <div style="text-align: center; margin-bottom: 8rem;">
        <span style="background: var(--primary-glow); color: var(--primary); padding: 8px 20px; border-radius: 50px; font-weight: 800; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px;">Our Journey</span>
        <h1 style="font-size: clamp(3rem, 7vw, 4.5rem); letter-spacing: -2.5px; margin: 1.5rem 0; line-height: 1.1;">Cultivating <span style="color: var(--primary);">Connections</span> Across Ethiopia</h1>
        <p style="color: var(--text-muted); font-size: 1.3rem; max-width: 700px; margin: 0 auto; line-height: 1.6; font-weight: 500;">
            Empowering local farmers and bringing the freshest harvest directly to your doorstep.
        </p>
    </div>

    <!-- Stats/Impact Section -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 3rem; margin-bottom: 8rem;">
        <div class="premium-card" style="text-align: center; padding: 3.5rem 2rem;">
            <div style="font-size: 3rem; font-weight: 900; color: var(--primary); margin-bottom: 10px;">500+</div>
            <div style="font-weight: 800; color: var(--text-main); text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.85rem;">Registered Farmers</div>
        </div>
        <div class="premium-card" style="text-align: center; padding: 3.5rem 2rem;">
            <div style="font-size: 3rem; font-weight: 900; color: var(--secondary); margin-bottom: 10px;">10k+</div>
            <div style="font-weight: 800; color: var(--text-main); text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.85rem;">Fresh Deliveries</div>
        </div>
        <div class="premium-card" style="text-align: center; padding: 3.5rem 2rem;">
            <div style="font-size: 3rem; font-weight: 900; color: var(--accent); margin-bottom: 10px;">100%</div>
            <div style="font-weight: 800; color: var(--text-main); text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.85rem;">Organic Quality</div>
        </div>
    </div>

    <!-- Mission & Vision -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 6rem; align-items: center; margin-bottom: 8rem;">
        <div>
            <h2 style="font-size: 2.5rem; letter-spacing: -1px; margin-bottom: 2rem;">Our <span style="color: var(--primary);">Mission</span></h2>
            <p style="color: var(--text-muted); font-size: 1.1rem; line-height: 1.8; margin-bottom: 2rem;">
                At Ethio Farmers Market, we believe that the backbone of Ethiopia's economy—our farmers—deserves a direct, fair, and transparent way to reach consumers. We eliminate the middleman, ensuring farmers get a fair price for their hard work and consumers get the freshest possible produce.
            </p>
            <ul style="list-style: none; padding: 0;">
                <li style="display: flex; align-items: center; gap: 15px; margin-bottom: 1rem; font-weight: 700; color: var(--text-main);">
                    <i class="fas fa-check-circle" style="color: var(--primary);"></i> Supporting Sustainable Agriculture
                </li>
                <li style="display: flex; align-items: center; gap: 15px; margin-bottom: 1rem; font-weight: 700; color: var(--text-main);">
                    <i class="fas fa-check-circle" style="color: var(--primary);"></i> Ensuring Food Security & Freshness
                </li>
                <li style="display: flex; align-items: center; gap: 15px; margin-bottom: 1rem; font-weight: 700; color: var(--text-main);">
                    <i class="fas fa-check-circle" style="color: var(--primary);"></i> Empowering Rural Communities
                </li>
            </ul>
        </div>
        <div class="premium-card" style="padding: 0; overflow: hidden; border-radius: 40px; height: 500px;">
            <img src="https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=800" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
    </div>

    <!-- Why Choose Us -->
    <div style="text-align: center; margin-bottom: 5rem;">
        <h2 style="font-size: 2.5rem; letter-spacing: -1px; margin-bottom: 1rem;">Why Choose <span style="color: var(--primary);">Ethio Farmers?</span></h2>
        <p style="color: var(--text-muted); font-weight: 600;">The standard for digital agriculture in Ethiopia.</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 3rem; margin-bottom: 8rem;">
        <div class="premium-card" style="padding: 3rem; display: flex; gap: 25px;">
            <div style="width: 60px; height: 60px; background: var(--primary-glow); color: var(--primary); border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">
                <i class="fas fa-shield-halved"></i>
            </div>
            <div>
                <h4 style="font-size: 1.2rem; margin-bottom: 10px;">Secure Transactions</h4>
                <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6;">Integrated with local payment systems like Telebirr and CBE Birr for safe and easy payments.</p>
            </div>
        </div>
        <div class="premium-card" style="padding: 3rem; display: flex; gap: 25px;">
            <div style="width: 60px; height: 60px; background: rgba(59, 130, 246, 0.1); color: #3b82f6; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">
                <i class="fas fa-truck-fast"></i>
            </div>
            <div>
                <h4 style="font-size: 1.2rem; margin-bottom: 10px;">Fast Delivery</h4>
                <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6;">Efficient logistics network ensuring your produce arrives fresh from the highlands to your home.</p>
            </div>
        </div>
        <div class="premium-card" style="padding: 3rem; display: flex; gap: 25px;">
            <div style="width: 60px; height: 60px; background: rgba(251, 192, 45, 0.1); color: #f59e0b; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">
                <i class="fas fa-seedling"></i>
            </div>
            <div>
                <h4 style="font-size: 1.2rem; margin-bottom: 10px;">Direct Sourcing</h4>
                <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6;">Every product is traced back to a specific farmer, ensuring quality and accountability.</p>
            </div>
        </div>
        <div class="premium-card" style="padding: 3rem; display: flex; gap: 25px;">
            <div style="width: 60px; height: 60px; background: rgba(239, 68, 68, 0.1); color: #ef4444; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <h4 style="font-size: 1.2rem; margin-bottom: 10px;">Community Driven</h4>
                <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6;">We invest a portion of our proceeds into rural agricultural education and infrastructure.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
