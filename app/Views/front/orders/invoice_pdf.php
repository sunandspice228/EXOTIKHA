<?php 
// SÉCURITÉ & DONNÉES
// On s'assure que les données existent
$order = isset($data['order']) ? $data['order'] : null;
$items = isset($data['items']) ? $data['items'] : [];
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';

if(!$order) die("Order data missing.");

// --- GESTION DU LOGO POUR DOMPDF ---
// DomPDF préfère les chemins absolus système ou le Base64
$logoPath = APPROOT . '/../public/uploads/logo.png'; 
$logoData = '';

if(file_exists($logoPath)){
    $type = pathinfo($logoPath, PATHINFO_EXTENSION);
    $dataImg = file_get_contents($logoPath);
    $logoData = 'data:image/' . $type . ';base64,' . base64_encode($dataImg);
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?php echo $order->order_number; ?></title>
    <style>
        /* BASE STYLES - Optimisé pour A4 (DomPDF) */
        @page { margin: 0px; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 12px; 
            color: #333; 
            line-height: 1.4; 
            margin: 0; 
            padding: 40px; 
            background: #fff; 
        }
        
        .container { width: 100%; }
        
        /* HEADER */
        .header { width: 100%; border-bottom: 2px solid #ca8a04; padding-bottom: 20px; margin-bottom: 30px; }
        .header-table { width: 100%; }
        .header-left { text-align: left; vertical-align: top; width: 50%; }
        .header-right { text-align: right; vertical-align: top; width: 50%; }

        .logo-img { height: 40px; width: auto; display: block; margin-bottom: 5px; }
        .logo h1 { margin: 0; font-size: 20px; font-family: serif; color: #0f172a; text-transform: uppercase; }
        .logo p { margin: 0; color: #ca8a04; font-size: 8px; text-transform: uppercase; letter-spacing: 2px; font-weight: bold; }
        
        .company-info { font-size: 10px; color: #64748b; line-height: 1.4; }
        .company-info strong { color: #0f172a; font-size: 11px; }

        /* INFO GRID */
        .info-table { width: 100%; margin-bottom: 30px; }
        .info-col { vertical-align: top; width: 50%; }
        .info-col.right { text-align: right; }

        .label { font-size: 9px; font-weight: bold; text-transform: uppercase; color: #94a3b8; margin-bottom: 5px; display: block; }
        .info-value { font-size: 12px; color: #0f172a; }Here you are on a manHere you are on a montéHere you are on a monté weHere you are on a monté Here you are on a monté wechat everyone House
        
        /* BADGES */
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; text-transform: uppercase; color: #fff; display: inline-block; }
        .paid { background-color: #22c55e; color: white; }
        .pending { background-color: #f97316; color: white; }
        .cancelled { background-color: #ef4444; color: white; }

        /* ITEMS TABLE */
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th { text-align: left; padding: 10px 5px; border-bottom: 2px solid #cbd5e1; font-size: 9px; text-transform: uppercase; color: #475569; font-weight: bold; background-color: #f1f5f9; }
        .items-table td { padding: 12px 5px; border-bottom: 1px solid #e2e8f0; vertical-align: top; font-size: 11px; }
        
        .text-right { text-align: right; }
        .item-name { font-weight: bold; color: #0f172a; display: block; }
        .item-meta { font-size: 9px; color: #64748b; display: block; margin-top: 2px; }

        /* TOTALS */
        .totals-table { width: 100%; border-collapse: collapse; page-break-inside: avoid; }
        .totals-table td { padding: 5px 0; font-size: 11px; }
        .total-row td { border-top: 2px solid #ca8a04; padding-top: 10px; font-size: 14px; font-weight: bold; color: #0f172a; }
        .amount-col { text-align: right; width: 25%; }
        .label-col { text-align: right; width: 75%; padding-right: 10px; }

        /* FOOTER */
        .footer { position: fixed; bottom: 40px; left: 40px; right: 40px; border-top: 1px dashed #cbd5e1; padding-top: 10px; font-size: 9px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>

    <div class="container">
        
        <div class="header">
            <table class="header-table">
                <tr>
                    <td class="header-left">
                        <div class="logo">
                            <?php if($logoData): ?>
                                <img src="<?php echo $logoData; ?>" class="logo-img" alt="Exotikha Logo">
                            <?php else: ?>
                                <h1>Exotikha.</h1>
                            <?php endif; ?>
                            <p>Sensual • Elegant • Confident</p>
                        </div>
                    </td>
                    <td class="header-right">
                        <div class="company-info">
                            <strong>EXOTIKHA GHANA LTD</strong><br>
                            Haatso, Accra - Ghana<br>
                            +233 53 938 2808<br>
                            sales@exotikha.com
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <table class="info-table">
            <tr>
                <td class="info-col">
                    <span class="label"><?php echo lang('inv_billed_to'); ?></span>
                    <div class="info-value">
                        <strong><?php echo strtoupper(htmlspecialchars($order->full_name)); ?></strong><br>
                        <?php echo htmlspecialchars($order->shipping_address); ?><br>
                        <?php echo htmlspecialchars($order->shipping_city) . ', ' . htmlspecialchars($order->shipping_region); ?><br>
                        <?php echo htmlspecialchars($order->shipping_phone); ?><br>
                        <?php echo isset($order->email) ? htmlspecialchars($order->email) : ''; ?>
                    </div>
                </td>
                <td class="info-col right">
                    <span class="label"><?php echo lang('inv_details'); ?></span>
                    <div class="info-value">
                        <strong><?php echo lang('invoice_title'); ?> #<?php echo $order->order_number; ?></strong><br>
                        <?php echo date('d M Y', strtotime($order->created_at)); ?><br>
                        
                        <?php 
                            // Couleur du badge via classe CSS
                            $badgeClass = 'pending';
                            if($order->payment_status == 'paid') $badgeClass = 'paid';
                            if($order->status == 'cancelled') $badgeClass = 'cancelled';
                        ?>
                        <div style="margin-top: 5px;">
                            <span class="badge <?php echo $badgeClass; ?>">
                                <?php echo strtoupper($order->payment_status); ?>
                            </span>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th width="50%"><?php echo lang('inv_item'); ?></th>
                    <th width="15%" class="text-right"><?php echo lang('inv_price'); ?></th>
                    <th width="10%" class="text-right"><?php echo lang('inv_qty'); ?></th>
                    <th width="25%" class="text-right"><?php echo lang('inv_total'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($items as $item): ?>
                <?php 
                    $pName = ($lang == 'fr' && !empty($item->name_fr)) ? $item->name_fr : $item->product_name;
                ?>
                <tr>
                    <td>
                        <span class="item-name"><?php echo htmlspecialchars($pName); ?></span>
                        <?php if(!empty($item->variant_info) || !empty($item->sku)): ?>
                            <span class="item-meta">
                                <?php echo !empty($item->sku) ? 'SKU: ' . htmlspecialchars($item->sku) . ' • ' : ''; ?>
                                <?php echo htmlspecialchars($item->variant_info); ?>
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="text-right"><?php echo number_format($item->price, 2); ?></td>
                    <td class="text-right"><?php echo $item->quantity; ?></td>
                    <td class="text-right"><?php echo number_format($item->price * $item->quantity, 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <table class="totals-table">
            <tr>
                <td class="label-col"><?php echo lang('order_subtotal'); ?></td>
                <td class="amount-col"><?php echo number_format($order->total_amount - $order->shipping_cost, 2); ?></td>
            </tr>
            <tr>
                <td class="label-col"><?php echo lang('order_shipping'); ?></td>
                <td class="amount-col"><?php echo number_format($order->shipping_cost, 2); ?></td>
            </tr>
            <tr class="total-row">
                <td class="label-col"><?php echo lang('inv_total_due'); ?> (<?php echo CURRENCY_SYMBOL; ?>)</td>
                <td class="amount-col"><?php echo number_format($order->total_amount, 2); ?></td>
            </tr>
        </table>

        <div class="footer">
            <p>
                <strong><?php echo lang('order_payment_method'); ?>:</strong> 
                <?php echo ($order->payment_method == 'paystack') ? 'Paystack (Online)' : 'Cash on Delivery'; ?>
            </p>
            <p><?php echo lang('inv_thank_you'); ?></p>
            <p>Exotikha Ghana Ltd • RC: 12345678 • TIN: C00012345678</p>
        </div>

    </div>
</body>
</html>