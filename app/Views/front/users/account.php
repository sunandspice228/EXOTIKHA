<?php require APPROOT . '/Views/front/layout/header.php'; ?>

<div class="bg-slate-50 py-10 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-6 text-center md:text-left">
        <h1 class="text-3xl font-serif font-bold text-slate-900">My Account</h1>
        <p class="text-slate-500 text-sm">Manage your orders, payments, and share your experience.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 py-12">
    <div class="flex flex-col lg:flex-row gap-10">
        
        <aside class="w-full lg:w-64 flex-shrink-0">
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden sticky top-24">
                
                <div class="p-6 border-b border-slate-100 bg-slate-50 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold text-lg">
                        <?php echo substr($data['user']->full_name, 0, 1); ?>
                    </div>
                    <div class="truncate">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Welcome,</p>
                        <p class="font-bold text-slate-900 truncate"><?php echo $data['user']->full_name; ?></p>
                    </div>
                </div>

                <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                    <div class="p-4 bg-slate-900">
                        <a href="<?php echo URLROOT; ?>/admin" class="block w-full bg-red-600 text-white text-center py-3 rounded-lg font-bold text-sm shadow-lg hover:bg-red-700 transition transform hover:scale-105">
                            <i class="fas fa-user-shield mr-2"></i> ADMIN PANEL
                        </a>
                    </div>
                <?php endif; ?>
                
                <nav class="p-2 space-y-1">
                    <a href="?tab=dashboard" class="nav-link <?php echo ($data['tab'] == 'dashboard') ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt w-5"></i> Dashboard</a>
                    <a href="?tab=orders" class="nav-link <?php echo ($data['tab'] == 'orders') ? 'active' : ''; ?>"><i class="fas fa-shopping-bag w-5"></i> Orders</a>
                    <a href="?tab=track" class="nav-link <?php echo ($data['tab'] == 'track') ? 'active' : ''; ?>"><i class="fas fa-search-location w-5"></i> Track Order</a>
                    <a href="?tab=review" class="nav-link <?php echo ($data['tab'] == 'review') ? 'active' : ''; ?> text-amber-600"><i class="fas fa-star w-5"></i> Write a Review</a>
                    <a href="?tab=addresses" class="nav-link <?php echo ($data['tab'] == 'addresses') ? 'active' : ''; ?>"><i class="fas fa-map-marker-alt w-5"></i> Addresses</a>
                    <a href="?tab=payment" class="nav-link <?php echo ($data['tab'] == 'payment') ? 'active' : ''; ?>"><i class="fas fa-credit-card w-5"></i> Payment Methods</a>
                    <a href="?tab=details" class="nav-link <?php echo ($data['tab'] == 'details') ? 'active' : ''; ?>"><i class="fas fa-user-cog w-5"></i> Account Details</a>
                    <a href="<?php echo URLROOT; ?>/users/logout" class="nav-link text-red-500 mt-4 border-t border-slate-50"><i class="fas fa-sign-out-alt w-5"></i> Logout</a>
                </nav>
            </div>
        </aside>

        <div class="flex-1">
            <?php flash('product_message'); ?>

            <?php if($data['tab'] == 'dashboard'): ?>
                <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm mb-6">
                    <h2 class="text-2xl font-serif font-bold text-slate-900 mb-4 italic">Hello, <?php echo $data['user']->full_name; ?></h2>
                    <p class="text-slate-600 leading-relaxed">From your account dashboard you can view your <a href="?tab=orders" class="text-accent font-bold">recent orders</a>, manage your <a href="?tab=addresses" class="text-accent font-bold">shipping addresses</a>, and share your <a href="?tab=review" class="text-accent font-bold">feedback</a> with us.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-6 bg-indigo-50 rounded-2xl border border-indigo-100 flex items-center gap-4">
                        <div class="w-12 h-12 bg-white text-indigo-600 rounded-full flex items-center justify-center text-xl shadow-sm"><i class="fas fa-shopping-cart"></i></div>
                        <div><span class="block text-2xl font-bold"><?php echo count($data['orders']); ?></span><span class="text-xs uppercase font-bold text-slate-400 tracking-widest">Total Orders</span></div>
                    </div>
                </div>

            <?php elseif($data['tab'] == 'orders'): ?>
                <h2 class="text-xl font-bold mb-6 italic font-serif">Order History</h2>
                <?php if(empty($data['orders'])): ?>
                    <div class="bg-white p-10 rounded-2xl border border-slate-100 text-center text-slate-400 italic">No orders found. <a href="<?php echo URLROOT; ?>/shop" class="text-accent font-bold not-italic ml-2 underline">Shop Now</a></div>
                <?php else: ?>
                    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 text-[10px] font-black uppercase text-slate-400 border-b">
                                <tr>
                                    <th class="px-6 py-4">ID</th><th class="px-6 py-4">Date</th><th class="px-6 py-4">Status</th><th class="px-6 py-4">Total</th><th class="px-6 py-4 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm italic font-medium">
                                <?php foreach($data['orders'] as $order): ?>
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-6 py-4 font-bold text-slate-900">#<?php echo $order->order_number; ?></td>
                                        <td class="px-6 py-4 text-slate-500"><?php echo date('M d, Y', strtotime($order->created_at)); ?></td>
                                        <td class="px-6 py-4"><span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase <?php echo ($order->status == 'paid' || $order->status == 'shipped') ? 'bg-green-50 text-green-600' : 'bg-slate-100 text-slate-500'; ?>"><?php echo $order->status; ?></span></td>
                                        <td class="px-6 py-4 font-bold text-slate-900"><?php echo CURRENCY_SYMBOL . number_format($order->total_amount, 2); ?></td>
                                        <td class="px-6 py-4 text-right">
                                            <form action="<?php echo URLROOT; ?>/pages/track" method="POST" target="_blank" class="inline">
                                                <input type="hidden" name="order_number" value="<?php echo $order->order_number; ?>">
                                                <input type="hidden" name="email" value="<?php echo $data['user']->email; ?>">
                                                <button class="text-accent hover:underline font-black text-xs uppercase tracking-tighter">Track</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

            <?php elseif($data['tab'] == 'track'): ?>
                <h2 class="text-xl font-bold mb-6 italic font-serif text-slate-900">Track Shipment</h2>
                <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm max-w-lg">
                    <form action="<?php echo URLROOT; ?>/pages/track" method="POST" class="space-y-4">
                        <div>
                            <label class="block text-xs font-black uppercase text-slate-400 mb-2">Order ID</label>
                            <input type="text" name="order_number" class="w-full rounded-xl border-slate-200 focus:ring-accent" placeholder="Ex: 58392" required>
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase text-slate-400 mb-2">Email</label>
                            <input type="email" name="email" value="<?php echo $data['user']->email; ?>" class="w-full rounded-xl border-slate-200 bg-slate-50 text-slate-400" readonly>
                        </div>
                        <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-xl font-bold hover:bg-accent transition shadow-lg uppercase tracking-widest">Track Now</button>
                    </form>
                </div>

            <?php elseif($data['tab'] == 'review'): ?>
                <h2 class="text-xl font-bold mb-6 italic font-serif">Leave a Testimonial</h2>
                <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm max-w-xl">
                    <form action="<?php echo URLROOT; ?>/users/add_review" method="POST" class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-3">Overall Satisfaction</label>
                            <div class="flex gap-4 items-center">
                                <?php for($i=1; $i<=5; $i++): ?>
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="rating" value="<?php echo $i; ?>" class="hidden peer" required>
                                        <i class="fas fa-star text-3xl text-slate-200 peer-checked:text-yellow-400 group-hover:text-yellow-300 transition-colors"></i>
                                    </label>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 italic">Tell us what you liked about Exotikha...</label>
                            <textarea name="comment" rows="4" class="w-full rounded-xl border-slate-200 focus:ring-accent px-4 py-3 text-sm italic" placeholder="The service was amazing..." required></textarea>
                        </div>
                        <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-xl font-bold hover:bg-accent transition shadow-lg">Submit Feedback</button>
                    </form>
                </div>

            <?php elseif($data['tab'] == 'addresses'): ?>
                <h2 class="text-xl font-bold mb-6 italic font-serif">Address Management</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                        <h3 class="font-bold border-b pb-2 mb-4">Billing Address</h3>
                        <form action="" method="POST" class="space-y-4">
                            <input type="hidden" name="address_type" value="billing">
                            <input type="text" name="billing_phone" value="<?php echo $data['user']->billing_phone; ?>" placeholder="Phone" class="w-full rounded-lg border-slate-200">
                            <div class="grid grid-cols-2 gap-2">
                                <select id="billing_region" name="billing_region" onchange="updateCities('billing')" class="w-full rounded-lg border-slate-200 text-sm"></select>
                                <select id="billing_city" name="billing_city" class="w-full rounded-lg border-slate-200 text-sm"></select>
                            </div>
                            <input type="text" name="billing_address" value="<?php echo $data['user']->billing_address; ?>" placeholder="Street Address / GPS" class="w-full rounded-lg border-slate-200">
                            <button type="submit" name="update_address" class="w-full bg-slate-900 text-white py-2 rounded-lg font-bold hover:bg-black transition">Save Changes</button>
                        </form>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                        <h3 class="font-bold border-b pb-2 mb-4 text-accent">Shipping Address</h3>
                        <form action="" method="POST" class="space-y-4">
                            <input type="hidden" name="address_type" value="shipping">
                            <input type="text" name="shipping_phone" value="<?php echo $data['user']->shipping_phone; ?>" placeholder="Phone" class="w-full rounded-lg border-slate-200">
                            <div class="grid grid-cols-2 gap-2">
                                <select id="shipping_region" name="shipping_region" onchange="updateCities('shipping')" class="w-full rounded-lg border-slate-200 text-sm"></select>
                                <select id="shipping_city" name="shipping_city" class="w-full rounded-lg border-slate-200 text-sm"></select>
                            </div>
                            <input type="text" name="shipping_address" value="<?php echo $data['user']->shipping_address; ?>" placeholder="Street Address / GPS" class="w-full rounded-lg border-slate-200">
                            <button type="submit" name="update_address" class="w-full bg-accent text-white py-2 rounded-lg font-bold hover:bg-indigo-600 transition">Save Changes</button>
                        </form>
                    </div>
                </div>

            <?php elseif($data['tab'] == 'payment'): ?>
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold italic font-serif">Payment preferences</h2>
                    <button onclick="togglePaymentForm()" class="bg-primary text-white px-4 py-2 rounded-full text-xs font-bold shadow hover:bg-slate-800 transition">Add New</button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                    <?php if(empty($data['cards'])): ?>
                        <div class="md:col-span-2 text-center py-10 text-slate-300 italic">No methods saved.</div>
                    <?php endif; ?>
                    <?php foreach($data['cards'] as $card): ?>
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden group">
                            <i class="fab fa-cc-<?php echo strtolower($card->card_type); ?> absolute -bottom-4 -right-4 text-7xl text-slate-50 opacity-50"></i>
                            <div class="relative z-10">
                                <div class="flex justify-between mb-4">
                                    <span class="text-[9px] font-black uppercase bg-slate-900 text-white px-2 py-0.5 rounded"><?php echo $card->bank; ?></span>
                                    <a href="<?php echo URLROOT; ?>/users/delete_card/<?php echo $card->id; ?>" class="text-red-300 hover:text-red-600"><i class="fas fa-trash"></i></a>
                                </div>
                                <p class="font-mono text-lg text-slate-700">**** **** **** <?php echo $card->last4; ?></p>
                                <p class="text-[10px] text-slate-400 mt-2 capitalize"><?php echo $card->card_type; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div id="paymentFormContainer" class="hidden animate-fade-in-down bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden max-w-2xl mx-auto mb-10">
                    <div class="flex border-b">
                        <button onclick="switchTab('momo')" id="btn-momo" class="flex-1 py-4 text-sm font-bold border-b-2 border-primary bg-slate-50 transition-all">Mobile Money (Ghana)</button>
                        <button onclick="switchTab('card')" id="btn-card" class="flex-1 py-4 text-sm font-bold border-b-2 border-transparent text-slate-400 hover:bg-slate-50 transition-all">Credit/Debit Card</button>
                    </div>

                    <div class="p-8">
                        <div id="form-momo" class="space-y-6">
                            <div class="flex justify-center gap-6 mb-6">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/9/93/MTN-Logo.600.png" class="h-8 object-contain grayscale hover:grayscale-0 cursor-pointer transition">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Vodafone_2017_logo.svg/1200px-Vodafone_2017_logo.svg.png" class="h-8 object-contain grayscale hover:grayscale-0 cursor-pointer transition">
                                <img src="https://thefindghana.com/wp-content/uploads/2021/04/AirtelTigo-logo-scaled.jpg" class="h-8 object-contain grayscale hover:grayscale-0 cursor-pointer transition">
                            </div>
                            <p class="text-center text-xs text-slate-500">Select your provider in the Paystack popup.</p>
                        </div>

                        <div id="form-card" class="hidden space-y-6">
                            <div class="text-center">
                                <i class="fas fa-credit-card text-4xl text-slate-200 mb-4"></i>
                                <p class="text-xs text-slate-500">Secure card tokenization via Paystack.</p>
                            </div>
                        </div>

                        <button onclick="processPaystack()" class="w-full mt-8 bg-primary text-white py-4 rounded-xl font-bold hover:bg-slate-800 transition shadow-lg flex items-center justify-center gap-2">
                            <i class="fas fa-shield-alt"></i> Verify & Save Method
                        </button>
                        <p class="text-[10px] text-center text-slate-400 mt-4 uppercase tracking-tighter">Your details are never stored on our servers. Encrypted by Paystack.</p>
                    </div>
                </div>

                <script src="https://js.paystack.co/v1/inline.js"></script>
                <form id="paystack_final" action="" method="POST" style="display:none;"><input type="hidden" name="paystack_ref" id="pref"><input type="hidden" name="save_new_card" value="1"></form>

                <script>
                    function togglePaymentForm() { 
                        const container = document.getElementById('paymentFormContainer');
                        container.classList.toggle('hidden');
                        if(!container.classList.contains('hidden')) container.scrollIntoView({ behavior: 'smooth' });
                    }
                    function switchTab(type) {
                        const isMomo = type === 'momo';
                        document.getElementById('form-momo').classList.toggle('hidden', !isMomo);
                        document.getElementById('form-card').classList.toggle('hidden', isMomo);
                        document.getElementById('btn-momo').className = isMomo ? 'flex-1 py-4 text-sm font-bold border-b-2 border-primary bg-slate-50' : 'flex-1 py-4 text-sm font-bold border-b-2 border-transparent text-slate-400 hover:bg-slate-50';
                        document.getElementById('btn-card').className = !isMomo ? 'flex-1 py-4 text-sm font-bold border-b-2 border-primary bg-slate-50' : 'flex-1 py-4 text-sm font-bold border-b-2 border-transparent text-slate-400 hover:bg-slate-50';
                    }
                    function processPaystack() {
                        const handler = PaystackPop.setup({
                            key: '<?php echo PAYSTACK_PUBLIC_KEY; ?>',
                            email: '<?php echo $data['user']->email; ?>',
                            amount: 10, // 0.10 GHS charge
                            currency: 'GHS',
                            callback: function(res){ document.getElementById('pref').value = res.reference; document.getElementById('paystack_final').submit(); }
                        });
                        handler.openIframe();
                    }
                </script>

            <?php elseif($data['tab'] == 'details'): ?>
                <h2 class="text-xl font-bold mb-6 italic font-serif">Personal Settings</h2>
                <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm max-w-2xl">
                    <form action="" method="POST" class="space-y-6 italic">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div><label class="block text-[10px] font-black uppercase text-slate-400 mb-2">Name</label><input type="text" name="name" value="<?php echo $data['user']->full_name; ?>" class="w-full rounded-xl border-slate-200"></div>
                            <div><label class="block text-[10px] font-black uppercase text-slate-400 mb-2">Email</label><input type="email" name="email" value="<?php echo $data['user']->email; ?>" class="w-full rounded-xl border-slate-200"></div>
                        </div>
                        <div class="border-t pt-6"><p class="font-bold text-slate-900 mb-4 not-italic">Security</p>
                            <input type="password" name="current_password" placeholder="Current Password" class="w-full rounded-xl border-slate-200 mb-4">
                            <div class="grid grid-cols-2 gap-4"><input type="password" name="new_password" placeholder="New" class="w-full rounded-xl border-slate-200"><input type="password" name="confirm_password" placeholder="Confirm" class="w-full rounded-xl border-slate-200"></div>
                        </div>
                        <button type="submit" name="update_details" class="w-full bg-primary text-white py-4 rounded-xl font-bold not-italic hover:bg-slate-800 transition shadow-lg">Update Profile</button>
                    </form>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<style>
    .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; font-size: 13px; font-weight: 700; color: #64748b; transition: all 0.3s; text-transform: uppercase; letter-spacing: 0.05em; }
    .nav-link:hover { background: #f1f5f9; color: #4f46e5; }
    .nav-link.active { background: #4f46e5 !important; color: white !important; box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.2); }
    @keyframes fade-in-down { 0% { opacity: 0; transform: translateY(-10px); } 100% { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-down { animation: fade-in-down 0.4s ease-out; }
</style>

<script>
const ghLocs = { "Greater Accra": ["Accra", "Tema", "East Legon", "Spintex", "Madina", "Adenta"], "Ashanti": ["Kumasi", "Obuasi"], "Central": ["Cape Coast", "Winneba", "Kasoa"], "Western": ["Takoradi", "Sekondi"], "Eastern": ["Koforidua"], "Volta": ["Ho"], "Northern": ["Tamale"] };
document.addEventListener('DOMContentLoaded', () => {
    initAdr('billing', "<?php echo $data['user']->billing_region; ?>", "<?php echo $data['user']->billing_city; ?>");
    initAdr('shipping', "<?php echo $data['user']->shipping_region; ?>", "<?php echo $data['user']->shipping_city; ?>");
});
function initAdr(t, rV, cV) {
    const r = document.getElementById(t + '_region');
    const c = document.getElementById(t + '_city');
    r.innerHTML = '<option value="">Select Region</option>';
    for (const key in ghLocs) { let o = document.createElement('option'); o.value = o.text = key; if(key === rV) o.selected = true; r.appendChild(o); }
    popC(t, cV);
}
function updateCities(t) { popC(t, null); }
function popC(t, s) {
    const r = document.getElementById(t + '_region').value; const c = document.getElementById(t + '_city');
    c.innerHTML = '<option value="">City</option>';
    if(r && ghLocs[r]) { ghLocs[r].forEach(cy => { let o = document.createElement('option'); o.value = o.text = cy; if(cy === s) o.selected = true; c.appendChild(o); }); }
}
</script>

<?php require APPROOT . '/Views/front/layout/footer.php'; ?>