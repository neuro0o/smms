<?php
session_start();

// Mock transaction history
if (!isset($_SESSION['transactions'])) {
    $_SESSION['transactions'] = [];
}

// Retrieve total price and type from query parameters
$total_price = isset($_GET['total_price']) ? $_GET['total_price'] : 0;
$checkoutType = isset($_GET['type']) ? $_GET['type'] : ''; // Define checkoutType

// Generate a receipt as plain text
function generateReceipt($transaction) {
    return "==== RECEIPT ====\n" .
        "Transaction ID: " . $transaction['id'] . "\n" .
        "Amount: RM" . $transaction['amount'] . "\n" .
        "Date: " . $transaction['date'] . "\n" .
        "==================\n\n";
}

// Download receipt as a text file
function downloadReceipt($transaction) {
    $filename = "receipt_" . $transaction['id'] . ".txt";
    header("Content-Type: text/plain");
    header("Content-Disposition: attachment; filename=" . $filename);
    echo generateReceipt($transaction);
    exit;
}

// Handle payment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay']) && isset($_POST['amount'])) {
    $amount = $_POST['amount'];
    $userID = $_SESSION['UID'];
    $transaction = [
        'id' => uniqid(),
        'amount' => $amount,
        'date' => date('Y-m-d H:i:s'),
    ];

    // Add to transaction history
    $_SESSION['transactions'][] = $transaction;

    // Store receipt in session
    $_SESSION['receipt'] = generateReceipt($transaction);

    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF'] . "?payment_success=1&type=" . urlencode($checkoutType));
    exit;
}

// Handle receipt download
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['download_receipt'])) {
    foreach ($_SESSION['transactions'] as $transaction) {
        if ($transaction['id'] === $_POST['transaction_id']) {
            downloadReceipt($transaction);
        }
    }
}

// Clear transaction history
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_history'])) {
    $_SESSION['transactions'] = [];
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Retrieve the receipt (if any)
$receipt = isset($_SESSION['receipt']) ? $_SESSION['receipt'] : null;
unset($_SESSION['receipt']); // Clear receipt to prevent re-display
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../SMMS/CSS/USER/payment.css">
    <title>Checkout</title>
</head>
<body>
    <h1>Checkout</h1>

    <?php if (isset($_GET['payment_success'])): ?>
        <div class="payment-modal show" id="paymentModal">
            <div class="payment-modal-content">
                <div class="spinner"></div>
                <p>Processing Payment...</p>
            </div>
        </div>
        <script>
            setTimeout(() => {
                document.getElementById('paymentModal').classList.remove('show');
                document.querySelector('.home-button').style.display = 'block'; // Show the button after processing
            }, 2000);
        </script>
    <?php endif; ?>

    <!-- Payment Form -->
    <form method="POST">
        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount" required value="<?= htmlspecialchars($total_price); ?>">
        <button type="submit" name="pay">Pay</button>
    </form>

    <!-- Transaction History -->
    <h2>Transaction History</h2>
    <table>
        <tr>
            <th>Transaction ID</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Download Receipt</th>
        </tr>
        <?php foreach ($_SESSION['transactions'] as $transaction): ?>
            <tr>
                <td><?= htmlspecialchars($transaction['id']) ?></td>
                <td>RM<?= htmlspecialchars($transaction['amount']) ?></td>
                <td><?= htmlspecialchars($transaction['date']) ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="transaction_id" value="<?= htmlspecialchars($transaction['id']) ?>">
                        <button type="submit" name="download_receipt">Download</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Clear Transaction History -->
    <div class="delete-history">
        <form method="POST">
            <button type="submit" name="delete_history">Clear Transaction History</button>
        </form>
    </div>

    <!-- Home Button -->
    <div class="home-button">
        <?php if ($checkoutType === 'food'): ?>
            <a href="../RESERVATION_MODULE/food_checkout.php">Proceed to Food Checkout</a>
        <?php elseif ($checkoutType === 'activity'): ?>
            <a href="../RESERVATION_MODULE/activity_checkout.php">Proceed to Activity Checkout</a>
        <?php else: ?>
            <span>Unknown Checkout type</span>
        <?php endif; ?>
    </div>
</body>
</html>