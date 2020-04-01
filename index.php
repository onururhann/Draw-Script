<?php
error_reporting(E_ALL & ~ E_NOTICE);
ob_start();

$vtAdi = "veritabani";
$vtKul = "kullaniciadi";
$vtSif = "sifre";
$vtSun = "localhost";

$baglanti = mysql_connect($vtSun,$vtKul,$vtSif) or die("MySQL Sunucu Bağlantı Hatası !");
mysql_select_db($vtAdi,$baglanti) or die("MySQL Veritabanı Seçilemedi !");

mysql_query("SET NAMES 'utf8'");
mysql_query("SET CHARACTER SET utf8");
mysql_query("SET COLLATION_CONNECTION = 'utf8_general_ci'");

$kazananlar = array();
$yedekler = array();

$kazananSorgu = mysql_query("SELECT * FROM kazananlar ORDER BY yedek ASC");
while($kazananYaz = mysql_fetch_object($kazananSorgu))
{
	if($kazananYaz->yedek == 0)
	{
		$kazananlar[] = $kazananYaz->katilimci;
	}
	else
	{
		$yedekler[]	  = $kazananYaz->katilimci;
	}
}
katilimciSil();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title>Basit Çekiliş Scripti</title>
</head>
<body>
	<h2>Katılımcı Ekle</h2>
	<form action="" method="post">
		<input type="hidden" name="islem" value="katilimciEkle" />
		<table>
			<tr>
				<td>Katılımcı Adı</td>
				<td>:</td>
				<td><input type="text" name="isim" /> * Harf ve Sayı kullanılabilir.</td>
			</tr>
			<tr>
				<td>Katılım Hakkı</td>
				<td>:</td>
				<td><select name="hak">
				<?php
					for($i=1;$i<=100;$i++)
					{
						echo '<option value="'.$i.'">'.$i.'</option>';
					}
				?>
				</select></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><input type="submit" value="Ekle" /></td>
			</tr>
			<?php if($_POST['islem'] == "katilimciEkle"){ ?>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><?php katilimciEkle(); ?></td>
			</tr>
			<?php } ?>
		</table>
	</form>
	<?php
		$katilimciSorgu = mysql_query("SELECT * FROM katilimcilar ORDER BY katilimci ASC");
		$katilimciSay	= mysql_num_rows($katilimciSorgu);
		if($katilimciSay > 0)
		{
	?>
	
	<h2>Katılımcılar ( <?php echo $katilimciSay; ?> )</h2>
	<ul>
		<?php
			while($katilimciYaz = mysql_fetch_object($katilimciSorgu))
			{
				echo '<li>'.$katilimciYaz->katilimci.' ('.$katilimciYaz->hak.') <a href="index.php?sil='.$katilimciYaz->id.'">Sil</a></li>';
			}
		?>
	</ul>
	<?php
		}
	?>
	
	<h2>Çekilişi Bitir</h2>
	<form action="" method="post">
		<input type="hidden" name="islem" value="cekilisBitir" />
		<table>
			<tr>
				<td>Kazanacak sayısı</td>
				<td>:</td>
				<td><select name="kazanacak">
				<?php
					for($i=1;$i<=100;$i++)
					{
						echo '<option value="'.$i.'">'.$i.'</option>';
					}
				?>
				</select></td>
			</tr>
			<tr>
				<td>Yedek var mı ?</td>
				<td>&nbsp;</td>
				<td><input type="checkbox" name="yedek" value="1" /></td>
			</tr>
		</table>
		<input type="submit" value="Çekilişi Bitir" />
	</form>
	<?php
		if($_POST['islem'] == 'cekilisBitir')
		{
			$yedekVarMi = $_POST['yedek'] == 1 ? true : false;
			$kacKisi = intval($_POST['kazanacak']);
			cekilisBitir($kacKisi,$yedekVarMi);
		}
	?>
	
	<?php
		if(count($kazananlar) > 0)
		{
	?>
		<h2>Kazananlar</h2>
		<ul>
		<?php
			foreach($kazananlar as $kazanan)
			{
				echo '<li>'.$kazanan.'</li>';
			}
			foreach($yedekler as $yedek)
			{
				echo '<li>'.$yedek.' - Yedek</li>';
			}
		?>
		</ul>
		<form action="" method="post">
			<input type="hidden" name="islem" value="sifirla" />
			<input type="submit" value="Çekilişi Sıfırla" />
		</form>
	<?php cekilisSifirla(); } ?>
</body>
</html>
<?php

function cekilisBitir($kacKisi,$yedek=false)
{
	mysql_query("TRUNCATE TABLE `kazananlar`");

	$katilimcilar = array();
	$kazananlar   = array();
	$yedekler	  = array();
	
	$sorgu = mysql_query("SELECT * FROM katilimcilar");
	$katilimciSay = mysql_num_rows($sorgu);
	while($yaz = mysql_fetch_object($sorgu))
	{
		for($i=1;$i<=$yaz->hak;$i++)
		{
			$katilimcilar[] = $yaz->katilimci;
		}
	}
	if(count($katilimcilar) >= count($kacKisi))
	{
		shuffle($katilimcilar);
		for($i=1;$i<=$kacKisi; )
		{
			$rastgeleSec = array_rand($katilimcilar);
			$katilimci = $katilimcilar[$rastgeleSec];
			if(!in_array($katilimci,$kazananlar))
			{
				$kazananlar[] = $katilimci;
				$i++;
			}
		}
	}
	
	if($yedek && (($katilimciSay-$kacKisi) >= $kacKisi))
	{
		shuffle($katilimcilar);
		for($i=1;$i<=$kacKisi; )
		{
			$rastgeleSec = array_rand($katilimcilar);
			$katilimci = $katilimcilar[$rastgeleSec];
			if(!in_array($katilimci,$kazananlar) && !in_array($katilimci,$yedekler))
			{
				$yedekler[] = $katilimci;
				$i++;
			}
		}
	}
	
	foreach($kazananlar as $kazanan)
	{
		mysql_query("INSERT INTO kazananlar VALUES ('','$kazanan','0')");
	}
	
	foreach($yedekler as $yedek)
	{
		mysql_query("INSERT INTO kazananlar VALUES ('','$yedek','1')");
	}
	
	Header("Location:index.php");
}

function katilimciEkle()
{
	$katilimciAdi 	= preg_replace('#([^0-9a-zA-Z ])#','',$_POST['isim']);
	$hak 			= intval($_POST['hak']);
	if(!empty($katilimciAdi) && !empty($hak))
	{
		$islem = mysql_query("INSERT INTO katilimcilar (katilimci,hak) VALUES ('$katilimciAdi','$hak')");
		if($islem)
		{
			echo "Katılımcı başarıyla eklendi.";
		}
		else
		{
			echo "Eklemede sorun oluştu. Lütfen katılımcı adını kontrol ediniz.";
		}
	}
	else
	{
		echo "Hiç bir alan boş bırakılamaz.";
	}
}

function katilimciSil()
{
	if($_GET['sil'])
	{
		$id = (int) $_GET['sil'];
		if(!empty($id))
		{
			mysql_query("DELETE FROM katilimcilar WHERE id = '$id'");
		}
		Header("Location:index.php");
	}
}

function cekilisSifirla()
{
	if($_POST['islem'] == 'sifirla')
	{
		mysql_query("TRUNCATE TABLE `katilimcilar`");
		mysql_query("TRUNCATE TABLE `kazananlar`");
		
		Header("Location:index.php");
	}
}

ob_end_flush();
?>