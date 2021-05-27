# Web Servis Nedir?

	Web servislerinin ne işe yaradıklarını basit bir örnekle anlatmak isterim.
	MySql üzerinde bulunan bir tablomuz var. Bu tablodaki verilerimizi web sitemizde kolaylıkla görüntüleyebiliriz. Fakat biz bu verileri geliştirdiğimiz bir oyunda veya telefona yaptığımız bir uygulamada da görüntülemek isteyebiliriz. Uygulamamızdan direk veri tabanına bağlanma yetkimiz yok diyelim. Bu durumda, yetkiye sahip olan bir dosyaya bağlanarak, bu dosya üzerinden veri aktarımı yapabiliriz. İşte bu bağlandığız dosyaya Web Servis adını veriyoruz.
	Web servis yalnızca veri tabanına bağlanmak için kullanılan bir dosya değildir. Web servisler temel olarak farklı platformların birbirleri ile iletişim kurmalarını sağlayan bir yapıdır.

# Web Servis Yazalım

	MySql veri tabanımızdaki araba_modelleri tablomuzdan, modelAd alanındaki verileri alıp ekrana Json formatında yazdıralım.

![resim]: (resim1.png)`

	MySql veritabanında tablomuzu oluşturduk. Şimdi Xampp için htdocs, Wamp için ise www klasörü içerisinde baglan.php isminde dosyamızı oluşturuyoruz.

## Baglan.php

	Bağlantı isminde MySql’e bağlanmak için kodlarımızı yazıyoruz. Sırasıyla değerlerimiz:
Server, kullanıcı adımız, bağlantı şifremiz ( Localde çoğunlukla boş olur ), bağlanacağımız tablomuz

```
<?php
$baglanti = mysqli_connect('localhost', 'root', '', 'arabalar');
```

	Verilerimizi düzenli bir şekilde ekrana basmak için ben Json veri tipini kullanmayı tercih ediyorum.