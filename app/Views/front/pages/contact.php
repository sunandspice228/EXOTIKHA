<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div class="bg-slate-900 text-white py-20 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-96 h-96 bg-accent/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
    <div class="max-w-7xl mx-auto px-6 relative z-10 text-center">
        <span class="text-primary font-black uppercase tracking-widest text-xs mb-4 block animate-fade-in-down">Customer Care</span>
        <h1 class="text-4xl md:text-6xl font-serif font-bold mb-6">Contact Us</h1>
        <p class="text-slate-400 text-lg max-w-2xl mx-auto">
            Have a question about an order, a product, or partnership? We are here to help you.
        </p>
    </div>
</div>

<div class="bg-slate-50 py-16">
    <div class="max-w-7xl mx-auto px-6">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 text-center hover:-translate-y-1 transition duration-300">
                <div class="w-14 h-14 bg-slate-50 text-slate-900 rounded-full flex items-center justify-center text-2xl mx-auto mb-6">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3 class="font-bold text-slate-900 mb-2">Visit Our Store</h3>
                <p class="text-slate-500 text-sm leading-relaxed">
                    Haatso, Accra – Ghana<br>
                    <span class="text-xs text-slate-400">Mon - Sat: 9am - 8pm</span>
                </p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 text-center hover:-translate-y-1 transition duration-300">
                <div class="w-14 h-14 bg-slate-50 text-slate-900 rounded-full flex items-center justify-center text-2xl mx-auto mb-6">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3 class="font-bold text-slate-900 mb-2">Email Us</h3>
                <p class="text-slate-500 text-sm leading-relaxed">
                    For general inquiries & orders:<br>
                    <a href="mailto:sales@exotikha.com" class="text-primary font-bold hover:underline">sales@exotikha.com</a>
                </p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 text-center hover:-translate-y-1 transition duration-300">
                <div class="w-14 h-14 bg-slate-50 text-slate-900 rounded-full flex items-center justify-center text-2xl mx-auto mb-6">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <h3 class="font-bold text-slate-900 mb-2">Call or WhatsApp</h3>
                <p class="text-slate-500 text-sm leading-relaxed">
                    Immediate assistance:<br>
                    <a href="https://wa.me/233539382808" class="text-primary font-bold hover:underline">+233 53 938 2808</a>
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 bg-white rounded-3xl shadow-xl overflow-hidden">
            
            <div class="h-96 lg:h-auto bg-slate-200 relative">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3970.223758401668!2d-0.20359462643803009!3d5.680764032385447!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xfdf9c4ecd2adf59%3A0x638fdae15ca9043!2s4%20Abuakwah%20Lk%2C%20Haatso%2C%20Ghana!5e0!3m2!1sfr!2stg!4v1769756499720!5m2!1sfr!2stg" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="absolute inset-0 grayscale hover:grayscale-0 transition duration-700"></iframe>
            </div>

            <div class="p-8 lg:p-12">
                <h2 class="text-2xl font-serif font-bold text-slate-900 mb-6">Send us a message</h2>
                
                <?php flash('contact_msg'); ?>

                <form action="<?php echo URLROOT; ?>/pages/contact" method="POST" class="space-y-6">
                    <?php echo csrfField(); ?>    
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Your Name</label>
                            <input type="text" name="name" class="w-full rounded-xl border-slate-200 focus:ring-primary focus:border-primary bg-slate-50 h-12 px-4 transition outline-none" placeholder="John Doe" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Email Address</label>
                            <input type="email" name="email" class="w-full rounded-xl border-slate-200 focus:ring-primary focus:border-primary bg-slate-50 h-12 px-4 transition outline-none" placeholder="john@example.com" required>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Subject</label>
                        <select name="subject" class="w-full rounded-xl border-slate-200 focus:ring-primary focus:border-primary bg-slate-50 h-12 px-4 transition outline-none">
                            <option value="General Inquiry">General Inquiry</option>
                            <option value="Order Status">Order Status</option>
                            <option value="Product Information">Product Information</option>
                            <option value="Returns & Refunds">Returns & Refunds</option>
                            <option value="Partnership">Partnership</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Message</label>
                        <textarea name="message" rows="5" class="w-full rounded-xl border-slate-200 focus:ring-primary focus:border-primary bg-slate-50 p-4 transition outline-none" placeholder="How can we help you today?" required></textarea>
                    </div>

                    <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-xl font-bold hover:bg-primary transition shadow-lg uppercase tracking-widest text-sm flex justify-center items-center gap-2 group">
                        Send Message <i class="fas fa-paper-plane group-hover:translate-x-1 transition"></i>
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>