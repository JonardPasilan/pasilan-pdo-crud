<?php
require_once 'p_config.php';

function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$productName = '';
$price = '';

if (isset($_GET['productName']) && isset($_GET['price'])) {
    $productName = sanitize_input($_GET['productName']);
    $price = sanitize_input($_GET['price']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = sanitize_input($_POST['productName']);
    $price = sanitize_input($_POST['price']);
    $paymentMethod = sanitize_input($_POST['paymentMethod']);

    if (!empty($productName) && !empty($price) && !empty($paymentMethod)) {
        $sql = "INSERT INTO payments (product_name, price, payment_method) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sds", $productName, $price, $paymentMethod);

        if ($stmt->execute()) {
            $paymentId = $stmt->insert_id;
            $stmt->close();
            header("Location: address.php?paymentId=$paymentId");
            exit();
        } else {
            echo "Error: Unable to record payment.";
        }
    } else {
        echo "Error: All fields are required.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: white;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: orange;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.9);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: black;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            color: black;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input[type="submit"] {
            width: auto;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .image {
            position: absolute;
            top: 120px; /* Adjust top position */
            left: 100px; /* Adjust left position */
            width: 300px; /* Adjust image width */
           
        }
    </style>
</head>
<body>
    <img src="https://i.pinimg.com/originals/04/76/8c/04768ce51fa5222fd0e5ec2b499196eb.gif" alt="image" class="image">

    <img src="https://i.pinimg.com/originals/ee/f6/a4/eef6a46e97fc7428ad471c2e02bdb2da.gif" alt="Image 2" class="image" style="top: 120px; left: 960px;">
    <div class="container">
        <h2>Make Payment</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="productName">Product Name:</label>
            <input type="text" id="productName" name="productName" value="<?php echo $productName; ?>" readonly>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" value="<?php echo $price; ?>" readonly>

            <label for="paymentMethod">Payment Method:</label>
            <select id="paymentMethod" name="paymentMethod">
                <option value="PayMaya">PayMaya</option>
                <option value="GCash">GCash</option>
                <option value="PayPal">PayPal</option>
            </select>

            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>
