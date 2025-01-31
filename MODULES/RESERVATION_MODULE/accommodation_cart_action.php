<?php
session_start();

// Include db config
include("../../CONFIG/config.php");

// Cart action to either (add/remove/empty) the cart list
if (!empty($_GET["action"])) {
    switch ($_GET["action"]) {
        // Add accommodation
        case "add":
            if (!empty($_POST["dateFrom"]) && !empty($_POST["dateUntil"])) {
                $accommodationID = $_GET["id"];
                $dateFrom = $_POST["dateFrom"];
                $dateUntil = $_POST["dateUntil"];

                // Initialize itemArray
                $itemArray = array();

                $sql = "SELECT * FROM accommodation WHERE accommodationID = '$accommodationID'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);

                if ($row) {
                    $accID = "accID" . $row["accommodationID"];

                    // Array for cart item
                    $itemArray = array(
                        $accID => array(
                            'accommodationID' => $row["accommodationID"],
                            'accommodationName' => $row["accommodationName"],
                            'accommodationImg' => $row["accommodationImg"],
                            'accommodationDesc' => $row["accommodationDesc"],
                            'accommodationPrice' => $row["accommodationPrice"],
                            'dateFrom' => $dateFrom,
                            'dateUntil' => $dateUntil
                        ),
                    );

                    // Update the cart
                    if (!empty($_SESSION["cart_item"])) {
                        // Check if current cart contains the accommodation or not
                        if (array_key_exists($accID, $_SESSION["cart_item"])) {
                            // Update dates
                            $_SESSION["cart_item"][$accID]["dateFrom"] = $dateFrom;
                            $_SESSION["cart_item"][$accID]["dateUntil"] = $dateUntil;
                        } else {
                            // Add new accommodation to the current cart if it doesn't exist
                            $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"], $itemArray);
                        }
                    } else {
                        // Add to cart if no accommodation exists in current cart yet
                        $_SESSION["cart_item"] = $itemArray;
                    }
                }
            } else {
                echo "<script type='text/javascript'>alert('Please provide check-in and check-out dates.')</script>";
            }
            break;

        // Remove accommodation
        case "remove":
            if (!empty($_SESSION["cart_item"])) {
                foreach ($_SESSION["cart_item"] as $k => $v) {
                    if ("accID" . $_GET["accommodationID"] == $k) {
                        unset($_SESSION["cart_item"][$k]);
                    }
                }
                if (empty($_SESSION["cart_item"])) {
                    unset($_SESSION["cart_item"]);
                }
            }
            break;

        // Delete all accommodations from the cart
        case "empty":
            unset($_SESSION["cart_item"]);
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- utils css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/utils.css">

    <!-- headerBanner css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/headerBanner.css">

    <!-- topNav css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/topNav.css">

    <!-- topHeader css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/MISC/topHeader.css">

    <!-- userHome css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/USER/userHome.css">

    <!-- cart css file -->
    <link rel="stylesheet" href="../../../SMMS/CSS/USER/cart.css">
    
    <title>USER HOME</title>
</head>
<body>

<!-- Include topNav.php -->
<?php include '../../INCLUDES/topHeader.php'; ?>

<!-- Include userNav.php -->
<?php include '../../INCLUDES/userNav.php'; ?>

<section class="userHome">
    <h2 id="section-title">MY ACCOMMODATION CART</h2>

    <div class="cart-action">
        <?php
        if (isset($_SESSION["cart_item"])) {
            $total_price = 0;
            ?>
            <table>
                <tr>
                    <th>Accommodation ID</th>
                    <th>Accommodation Name</th>
                    <th>Check-in Date</th>
                    <th>Check-out Date</th>
                    <th>Unit Price (RM)</th>
                    <th>Actions</th>
                </tr>

                <?php
                foreach ($_SESSION["cart_item"] as $item) {
                    $item_price = isset($item["accommodationPrice"]) ? $item["accommodationPrice"] : 0; // Ensure price is set
                    $total_price += $item_price; // Add to total price
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item["accommodationID"]); ?></td>
                        <td><?php echo htmlspecialchars($item["accommodationName"]); ?></td>
                        <td><?php echo htmlspecialchars($item["dateFrom"]); ?></td>
                        <td><?php echo htmlspecialchars($item["dateUntil"]); ?></td>
                        <td><?php echo number_format($item_price, 2); ?></td>
                        <td>
                            <a href="accommodation_cart_action.php?action=remove&accommodationID=<?php echo $item['accommodationID']; ?>">
                                <i class="fa fa-times-circle"></i> Remove
                            </a>
                        </td>
                    </tr>
                    <?php
                }
                ?>

                <tr>
                    <th colspan="6"></th>
                </tr>

                <tr id="total-cart">
                    <td colspan="4" align="right">TOTAL AMOUNT:</td>
                    <td colspan="2"><?php echo "RM " . number_format($total_price, 2); ?></td>
                </tr>

                <tr>
                    <th style="background-color: #ccfbf1;" colspan="6"></th>
                </tr>

                <tr id="checkout-cart">
                    <td colspan="5" align="right"></td>
                    <td colspan="1">
                    <form method="post" action="../PAYMENT_MODULE/payment.php?total_price=<?php echo $total_price; ?>&type=accommodation">
                        <input type="hidden" name="tot_price" value="<?php echo $total_price; ?>">
                        <button id="checkout-button" type="submit">CHECKOUT</button>
                    </form>
                    </td>
                </tr>
            </table>

            <p id="cart-misc-button">
                <a href="accommodation_cart_action.php?action=empty">
                    <i class="fa fa-trash"></i>
                    Empty Cart
                </a>
            </p>

            <p id="cart-misc-button">
                <a href="../ACCOMMODATION_MODULE/accommodationList.php">
                    <i class="fa fa-shopping-bag"></i>
                    Continue Shopping
                </a>
            </p>

            <?php
        } else {
            ?>
            <p id="section-title">-- Your Cart is Empty --</p>
            <?php
        }
        ?>
    </div>
</section>

<!-- Include topNav.js -->
<script src="../../../SMMS/JS/topNav.js"></script>

</body>
</html>