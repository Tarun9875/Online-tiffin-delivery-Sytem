<?php include( 'partials/admin-menu.php'); ?>
<div class="wraper">
    <div class="main">
        <div >
      <h3>  <strong >Update Order</strong></h3>
        <br>
        </div>
        <br/>
<!--Main update content -->
<form action="" method="post">
        <table class="tbl-30">
                <tr>
                    <td>
                        Tiffin Name:
                            <td>

                            </td>
                    </td>
                </tr>
                        <tr>
                            <td>Qty</td>
                            <td>
                                <input type="number" name="qty" value="">
                            </td>
                        </tr>           
                            <tr>
                                <td>Status:</td>
                                <td>
                                    <select name="status">
                                        <option value="ordered">Ordered</option>
                                        <option value="On Delivery">On Delivery</option>
                                        <option value="Delivered">Delivered</option>
                                        <option value="Cancelled">Cancelled</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <input type="submit" name="submit"value="Update Order" class="btn-secondary">
                                </td>
                            </tr>
        </table>


</form>





<!--Main update content end.. -->
        </div>

</div>
<?php include('partials/footer.php');?>