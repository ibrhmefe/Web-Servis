# Web Servis Nedir?  
  
Web servislerinin ne işe yaradıklarını basit bir örnekle anlatmak isterim.  

MySql üzerinde bulunan bir tablomuz var. Bu tablodaki verilerimizi web sitemizde kolaylıkla görüntüleyebiliriz. Fakat biz bu verileri geliştirdiğimiz bir oyunda veya telefona yaptığımız bir uygulamada da görüntülemek isteyebiliriz. Uygulamamızdan direk veri tabanına bağlanma yetkimiz yok diyelim. Bu durumda, yetkiye sahip olan bir dosyaya bağlanarak, bu dosya üzerinden veri aktarımı yapabiliriz. İşte bu bağlandığız dosyaya Web Servis adını veriyoruz.  

Web servis yalnızca veri tabanına bağlanmak için kullanılan bir dosya değildir. Web servisler temel olarak farklı platformların birbirleri ile iletişim kurmalarını sağlayan bir yapıdır.

# Web Servis Yazalım

MySql veri tabanımızdaki araba_modelleri tablomuzdan, modelAd alanındaki verileri alıp ekrana Json formatında yazdıralım.

![resim](https://raw.githubusercontent.com/ibrhmefe/Web-Servis/main/resim1.png)

MySql veritabanında tablomuzu oluşturduk. Şimdi Xampp için htdocs, Wamp için ise www klasörü içerisinde baglan.php isminde dosyamızı oluşturuyoruz.

## Baglan.php

Bağlantı isminde MySql’e bağlanmak için kodlarımızı yazıyoruz. Sırasıyla değerlerimiz:  
Server, kullanıcı adımız, bağlantı şifremiz ( Localde çoğunlukla boş olur ), bağlanacağımız tablomuz

```
<?php
$baglanti = mysqli_connect('localhost', 'root', '', 'arabalar');
```

Verilerimizi düzenli bir şekilde ekrana basmak için ben Json veri tipini kullanmayı tercih ediyorum.


```
$json = array();
```

Yazdığımız kodlarda bir hata olduğunda yakalamak için try-catch kullanıyoruz. Burada sorgu değişkenimiz veri tabanına bağlanıp içerisinde yazan sql koduna göre verileri alıp getirecek.
```
try {
    $sorgu = mysqli_query($baglanti, "select * from araba_modelleri");
```
Mysqli_num_rows() ile sorgumuzun içerisinde satır kontrolü yapıyoruz. Belkide yazdığımız sql koduna uyan veriler olmayabilir. Basit bir örnek ile e_posta ve şifresini kontrol ettiğimiz sorgudan veri dönmeyebilir. Ya da bu sql kodları select değil, update olabilir.  

Sorgunun içerisinde 0 dan fazla satır varsa, gelen verileri json içerisine while döngüsü ile aktarıyorum. Eğer satır sayısı 0 a eşitse, yani gelen veri yoksa json içerisinde “sonuc” alanına karşılık “0” değerini veriyorum.
```
        if (mysqli_num_rows($sorgu) > 0) {
            while ($sonuc = mysqli_fetch_assoc($sorgu)) {
                $json[] = $sonuc;
            }
        } else {
            $json[] = array(
                "sonuc" => "0"
            );
        }
```  

Catch içerisinde ise “sonuc” alanına “1” değerini verdim.  

```
} catch (Exception $e) {
    $json[] = array(
        "sonuc" => "1"
    );
}
```
Şimdi ise ekranıma json içerindeki tüm verileri yazdırıyorum. MySql bağlantımı ve php kodlarımı sonlandırıyorum.
```
echo json_encode($json);
mysqli_close($baglanti);
?>
```
Ve sonuç aşağıda görülmektedir. Fakat Json ile çok uğraşacaksak JSONView uzantısını indirmenizi öneririm. İkinci resime bakarsanız ne demek istediğimi çok daha iyi anlayacaksınız.

![resim](https://raw.githubusercontent.com/ibrhmefe/Web-Servis/main/resim2.png)
![resim](https://raw.githubusercontent.com/ibrhmefe/Web-Servis/main/resim3.png)
 
## Baglan.php Tüm Kodlar
```
<?php
$baglanti = mysqli_connect('localhost', 'root', '', 'arabalar');
$json = array();
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
echo json_encode($json);
mysqli_close($baglanti);
?>
```
## Verileri uygulamamızdan okuyalım

Veri tabanımızdan verileri aldığımıza göre, şimdi sıra bu verileri başka programlarda kullanmaya geldi. Ben bunu web projesinde javascript ile ajax fonksiyonu üzerinden anlatacağım.  

Basit bir html dosyası oluşturup içerisinde javascript yazabilmemiz için ayarlayalım. Ben her web projemde Jquery dosyamı da ekliyorum. Bunu siz de eklemelisiniz.

## Baglan.html Tüm Kodlar
```
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
</head>
<script type="text/javascript" src="jquery-3.2.1.min.js"></script>
<body>
    <div id="ekleme_alani"></div>
</body>
<script>
    $.ajax({
            url: "http://192.168.1.6/baglan.php",
            dataType: "json",
            success: function (cevap) {
                if(cevap[0].sonuc == "0"){
                    // Veri tabanından sonuç dönmedi
                } else if(cevap[0].sonuc == "1"){
                    // Hata oluştu
                } else {
                    for (i = 0; i < cevap.length; i++) {
                        $('#ekleme_alani').after(cevap[i].modelAd +"</br>");
                    }
                }
            }
        });
</script>
</html>
```
Yukarıdaki kodlarda açıklayacağım pek fazla birşey görmüyorum. Temel olarak, bir ajax fonksiyona web servisimizin bulunduğu url’yi verdik. Dönüş olarak da Json formatında alacağımızı belirttik. Cevap değişkenimizin içerisine gelen tüm veriler basıldı. Bunları nasıl işleyeceğimiz bize kalmış.
 
![resim](https://raw.githubusercontent.com/ibrhmefe/Web-Servis/main/resim4.png)

Kodlarımızın çıktısı yukarıdaki gibidir.

## Peki veritabanımızda işlemler yapmak istiyorsak

Verilerimizi alıp uygulama içerisinde görüntülemeyi başardık. Şimdi ise biz bazı veriler gönderip, bu verileri veritabanımıza kaydetmemiz lazım. Örnek olarak, oyunda kalınan bölümü veri tabanına kaydedebilir. Bu kullanıcı oyunu silse bile tekrar yükleyip kendi hesabıyla giriş yaptığında kaldığı yerden tekrar etmesini sağlayabiliriz. Ya da en basiti bu kullanıcı kayıt olduğunda tüm bilgilerini veri tabanına eklememiz gerekir.  

Bunu başarmak ajax fonksiyonundan, web servisimize verileri göndermemiz lazım. Ben verilerimi post tipinde gönderiyorum. Kayıt için yeni bir web servis yazabiliriz. Fakat ben daha iyi anlaşılması için hepsini tek bir dosya üzerinde işlemleri ayırarak yapmayı göstereceğim.

## Baglan.php

Evet, tek bir sayfa üzerinde işlem yapmam lazım. Fakat bu işlemleri de birbirlerinden ayrı tutmalıyım. Ben bu sayfaya post tipinde veriler göndereceğim. Bu verilerden bir tanesi “islem” olacak. Buna göre:
```
if ($_POST["islem"] == "araba_modeli_ekle") {
```
islem “araba_modeli_ekle” ise parantezimizin içerisindeki kodlar çalıştırılacak.
```
	$model = $_POST["model"];
```
Post ile gelen veriler arasında “model” ismiyle gelen veriyi de model isimli bir değişkene atıyorum.
```
try {
	$sorgu = mysqli_query($baglanti, "insert into araba_modelleri(model) values ('$model')");
```
Sorgumun içerisinde model değişkenimin verisini ekledim.
```
        	if ($sorgu) {
                	$json[] = array(
                	"sonuc" => "1"
            	);
```

Burada gelen bir veri olmayacak. Sorgumuzun sorunsuz çalıştığının kontrolünü yapıyorum. Başarılı ise “sonuc” değerimiz “1” olacak değilse aşağıda “0” olarak ayarlayacağız.
```
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
```

## Baglan.php Tüm Kodlar
```
if ($_POST["islem"] == "araba_modeli_ekle") {
	$model = $_POST["model"];
try {
	$sorgu = mysqli_query($baglanti, "insert into araba_modelleri($model) values (5, 'Mazda')");
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
```

Web servisle işimiz bu kadar şimdi baglan.html dosyamızda gerekli birkaç değişikliği yapalım. Data içerisinde 2 adet veri, birisi işlem tipi diğeri ise veri tabanımıza işleyecek olduğumuz verimiz. Eğer ki araba modellerini almak istiyorsak islem değerini araba_modelleri_getir olarak ayarlamamız ve diğer verileri silmemiz yeterli olacaktır.
```
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
</head>
<script type="text/javascript" src="jquery-3.2.1.min.js"></script>
<body>
</body>
<script>
    $.ajax({
            type: "post",
            url: "http://192.168.1.6/baglan.php",
            dataType: "json",
            data: {"islem": "araba_modeli_ekle",								"model": "Mazda"},
            success: function (cevap) {
                if(cevap[0].sonuc == "1"){
                    // İşlem başarılı
                } else {
                    // Hata oluştu
                }
            }
        });
</script>
</html>
```


