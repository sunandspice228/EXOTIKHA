<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 1px solid #ddd; padding-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #000; }
        .info-box { width: 100%; margin-bottom: 30px; }
        .info-col { width: 48%; display: inline-block; vertical-align: top; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #f8f9fa; padding: 10px; text-align: left; border-bottom: 2px solid #ddd; }
        td { padding: 10px; border-bottom: 1px solid #eee; }
        .total-row td { font-weight: bold; font-size: 16px; background: #f8f9fa; }
        .status { padding: 5px 10px; background: #d1fae5; color: #065f46; border-radius: 4px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">EXOTIKHA</div>
        <p>Facture #<?php echo $order->order_number; ?></p>
        <p>Date: <?php echo date('d/m/Y', strtotime($order->created_at)); ?></p>
    </div>

    <div class="info-box">
        <div class="info-col">
            <h3>Client</h3>
            <p>
                <strong><?php echo $order->full_name; ?></strong><br>
                <?php echo $order->email; ?><br>
                <?php echo $order->shipping_phone; ?>
            </p>
        </div>
        <div class="info-col">
            <h3>Livraison</h3>
            <p>
                <?php echo $order->shipping_address; ?><br>
                <?php echo $order->shipping_city; ?>, <?php echo $order->shipping_region; ?>
            </p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Prix</th>
                <th>Qté</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($items as $item): ?>
            <tr>
                <td>
                    <?php echo $item->product_name; ?>
                    <?php if($item->size): ?> <br><small>Taille: <?php echo $item->size; ?></small> <?php endif; ?>
                </td>
                <td><?php echo number_format($item->price, 2); ?></td>
                <td><?php echo $item->quantity; ?></td>
                <td><?php echo number_format($item->price * $item->quantity, 2); ?></td>
            </tr>
            <?php endforeach; ?>
            
            <tr class="total-row">
                <td colspan="3" style="text-align: right;">Sous-total</td>
                <td><?php echo number_format($order->total_amount - $order->shipping_cost, 2); ?></td>
            </tr>
            <tr class="total-row">
                <td colspan="3" style="text-align: right;">Livraison</td>
                <td><?php echo number_format($order->shipping_cost, 2); ?></td>
            </tr>
            <tr class="total-row">
                <td colspan="3" style="text-align: right; color: #4f46e5;">TOTAL PAYÉ</td>
                <td style="color: #4f46e5;"><?php echo number_format($order->total_amount, 2); ?> <?php echo CURRENCY_SYMBOL; ?></td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 50px; text-align: center; font-size: 12px; color: #777;">
        <p>Merci pour votre confiance !</p>
        <p>Exotikha Store - Accra, Ghana - contact@exotikha.com</p>
    </div>
</body>
</html>