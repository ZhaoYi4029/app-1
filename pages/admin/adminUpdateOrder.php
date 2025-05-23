<?php
// filepath: c:\Users\shenl\app\pages\admin\adminUpdateOrder.php
require_once '../../_base.php';
include '../../_header.php';
ob_start();

$order = null;

try {
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['order_id'])) {
        $order_id = $_GET['order_id'];
        $stm = $_db->prepare("SELECT * FROM orders WHERE order_id = :order_id");
        $stm->execute([':order_id' => $order_id]);
        $order = $stm->fetch(PDO::FETCH_ASSOC);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'])) {
        $order_id = (int)$_POST['order_id'];
        $status = $_POST['status'] ?? 'Pending';

        $stm = $_db->prepare("UPDATE orders SET status = :status WHERE order_id = :order_id");
        $stm->execute([
            ':status' => $status,
            ':order_id' => $order_id
        ]);

        header("Location: adminOrder.php");
        exit;
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Order</title>
</head>
<body>
    <h1>Update Order</h1>
    <?php if ($order): ?>
        <form action="adminUpdateOrder.php" method="POST">
            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
            <label for="status">Status:</label>
            <select id="status" name="status">
                <option value="Pending" <?php echo $order['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="Processing" <?php echo $order['status'] == 'Processing' ? 'selected' : ''; ?>>Processing</option>
                <option value="Completed" <?php echo $order['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                <option value="Cancelled" <?php echo $order['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
            </select>
            <button type="submit">Update Order</button>
        </form>
    <?php else: ?>
        <p>Order not found.</p>
    <?php endif; ?>
</body>
</html>