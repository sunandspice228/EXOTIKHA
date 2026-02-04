<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<?php
// Helper local pour la langue
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';
?>

<div class="bg-slate-900 text-white py-20 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-96 h-96 bg-primary/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
    <div class="max-w-7xl mx-auto px-6 relative z-10 text-center">
        <span class="text-primary font-black uppercase tracking-widest text-xs mb-4 block animate-fade-in-down"><?php echo lang('contact_subtitle'); ?></span>
        <h1 class="text-4xl md:text-6xl font-serif font-bold mb-6"><?php echo lang('contact_title'); ?></h1>
        <p class="text-slate-400 text-lg max-w-2xl mx-auto font-light">
            <?php echo lang('contact_text'); ?>
        </p>
    </div>
</div>

<div class="bg-slate-50 py-16">
    <div class="max-w-7xl mx-auto px-6">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">
            
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 text-center hover:-translate-y-1 transition duration-300 group">
                <div class="w-14 h-14 bg-slate-50 text-slate-900 rounded-full flex items-center justify-center text-2xl mx-auto mb-6 group-hover:bg-primary group-hover:text-white transition">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3 class="font-bold text-slate-900 mb-2"><?php echo lang('contact_card_visit_title'); ?></h3>
                <p class="text-slate-500 text-sm leading-relaxed">
                    Haatso, Accra – Ghana<br>
                    <span class="text-xs text-slate-400"><?php echo lang('contact_hours'); ?></span>
                </p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 text-center hover:-translate-y-1 transition duration-300 group">
                <div class="w-14 h-14 bg-slate-50 text-slate-900 rounded-full flex items-center justify-center text-2xl mx-auto mb-6 group-hover:bg-primary group-hover:text-white transition">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3 class="font-bold text-slate-900 mb-2"><?php echo lang('contact_card_email_title'); ?></h3>
                <p class="text-slate-500 text-sm leading-relaxed">
                    <?php echo lang('contact_card_email_text'); ?><br>
                    <a href="mailto:sales@exotikha.com" class="text-primary font-bold hover:underline">sales@exotikha.com</a>
                </p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 text-center hover:-translate-y-1 transition duration-300 group">
                <div class="w-14 h-14 bg-slate-50 text-slate-900 rounded-full flex items-center justify-center text-2xl mx-auto mb-6 group-hover:bg-green-500 group-hover:text-white transition">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <h3 class="font-bold text-slate-900 mb-2"><?php echo lang('contact_card_phone_title'); ?></h3>
                <p class="text-slate-500 text-sm leading-relaxed">
                    <?php echo lang('contact_card_phone_text'); ?><br>
                    <a href="https://wa.me/233539382808" class="text-primary font-bold hover:underline">+233 53 938 2808</a>
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 bg-white rounded-3xl shadow-xl overflow-hidden">
            
            <div class="h-96 lg:h-auto bg-slate-200 relative min-h-[400px]">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3970.435774880577!2d-0.205833!3d5.649167!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xfdf9c6e5b5b5b5b%3A0x5b5b5b5b5b5b5b5b!2sHaatso%2C%20Accra%2C%20Ghana!5e0!3m2!1sen!2sgh!4v1620000000000!5m2!1sen!2sgh" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="absolute inset-0 grayscale hover:grayscale-0 transition duration-700"></iframe>
            </div>

            <div class="p-8 lg:p-12">
                <h2 class="text-2xl font-serif font-bold text-slate-900 mb-6"><?php echo lang('contact_form_title'); ?></h2>
                
                <?php if(function_exists('flash')) flash('contact_msg'); ?>
                
                <?php if(isset($data['error']) && !empty($data['error'])): ?>
                    <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded mb-6 text-sm">
                        <?php echo $data['error']; ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo URLROOT; ?>/pages/contact" method="POST" class="space-y-6">
                    <?php echo isset($_SESSION['csrf_token']) ? csrfField() : ''; ?>    
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2"><?php echo lang('form_name'); ?></label>
                            <input type="text" name="name" class="w-full rounded-xl border-slate-200 focus:ring-primary focus:border-primary bg-slate-50 h-12 px-4 transition outline-none text-sm" placeholder="John Doe" required value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2"><?php echo lang('form_email'); ?></label>
                            <input type="email" name="email" class="w-full rounded-xl border-slate-200 focus:ring-primary focus:border-primary bg-slate-50 h-12 px-4 transition outline-none text-sm" placeholder="john@example.com" required value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2"><?php echo lang('form_subject'); ?></label>
                        <select name="subject" class="w-full rounded-xl border-slate-200 focus:ring-primary focus:border-primary bg-slate-50 h-12 px-4 transition outline-none text-sm cursor-pointer">
                            <option value="General Inquiry"><?php echo lang('subject_general'); ?></option>
                            <option value="Order Status"><?php echo lang('subject_order'); ?></option>
                            <option value="Product Information"><?php echo lang('subject_product'); ?></option>
                            <option value="Returns & Refunds"><?php echo lang('subject_return'); ?></option>
                            <option value="Partnership"><?php echo lang('subject_partner'); ?></option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2"><?php echo lang('form_message'); ?></label>
                        <textarea name="message" rows="5" class="w-full rounded-xl border-slate-200 focus:ring-primary focus:border-primary bg-slate-50 p-4 transition outline-none text-sm" placeholder="<?php echo lang('form_message_ph'); ?>" required><?php echo isset($data['message']) ? $data['message'] : ''; ?></textarea>
                    </div>

                    <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-xl font-bold hover:bg-primary transition shadow-lg uppercase tracking-widest text-sm flex justify-center items-center gap-2 group">
                        <?php echo lang('btn_send_message'); ?> <i class="fas fa-paper-plane group-hover:translate-x-1 transition"></i>
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>