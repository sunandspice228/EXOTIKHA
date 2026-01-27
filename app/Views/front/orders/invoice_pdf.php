<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?php echo $data['order']->order_number; ?></title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; line-height: 1.4; }
        .header { width: 100%; border-bottom: 2px solid #ca8a04; padding-bottom: 20px; margin-bottom: 30px; } /* Accent Gold Color */
        
        .company-info { text-align: right; }
        
        .details-box { width: 100%; margin-bottom: 30px; }
        .title { font-weight: bold; font-size: 14px; margin-bottom: 5px; color: #ca8a04; text-transform: uppercase; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #f8fafc; color: #111; padding: 12px 10px; text-align: left; font-weight: bold; border-bottom: 1px solid #e2e8f0; text-transform: uppercase; font-size: 11px; }
        td { padding: 12px 10px; border-bottom: 1px solid #eee; }
        
        .text-right { text-align: right; }
        .total-row td { border-top: 2px solid #333; font-weight: bold; font-size: 16px; padding-top: 15px; }
        
        .footer { position: fixed; bottom: 0; left: 0; right: 0; height: 40px; text-align: center; font-size: 10px; color: #94a3b8; border-top: 1px solid #f1f5f9; padding-top: 15px; }
        
        .badge { padding: 4px 8px; border-radius: 4px; font-weight: bold; text-transform: uppercase; font-size: 10px; display: inline-block; }
        .paid { background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .pending { background-color: #fff7ed; color: #9a3412; border: 1px solid #ffedd5; }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <table width="100%">
            <tr>
                <td style="border:none; vertical-align: top;">
                    <h1 style="color: #1a1a1a; margin:0; font-size: 24px; font-family: serif;">EXOTIKHA.</h1>
                    <p style="color: #64748b; margin-top: 5px;">Sensual • Elegant • Confident</p>
                </td>
                <td style="border:none; text-align:right; vertical-align: top;">
                    <strong style="font-size: 14px;">EXOTIKHA GHANA LTD</strong><br>
                    Haatso, Accra - Ghana<br>
                    Tel: +233 53 938 2808<br>
                    Email: sales@exotikha.com
                </td>
            </tr>
        </table>
    </div>

    <div class="details-box">
        <table width="100%">
            <tr>
                <td style="border:none; width:50%; vertical-align:top;">
                    <div class="title">Bill To</div>
                    <strong style="font-size: 13px;"><?php echo $data['order']->full_name; ?></strong><br>
                    <?php echo $data['order']->email; ?><br>
                    <?php echo $data['order']->billing_phone; ?><br>
                    <?php echo $data['order']->billing_address; ?><br>
                    <?php echo $data['order']->billing_city . ', ' . $data['order']->billing_region; ?>
                </td>
                <td style="border:none; width:50%; vertical-align:top; text-align:right;">
                    <div class="title">Order Details</div>
                    <strong>Invoice No: #<?php echo $data['order']->order_number; ?></strong><br>
                    Date: <?php echo date('M d, Y', strtotime($data['order']->created_at)); ?><br>
                    Status: 
                    <span class="badge <?php echo ($data['order']->status == 'paid' || $data['order']->status == 'delivered') ? 'paid' : 'pending'; ?>">
                        <?php echo strtoupper($data['order']->status); ?>
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th width="50%">Item Description</th>
                <th width="15%" class="text-right">Unit Price</th>
                <th width="10%" class="text-right">Qty</th>
                <th width="25%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data['items'] as $item): ?>
            <tr>
                <td>
                    <strong style="color: #1e293b;"><?php echo $item->name; ?></strong>
                    <?php if(!empty($item->attributes)): ?>
                        <br><span style="font-size:10px; color:#64748b;">Specs: <?php echo $item->attributes; ?></span>
                    <?php endif; ?>
                    <?php if(!empty($item->sku)): ?>
                        <br><span style="font-size:10px; color:#94a3b8;">SKU: <?php echo $item->sku; ?></span>
                    <?php endif; ?>
                </td>
                <td class="text-right"><?php echo number_format($item->price, 2); ?></td>
                <td class="text-right"><?php echo $item->quantity; ?></td>
                <td class="text-right"><?php echo number_format($item->price * $item->quantity, 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="border:none;"></td>
                <td class="text-right" style="padding-top:20px; color: #64748b;">Subtotal</td>
                <td class="text-right" style="padding-top:20px; font-weight: bold;"><?php echo number_format($data['order']->total_amount, 2); ?></td>
            </tr>
            <tr>
                <td colspan="2" style="border:none;"></td>
                <td class="text-right" style="color: #64748b;">Shipping</td>
                <td class="text-right">0.00</td> 
                </tr>
            <tr class="total-row">
                <td colspan="2" style="border:none;"></td>
                <td class="text-right" style="color: #1a1a1a;">NET TOTAL (<?php echo CURRENCY_SYMBOL; ?>)</td>
                <td class="text-right" style="color: #ca8a04;"><?php echo number_format($data['order']->total_amount, 2); ?></td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 40px; border-left: 4px solid #ca8a04; padding-left: 15px; background: #fffbeb; padding: 15px; font-size: 11px; color: #78350f;">
        <strong>Payment Method:</strong> Paystack / Mobile Money / Card<br>
        <em>Thank you for your business. If you have any questions about this invoice, please contact us at support@exotikha.com</em>
    </div>

    <div class="footer">
        Exotikha Ghana Ltd - Generated automatically on <?php echo date('F d, Y \a\t H:i'); ?>
    </div>

</body>
</html>