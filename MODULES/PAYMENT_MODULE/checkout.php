<?php
session_start();

// Mock transaction history
if (!isset($_SESSION['transactions'])) {
    $_SESSION['transactions'] = [];
}

// Function to generate a receipt
function generateReceipt($transaction) {
    return "Receipt:\n" .
        "Transaction ID: " . $transaction['id'] . "\n" .
        "Amount: $" . $transaction['amount'] . "\n" .
        "Date: " . $transaction['date'] . "\n\n";
}

// Function to generate PDF receipt
function generatePDFReceipt($transaction) {
    require 'MODULES/PAYMENT_MODULE/vendor/setasign/fpdf/fpdf.php'; // Include FPDF library
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    
    // Title
    $pdf->Cell(0, 10, 'Receipt', 0, 1, 'C');
    $pdf->Ln(10);
    
    // Transaction details
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Transaction ID: ' . $transaction['id'], 0, 1);
    $pdf->Cell(0, 10, 'Amount: $' . $transaction['amount'], 0, 1);
    $pdf->Cell(0, 10, 'Date: ' . $transaction['date'], 0, 1);
    
    // Output PDF for download
    $pdf->Output('D', 'receipt_' . $transaction['id'] . '.pdf'); // Download as PDF file
}

// Handle payment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay'])) {
    $amount = $_POST['amount'];
    $transaction = [
        'id' => uniqid(),
        'amount' => $amount,
        'date' => date('Y-m-d H:i:s'),
    ];

    // Add to transaction history
    $_SESSION['transactions'][] = $transaction;
    $receipt = generateReceipt($transaction);
}

// Handle refund
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['refund'])) {
    $idToRefund = $_POST['transaction_id'];
    $_SESSION['transactions'] = array_filter($_SESSION['transactions'], function($transaction) use ($idToRefund) {
        return $transaction['id'] !== $idToRefund;
    });
    $refundMessage = "Transaction ID $idToRefund has been refunded.";
}

// Handle PDF generation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_pdf'])) {
    foreach ($_SESSION['transactions'] as $transaction) {
        if ($transaction['id'] === $_POST['transaction_id']) {
            generatePDFReceipt($transaction);
            exit; // Stop further processing after generating PDF
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Environment</title>
    <style>
                body {
            font-family: Arial, sans-serif;
            background-color: #e0f7f9;
            color: #004d4d;
            margin: 0;
            padding: 20px;
        }

        h1, h2, h3 {
            color: #006666;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="number"] {
            padding: 5px;
            border: 1px solid #006666;
            border-radius: 4px;
        }

        button {
            background-color: #006666;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #004d4d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #006666;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #004d4d;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f9f9;
        }

        pre {
            background-color: #f2f9f9;
            padding: 10px;
            border: 1px solid #006666;
            border-radius: 4px;
        }

        p {
            font-size: 1em;
        }
    </style>
</head>
<body>
    <h1>Payment Environment</h1>

    <!-- Payment Form -->
    <form method="POST">
        <h2>Make a Payment</h2>
        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount" required>
        <button type="submit" name="pay">Pay</button>
    </form>

    <?php if (isset($receipt)): ?>
        <h3>Receipt</h3>
        <pre><?= htmlspecialchars($receipt) ?></pre>
    <?php endif; ?>

    <!-- Transaction History -->
    <h2>Transaction History</h2>
    <table border="1">
        <tr>
            <th>Transaction ID</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Action</th>
            <th>Generate PDF</th>
        </tr>
        <?php foreach ($_SESSION['transactions'] as $transaction): ?>
            <tr>
                <td><?= htmlspecialchars($transaction['id']) ?></td>
                <td>RM<?= htmlspecialchars($transaction['amount']) ?></td>
                <td><?= htmlspecialchars($transaction['date']) ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="transaction_id" value="<?= htmlspecialchars($transaction['id']) ?>">
                        <button type="submit" name="refund">Refund</button>
                    </form>
                </td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="transaction_id" value="<?= htmlspecialchars($transaction['id']) ?>">
                        <button type="submit" name="generate_pdf">Generate PDF</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php if (isset($refundMessage)): ?>
        <p style="color: green;"> <?= htmlspecialchars($refundMessage) ?> </p>
    <?php endif; ?>
</body>
</html>
