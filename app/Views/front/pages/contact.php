<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div class="bg-slate-50 py-16">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            
            <div>
                <span class="text-accent font-bold uppercase tracking-widest text-xs mb-2 block">Get in Touch</span>
                <h1 class="text-4xl md:text-5xl font-serif font-bold text-slate-900 mb-6">We'd love to hear from you.</h1>
                <p class="text-slate-600 text-lg mb-10 leading-relaxed">Whether you have a question about products, shipping, or just want to say hello, we are always here to help.</p>
                
                <div class="space-y-8">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-accent shadow-sm border border-slate-100 text-xl"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <h4 class="font-bold text-slate-900">Visit Us</h4>
                            <p class="text-slate-500 text-sm">Haatso, Accra – Ghana</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-accent shadow-sm border border-slate-100 text-xl"><i class="fas fa-envelope"></i></div>
                        <div>
                            <h4 class="font-bold text-slate-900">Email Us</h4>
                            <p class="text-slate-500 text-sm">sales@exotikha.com</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-accent shadow-sm border border-slate-100 text-xl"><i class="fab fa-whatsapp"></i></div>
                        <div>
                            <h4 class="font-bold text-slate-900">WhatsApp</h4>
                            <p class="text-slate-500 text-sm">+233 53 938 2808</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 md:p-10 rounded-3xl shadow-xl border border-slate-100">
                <?php flash('product_message'); ?>
                <form action="<?php echo URLROOT; ?>/pages/contact" method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Name</label>
                            <input type="text" name="name" class="w-full rounded-xl border-slate-200 focus:ring-accent bg-slate-50 h-12" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Email</label>
                            <input type="email" name="email" class="w-full rounded-xl border-slate-200 focus:ring-accent bg-slate-50 h-12" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Subject</label>
                        <select name="subject" class="w-full rounded-xl border-slate-200 focus:ring-accent bg-slate-50 h-12">
                            <option value="General Inquiry">General Inquiry</option>
                            <option value="Order Status">Order Status</option>
                            <option value="Product Question">Product Question</option>
                            <option value="Partnership">Partnership</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Message</label>
                        <textarea name="message" rows="5" class="w-full rounded-xl border-slate-200 focus:ring-accent bg-slate-50 p-4" required></textarea>
                    </div>
                    <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-xl font-bold hover:bg-accent transition shadow-lg">Send Message</button>
                </form>
            </div>

        </div>
    </div>
</div>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>