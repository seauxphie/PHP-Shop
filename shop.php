<?php session_start();
$db = new mysqli("localhost", "root", "", "shop"); ?>
<!DOCTYPE html>
<html>

<head>
    <title>Shop</title>
    <link rel="stylesheet" type="text/css" href="style.css" title="CSS file">
    <script src="script.js"></script>
    <script src="jquery-3.3.1.min.js"></script>
    <script type="text/javascript">
        let inCart = {};

        function addToCart(x) {

            let row = $(x).parent().parent();
            let item = $(row).children('td.item:first-child').text();
            if (!inCart[item]) {
                inCart[item] = true;
                let cart = $('#cart .items');
                let tb = $('#cart .items').html();
                $(cart).html(tb + '<input type="text" name="item[]" value="' + item + '">');
            }
        }

        function clearCart() {
            $('#cart .items').html('');
            Object.keys(inCart).forEach(function(key, index) {
                inCart[key] = false;
            });


            $('.clear').one('click', clearCart);
        }


        $(document).ready(function() {
            // $('.addtocart').one('click', addToCart);
            $('.clear').one('click', clearCart);
        });
    </script>
</head>

<body>
    <h1>Shop</h1>



    <table>

        <?php
        
        if (!$db) { ?>
            <?= "Connect failed: " . mysqli_connect_error() . "\n"; ?>
            <?php
            exit();
        }
        $result = $db->query("select * from products");
        foreach ($result as $v) : ?>
            <tr>
                <td class="item"><?= $v["name"] ?></td>
                <td><button class="addtocart" onClick="addToCart(this)">Add to cart</button></td>
            </tr>

        <?php endforeach;
    ?>

    </table>


    <form action="shop.php" id="cart" method="post">
            <div class="button">CART</div>
            <table class="items">

            </table>
            <input type="hidden" name="submitted" value="1" />
            <input type="submit" class="buy" name="buy" value="Buy">
            <button class="clear">Clear</button> 

    </form>

    <?php

if (isset($_POST['submitted'])) {
    $rowCount = count($_POST['item']);
    for ($i = 0; $i < $rowCount; $i++) {

$q = $db->prepare("delete from products where name like ?");
$q -> bind_param("s", $_POST['item'][$i]);
$q.execute();
        //$db->query("delete from products where name like '".$_POST['item'][$i]."';");
    }

    session_unset();
    session_destroy();
    header("Refresh:0");
}
?>




</body>

</html>