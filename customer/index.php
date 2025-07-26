<?php include( 'partials-customer/menu.php'); ?>

    <!-- fOOD sEARCH Section Starts Here -->
    <section class="food-search text-center">
        <div class="container">
            
            <form action="food-search.php" method="POST">
                <input type="search" name="search" placeholder="Search for Food.." required>
                <input type="submit" name="submit" value="Search" class="btn btn-primary">
            </form>

        </div>
    </section>
    <!-- fOOD sEARCH Section Ends Here -->

    <!-- CAtegories Section Starts Here -->
    <section class="categories">
        <div class="container">
            <h2 class="text-center">Explore Categories</h2>

            <a href="categories.php">
            <div class="box-3 float-container">
                <img src="\images\3.jpg" alt="Desert" class="img-responsive img-curve">

                <h3 class="float-text text-white">Desert</h3>
            </div>
            </a>

            <a href="categories.php">
            <div class="box-3 float-container">
                <img src="\images\2.jpg" alt="Tiffin" class="img-responsive img-curve">

                <h3 class="float-text text-white">Tiffin</h3>
            </div>
            </a>

            <a href="categories.php">
            <div class="box-3 float-container">
                <img src="\images\7.jpg" alt="Veg" class="img-responsive img-curve">

                <h3 class="float-text text-white">Veg Thali</h3>
            </div>
            </a>

            <div class="clearfix"></div>
        </div>
    </section>
    <!-- Categories Section Ends Here -->

    <!-- fOOD MEnu Section Starts Here -->
    <section class="food-menu">
        <div class="container">
            <h2 class="text-center">Tiffin Menu</h2>

            <div class="food-menu-box">
                <div class="food-menu-img">
                    <img src="\images\1.jpg" alt="Simple Bhaji Tiffin Box" class="img-responsive img-curve">
                </div>

                <div class="food-menu-desc">
                    <h4>Simple Bhaji Tiffin Box</h4>
                    <p class="food-price">₹100</p>
                    <p class="food-detail">
                    Made with Indian Sauce, indian masalas, and organice vegetables.
                    </p>
                    <br>

                   <!--   <a href="order.php" class="btn btn-primary">Order Now</a>-->
                </div>
            </div>

            <div class="food-menu-box">
                <div class="food-menu-img">
                    <img src="\images\6.jpg" alt="Paneer Thali" class="img-responsive img-curve">
                </div>

                <div class="food-menu-desc">
                    <h4>Paneer Thali</h4>
                    <p class="food-price">₹500</p>
                    <p class="food-detail">
                    Made with Indian Sauce, indian masalas, and organice vegetables.
                    </p>
                    <br>

                   <!--   <a href="order.php" class="btn btn-primary">Order Now</a>-->
                </div>
            </div>

            <div class="food-menu-box">
                <div class="food-menu-img">
                    <img src="\images\7.jpg" alt="Sahi Paneer Thali" class="img-responsive img-curve">
                </div>

                <div class="food-menu-desc">
                    <h4>Sahi Paneer Thali</h4>
                    <p class="food-price">₹300</p>
                    <p class="food-detail">
                    Made with Indian Sauce, indian masalas, and organice vegetables.
                    </p>
                    <br>

                  <!--   <a href="order.php" class="btn btn-primary">Order Now</a>-->
                </div>
            </div>

            <div class="food-menu-box">
                <div class="food-menu-img">
                    <img src="\images\2.jpg" alt="Rise Bhaji Tiffin Box" class="img-responsive img-curve">
                </div>

                <div class="food-menu-desc">
                    <h4>Rise Bhaji Tiffin Box</h4>
                    <p class="food-price">₹250</p>
                    <p class="food-detail">
                    Made with Indian Sauce, indian masalas, and organice vegetables.
                    </p>
                    <br>

                    <!--   <a href="order.php" class="btn btn-primary">Order Now</a>-->
                </div>
            </div>

            <div class="food-menu-box">
                <div class="food-menu-img">
                    <img src="\images\8.jpg" alt="Rise Bhaji Tiffin Box" class="img-responsive img-curve">
                </div>

                <div class="food-menu-desc">
                    <h4>Rise Bhaji Tiffin Box</h4>
                    <p class="food-price">₹200</p>
                    <p class="food-detail">
                    Made with Indian Sauce, indian masalas, and organice vegetables.
                    </p>
                    <br>

                    <!--   <a href="order.php" class="btn btn-primary">Order Now</a>-->
                </div>
            </div>

            <div class="food-menu-box">
                <div class="food-menu-img">
                    <img src="\images\9.jpg" alt="Staff Tiffin Box" class="img-responsive img-curve">
                </div>

                <div class="food-menu-desc">
                    <h4>Staff Tiffin Box</h4>
                    <p class="food-price">₹100</p>
                    <p class="food-detail">
                    Made with Indian Sauce, indian masalas, and organice vegetables.
                    </p>
                    <br>

                    <!--   <a href="order.php" class="btn btn-primary">Order Now</a>-->
                </div>
            </div>


            <div class="clearfix"></div>

            

        </div>

        <p class="text-center">
            <a href="#">See All Foods</a>
        </p>
    </section>
    <!-- fOOD Menu Section Ends Here -->

   

    <?php include('partials-customer/footer.php');?>