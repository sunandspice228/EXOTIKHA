<?php 
// Extraction des données
$order = $data['order'];
$items = $data['items'];

// Helper langue (si nécessaire pour des formats spécifiques)
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <title><?php echo lang('invoice_title'); ?> #<?php echo $order->order_number; ?></title>
    <style>
        /* BASE STYLES - Optimisé pour MPDF/DomPDF et Impression Navigateur */
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; line-height: 1.4; margin: 0; padding: 20px; background: #fff; }
        
        .container { width: 100%; max-width: 800px; margin: 0 auto; }
        
        /* HEADER */
        .header { width: 100%; border-bottom: 2px solid #ca8a04; padding-bottom: 20px; margin-bottom: 30px; }
        .header-table { width: 100%; }
        .header-left { text-align: left; vertical-align: top; }
        .header-right { text-align: right; vertical-align: top; }

        .logo h1 { margin: 0; font-size: 24px; font-family: serif; color: #0f172a; text-transform: uppercase; }
        .logo p { margin: 0; color: #ca8a04; font-size: 9px; text-transform: uppercase; letter-spacing: 2px; font-weight: bold; }
        
        .company-info { font-size: 10px; color: #64748b; line-height: 1.4; }
        .company-info strong { color: #0f172a; font-size: 11px; }

        /* INFO GRID */
        .info-table { width: 100%; margin-bottom: 30px; }
        .info-col { vertical-align: top; width: 50%; }
        .info-col.right { text-align: right; }

        .label { font-size: 9px; font-weight: bold; text-transform: uppercase; color: #94a3b8; margin-bottom: 5px; display: block; }
        .info-value { font-size: 12px; color: #0f172a; }
        
        /* BADGES */
        .badge { padding: 3px 6px; border-radius: 4px; font-size: 9px; font-weight: bold; text-transform: uppercase; display: inline-block; }
        .badge.paid { background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .badge.pending { background-color: #ffedd5; color: #9a3412; border: 1px solid #fed7aa; }
        .badge.cancelled { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        /* ITEMS TABLE */
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th { text-align: left; padding: 10px 5px; border-bottom: 2px solid #e2e8f0; font-size: 9px; text-transform: uppercase; color: #64748b; font-weight: bold; background-color: #f8fafc; }
        .items-table td { padding: 10px 5px; border-bottom: 1px solid #f1f5f9; vertical-align: top; font-size: 11px; }
        
        .text-right { text-align: right; }
        .item-name { font-weight: bold; color: #0f172a; }
        .item-meta { font-size: 10px; color: #64748b; display: block; margin-top: 2px; }

        /* TOTALS */
        .totals-table { width: 40%; margin-left: auto; border-collapse: collapse; }
        .totals-table td { padding: 5px 0; font-size: 11px; }
        .totals-table .total-row td { border-top: 2px solid #ca8a04; padding-top: 10px; font-size: 14px; font-weight: bold; color: #0f172a; }
        .totals-table .total-row .amount { color: #ca8a04; }

        /* FOOTER */
        .footer { margin-top: 50px; padding-top: 20px; border-top: 1px dashed #e2e8f0; font-size: 10px; color: #94a3b8; text-align: center; }
        
        /* PRINT UTILS */
        .no-print { margin-bottom: 20px; text-align: center; }
        .btn-print { background: #0f172a; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 12px; }
        
        @media print {
            .no-print { display: none; }
            body { background: white; padding: 0; }
            .container { width: 100%; max-width: 100%; margin: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn-print">
            🖨️ <?php echo lang('btn_print'); ?>
        </button>
    </div>

    <div class="container">
        
        <div class="header">
            <table class="header-table">
                <tr>
                    <td class="header-left">
                        <div class="logo">
                            <h1>Exotikha.</h1>
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
                        <strong><?php echo strtoupper($order->full_name); ?></strong><br>
                        <?php echo $order->shipping_address; ?><br>
                        <?php echo $order->shipping_city . ', ' . $order->shipping_region; ?><br>
                        <?php echo $order->shipping_phone; ?><br>
                        <?php echo $order->email; ?>
                    </div>
                </td>
                <td class="info-col right">
                    <span class="label"><?php echo lang('inv_details'); ?></span>
                    <div class="info-value">
                        <strong><?php echo lang('invoice_title'); ?> #<?php echo $order->order_number; ?></strong><br>
                        <?php echo date('d M Y', strtotime($order->created_at)); ?><br>
                        <span class="badge <?php echo strtolower($order->payment_status); ?>">
                            <?php echo lang('status_' . strtolower($order->payment_status)); ?>
                        </span>
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
                <tr>
                    <td>
                        <span class="item-name"><?php echo $item->product_name; ?></span>
                        <?php if(!empty($item->variant_info) || !empty($item->sku)): ?>
                            <span class="item-meta">
                                <?php echo !empty($item->sku) ? 'SKU: ' . $item->sku . ' • ' : ''; ?>
                                <?php echo $item->variant_info; ?>
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
                <td class="text-right label"><?php echo lang('order_subtotal'); ?></td>
                <td class="text-right"><?php echo number_format($order->total_amount - $order->shipping_cost, 2); ?></td>
            </tr>
            <tr>
                <td class="text-right label"><?php echo lang('order_shipping'); ?></td>
                <td class="text-right"><?php echo number_format($order->shipping_cost, 2); ?></td>
            </tr>
            <tr class="total-row">
                <td class="text-right"><?php echo lang('inv_total_due'); ?> (<?php echo CURRENCY_SYMBOL; ?>)</td>
                <td class="text-right amount"><?php echo number_format($order->total_amount, 2); ?></td>
            </tr>
        </table>

        <div class="footer">
            <p>
                <strong><?php echo lang('order_payment_method'); ?>:</strong> 
                <?php echo ($order->payment_method == 'paystack') ? 'Paystack (Online)' : 'Cash on Delivery'; ?>
            </p>
            <p><?php echo lang('inv_thank_you'); ?></p>
        </div>

    </div>

</body>
</html>