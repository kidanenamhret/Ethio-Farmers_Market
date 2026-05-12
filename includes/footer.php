        </div> <!-- End content-body -->

        <footer style="background: #0a1a0d; color: var(--white); padding: 8rem 3rem 4rem; margin-top: 10rem; position: relative; z-index: 10; border-top: 1px solid rgba(255,255,255,0.05);">
    <div style="max-width: 1400px; margin: 0 auto;">
        <!-- Top Section: Brand & Newsletter -->
        <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 8rem; margin-bottom: 8rem; align-items: start;">
            <div>
                <a href="<?php echo url('public/index.php'); ?>" style="text-decoration: none; display: flex; align-items: center; gap: 15px; margin-bottom: 2rem;">
                    <i class="fas fa-leaf" style="font-size: 2.2rem; color: var(--primary-light);"></i>
                    <span style="font-size: 2rem; font-weight: 850; color: white; letter-spacing: -1.5px;">Ethio Farmers</span>
                </a>
                <p style="color: #94a3b8; line-height: 1.8; font-size: 1.1rem; margin-bottom: 3rem; max-width: 400px;">
                    Empowering Ethiopia's local farmers by providing a direct bridge to quality-conscious consumers. Freshness delivered, community supported.
                </p>
                <div style="display: flex; gap: 1.2rem;">
                    <a href="#" class="glass" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 14px; color: white; text-decoration: none; background: rgba(255,255,255,0.05); transition: var(--transition);"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="glass" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 14px; color: white; text-decoration: none; background: rgba(255,255,255,0.05); transition: var(--transition);"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="glass" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 14px; color: white; text-decoration: none; background: rgba(255,255,255,0.05); transition: var(--transition);"><i class="fab fa-whatsapp"></i></a>
                    <a href="#" class="glass" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 14px; color: white; text-decoration: none; background: rgba(255,255,255,0.05); transition: var(--transition);"><i class="fab fa-telegram"></i></a>
                </div>
            </div>

            <!-- AJAX Newsletter Form (Course Requirement) -->
            <div style="background: rgba(255,255,255,0.03); padding: 4rem; border-radius: 40px; border: 1px solid rgba(255,255,255,0.05);">
                <h3 style="font-size: 1.8rem; font-weight: 850; margin-bottom: 1rem;">Join the Green Revolution</h3>
                <p style="color: #94a3b8; font-weight: 600; margin-bottom: 2.5rem;">Subscribe for weekly harvest updates and exclusive local recipes.</p>
                <form id="newsletter-form" style="display: flex; gap: 1rem;">
                    <input type="email" name="email" placeholder="Your email address" required 
                           style="flex: 1; padding: 1.2rem 2rem; border-radius: 18px; border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.05); color: white; outline: none; font-weight: 600;">
                    <button type="submit" class="btn btn-primary" style="padding: 0 3rem; border-radius: 18px; font-weight: 850;">Subscribe</button>
                </form>
                <div id="newsletter-status" style="margin-top: 1.5rem; font-weight: 700; font-size: 0.9rem;"></div>
            </div>
        </div>

        <!-- Middle Section: Links -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 4rem; padding-bottom: 6rem; border-bottom: 1px solid rgba(255,255,255,0.05);">
            <div>
                <h4 style="color: white; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 2px; font-weight: 800; margin-bottom: 2.5rem;">Quick Links</h4>
                <ul style="list-style: none; padding: 0; display: flex; flex-direction: column; gap: 1.2rem;">
                    <li><a href="<?php echo url('public/products.php'); ?>" class="footer-link">Browse Marketplace</a></li>
                    <?php if (isLoggedIn() && $_SESSION['role'] == 'farmer'): ?>
                        <li><a href="<?php echo url('public/farmer/dashboard.php'); ?>" class="footer-link">My Dashboard</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo url('public/register.php?role=farmer'); ?>" class="footer-link">Farmer Registration</a></li>
                    <?php endif; ?>
                    <li><a href="#" class="footer-link">Success Stories</a></li>
                    <li><a href="#" class="footer-link">Sustainability</a></li>
                </ul>
            </div>
            
            <div>
                <h4 style="color: white; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 2px; font-weight: 800; margin-bottom: 2.5rem;">Company</h4>
                <ul style="list-style: none; padding: 0; display: flex; flex-direction: column; gap: 1.2rem;">
                    <li><a href="#" class="footer-link">About Us</a></li>
                    <li><a href="#" class="footer-link">Quality Standards</a></li>
                    <li><a href="#" class="footer-link">Privacy Policy</a></li>
                    <li><a href="#" class="footer-link">Terms of Service</a></li>
                </ul>
            </div>

            <div>
                <h4 style="color: white; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 2px; font-weight: 800; margin-bottom: 2.5rem;">Support</h4>
                <ul style="list-style: none; padding: 0; display: flex; flex-direction: column; gap: 1.2rem;">
                    <li><a href="#" class="footer-link">Help Center</a></li>
                    <li><a href="#" class="footer-link">Delivery Areas</a></li>
                    <li><a href="#" class="footer-link">Returns & Refunds</a></li>
                    <li><a href="#" class="footer-link">Payment FAQ</a></li>
                </ul>
            </div>

            <div>
                <h4 style="color: white; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 2px; font-weight: 800; margin-bottom: 2.5rem;">Connect</h4>
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <a href="tel:+251911223344" style="display: flex; align-items: center; gap: 15px; text-decoration: none; color: #94a3b8; font-weight: 600; transition: var(--transition);">
                        <i class="fas fa-phone-volume" style="color: var(--primary-light); font-size: 1.1rem;"></i> +251 911 223 344
                    </a>
                    <a href="mailto:hello@ethiofarmers.com" style="display: flex; align-items: center; gap: 15px; text-decoration: none; color: #94a3b8; font-weight: 600; transition: var(--transition);">
                        <i class="fas fa-envelope-open-text" style="color: var(--primary-light); font-size: 1.1rem;"></i> hello@ethiofarmers.com
                    </a>
                    <div style="display: flex; align-items: center; gap: 15px; color: #94a3b8; font-weight: 600;">
                        <i class="fas fa-location-dot" style="color: var(--primary-light); font-size: 1.1rem;"></i> Addis Ababa, Ethiopia
                    </div>
                </div>
            </div>
        </div>

        <!-- Development Team Section -->
        <div style="padding: 3rem; background: rgba(255,255,255,0.02); border-radius: 30px; margin-bottom: 6rem; border: 1px solid rgba(255,255,255,0.05);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem;">
                <div>
                    <h4 style="color: white; font-size: 1.1rem; font-weight: 850; letter-spacing: -0.5px; margin-bottom: 5px;">Development Team</h4>
                    <p style="font-size: 0.85rem; color: #94a3b8; font-weight: 600;">The minds behind the marketplace</p>
                </div>
                <div style="height: 1px; flex: 1; background: linear-gradient(to right, rgba(255,255,255,0.08), transparent); margin-left: 3rem;"></div>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 2rem;">
                <?php 
                $team = [
                    ['name' => 'Mesfin', 'role' => 'Frontend Developer', 'color' => '#1b5e20'],
                    ['name' => 'Edget', 'role' => 'Arch & Backend', 'color' => '#3b82f6'],
                    ['name' => 'Yonas', 'role' => 'DB & Security', 'color' => '#8b5cf6'],
                    ['name' => 'Ebsitu', 'role' => 'UI/UX Design', 'color' => '#f59e0b'],
                    ['name' => 'Biruktawit', 'role' => 'DevOps & QA', 'color' => '#ef4444']
                ];
                foreach ($team as $member):
                ?>
                    <div style="text-align: center; transition: var(--transition);" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div style="width: 50px; height: 50px; background: <?php echo $member['color']; ?>20; color: <?php echo $member['color']; ?>; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; margin: 0 auto 12px; font-weight: 850; border: 1px solid <?php echo $member['color']; ?>30;">
                            <?php echo substr($member['name'], 0, 1); ?>
                        </div>
                        <div style="font-weight: 800; color: white; font-size: 0.9rem;"><?php echo $member['name']; ?></div>
                        <div style="font-size: 0.65rem; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-top: 3px;"><?php echo $member['role']; ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Bottom Section: Copyright & Badges -->
        <div style="padding-top: 4rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 2rem;">
            <div style="color: #64748b; font-weight: 700;">
                &copy; <?php echo date("Y"); ?> Ethio Farmers Market. Crafted with <i class="fas fa-heart" style="color: #ef4444;"></i> for Ethiopia.
            </div>
            
            <!-- Payment Icons -->
            <div style="display: flex; gap: 2rem; align-items: center;">
                <div style="display: flex; gap: 1.5rem; filter: grayscale(1) opacity(0.5);">
                    <i class="fas fa-money-bill-transfer" title="Cash on Delivery" style="font-size: 1.5rem; color: white;"></i>
                    <i class="fas fa-mobile-screen-button" title="Telebirr" style="font-size: 1.5rem; color: white;"></i>
                    <i class="fas fa-credit-card" title="Chapa" style="font-size: 1.5rem; color: white;"></i>
                </div>
                <div style="width: 1px; height: 30px; background: rgba(255,255,255,0.1);"></div>
                <div style="display: flex; gap: 2rem; font-size: 0.9rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px;">
                    <span style="display: flex; align-items: center; gap: 10px;"><i class="fas fa-lock" style="color: var(--primary-light);"></i> Secure Payments</span>
                    <span style="display: flex; align-items: center; gap: 10px;"><i class="fas fa-leaf" style="color: var(--primary-light);"></i> 100% Organic</span>
                </div>
            </div>
        </div>
    </div>
</footer>

    </div> <!-- End main-content-area -->
</div> <!-- End app-container -->

<script src="<?php echo url('assets/js/main.js'); ?>"></script>
<script src="<?php echo url('assets/js/live-search.js'); ?>"></script>
</body>
</html>
