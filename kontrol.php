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
        <title><?php echo $config['kontrol_baslik'];?></title>

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
    <?php
    if ( isset( $_POST['datepicker'] ) ){
        $dts = explode("/", $_POST['datepicker']);
        $ay = $dts[1]-1;
        echo '$( function() {$( "#datepicker" ).datepicker({ dateFormat: "dd/mm/yy"}).datepicker(\'setDate\',  new Date(' . $dts[2] . ',' . $ay . ',' . $dts[0] . '));} );';
    }else{
        echo '$( function() {$( "#datepicker" ).datepicker({ dateFormat: "dd/mm/yy"}).datepicker("setDate", "+0")} );';
    }
    ?>
</script>

<div class="main">
    <div id="header" class="header">YOKLAMA KONTROL</div>
    <form action="kontrol.php" method="post">
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

        <br>
        <br>
        <input name="listele" value="Seçili Sınıfı Listele" type="submit" class="btn btn-primary btn-md">
        <input name="tumliste" value="Tüm Sınıfları Listele" type="submit" class="btn btn-primary btn-md">
        <br>
        <br>

    <div id="yoklama_php">
        <?php
            $config = include('config.php');
            $seviyeler = $config['seviyeler'];
            $birincisiniflar = $config['birincisiniflar'];
            $ikincisiniflar = $config['ikincisiniflar'];
            $ucuncusiniflar = $config['ucuncusiniflar'];
            $dorduncusiniflar = $config['dorduncusiniflar'];

            $sinif = $_POST['sinif'];
            $ders = $_POST['ders'];
            $tarih = $_POST['datepicker'];

            function liststudents($buttonval, $ders, $sinif, $tarih) {
                $config = include('config.php');
                $dbname = $config['dbname'];
                $db = new SQLite3($dbname);
                $mevcut = 0;
                $say = 0;
                if ( isset( $buttonval ) ) {
                    $bsinif = strtoupper($sinif);
                    $dders = strtoupper(str_replace("_", ". ", $ders));

                    ?>

                    <p class="text-primary"><?php echo $bsinif; ?> SINIFI (<?php echo $tarih; ?> - <?php echo $dders; ?>)</p>
                    <br>
                    =============================

                    <?php
                    $check_teacher = count($db->querySingle("SELECT tarih, \"$ders\" FROM yoklama WHERE "
                            . "(sinif=\"$sinif\" AND \"$ders\"=\"1\" AND tarih=\"$tarih\") OR "
                            . "(sinif=\"$sinif\" AND \"$ders\"=\"-1\" AND tarih=\"$tarih\") OR "
                            . "(sinif=\"$sinif\" AND \"$ders\"=\"0\" AND tarih=\"$tarih\")"));
                    if($check_teacher == 0) {
                        ?>
                        <br>
                        <br>
                        <p class="text-danger"><?php echo $bsinif; ?> sınıfında yoklama henüz ALINMAMIŞ!</p>
                        <br>
                        <br>
                        <?php
                    }
                    else
                    {
                        $results = $db->query("SELECT numara, ad, soyad, \"$ders\" FROM yoklama WHERE "
                                . "(sinif=\"$sinif\" AND \"$ders\"=\"1\" AND tarih=\"$tarih\") OR "
                                . "(sinif=\"$sinif\" AND \"$ders\"=\"-1\" AND tarih=\"$tarih\")");

                        $results2 = $db->query("SELECT numara, ad, soyad, \"$ders\" FROM yoklama WHERE "
                                . "(sinif=\"$sinif\" AND \"$ders\"=\"1\" AND tarih=\"$tarih\") "
                                . "OR (sinif=\"$sinif\" AND \"$ders\"=\"-1\" AND tarih=\"$tarih\") "
                                . "OR (sinif=\"$sinif\" AND \"$ders\"=\"0\" AND tarih=\"$tarih\") ORDER BY numara ASC");
                        ?>

                        <table class="table-sm table-hover table-bordered" id="liste">
                            <tr><th>No</th><th>Ad</th><th>Soyad</th><th>Durum</th></tr>

                        <?php
                        while ($row = $results2->fetchArray()) {
                            $say++;
			}

                        while ($row = $results->fetchArray()) {
                            $mevcut++;
                            $durum = $row[3];
                            if($durum == 1) {
                                $varyok = "YOK";
                                $colorize = "table-active";
                            }
                            elseif ($durum == 0) {
                                $varyok = "VAR";
                                $colorize = "table-success";
                            }
                            elseif ($durum == -1) {
                                $varyok = "İZİNLİ";
                                $colorize = "table-warning";
                            }
                            ?>
                                <tr class=<?php echo $colorize;?>>
                                    <td><?php echo $row[0]; ?></td>
                                    <td><?php echo $row[1]; ?></td>
                                    <td><?php echo $row[2]; ?></td>
                                    <td><?php echo $varyok; ?></td>
                                </tr>
                            <?php
                        }
                        ?>
                        </table>
                        <?php
                        if($mevcut == 0) {
                            ?>
                                <br>
                                <br>
                                <p class="text-success"><?php echo $bsinif; ?> sınıfı TAM.</p>
                                <p class="text-success"><?php echo $bsinif; ?> sınıf mevcudu <?php echo $say; ?> (.....).</p>
                                <br>
                                <br>
                            <?php
                        }
                        else {
                            ?>
                                <br>
                                <br>
                                <p class="text-warning"><?php echo $bsinif; ?> sınıfından <?php echo $mevcut; ?> kişi sınıfta değil!</p>
                                <p class="text-success"><?php echo $bsinif; ?> sınıf mevcudu <?php echo $say; ?> (.....).</p>
                                <br>
                                <br>
                            <?php
                        }
                    }
                }
            }


            if(isset($_POST['tumliste'])) {

                ?>
                <table  class="table-sm table-hover table-bordered" id="okul">
                    <tr><th><?php echo $seviyeler[0]; ?></th>
                        <th><?php echo $seviyeler[1]; ?></th>
                        <th><?php echo $seviyeler[2]; ?></th>
                        <th><?php echo $seviyeler[3]; ?></th>
                    </tr>
                    <tr>

                    <td class="align-top">
                    <!-- Birinci kademeler -->
                    <table  class="table-sm table-hover table-bordered" id="birincisiniflar">

                        <?php
                        foreach ($birincisiniflar as $value) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo liststudents($_POST['tumliste'],
                                            $ders, $value, $tarih); ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                    </td>

                   <td class="align-top">
                    <!-- İkinci kademeler -->
                    <table  class="table-sm table-hover" id="ikincisiniflar">

                        <?php
                        foreach ($ikincisiniflar as $value) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo liststudents($_POST['tumliste'],
                                            $ders, $value, $tarih); ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                    </td>

                   <td class="align-top">
                    <!-- Ucuncu kademeler -->
                    <table  class="table-sm table-hover table-bordered" id="ucuncusiniflar">

                        <?php
                        foreach ($ucuncusiniflar as $value) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo liststudents($_POST['tumliste'],
                                            $ders, $value, $tarih); ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                    </td>

                   <td class="align-top">
                    <!-- Dorduncu kademeler -->
                    <table  class="table-sm table-hover table-bordered" id="dorduncusiniflar">
                        <?php
                        foreach ($dorduncusiniflar as $value) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo liststudents($_POST['tumliste'],
                                            $ders, $value, $tarih); ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                    </td>

                    </tr>
                    </table>
                    <?php
            }

            if(isset($_POST['listele'])) {
                liststudents($_POST['listele'], $ders, $sinif, $tarih);
            }

        ?>
    </div>
    </form>
    <footer class="footer">
        <div class="main">
            <br>
            <br>
            <br>
            <font size="2">
                Powered by <a href="http://www.kayihan.net">H. Aziz KAYIHAN</a>.
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
</body></html>
