<?php
/*Monitor all escrow transactions on the platform*/

require_once '../config.php';

if (!isAdmin()) {
    redirect('login.php');
}

$page_title = 'Escrow Transactions';

$transactions = $conn->query("
    SELECT o.*, u1.full_name as buyer_name, u2.full_name as seller_name, p.title
    FROM orders o
    JOIN users u1 ON o.buyer_id = u1.user_id
    JOIN users u2 ON o.seller_id = u2.user_id
    JOIN products p ON o.product_id = p.product_id
    ORDER BY o.created_at DESC
");

include 'admin_header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'admin_sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Escrow Transactions</h1>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Order #</th><th>Product</th><th>Buyer</th><th>Seller</th>
                                    <th>Amount</th><th>Escrow Fee</th><th>Status</th><th>Date</th>
                                 </tr>
                            </thead>
                            <tbody>
                                <?php while($t = $transactions->fetch_assoc()): ?>
                                 <tr>
                                     <td><?php echo $t['order_number']; ?></td>
                                     <td><?php echo htmlspecialchars($t['title']); ?></td>
                                     <td><?php echo $t['buyer_name']; ?></td>
                                     <td><?php echo $t['seller_name']; ?></td>
                                     <td>R <?php echo number_format($t['total_amount'], 2); ?></td>
                                     <td>R <?php echo number_format($t['escrow_fee'], 2); ?></td>
                                     <td><span class="badge bg-<?php echo $t['status'] == 'completed' ? 'success' : 'warning'; ?>"><?php echo ucfirst($t['status']); ?></span></td>
                                     <td><?php echo date('M d, Y', strtotime($t['created_at'])); ?></td>
                                 </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include 'admin_footer.php'; ?>