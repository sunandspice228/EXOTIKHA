<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-4 animate-fade-in-up">
    <div>
        <h2 class="text-3xl font-bold text-slate-900 mb-1"><?php echo lang('dash_welcome'); ?></h2>
        <p class="text-slate-500 text-sm"><?php echo lang('dash_subtitle'); ?></p>
    </div>
    <div class="flex gap-2">
        <a href="<?php echo URLROOT; ?>/admin/products/add" class="bg-slate-900 text-white px-4 py-2 rounded-lg font-bold text-sm hover:bg-primary transition shadow-lg flex items-center gap-2">
            <i class="fas fa-plus"></i> <?php echo lang('btn_add_product'); ?>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition">
        <div class="relative z-10">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1"><?php echo lang('stat_revenue'); ?></p>
            <h3 class="text-2xl font-black text-slate-800">
                <?php echo number_format((float)($data['revenue'] ?? 0), 2); ?> 
                <span class="text-sm text-slate-400"><?php echo CURRENCY_SYMBOL; ?></span>
            </h3>
        </div>
        <div class="absolute right-4 bottom-4 w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center text-xl shadow-sm">
            <i class="fas fa-wallet"></i>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition">
        <div class="relative z-10">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1"><?php echo lang('stat_orders'); ?></p>
            <h3 class="text-2xl font-black text-slate-800"><?php echo $data['total_orders']; ?></h3>
        </div>
        <div class="absolute right-4 bottom-4 w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shadow-sm">
            <i class="fas fa-shopping-bag"></i>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition">
        <div class="relative z-10">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1"><?php echo lang('stat_products'); ?></p>
            <h3 class="text-2xl font-black text-slate-800"><?php echo $data['total_products']; ?></h3>
        </div>
        <div class="absolute right-4 bottom-4 w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shadow-sm">
            <i class="fas fa-tshirt"></i>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition">
        <div class="relative z-10">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1"><?php echo lang('stat_users'); ?></p>
            <h3 class="text-2xl font-black text-slate-800"><?php echo $data['total_users']; ?></h3>
        </div>
        <div class="absolute right-4 bottom-4 w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-xl shadow-sm">
            <i class="fas fa-users"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-slate-800"><?php echo lang('dash_sales_overview'); ?></h3>
            <span class="text-xs font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded">Last 6 months</span>
        </div>
        <div class="h-72 w-full relative">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
        <div class="p-6 border-b border-slate-50 flex justify-between items-center">
            <h3 class="font-bold text-slate-800"><?php echo lang('dash_recent_orders'); ?></h3>
            <a href="<?php echo URLROOT; ?>/admin/orders" class="text-xs font-bold text-primary hover:underline"><?php echo lang('btn_view_all'); ?></a>
        </div>
        
        <div class="overflow-y-auto custom-scrollbar flex-1 p-2 max-h-[300px]">
            <?php if(empty($data['recent_orders'])): ?>
                <p class="text-center text-slate-400 text-sm py-8 italic"><?php echo lang('dash_no_orders'); ?></p>
            <?php else: ?>
                <div class="space-y-2">
                    <?php foreach($data['recent_orders'] as $order): ?>
                        <a href="<?php echo URLROOT; ?>/admin/orders/details/<?php echo $order->id; ?>" class="block p-3 rounded-xl hover:bg-slate-50 transition group border border-transparent hover:border-slate-100">
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
                                    <p class="text-[10px] uppercase font-bold <?php echo $color; ?>"><?php echo lang('status_' . $order->status); ?></p>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart');
        if (ctx) {
            // PHP Data injection
            const rawData = <?php echo json_encode(array_reverse($data['monthly_stats'])); ?>;
            
            const labels = rawData.map(item => {
                const date = new Date(item.month + '-01');
                return date.toLocaleString('default', { month: 'short' }); // Auto localize
            });
            
            const dataPoints = rawData.map(item => item.total);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Sales',
                        data: dataPoints,
                        borderColor: '#6366f1', // Indigo 500
                        backgroundColor: (context) => {
                            const ctx = context.chart.ctx;
                            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                            gradient.addColorStop(0, 'rgba(99, 102, 241, 0.2)');
                            gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');
                            return gradient;
                        },
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#6366f1',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
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