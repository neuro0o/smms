<?php
session_start();

// Mock transaction history
if (!isset($_SESSION['transactions'])) {
    $_SESSION['transactions'] = [];
}

// Function to generate a receipt as plain text
function generateReceipt($transaction) {
    return "==== RECEIPT ====\n" .
        "Transaction ID: " . $transaction['id'] . "\n" .
        "Amount: RM" . $transaction['amount'] . "\n" .
        "Date: " . $transaction['date'] . "\n" .
        "==================\n\n";
}

// Function to download receipt as plain text
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
    $transaction = [
        'id' => uniqid(),
        'amount' => $amount,
        'date' => date('Y-m-d H:i:s'),
    ];

    // Add to transaction history
    $_SESSION['transactions'][] = $transaction;

    // Store receipt in a session variable for display
    $_SESSION['receipt'] = generateReceipt($transaction);

    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF'] . "?payment_success=1");
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

// Handle deleting transaction history
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_history'])) {
    $_SESSION['transactions'] = []; // Clear the transaction history
    header("Location: " . $_SERVER['PHP_SELF']); // Redirect to refresh the page
    exit;
}

// Retrieve the receipt if it exists
$receipt = isset($_SESSION['receipt']) ? $_SESSION['receipt'] : null;
// Clear the receipt after it's displayed to avoid re-display on refresh
unset($_SESSION['receipt']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- checkout css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/USER/checkout.css">

    <title>Checkout</title>

</head>
<body>
    <h1>Checkout</h1>

    <?php if (isset($_GET['payment_success'])): ?>
        <div class="payment-modal show" id="paymentModal">
            <div class="payment-modal-content">
                <div class="spinner"></div>
                <p>Payment Completed! Redirecting...</p>
            </div>
        </div>
        <script>
            setTimeout(() => {
                document.getElementById('paymentModal').classList.remove('show');
            }, 2000);
        </script>
    <?php endif; ?>
    
 

    <!-- Payment Form -->
    <form method="POST">
        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount" required>
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
        <a href="../USER_MANAGEMENT_MODULE/userHome.php">Home</a>
    </div>
</body>
</html>