<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<?php
    // Helper langue
    $lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';

    // --- 1. DATA RETRIEVAL ---
    $user = isset($data['user']) ? $data['user'] : null;
    $cartItems = isset($data['cart_items']) ? $data['cart_items'] : [];
    $productSubtotal = isset($data['total']) ? $data['total'] : 0;

    // User Identity
    $firstName = $user->first_name ?? '';
    $lastName = $user->last_name ?? '';
    $fullName = trim($firstName . ' ' . $lastName);
    $email = $user->email ?? '';

    // ADDRESS & PHONE
    $shippingPhone = $user->shipping_phone ?? $user->phone ?? '';
    $savedAddress = $user->shipping_address ?? $user->address ?? '';

    // ============================================================
    // 🚚 ACCRA ZONES AND RATES CONFIGURATION (GHS)
    // ============================================================
    $accraLocations = [
        'Adabraka' => 20,
        'Airport Residential' => 25,
        'Achimota' => 30,
        'Cantonments' => 25,
        'Dansoman' => 35,
        'Dzorwulu' => 25,
        'East Legon' => 30,
        'Labadi' => 25,
        'Labone' => 25,
        'Lapaz' => 30,
        'Madina' => 40,
        'Osu' => 20,
        'Ridge' => 20,
        'Spintex' => 40,
        'Tema (Port)' => 60,
        'Teshie' => 35,
        'University of Ghana (Legon)' => 30,
        'Weija' => 50,
        'West Legon' => 35,
        'Other location (Accra)' => 45 
    ];
    ksort($accraLocations);
?>

<div class="bg-slate-50 py-12 min-h-screen">
    <div class="max-w-7xl mx-auto px-6">
        
        <div class="flex items-center gap-4 mb-8">
            <a href="<?php echo URLROOT; ?>/cart" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-900 hover:text-white transition shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-3xl font-serif font-bold text-slate-900"><?php echo lang('checkout_title'); ?></h1>
                <p class="text-slate-500 text-sm"><?php echo lang('checkout_subtitle'); ?></p>
            </div>
        </div>

        <?php if(function_exists('flash')) flash('cart_msg'); ?>

        <form action="<?php echo URLROOT; ?>/cart/place_order" method="POST" id="checkoutForm" class="flex flex-col lg:flex-row gap-12">
            <?php echo csrfField(); ?>
            
            <input type="hidden" name="gps_coordinates" value="">
            <input type="hidden" name="shipping_cost" id="shipping_cost_input" value="0">
            <input type="hidden" name="total_amount" id="total_amount_input" value="<?php echo $productSubtotal; ?>">

            <div class="flex-1 space-y-8">
                
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-sm">1</span>
                        <?php echo lang('step_shipping_info'); ?>
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2"><?php echo lang('form_fullname'); ?></label>
                            <input type="text" name="full_name" value="<?php echo $fullName; ?>" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary transition" required>
                        </div>
                        
                        <div class="md:col-span-1">
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2"><?php echo lang('form_phone'); ?></label>
                            <input type="tel" name="phone" value="<?php echo htmlspecialchars($shippingPhone); ?>" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary transition" placeholder="024 XXXXXXX" required>
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2"><?php echo lang('form_email'); ?></label>
                            <input type="email" name="email" value="<?php echo $email; ?>" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-500" readonly>
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2"><?php echo lang('form_city'); ?></label>
                            <input type="text" name="city" value="Accra" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-100 text-slate-600 font-bold cursor-not-allowed" readonly>
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2"><?php echo lang('form_region'); ?></label>
                            <input type="text" name="region" value="Greater Accra" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-100 text-slate-600 font-bold cursor-not-allowed" readonly>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2"><?php echo lang('form_neighborhood'); ?></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <i class="fas fa-map-signs"></i>
                                </span>
                                <select name="address" id="neighborhood_select" class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary focus:border-primary transition appearance-none bg-white cursor-pointer" required>
                                    <option value="" disabled <?php echo empty($savedAddress) ? 'selected' : ''; ?>>-- <?php echo lang('select_option'); ?> --</option>
                                    
                                    <?php foreach($accraLocations as $loc => $price): ?>
                                        <?php $isSelected = ($loc == $savedAddress) ? 'selected' : ''; ?>
                                        <option value="<?php echo $loc; ?>" data-price="<?php echo $price; ?>" <?php echo $isSelected; ?>>
                                            <?php echo $loc; ?> (+ <?php echo $price; ?> <?php echo CURRENCY_SYMBOL; ?>)
                                        </option>
                                    <?php endforeach; ?>
                                    
                                </select>
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </span>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2"><?php echo lang('form_additional_info'); ?></label>
                            <textarea name="address_details" rows="2" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary transition" placeholder="<?php echo lang('ph_additional_info'); ?>"></textarea>
                        </div>

                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                    <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-sm">2</span>
                        <?php echo lang('step_payment_method'); ?>
                    </h2>
                    <div class="space-y-4">
                        <label class="relative flex items-center p-4 border border-slate-200 rounded-xl cursor-pointer hover:border-primary transition group has-[:checked]:border-primary has-[:checked]:bg-blue-50/50 has-[:checked]:ring-1 has-[:checked]:ring-primary">
                            <input type="radio" name="payment_method" value="paystack" class="h-5 w-5 text-primary focus:ring-primary border-gray-300" checked>
                            <div class="ml-4 flex-1">
                                <span class="block font-bold text-slate-900"><?php echo lang('pay_now_title'); ?></span>
                                <span class="block text-xs text-slate-500"><?php echo lang('pay_now_desc'); ?></span>
                            </div>
                            <i class="fas fa-mobile-alt text-2xl text-slate-400"></i>
                        </label>

                        <label class="relative flex items-center p-4 border border-slate-200 rounded-xl cursor-pointer hover:border-primary transition group has-[:checked]:border-primary has-[:checked]:bg-blue-50/50 has-[:checked]:ring-1 has-[:checked]:ring-primary">
                            <input type="radio" name="payment_method" value="cod" class="h-5 w-5 text-primary focus:ring-primary border-gray-300">
                            <div class="ml-4 flex-1">
                                <span class="block font-bold text-slate-900"><?php echo lang('pay_cod_title'); ?></span>
                                <span class="block text-xs text-slate-500"><?php echo lang('pay_cod_desc'); ?></span>
                            </div>
                            <i class="fas fa-money-bill-wave text-2xl text-slate-400"></i>
                        </label>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-96 flex-shrink-0">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 sticky top-24">
                    <h3 class="font-bold text-slate-900 text-lg mb-6"><?php echo lang('summary_title'); ?></h3>

                    <div class="space-y-4 mb-6 max-h-60 overflow-y-auto custom-scrollbar pr-2">
                        <?php if(!empty($cartItems)): ?>
                            <?php foreach($cartItems as $item): ?>
                                <?php 
                                    // Bilinguisme Produit
                                    $pName = ($lang == 'fr' && !empty($item->name_fr)) ? $item->name_fr : $item->name;
                                    $imgUrl = !empty($item->image) ? URLROOT . '/uploads/' . $item->image : URLROOT . '/img/no-image.jpg';
                                ?>
                                <div class="flex gap-4 items-center">
                                    <div class="w-12 h-12 bg-slate-100 rounded-lg overflow-hidden flex-shrink-0 border border-slate-200">
                                        <img src="<?php echo $imgUrl; ?>" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-slate-900 truncate"><?php echo $pName; ?></p>
                                        <p class="text-[10px] text-slate-500">x<?php echo $item->qty; ?></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-slate-800">
                                            <?php echo number_format($item->line_total, 2); ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="border-t border-slate-100 pt-4 space-y-3 mb-6">
                        <div class="flex justify-between text-slate-500 text-sm">
                            <span><?php echo lang('order_subtotal'); ?></span>
                            <span class="font-medium text-slate-900"><?php echo number_format($productSubtotal, 2); ?> <?php echo CURRENCY_SYMBOL; ?></span>
                        </div>
                        <div class="flex justify-between text-slate-500 text-sm">
                            <span><?php echo lang('order_shipping'); ?></span>
                            <span class="text-slate-900 font-bold text-xs uppercase" id="shipping_display">--</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-end mb-8 pt-4 border-t border-dashed border-slate-200">
                        <span class="font-bold text-lg text-slate-900"><?php echo lang('order_total'); ?></span>
                        <div class="text-right">
                            <span class="font-black text-2xl text-primary" id="total_display">
                                <?php echo number_format($productSubtotal, 2); ?> <?php echo CURRENCY_SYMBOL; ?>
                            </span>
                        </div>
                    </div>

                    <button type="submit" id="submitBtn" class="w-full bg-slate-900 text-white py-4 rounded-xl font-bold hover:bg-primary transition shadow-xl shadow-slate-900/20 flex justify-center items-center gap-2 group disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <span><?php echo lang('btn_select_address'); ?></span>
                        <i class="fas fa-check-circle group-hover:translate-x-1 transition"></i>
                    </button>
                    
                    <p class="text-center text-[10px] text-slate-400 mt-4 flex items-center justify-center gap-1">
                        <i class="fas fa-shield-alt text-green-500"></i> SSL Secure Payment
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // === DYNAMIC CALCULATION SCRIPT ===
    const CURRENCY_SYMBOL = "<?php echo CURRENCY_SYMBOL; ?>";
    const subtotal = <?php echo $productSubtotal; ?>;
    const btnTextPlace = "<?php echo lang('btn_place_order'); ?>"; // Translated text for JS
    
    const select = document.getElementById('neighborhood_select');
    const shippingDisplay = document.getElementById('shipping_display');
    const totalDisplay = document.getElementById('total_display');
    const shippingInput = document.getElementById('shipping_cost_input');
    const totalInput = document.getElementById('total_amount_input');
    const submitBtn = document.getElementById('submitBtn');

    // Update function
    function updateTotals() {
        const selectedOption = select.options[select.selectedIndex];
        
        if(selectedOption.disabled) return;

        const shippingCost = parseFloat(selectedOption.getAttribute('data-price'));

        if(!isNaN(shippingCost)) {
            // 1. Visual update
            shippingDisplay.innerHTML = shippingCost.toFixed(2) + ' ' + CURRENCY_SYMBOL;
            shippingDisplay.classList.add('text-green-600');
            
            const total = subtotal + shippingCost;
            // Format
            totalDisplay.innerHTML = total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, " ") + ' ' + CURRENCY_SYMBOL;

            // 2. Update hidden fields
            shippingInput.value = shippingCost;
            totalInput.value = total;

            // 3. Enable button
            submitBtn.disabled = false;
            submitBtn.querySelector('span').textContent = btnTextPlace;
            submitBtn.classList.remove('bg-slate-400');
            submitBtn.classList.add('bg-slate-900');
        }
    }

    select.addEventListener('change', updateTotals);

    document.addEventListener('DOMContentLoaded', function() {
        if(select.value !== "") {
            updateTotals();
        }
    });
</script>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>