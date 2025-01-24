<?php
session_start();

// Include db config
include("../../CONFIG/config.php");

// Cart action to either (add/remove/empty) the cart list
if (!empty($_GET["action"])) {
    switch ($_GET["action"]) {
        // Add activity
        case "add":
            if (!empty($_POST["quantity"])) {
                $activityID = $_GET["id"];
                $quantityToAdd = $_POST["quantity"];

                // Initialize itemArray
                $itemArray = array();

                $sql = "SELECT * FROM activity WHERE activityID = '$activityID'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);

                if ($row) {
                    $actID = "actID" . $row["activityID"];

                    // Array for cart item
                    $itemArray = array(
                        $actID => array(
                            'activityID' => $row["activityID"],
                            'activityName' => $row["activityName"],
                            'activityImg' => $row["activityImg"],
                            'activityDesc' => $row["activityDesc"],
                            'activityPrice' => $row["activityPrice"],
                            'quantity' => $quantityToAdd // Add quantity here
                        ),
                    );

                    // Update the cart
                    if (!empty($_SESSION["cart_item"])) {
                        // Check if current cart contains the activity or not
                        if (array_key_exists($actID, $_SESSION["cart_item"])) {
                            // Update quantity
                            $_SESSION["cart_item"][$actID]["quantity"] = $quantityToAdd;
                        } else {
                            // Add new activity to the current cart if it doesn't exist
                            $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"], $itemArray);
                        }
                    } else {
                        // Add to cart if no activity exists in current cart yet
                        $_SESSION["cart_item"] = $itemArray;
                    }
                }
            } else {
                echo "<script type='text/javascript'>alert('No changes to current cart.')</script>";
            }
            break;

        // Remove activity
        case "remove":
            if (!empty($_SESSION["cart_item"])) {
                foreach ($_SESSION["cart_item"] as $k => $v) {
                    if ("actID" . $_GET["activityID"] == $k) {
                        unset($_SESSION["cart_item"][$k]);
                    }
                }
                if (empty($_SESSION["cart_item"])) {
                    unset($_SESSION["cart_item"]);
                }
            }
            break;

        // Delete all activities from the cart
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

<!-- Include headerBanner.php -->
<?php include '../../INCLUDES/headerBanner.php'; ?>

<section class="userHome">
    <h2 id="section-title">MY CART</h2>

    <div class="cart-action">
        <?php
        if (isset($_SESSION["cart_item"])) {
            $total_quantity = 0;
            $total_price = 0;
            ?>
            <table>
                <tr>
                    <th>ACTIVITY ID</th>
                    <th>ACTIVITY NAME</th>
                    <th>QUANTITY</th>
                    <th>UNIT PRICE (RM)</th>
                    <th>PRICE (RM)</th>
                    <th>ACTIONS</th>
                </tr>

                <?php
                foreach ($_SESSION["cart_item"] as $item) {
                    $item_price = isset($item["activityPrice"]) ? $item["activityPrice"] : 0; // Ensure price is set
                    $item_quantity = isset($item["quantity"]) ? $item["quantity"] : 0; // Ensure quantity is set
                    $total_item_price = $item_price * $item_quantity;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item["activityID"]); ?></td>
                        <td><?php echo htmlspecialchars($item["activityName"]); ?></td>
                        <td><?php echo htmlspecialchars($item_quantity); ?></td>
                        <td><?php echo htmlspecialchars($item_price); ?></td>
                        <td><?php echo number_format($total_item_price, 2); ?></td>
                        <td>
                            <a href="cart_action.php?action=remove&activityID=<?php echo $item['activityID']; ?>">
                                <i class="fa fa-times-circle"></i> Remove
                            </a>
                        </td>
                    </tr>
                    <?php
                    $total_quantity += $item_quantity;
                    $total_price += $total_item_price;
                }
                ?>

                <tr>
                    <th colspan="6"></th>
                </tr>

                <tr id="total-cart">
                    <td colspan="2" align="right">TOTAL ITEM:</td>
                    <td><?php echo $total_quantity; ?></td>
                    <td colspan="1" align="right">TOTAL AMOUNT:</td>
                    <td colspan="2"><?php echo "RM " . number_format($total_price, 2); ?></td>
                </tr>

                <tr>
                    <th style="background-color: #ccfbf1;" colspan="6"></th>
                </tr>

                <tr id="checkout-cart">
                    <td colspan="5" align="right"></td>
                    <td colspan="1">
                        <form method="post" action="activity_checkout.php?price=<?php echo $total_price; ?>">
                            <input type="hidden" name="tot_price" value="<?php echo $total_price; ?>">
                            <button id="checkout-button" type="submit">CHECKOUT</button>
                        </form>
                    </td>
                </tr>

                <tr>
                    <th style="background-color: #ccfbf1;" colspan="6"></th>
                </tr>
            </table>

            <p id="cart-misc-button">
                <a href="cart_action.php?action=empty">
                    <i class="fa fa-trash"></i>
                    Empty Cart
                </a>
            </p>

            <p id="cart-misc-button">
                <a href="../ACTIVITY_MODULE/activityList.php">
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