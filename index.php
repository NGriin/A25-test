<?php
require_once 'backend/sdbh.php';

$dbh = new sdbh();
?>
<html>
    <head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="assets/css/style.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"  crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#form').submit(function(e) {
                    e.preventDefault();
                    $.ajax({
                        type: "POST",
                        url: 'backend/calculation.php',
                        data: $(this).serialize(),
                        success: function(response)
                        {
                            var jsonData = JSON.parse(response);
                            document.getElementById("value").innerHTML = jsonData.success;
                        }
                    });
                });
            });
        </script>
    </head>
    <body>
        <div class="container">
            <div class="row row-header">
                <div class="col-12">
                    <img src="assets/img/logo.png" alt="logo" style="max-height:50px"/>
                    <h1>Прокат</h1>
                </div>
            </div>
            <form method="post" action="backend/calculation.php" id="form">
            <div class="row row-body">
            <div class="col-12">
                    <h4>Выберите продукт:</h4>
                    <select class="form-select" name="product" id="product">
                    <?php
                    $products = ($dbh->mselect_rows('a25_products', 'TARIFF' , 0, 3, 'ID'));
                    foreach($products as $product) { ?>
                        <option><?=$product['NAME']?></option>
                        <?php
                        }
                        ?>
                    </select>
                    <h4>Количество дней:</h4>
                    <input type="text" name="days" class="form-control" id="customRange1" min="1" max="30"/>
                    <h4>Дополнительные услуги:</h4>
            <div class="form-check">
                <?php
                $services = unserialize($dbh->mselect_rows('a25_settings', ['set_key' => 'services'], 0, 1, 'id')[0]['set_value']);
                foreach($services as $k => $s) { ?>
                    <input class="form-check-input" type="checkbox" name="service[]">
                <dd><?=$k?>: <?=$s?></dd>
                    <?php
                }
                ?>
            </div>
                <input type="submit" class="btn btn-primary" value="Рассчиать"/>
                <h4 align="right">Итоговая стоимость:</h4>
                <h4 align="right"><div id="value"></div></h4>
                </form>
            </div>
            </div>
        </div>
    </body>
</html>