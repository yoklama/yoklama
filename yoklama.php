<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="Yoklama Yazılımı" content="">
        <meta name="Yücel KILIÇ" content="">
        <link rel="icon" href="favicon.ico">
        <?php $config = include('config.php');?>
        <title><?php echo $config['baslik'];?></title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity=$
        <link rel="stylesheet" href="style/bootstrap.min.css" type="text/css">
        <link href="style/jquerysctipttop.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="style/style.css">
        <link rel="stylesheet" href="style/jquery-ui.css">

        <script src="js/jquery-1.12.4.js"></script>
        <script src="js/jquery-ui.js"></script>
        <script src="js/jquery.twbs-toggle-buttons.min.js"></script>

    </head>

<body>

<center>

<!-- Developer: Yücel KILIÇ yucelkilic@antalyasinavkoleji.com -->

<script>

$( function() {

$("#datepicker").datepicker({ dateFormat: "dd/mm/yy"}).datepicker("setDate", "+0");

  } );

</script>

<div class="main">
    <div id="header" class="header">YOKLAMA</div>
    <hr class="threeQuarter"><br>
    <form action="yoklama.php" method="post">
        <label for="tarih">Tarih:</label>
        <input id="datepicker" name="datepicker" type="text" class="input text_left">
        <br><br>
        <label class="mr-sm-2" for="sinif">Sınıf:</label>
        <select id="sinif" name="sinif" class="custom-select mb-2 mr-sm-2 mb-sm-0">
            <?php
                $bk = $config['birincisiniflar'];
                $ik = $config['ikincisiniflar'];
                $uk = $config['ucuncusiniflar'];
                $dk = $config['dorduncusiniflar'];

                $siniflar = array_merge($bk, $ik, $uk, $dk);

                foreach ($siniflar as $value) {
                    ?>
                    <option value="<?php echo $value; ?>"<?php if ($_POST['sinif'] == $value) echo ' selected="selected"'; ?>><?php echo $value; ?></option>
                    <?php
                }
            ?>
        </select>

        <input name="listele" value="Listele" type="submit" class="btn btn-primary btn-md">
        <br><br>
        <label class="mr-sm-2" for="sinif">Ders:</label>
        <select id="ders" name="ders" class="custom-select mb-2 mr-sm-2 mb-sm-0">

            <?php $saatler = $config['saatler']; ?>

            <option value="1_ders"<?php if ($_POST['ders'] == '1_ders') echo ' selected="selected"'; ?>>1. Ders (<?php echo $saatler[0]; ?>)</option>
            <option value="2_ders"<?php if ($_POST['ders'] == '2_ders') echo ' selected="selected"'; ?>>2. Ders  (<?php echo $saatler[1]; ?>)</option>
            <option value="3_ders"<?php if ($_POST['ders'] == '3_ders') echo ' selected="selected"'; ?>>3. Ders  (<?php echo $saatler[2]; ?>)</option>
            <option value="4_ders"<?php if ($_POST['ders'] == '4_ders') echo ' selected="selected"'; ?>>4. Ders (<?php echo $saatler[3]; ?>)</option>
            <option value="5_ders"<?php if ($_POST['ders'] == '5_ders') echo ' selected="selected"'; ?>>5. Ders (<?php echo $saatler[4]; ?>)</option>
            <option value="6_ders"<?php if ($_POST['ders'] == '6_ders') echo ' selected="selected"'; ?>>6. Ders (<?php echo $saatler[5]; ?>)</option>
            <option value="7_ders"<?php if ($_POST['ders'] == '7_ders') echo ' selected="selected"'; ?>>7. Ders (<?php echo $saatler[6]; ?>)</option>
            <option value="8_ders"<?php if ($_POST['ders'] == '8_ders') echo ' selected="selected"'; ?>>8. Ders (<?php echo $saatler[7]; ?>)</option>
            <option value="9_ders"<?php if ($_POST['ders'] == '9_ders') echo ' selected="selected"'; ?>>9. Ders (<?php echo $saatler[8]; ?>)</option>
            <option value="10_ders"<?php if ($_POST['ders'] == '10_ders') echo ' selected="selected"'; ?>>10. Ders (<?php echo $saatler[9]; ?></option>
        </select>
        <br><br>

        <ul>
            <li><div class="blink text-primary">ÖNEMLİ: Sınıf listelemeden önce günün tarihinin seçili olduğundan emin olunuz.</div></li>
            <li><div class="blink text-primary">İlgili sınıfın öğrencilerini 'Listele' butonu ile listeledikten sonra yoklama alıp 'Gönder' butonuna basınız.</div></li>
            <li><div class="blink text-primary">'Gönder' butonuna tıkladıktan sonra 'Yoklama gönderildi.' uyarısını görmeden sayfayı kapatmayınız.</div></li>
            <li><div class="blink text-primary">Yoklamada bir yanlışlık olduğunu düşünüyorsanız ya da yeni bir öğrenci okula gelirse yeniden yoklama alabilirsiniz.</div></li>
            <li><div class="blink text-primary">Eğer sınıf tamsa sınıf seçiminden sonra doğrudan 'Gönder' butonuna basabilirsiniz.</div></li>
            <li><div class="blink text-primary">Sınıf listesinin tamamını görmek için sayfayı aşağı kaydırınız.</div></li>
        </ul><br>

    <hr class="threeQuarter"><br>
    <div id="yoklama">
        <?php
           $tarih = $_POST['datepicker'];
           $sinif = $_POST['sinif'];
           $ders = $_POST['ders'];
           $config = include('config.php');
           $dbname = $config['dbname'];
           $db = new SQLite3($dbname);
           $mevcut = 0;
            if ( isset( $_POST['listele'] ) ) {

                $checkprevious = count($db->querySingle("SELECT numara, ad, soyad, \"$ders\" FROM yoklama WHERE "
                                . "(sinif=\"$sinif\" AND \"$ders\"=\"1\" AND tarih=\"$tarih\") "
                                . "OR (sinif=\"$sinif\" AND \"$ders\"=\"-1\" AND tarih=\"$tarih\") "
                                . "OR (sinif=\"$sinif\" AND \"$ders\"=\"0\" AND tarih=\"$tarih\") ORDER BY numara ASC"));

                if($checkprevious == 0) {
                    $results = $db->query("SELECT numara, ad, soyad FROM \"$sinif\" ORDER BY numara ASC");
                }else{
                    $results = $db->query("SELECT numara, ad, soyad, \"$ders\" FROM yoklama WHERE "
                                . "(sinif=\"$sinif\" AND \"$ders\"=\"1\" AND tarih=\"$tarih\") "
                                . "OR (sinif=\"$sinif\" AND \"$ders\"=\"-1\" AND tarih=\"$tarih\") "
                                . "OR (sinif=\"$sinif\" AND \"$ders\"=\"0\" AND tarih=\"$tarih\") ORDER BY numara ASC");
                }
                ?>
                <table id="liste" class="table table-sm table-hover table-bordered table-fit table-striped table-responsive">
                    <tr>
                        <th>No</th><th>Ad</th><th>Soyad</th><th>Durum</th>
                    </tr>
                    <?php
                    while ($row = $results->fetchArray()) {
                        $mevcut++;
                        ?>
                        <tr>
                            <td><?php echo $row[0]; ?></td>
                            <td><?php echo $row[1]; ?></td>
                            <td><?php echo $row[2]; ?></td>

                            <td align="center">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                      <label class="btn<?php if ($row[3] == 0) echo " active "; ?><?php if($checkprevious == 0) echo " active "; ?> btn-sm" role="button">
                                        <input type="radio" name="options<?php echo $mevcut; ?>" value="0" required>VAR
                                      </label>
                                      <label class="btn<?php if ($row[3] == -1) echo " active "; ?> btn-sm" role="button" data-twbs-toggle-buttons-class-active="btn-warning" data-twbs-toggle-buttons-class-inactive="btn-error">
                                        <input type="radio" name="options<?php echo $mevcut; ?>" value="-1">İZİNLİ
                                      </label>
                                      <label class="btn<?php if ($row[3] == 1) echo " active "; ?> btn-sm" role="button" data-twbs-toggle-buttons-class-active="btn-danger">
                                        <input type="radio" name="options<?php echo $mevcut; ?>" value="1">YOK
                                      </label>
                                    </div>
                                <script>
                                $(".btn-group-toggle").twbsToggleButtons();
                                </script>
                            </td>
                        </tr>
                    <?php }; ?>

                </table>
                <br>
                <br>
                    <b>
                        <p class="text-primary"><?php echo strtoupper($sinif); ?> sınıfından <?php echo $mevcut; ?> kişi listelendi.</p>
                    <b>
                <br>
                <br>

            <?php }
            $mevcut = 0;
            if ( isset( $_POST["gonder"] ) ) {
                $ders = $_POST["ders"];
                $tarih = $_POST["datepicker"];
                $sorgu = "SELECT numara, ad, soyad FROM \"$sinif\" ORDER BY numara ASC";
                $results = $db->query($sorgu);

                while ($row = $results->fetchArray()) {
                    $mevcut++;

                    if(isset($_POST["options".$mevcut])) {
                        $durum = $_POST["options".$mevcut];
                    }else{
                        $durum = 0;
                    }

                    $numara = $row[0];
                    $ad = $row[1];
                    $soyad = $row[2];

                    $check_duplicate = count($db->querySingle("SELECT tarih, \"$ders\" FROM yoklama WHERE "
                            . "(sinif=\"$sinif\" AND \"$ders\"=\"1\" AND tarih=\"$tarih\") "
                            . "OR (sinif=\"$sinif\" AND \"$ders\"=\"-1\" AND tarih=\"$tarih\") "
                            . "OR (sinif=\"$sinif\" "
                            . "AND \"$ders\"=\"0\" AND tarih=\"$tarih\")"));
                    if($check_duplicate == 0) {
                            $qry = $db->prepare("INSERT INTO yoklama "
                                    . "(tarih, numara, ad, soyad, sinif, '$ders') "
                                    . "VALUES ('$tarih', '$numara', '$ad', '$soyad', '$sinif', '$durum')");
                            $qry->execute();
                    }else {
                        if($mevcut == 1) {
                                     $qry = $db->prepare("DELETE FROM yoklama WHERE "
                                             . "(sinif=\"$sinif\" AND \"$ders\"=\"1\" AND tarih=\"$tarih\") "
                                             . "OR (sinif=\"$sinif\" AND \"$ders\"=\"-1\" AND tarih=\"$tarih\") "
                                             . "OR (sinif=\"$sinif\" AND \"$ders\"=\"0\" AND tarih=\"$tarih\")");
                                     $qry->execute();
                        }

                        $qry = $db->prepare("INSERT INTO yoklama (tarih, numara, ad, soyad, sinif, '$ders') "
                                . "VALUES ('$tarih', '$numara', '$ad', '$soyad', '$sinif', '$durum')");
                        $qry->execute();
                    }
                }
                ?>
                <b>
                    <p class="text-success">Yoklama gönderildi.</p>
                </b>
                <?php
            }
        ?>
        <input name="gonder" value="Gönder" type="submit" class="btn btn-primary btn-md">
    </div>
    </form>
    <footer class="footer">
        <div class="main">
            <br>
            <br>
            <br>
            <font size="2">
                Powered by <a href="http://kayihan.net">H. Aziz KAYIHAN</a>.
                <br>
                Bu çalışma <a href="http://ozgurlisanslar.org.tr/gpl/gpl-v3/">GNU Genel Kamu Lisansı</a> <a href="https://www.gnu.org/licenses/gpl-3.0.en.html">( GNU GPL v3)</a> ile lisanslanmıştır.
                <br>
                <p>
                <a href="mailto:aziz.kayihan@sinavkoleji.com.tr?Subject=Merhaba%20Aziz! :)" target="_top">E-posta -> aziz.kayihan@sinavkoleji.com.tr</a>
                </p>
            </font>
        </div>
    </footer>
</div>
</center>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
