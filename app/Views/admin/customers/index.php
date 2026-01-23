<?php require APPROOT . '/Views/admin/inc/header.php'; ?>

<div class="p-6">
    <h1 class="text-2xl font-bold text-slate-800 mb-8">Base Clients</h1>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-xs font-black uppercase text-slate-400 border-b">
                <tr>
                    <th class="px-6 py-4">Nom</th>
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4">Téléphone</th>
                    <th class="px-6 py-4">Inscrit le</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 text-sm">
                <?php foreach($data['customers'] as $customer): ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-bold text-slate-800"><?php echo $customer->full_name; ?></td>
                        <td class="px-6 py-4 text-slate-500"><?php echo $customer->email; ?></td>
                        <td class="px-6 py-4 font-mono text-slate-600"><?php echo $customer->billing_phone ?? 'N/A'; ?></td>
                        <td class="px-6 py-4 text-slate-400"><?php echo date('d/m/Y', strtotime($customer->created_at)); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require APPROOT . '/Views/admin/inc/footer.php'; ?>