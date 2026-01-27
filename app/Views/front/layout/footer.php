<footer class="bg-slate-900 text-slate-300 pt-20 pb-10 mt-auto border-t border-slate-800 relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-slate-900 via-accent to-slate-900"></div>

        <div class="max-w-7xl mx-auto px-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                
                <div class="space-y-6">
                    <a href="<?php echo URLROOT; ?>" class="text-3xl font-serif font-black tracking-tighter text-white block">
                        EXOTIKHA<span class="text-accent">.</span>
                    </a>
                    <p class="text-sm leading-relaxed text-slate-400">
                        Sensual, Elegant, Confident. <br>
                        A new definition of the modern African boutique experience.
                    </p>
                    
                    <div class="space-y-3 pt-2">
                        <div class="flex items-start gap-3">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/1/19/Flag_of_Ghana.svg" class="w-5 h-auto mt-1 shadow-sm rounded-sm">
                            <div class="text-sm">
                                <p class="font-bold text-white">Accra, Ghana</p>
                                <a href="tel:+233539382808" class="hover:text-accent transition">+233 53 938 2808</a>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/6/68/Flag_of_Togo.svg" class="w-5 h-auto mt-1 shadow-sm rounded-sm">
                            <div class="text-sm">
                                <p class="font-bold text-white">Lomé, Togo</p>
                                <a href="https://wa.me/22891081688" target="_blank" class="hover:text-accent transition flex items-center gap-1">
                                    <i class="fab fa-whatsapp"></i> +228 91 08 16 88
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-white font-bold uppercase tracking-widest text-xs mb-6">Collections</h4>
                    <ul class="space-y-4 text-sm">
                        <li><a href="<?php echo URLROOT; ?>/shop/promotions" class="text-red-500 hover:text-red-400 font-bold flex items-center gap-2 transition"><i class="fas fa-bolt text-xs"></i> Flash Sales</a></li>
                        <li><a href="<?php echo URLROOT; ?>/shop?gender=women" class="hover:text-white hover:translate-x-1 transition inline-block">Women's Fashion</a></li>
                        <li><a href="<?php echo URLROOT; ?>/shop?gender=men" class="hover:text-white hover:translate-x-1 transition inline-block">Men's Collection</a></li>
                        <li><a href="<?php echo URLROOT; ?>/shop" class="hover:text-white hover:translate-x-1 transition inline-block">Accessories</a></li>
                        <li><a href="<?php echo URLROOT; ?>/shop" class="hover:text-white hover:translate-x-1 transition inline-block">New Arrivals</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold uppercase tracking-widest text-xs mb-6">Assistance</h4>
                    <ul class="space-y-4 text-sm">
                        <li><a href="<?php echo URLROOT; ?>/users/account?tab=orders" class="hover:text-white hover:translate-x-1 transition inline-block">Track My Order</a></li>
                        <li><a href="#" class="hover:text-white hover:translate-x-1 transition inline-block">Shipping & Delivery</a></li>
                        <li><a href="#" class="hover:text-white hover:translate-x-1 transition inline-block">Returns & Refunds</a></li>
                        <li><a href="<?php echo URLROOT; ?>/pages/contact" class="hover:text-white hover:translate-x-1 transition inline-block">Contact Us</a></li>
                        <li><a href="#" class="hover:text-white hover:translate-x-1 transition inline-block">Terms & Privacy</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold uppercase tracking-widest text-xs mb-6">Stay in the loop</h4>
                    <p class="text-sm text-slate-400 mb-4">Subscribe to receive updates, access to exclusive deals, and more.</p>
                    
                    <form action="<?php echo URLROOT; ?>/pages/subscribe" method="POST" class="flex flex-col gap-3 mb-8">
                    <?php echo csrfField(); ?>    
                    <input type="email" name="email" placeholder="Enter your email" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition" required>
                        <button type="submit" class="bg-white text-slate-900 hover:bg-accent hover:text-white px-4 py-3 rounded-lg text-sm font-bold transition uppercase tracking-wide">Subscribe</button>
                    </form>

                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-blue-600 hover:text-white transition duration-300"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-pink-600 hover:text-white transition duration-300"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-black hover:text-white transition duration-300 border border-slate-700"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-6 text-xs text-slate-500">
                
                <div class="text-center md:text-left">
                    <p>&copy; <?php echo date('Y'); ?> <span class="text-white font-bold">EXOTIKHA</span>. All Rights Reserved.</p>
                    <p class="mt-1">Designed with <i class="fas fa-heart text-red-500 mx-1"></i> by <span class="text-slate-300">Lilmoussis</span></p>
                </div>

                <div class="flex items-center gap-4 text-2xl opacity-70 grayscale hover:grayscale-0 transition duration-500">
                    <i class="fab fa-cc-visa text-white"></i>
                    <i class="fab fa-cc-mastercard text-white"></i>
                    <i class="fas fa-mobile-alt text-white" title="Mobile Money"></i>
                    <i class="fas fa-lock text-accent text-sm ml-2"></i> <span class="text-[10px] font-bold uppercase tracking-wider">Secure Payment</span>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>