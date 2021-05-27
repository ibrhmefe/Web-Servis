<?php
    $baglanti = mysqli_connect('localhost', 'root', '', 'arabalar');
    $json = array();

    if ($_POST["islem"] == "araba_modeli_ekle") {
        $model = $_POST["model"];

        try {
            $sorgu = mysqli_query($baglanti, "insert into araba_modelleri(modelAd) values ('$model')");
            if ($sorgu) {
                $json[] = array(
                "sonuc" => "1"
            );

            } else {
                $json[] = array(
                    "sonuc" => "0"
                );
            }
        } catch (Exception $e) {
            $json[] = array(
                "sonuc" => "0"
            );
        }
    }

    if ($_POST["islem"] == "araba_modelleri_getir") {
        try {
            $sorgu = mysqli_query($baglanti, "select * from araba_modelleri");
            if (mysqli_num_rows($sorgu) > 0) {
                while ($sonuc = mysqli_fetch_assoc($sorgu)) {
                    $json[] = $sonuc;
                }
            } else {
                $json[] = array(
                    "sonuc" => "0"
                );
            }
        } catch (Exception $e) {
            $json[] = array(
            "sonuc" => "1"
            );
        }
    }

    echo json_encode($json);
    mysqli_close($baglanti);
?>
