<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="max-w-7xl mx-auto">
    
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Dashboard</h1>
            <p class="text-slate-500 mt-1">
                Overview of your store performance.
            </p>
        </div>
        <div class="flex gap-3">
            <a href="<?php echo URLROOT; ?>/admin/products/add" class="px-4 py-2 bg-slate-900 text-white rounded-lg text-sm font-bold hover:bg-primary transition shadow-lg flex items-center gap-2">
                <i class="fas fa-plus"></i> New Product
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between group hover:border-green-200 transition duration-300 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Revenue</p>
                <h3 class="text-2xl font-black text-slate-800">
                    <?php echo number_format($data['revenue'], 0, ',', ' '); ?> <span class="text-sm text-slate-400"><?php echo CURRENCY_SYMBOL; ?></span>
                </h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-xl shadow-sm">
                <i class="fas fa-wallet"></i>
            </div>
            <div class="absolute -bottom-4 -left-4 w-24 h-24 bg-green-50 rounded-full opacity-50"></div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between group hover:border-blue-200 transition duration-300 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Orders</p>
                <h3 class="text-2xl font-black text-slate-800"><?php echo $data['total_orders']; ?></h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shadow-sm">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="absolute -bottom-4 -left-4 w-24 h-24 bg-blue-50 rounded-full opacity-50"></div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between group hover:border-purple-200 transition duration-300 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Catalog</p>
                <h3 class="text-2xl font-black text-slate-800"><?php echo $data['total_products']; ?> <span class="text-sm font-medium text-slate-400">products</span></h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shadow-sm">
                <i class="fas fa-tshirt"></i>
            </div>
            <div class="absolute -bottom-4 -left-4 w-24 h-24 bg-purple-50 rounded-full opacity-50"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-slate-800">Sales Overview</h3>
                <span class="text-xs font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded">Last 6 months</span>
            </div>
            <div class="h-72 w-full">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
            <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Recent Orders</h3>
                <a href="<?php echo URLROOT; ?>/admin/orders" class="text-xs font-bold text-primary hover:underline">View All</a>
            </div>
            
            <div class="overflow-y-auto custom-scrollbar flex-1 p-2">
                <?php if(empty($data['recent_orders'])): ?>
                    <p class="text-center text-slate-400 text-sm py-8 italic">No recent orders.</p>
                <?php else: ?>
                    <div class="space-y-2">
                        <?php foreach($data['recent_orders'] as $order): ?>
                            <a href="<?php echo URLROOT; ?>/admin/order_details/<?php echo $order->id; ?>" class="block p-3 rounded-xl hover:bg-slate-50 transition group">
                                <div class="flex justify-between items-start mb-1">
                                    <span class="font-mono text-xs font-bold text-slate-500">#<?php echo $order->order_number; ?></span>
                                    <span class="text-[10px] font-bold text-slate-400"><?php echo date('d M', strtotime($order->created_at)); ?></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="text-sm font-bold text-slate-800 group-hover:text-primary transition"><?php echo $order->full_name; ?></p>
                                        
                                        <?php 
                                            $color = 'text-slate-400';
                                            if($order->status == 'completed' || $order->status == 'delivered') $color = 'text-green-500';
                                            if($order->status == 'processing') $color = 'text-blue-500';
                                            if($order->status == 'cancelled') $color = 'text-red-500';
                                        ?>
                                        <p class="text-[10px] uppercase font-bold <?php echo $color; ?>"><?php echo $order->status; ?></p>
                                    </div>
                                    <span class="font-bold text-slate-900 text-sm"><?php echo number_format($order->total_amount, 0, ',', ' '); ?> <?php echo CURRENCY_SYMBOL; ?></span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart');
        if (ctx) {
            // PHP Data to JS
            const rawData = <?php echo json_encode(array_reverse($data['monthly_stats'])); ?>;
            
            const labels = rawData.map(item => {
                // Format date (e.g., 2024-01 -> Jan)
                const date = new Date(item.month + '-01');
                return date.toLocaleString('en-US', { month: 'short' });
            });
            
            const dataPoints = rawData.map(item => item.total);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Sales',
                        data: dataPoints,
                        borderColor: '#4f46e5', // Primary Color (Indigo)
                        backgroundColor: (context) => {
                            const ctx = context.chart.ctx;
                            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                            gradient.addColorStop(0, 'rgba(79, 70, 229, 0.2)');
                            gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');
                            return gradient;
                        },
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#4f46e5',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        fill: true,
                        tension: 0.4 // Smooth curve
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            padding: 12,
                            titleFont: { size: 13 },
                            bodyFont: { size: 14, weight: 'bold' },
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y.toLocaleString() + ' <?php echo CURRENCY_SYMBOL; ?>';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [4, 4], color: '#f1f5f9' },
                            ticks: { font: { size: 10 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 } }
                        }
                    }
                }
            });
        }
    });
</script>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>