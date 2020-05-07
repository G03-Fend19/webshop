<?php
require_once "./db.php";
$sql = "SELECT
ws_products.name            AS ProductName,
ws_products.price           AS ProductPrice,
ws_products.id              AS ProductId,
ws_products.stock_qty       AS ProductQty,
ws_images.img               AS ImageName,
ws_products_images.img_id   AS ProductImageImageId,
ws_products_images.feature  AS FeatureImg,
ws_products.added_date      AS AddedDate
FROM
ws_products
LEFT JOIN
ws_products_images
ON
ws_products.id = ws_products_images.product_id
AND
ws_products_images.feature = 1
LEFT JOIN
ws_images
ON
ws_products_images.img_id = ws_images.id
WHERE
ws_products.stock_qty >= 0
AND ws_products.active = 1";
$stmt = $db->prepare($sql);
$stmt->execute();
$allProductsArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
</main>
<footer>
  <section class="foot__content">
  <div class="foot__content__about">
  <div class="foot__logo"><a href="index.php"> <img src="./media/images/logo_white.png"  />
        </a>
      </div>
         <p> Some text that discribe us. Lorem ipsum dolor sit amet consectetur adipisicing elit. Nam consectetur quidem autem neque iste suscipit saepe recusandae.</p>
</div>
<div class="foot__content__wrapper">
<div class="foot__content__nav">
    <h3>Links</h3>
    <ul>
    <li><a class="header__nav__item__a header__nav__item__home" href="index.php">Home</a></li>
    <li><a id="contact-desktop" class="header__nav__item__a header__nav__item__contact2"
            href="">Contact</a></li>
            <li><a id="contact-desktop" class="header__nav__item__a header__nav__item__contact2"
            href="">FAQ</a></li>
            <li><a id="contact-desktop" class="header__nav__item__a header__nav__item__contact2"
            href="">Terms</a></li>
            </ul>
</div>
            <div class="foot__content__social">
    <h3>Social</h3>
    <a href="https://www.instagram.com/"><i class="fab fa-instagram"></i> Instagram</a>
    <a href="https://www.facebook.com/"><i class="fab fa-facebook"></i>Facebook</a>
    <a href="https://www.twitter.com/"><i class="fab fa-twitter"></i>Twitter</a>
    <a href="https://www.youtube.com/"><i class="fab fa-youtube"></i>Youtube</a>
    </div>
    <div class="foot__content__contact">
    <h3>Contact us</h3>
    <p>08-466 60 00</p>
    <a href="mailto:ebostrom@live.se">webshop@mail.com</a>
    <p>Tomtebodavägen 3a <br> 171 65 Solna</p>
</div>
</div>
</section>
<div class="footer__foot">
© <?php echo date("Y"); ?> Copyright Webshop
</div>
</footer>
<script>let allProductsFromPHP = <?php echo json_encode($allProductsArr);?>; </script>
<script src="./cart.js"></script>
<script src="https://kit.fontawesome.com/06088ec039.js" crossorigin="anonymous"></script>
<script src="./search_validation.js"></script>
<script src="./toggle_menu.js"></script>
</body>
</html>