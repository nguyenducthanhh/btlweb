<?php
  require('../config/config.php');
  include('../functions/common_function.php');

// xóa sản phẩm trong giỏ hàng
if(isset($_GET["task"]) && $_GET["task"]=="delete") {
    $get_ip_add = getIPAddress();
    $product_id = $_GET["id"];
    $sql_delete = "delete from tbl_cart_detail where product_id = " .$product_id;
    if (mysqli_query($conn, $sql_delete)) {
        // header("location:category.php");
        // echo "New record created successfully";
        echo "<script>alert('Đã xóa sản phẩm khỏi giỏ hàng')</script>";
    }
    else {
        echo "Error: " .$sql . "</br>" . mysqli_error($conn); 
    }
}

// update query
if(isset($_POST['update_qty_prd'])) {
    $update_value = $_POST['qty'];
    $update_id = $_POST['update_qty_id'];
    $sql_update = "update `tbl_cart_detail` set quantity = '$update_value' where product_id = $update_id";
    if (mysqli_query($conn, $sql_update)) {
                echo "<script>alert('Cập nhật số lượng thành công')</script>";
                echo "<script>window.open('cart.php','_self')</script>";
                // echo "New record created successfully";
            }
            else {
                echo "Error: " .$sql_update . "</br>" . mysqli_error($conn); 
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
    <link rel="stylesheet" href="../assets/css/cart.css">
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/app.js"></script>
    </head>
    <body>
        <div id="cart">
            <?php
                    include '../config/header.php';
            ?>
            <div class="container-fluid">
                <div class="row">
                    <a href="product.php" class="back-more-product">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                        </svg>
                        Trở lại
                    </a>
                </div>
                <div class="row">
                    <div class="col-sm-8 col-md-8 col-lg-8 left">
                        <ul class="giohang">
                            <h1>GIỎ HÀNG</h1>
                                    <table class="shop_table table">
                                        <thead>
                                            <tr>
                                                <th class="product-thumbnail">Hình ảnh</th>
                                                <th class="product-name">Sản phẩm</th>
                                                <th class="product-price">Giá</th>
                                                <th class="product-quantity">Số lượng</th>
                                                <th class="product-subtotal">Thành tiền</th>
                                                <!-- <th class="product-option">Xóa</th> -->
                                                <th class="product-remove">Xóa</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                                <?php
                                                    if (isset($_SESSION['username'])) {
                                                    $get_ip_add = getIPAddress();
                                                    $total_price = 0;
                                                    $cart_query = "SELECT * FROM `tbl_cart_detail` WHERE ip_address = '$get_ip_add'";
                                                    $result = mysqli_query($conn, $cart_query);
                                                    if (mysqli_num_rows($result) == 0) {
                                                            echo "<h2>Không có sản phẩm nào!</h2>";
                                                    }
                                                    else {
                                                    while($row=mysqli_fetch_array($result)) {
                                                        $product_id = $row['product_id'];
                                                        $select_product = "SELECT * FROM `tbl_product` WHERE product_id = '$product_id'";            
                                                        $result_product = mysqli_query($conn, $select_product);
                                                        while($row_prd_price=mysqli_fetch_array($result_product)) {
                                                            $product_img = $row_prd_price['img'];
                                                            $product_name = $row_prd_price['product_name'];
                                                            $prd_pr = $row_prd_price['product_price'];
                                                            $product_price = number_format($row_prd_price['product_price'], 0, ',', '.');

                                                            // lấy số lượng ra
                                                            $sql = "SELECT * FROM `tbl_cart_detail` WHERE product_id = '$product_id'";
                                                            $result_qty = mysqli_query($conn, $sql);
                                                            while($row_cart=mysqli_fetch_array($result_qty)) {
                                                                $quantity = $row_cart['quantity'];
                                                                $prd_qty_price = $prd_pr * $quantity;
                                                                $prd_qty_price = number_format($prd_qty_price, 0, ',', '.');
                                                                $prd_price = array(($prd_pr * $row_cart['quantity']));
                                                                $product_value = array_sum($prd_price);
                                                                $total_price = $total_price + $product_value;
                                                                // $total_price_in = number_format($total_price, 0, ',', '.')
                                                                
                                                                ?>     
                                                                <form action='cart.php' method = 'post'>
                                                                    <?php
                                                                        echo "<input type='hidden' value = '$product_id' class='qty' name='update_qty_id'>";
                                                                        echo " <tr class='cart-item'>";
                                                                        echo "    <td class='product-thumbnail'><img class='item' src='./../admin/quanlysanpham/upload/$product_img' alt=''></td>";
                                                                        echo "    <td class='product-name'>$product_name</td>";
                                                                        echo "    <td class='product-price'>$product_price VNĐ </td>";
                                                                        echo "    <td class='product-quantity'>
                                                                                    <div class='quantity'>
                                                                                        <input type='number' min = '1' value = '$quantity' class='qty' name='qty'>
                                                                                        <input type='submit'  value = 'Cập nhật' class='update_quantity' name='update_qty_prd'>
                                                                                    </div>
                                                                                </td>";
                                                                        echo "    <td class='product-subtotal'>$prd_qty_price VNĐ</td>";
                                                                        echo "    <td class='product-option'>
                                                                                    <a class='btn' href='cart.php?task=delete&id=".$row["product_id"]."'>
                                                                                            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash3' viewBox='0 0 16 16'>
                                                              s
                                                                                                <path d='M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z'/>
                                                                                            </svg>
                                                                                        </a>";
                                                                        echo "      </td>";
                                                                        echo"</tr>";
                                                                    ?>
                                                                </form>  
                                                        <?php
                                                            }
                                                            }
                                                            }
                                                        }                
                                                    }
                                                ?>                                  
                                        </tbody>
                                        <!-- <a href=""></a> -->
                                    </table>
                                    <!-- <input type="submit" value="Cập nhật số lượng"> -->
                        </ul>
                    </div>


                    <div class="col-sm-4 col-md-4 col-lg-4 right">
                        <ul class="donhang">
                            <h3><label>ĐƠN HÀNG</label></h3>
                            <li class="list-group-item divider"></li>
                            <div class="khuyenmai">
                            <h4><label>NHẬP MÃ KHUYẾN MÃI</h4>
                            </div>
                        <div class="input-group">
                            <input type="text" class="form-control text-uppercase" value="">
                            <span class="input-group-btn">
                                <button class="btn btn-apply" type="button">ÁP DỤNG</button>
                            </span>
                        </div>
                            <li class="list-group-item divider-1"></li>
                            <div id="order_review" class="xemlaidonhang">
                                <table class="table table-hover shop_table">
                                    <tfoot>
                                    <?php
                                    if (isset($_SESSION['username'])) {
                                        echo "
                                            <tr class='order'>
                                                <th>Đơn hàng</th>
                                                <td></td>
                                                <td>" . number_format($total_price, 0, ',', '.') . " VNĐ</td>
                                            </tr>
                                            <tr class='discount'>
                                                <th>Giảm</th>
                                                <td></td>
                                                <td>0 VNĐ</td>
                                            </tr>
                                            <tr class='order-total'>
                                                <th>TẠM TÍNH</th>
                                                <td></td>
                                                <td>" . number_format($total_price, 0, ',', '.') . " VNĐ</td>
                                            </tr>";
                                    }
                                    ?>
                                    </tfoot>
                                </table>
                            </div>
                            <?php
                            if (isset($_SESSION['username'])) {
                                echo "
                                    <a href='../user_area/checkout.php?task=checkout&ip=$get_ip_add'>
                                        <input type='submit' id='place_order' name='checkout' value='TIẾP TỤC THANH TOÁN'>
                                    </a>";
                            }
                            ?>
                            <button class="btn btn-cart btn-complete-detail "></button>
                        </ul>
                    </div>
                </div>
            </div>            
            <?php
                include '../config/footer.php';
            ?>
        </div>
    </body>
</html>
    
