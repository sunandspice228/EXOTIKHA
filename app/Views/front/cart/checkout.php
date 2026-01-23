<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div class="max-w-7xl mx-auto px-6 py-16">
    <h1 class="text-3xl font-serif font-bold mb-10 text-center">Finalize Your Order</h1>
    
    <?php flash('cart_msg'); ?>

    <form id="checkoutForm" action="<?php echo URLROOT; ?>/cart/checkout" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <input type="hidden" name="paystack_ref" id="paystack_ref_final">
        
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                <h2 class="text-xl font-bold mb-6">Delivery Address</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="phone" placeholder="Phone Number *" required class="border-slate-200 rounded-lg p-3">
                    <select name="region" required class="border-slate-200 rounded-lg p-3"><option value="Greater Accra">Greater Accra</option><option value="Ashanti">Ashanti</option></select>
                    <input type="text" name="city" placeholder="City / Town *" required class="border-slate-200 rounded-lg p-3">
                    <input type="text" name="address" placeholder="Address / GPS *" required class="border-slate-200 rounded-lg p-3 md:col-span-2">
                </div>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                <h2 class="text-xl font-bold mb-6">Payment Method</h2>
                <div class="space-y-4">
                    <?php foreach($data['savedCards'] as $card): ?>
                        <label class="flex items-center gap-4 p-4 border rounded-xl cursor-pointer hover:bg-slate-50 transition">
                            <input type="radio" name="payment_method" value="saved_card" class="text-accent" onclick="document.getElementById('saved_card_id').value='<?php echo $card->id; ?>'">
                            <span>Pay with card <strong>**** <?php echo $card->last4; ?></strong></span>
                        </label>
                    <?php endforeach; ?>
                    <input type="hidden" name="saved_card_id" id="saved_card_id">

                    <label class="flex items-center gap-4 p-4 border rounded-xl cursor-pointer hover:bg-slate-50 transition">
                        <input type="radio" name="payment_method" value="paystack" checked class="text-accent" onclick="document.getElementById('saved_card_id').value=''">
                        <span>Pay Now (Mobile Money / Card)</span>
                    </label>

                    <label class="flex items-center gap-4 p-4 border rounded-xl cursor-pointer hover:bg-slate-50 transition">
                        <input type="radio" name="payment_method" value="cod" class="text-accent" onclick="document.getElementById('saved_card_id').value=''">
                        <span>Cash on Delivery</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 h-fit bg-slate-50 p-8 rounded-2xl border border-slate-200 sticky top-24">
            <h3 class="font-bold text-xl mb-6">Summary</h3>
            <div class="flex justify-between font-bold text-2xl border-t pt-4 mb-8">
                <span>Total</span><span><?php echo CURRENCY_SYMBOL . number_format($data['total'], 2); ?></span>
            </div>
            <button type="button" onclick="handlePay()" class="w-full bg-primary text-white py-4 rounded-xl font-bold shadow-lg hover:bg-slate-900 transition uppercase tracking-widest">Place Order</button>
        </div>
    </form>
</div>

<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
    function handlePay() {
        const form = document.getElementById('checkoutForm');
        const method = document.querySelector('input[name="payment_method"]:checked').value;
        if(!form.checkValidity()){ form.reportValidity(); return; }

        if(method === 'paystack') {
            let handler = PaystackPop.setup({
                key: '<?php echo PAYSTACK_PUBLIC_KEY; ?>',
                email: '<?php echo $_SESSION['user_email']; ?>',
                amount: <?php echo $data['total'] * 100; ?>,
                currency: 'GHS',
                callback: function(res){ document.getElementById('paystack_ref_final').value = res.reference; form.submit(); }
            });
            handler.openIframe();
        } else {
            form.submit();
        }
    }
</script>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>