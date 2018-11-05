<?php session_start(); ?>
<?php require "../layouts/lay_adminmaintop.php"; ?>
<?php  
$_SESSION['page_title'] = "Carian Rekod Dokumen";
$_SESSION['addnew_form_title'] = "Borang Carian Mudah Rekod Dokumen";
$_SESSION['addnew_form_action'] = "Simpan Dokumen Baharu";
$_SESSION['update_form_title'] = "Borang Carian Lengkap Rekod Dokumen";
$_SESSION['update_form_action'] = "Kemaskini Dokumen Sedia Ada";
$_SESSION['ss_table_title'] = "Hasil Carian Mudah Rekod Dokumen";
$_SESSION['ss_table_action'] = "Senaraikan Dokumen";
$_SESSION['as_table_title'] = "Hasil Carian Lengkap Rekod Dokumen";
$_SESSION['as_table_action'] = "Senaraikan Dokumen";
$actionfilename = "searchdoc.php";
$table01name = "dokumen";
$field01name = "kod_data";
$field02name = "nama_data";

# when search page is opened from the menu
if (!isset($_GET['s']) == "n" OR isset($_GET['s']) == "n") {
	$_SESSION['search_form_opened'] = "advanced";
	fnClearSimpleDocSearchSessions();
	fnClearAdvancedDocSearchSessions();
	$_SESSION['status_papar_perincian_dokumen'] = 0;
}

# when user clicked 'Cari' in simple search form
if (isset($_POST['sbmt_cari_mudah'])) {
	$_SESSION['search_form_opened'] = "simple";
	$_SESSION['kata_kunci_mudah'] = $_POST['kata_kunci_mudah'];
	# start verifying form
	$_SESSION['verifiedOK'] = 3; // initial value
	if ($_SESSION['verifiedOK'] == 3) {
		# 1. user logged in?
		# semak jika ada loggedinid; user yang sah telah log masuk
		if ($_SESSION['loggedinid'] != 0) {
			$_SESSION['verifiedOK'] = 1;
		}
		else {
			$_SESSION['verifiedOK'] = 0;
			fnRunAlert("Maaf, borang tidak dapat diproses kerana pengguna tidak log masuk dengan sah.");
		}
	    # semak katakunci diisi
		if ($_SESSION['verifiedOK'] == 1) {
			if ($_SESSION['kata_kunci_mudah'] != "") {
				$_SESSION['verifiedOK'] = 1;
			}
			else {
				$_SESSION['verifiedOK'] = 0;
				fnRunAlert("Sila masukkan kata kunci carian.");
			}
		}
		if ($_SESSION['verifiedOK'] == 1) {
			fnSearchDocSimple();
		}
	}
}
# when user clicked 'Cari' in advanced search form
if (isset($_POST['sbmt_cari_lengkap'])) {
	$_SESSION['search_form_opened'] = "advanced"; # beri nilai
	// fnRunAlert("Carian lengkap dimulakan...");
	# kategori, tahun, tajuk, kementerian, agensi, sektor, bahagian, status
	fnClearAdvancedDocSearchSessions();
	$_SESSION['cl_tajuk_dokumen'] = $_POST['cl_tajuk_dokumen']; # beri nilai drp borang
	$_SESSION['cl_tahun_dokumen'] = $_POST['cl_tahun_dokumen']; # beri nilai drp borang
	$_SESSION['kod_kat'] = $_POST['kod_kat']; # beri nilai drp borang
	$_SESSION['kod_sektor'] = $_POST['kod_sektor']; # beri nilai drp borang
	$_SESSION['kod_bah'] = $_POST['kod_bah']; # beri nilai drp borang
	$_SESSION['kod_status'] = $_POST['kod_status']; # beri nilai drp borang
	$_SESSION['cl_tajuk_dokumen_to_show_below_view'] = $_POST['cl_tajuk_dokumen']; # beri nilai drp borang
	$_SESSION['cl_tahun_dokumen_to_show_below_view'] = $_POST['cl_tahun_dokumen']; # beri nilai drp borang
	$_SESSION['kod_kat_to_show_below_view'] = $_POST['kod_kat']; # beri nilai drp borang
	$_SESSION['kod_sektor_to_show_below_view'] = $_POST['kod_sektor']; # beri nilai drp borang
	$_SESSION['kod_bah_to_show_below_view'] = $_POST['kod_bah']; # beri nilai drp borang
	$_SESSION['kod_status_to_show_below_view'] = $_POST['kod_status']; # beri nilai drp borang
	
	// fnRunAlert("kod_kat=$_SESSION[kod_kat]");
	// fnRunAlert("tajuk=$_SESSION[cl_tajuk_dokumen]");
	// fnRunAlert("tahun=$_SESSION[cl_tahun_dokumen]");
	// fnRunAlert("kod_sektor=$_SESSION[kod_sektor]");
	// fnRunAlert("kod_bah=$_SESSION[kod_bah]");
	// fnRunAlert("kod_status=$_SESSION[kod_status]");
	# 63 kombinasi carian
	# set kepada 0
	// $_SESSION['kombinasi_cl_dok'] = 0;
	## kombinasi 01 - 000001
	if ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 1;
		// fnRunAlert("kombinasi yg sesuai 1");
	}
	## kombinasi 02 - 000010
	elseif ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 2;
		// fnRunAlert("kombinasi yg sesuai 2");
	}
	## kombinasi 03 - 000011
	elseif ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 3;
		// fnRunAlert("kombinasi yg sesuai 3");
	}
	## kombinasi 04 - 000100
	elseif ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 4;
		// fnRunAlert("kombinasi yg sesuai 4");
	}
	## kombinasi 05 - 000101
	elseif ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 5;
		// fnRunAlert("kombinasi yg sesuai 5");
	}
	## kombinasi 06 - 000110
	elseif ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 6;
		// fnRunAlert("kombinasi yg sesuai 6");
	}
	## kombinasi 07 - 000111
	elseif ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 7;
		// fnRunAlert("kombinasi yg sesuai 7");
	}
	## kombinasi 08 - 001000
	elseif ($_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 8;
		// fnRunAlert("kombinasi yg sesuai 8");
	}
	## kombinasi 09 - 001001
	elseif ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 9;
		// fnRunAlert("kombinasi yg sesuai 9");
	}
	## kombinasi 10 - 001010
	elseif ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 10;
		// fnRunAlert("kombinasi yg sesuai 10");
	}
	## kombinasi 11 - 001011
	elseif ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 11;
		// fnRunAlert("kombinasi yg sesuai 11");
	}
	## kombinasi 12 - 001100
	elseif ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 12;
		// fnRunAlert("kombinasi yg sesuai 12");
	}
	## kombinasi 13 - 001101
	elseif ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 13;
		// fnRunAlert("kombinasi yg sesuai 13");
	}
	## kombinasi 14 - 001110
	elseif ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 14;
		// fnRunAlert("kombinasi yg sesuai 14");
	}
	## kombinasi 15 - 001111
	elseif ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 15;
		// fnRunAlert("kombinasi yg sesuai 15");
	}
	## kombinasi 16 - 010000
	elseif ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 16;
		// fnRunAlert("kombinasi yg sesuai 16");
	}
	## kombinasi 17 - 010001
	elseif ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 17;
		// fnRunAlert("kombinasi yg sesuai 17");
	}
	## kombinasi 18 - 010010
	elseif ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 18;
		// fnRunAlert("kombinasi yg sesuai 18");
	}
	## kombinasi 19 - 010011
	elseif ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 19;
		// fnRunAlert("kombinasi yg sesuai 19");
	}
	## kombinasi 20 - 010100
	elseif ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 20;
		// fnRunAlert("kombinasi yg sesuai 20");
	}
	## kombinasi 21 - 010101
	elseif ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 21;
		// fnRunAlert("kombinasi yg sesuai 21");
	}
	## kombinasi 22 - 010110
	elseif ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 22;
		// fnRunAlert("kombinasi yg sesuai 22");
	}
	## kombinasi 30 - 010111
	elseif ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 23;
		// fnRunAlert("kombinasi yg sesuai 23");
	}
	## kombinasi 20 - 011000
	elseif ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 24;
		// fnRunAlert("kombinasi yg sesuai 24");
	}
	## kombinasi 30 - 011001
	elseif ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 25;
		// fnRunAlert("kombinasi yg sesuai 25");
	}
	## kombinasi 30 - 011010
	elseif ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 26;
		// fnRunAlert("kombinasi yg sesuai 26");
	}
	## kombinasi 30 - 011011
	elseif ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 27;
		// fnRunAlert("kombinasi yg sesuai 27");
	}
	## kombinasi 30 - 011100
	elseif ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 28;
		// fnRunAlert("kombinasi yg sesuai 28");
	}
	## kombinasi 30 - 011101
	elseif ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 29;
		// fnRunAlert("kombinasi yg sesuai 29");
	}
	## kombinasi 30 - 011110
	elseif ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 30;
		// fnRunAlert("kombinasi yg sesuai 30");
	}
	## kombinasi 04 - 011111
	elseif ($_SESSION['cl_tajuk_dokumen'] == "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 31;
		// fnRunAlert("kombinasi yg sesuai 31");
	}
	## kombinasi 08 - 100000
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 32;
		// fnRunAlert("kombinasi yg sesuai 32");
	}
	## kombinasi 19 - 100001
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 33;
		// fnRunAlert("kombinasi yg sesuai 33");
	}
	## kombinasi 18 - 100010
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 34;
		// fnRunAlert("kombinasi yg sesuai 34");
	}
	## kombinasi 30 - 100011
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 35;
		// fnRunAlert("kombinasi yg sesuai 35");
	}
	## kombinasi 17 - 100100
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 36;
		// fnRunAlert("kombinasi yg sesuai 36");
	}
	## kombinasi 30 - 100101
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 37;
		// fnRunAlert("kombinasi yg sesuai 37");
	}
	## kombinasi 30 - 100110
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 38;
		// fnRunAlert("kombinasi yg sesuai 38");
	}
	## kombinasi 30 - 100111
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 39;
		// fnRunAlert("kombinasi yg sesuai 39");
	}
	## kombinasi 16 - 101000
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 40;
		// fnRunAlert("kombinasi yg sesuai 40");
	}
	## kombinasi 30 - 101001
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 41;
		// fnRunAlert("kombinasi yg sesuai 41");
	}
	## kombinasi 30 - 101010
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 42;
		// fnRunAlert("kombinasi yg sesuai 42");
	}
	## kombinasi 30 - 101011
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 43;
		// fnRunAlert("kombinasi yg sesuai 43");
	}
	## kombinasi 30 - 101100
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 44;
		// fnRunAlert("kombinasi yg sesuai 44");
	}
	## kombinasi 30 - 101101
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 45;
		// fnRunAlert("kombinasi yg sesuai 45");
	}
	## kombinasi 30 - 101110
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 46;
		// fnRunAlert("kombinasi yg sesuai 46");
	}
	## kombinasi 03 - 101111
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] == "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 47;
		// fnRunAlert("kombinasi yg sesuai 47");
	}
	## kombinasi 15 - 110000
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 48;
		// fnRunAlert("kombinasi yg sesuai 48");
	}
	## kombinasi 30 - 110001
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 49;
		// fnRunAlert("kombinasi yg sesuai 49");
	}
	## kombinasi 30 - 110010
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 50;
		// fnRunAlert("kombinasi yg sesuai 50");
	}
	## kombinasi 30 - 110011
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 51;
		// fnRunAlert("kombinasi yg sesuai 51");
	}
	## kombinasi 30 - 110100
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 52;
		// fnRunAlert("kombinasi yg sesuai 52");
	}
	## kombinasi 30 - 110101
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 53;
		// fnRunAlert("kombinasi yg sesuai 53");
	}
	## kombinasi 30 - 110110
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 54;
		// fnRunAlert("kombinasi yg sesuai 54");
	}
	## kombinasi 02 - 110111
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] == 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 55;
		// fnRunAlert("kombinasi yg sesuai 55");
	}
	## kombinasi 30 - 111000
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 56;
		// fnRunAlert("kombinasi yg sesuai 56");
	}
	## kombinasi 30 - 111001
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 57;
		// fnRunAlert("kombinasi yg sesuai 57");
	}
	## kombinasi 30 - 111010
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 58;
		// fnRunAlert("kombinasi yg sesuai 58");
	}
	## kombinasi 05 - 111011
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] == 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 59;
		// fnRunAlert("kombinasi yg sesuai 59");
	}
	## kombinasi 30 - 111100
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 60;
		// fnRunAlert("kombinasi yg sesuai 60");
	}
	## kombinasi 06 - 111101
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] == 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 61;
		// fnRunAlert("kombinasi yg sesuai 61");
	}
	## kombinasi 07 - 111110
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] == 1) {
		$_SESSION['kombinasi_cl_dok'] = 62;
		// fnRunAlert("kombinasi yg sesuai 62");
	}
	## kombinasi 01 - 111111
	elseif ($_SESSION['cl_tajuk_dokumen'] != "" AND $_SESSION['cl_tahun_dokumen'] != "" AND $_SESSION['kod_kat'] != 1 AND $_SESSION['kod_sektor'] != 1 AND $_SESSION['kod_bah'] != 1 AND $_SESSION['kod_status'] != 1) {
		$_SESSION['kombinasi_cl_dok'] = 63;
		// fnRunAlert("kombinasi yg sesuai 63");
	}
	else {
		$_SESSION['kombinasi_cl_dok'] = 0;
		// fnRunAlert("kombinasi yg sesuai 0");
	}
	# start checking form
	$_SESSION['verifiedOK'] = 3; # beri nilai awal
	if ($_SESSION['verifiedOK'] == 3) {
		# 1. user logged in?
		# semak jika ada loggedinid; user yang sah telah log masuk
		$_SESSION['field_filled_counter'] = 0;
		if ($_SESSION['loggedinid'] != 0) {
			$_SESSION['verifiedOK'] = 1;
		}
		else {
			$_SESSION['verifiedOK'] = 0;
			fnRunAlert("Maaf, borang tidak dapat diproses kerana pengguna tidak log masuk dengan sah.");
		}
	    # semak katakunci diisi
		if ($_SESSION['verifiedOK'] == 1) {
			if ($_SESSION['cl_tahun_dokumen'] != "") {
				$_SESSION['field_filled_counter'] += 1;
			}
			if ($_SESSION['cl_tajuk_dokumen'] != "") {
				$_SESSION['field_filled_counter'] += 1;
			}
			if ($_SESSION['kod_kat'] != 1) {
				$_SESSION['field_filled_counter'] += 1;
			}
			if ($_SESSION['kod_sektor'] != 1) {
				$_SESSION['field_filled_counter'] += 1;
			}
			if ($_SESSION['kod_bah'] != 1) {
				$_SESSION['field_filled_counter'] += 1;
			}
			if ($_SESSION['kod_status'] != 1) {
				$_SESSION['field_filled_counter'] += 1;
			}
		}
		if ($_SESSION['field_filled_counter'] > 0) {
			// fnRunAlert("field filled=$_SESSION[field_filled_counter]");
			fnSearchDocAdvanced();
			fnClearAdvancedDocSearchSessions();
            // fnRunAlert("$_SESSION[bil_dok_carian_lengkap] bil hasil carian lengkap selepas function.php");
		}
		else {
			fnRunAlert("Sila isi medan carian atau pilih salah satu kriteria rekod.");
		}
	}
}

# When a user clicks the 'show doc detail'
if (isset($_POST['btn_papar_perincian_dokumen'])) {
	// fnRunAlert("$_POST[btn_papar_perincian_dokumen]");
	// $_SESSION['updateDocOK'] = 0;
	// $_SESSION['status_papar_perincian_dokumen'] = 0;
	$_SESSION['status_papar_perincian_dokumen'] = 1;
	// $_SESSION['status_buka_borang_kemaskini_dokumen'] = "";
	// $_SESSION['status_buka_borang_kemaskini_dokumen'] = 0;
	// $_SESSION['kod_dok_untuk_dikemaskini'] = $_POST['btn_papar_perincian_dokumen'];
	$_SESSION['kod_dok_untuk_dipapar'] = $_POST['btn_papar_perincian_dokumen'];
}

if ($_SESSION['search_form_opened'] == "simple") {
	$_SESSION['simple_form_search_status'] = "";
	$_SESSION['advanced_form_search_status'] = "hidden";
	$_SESSION['select_menu_none'] = "";
	$_SESSION['select_menu_simple'] = "selected";
	$_SESSION['select_menu_advanced'] = "";
}
elseif ($_SESSION['search_form_opened'] == "advanced") {
	$_SESSION['simple_form_search_status'] = "hidden";
	$_SESSION['advanced_form_search_status'] = "";
	$_SESSION['select_menu_none'] = "";
	$_SESSION['select_menu_simple'] = "";
	$_SESSION['select_menu_advanced'] = "selected";
}
else {
	$_SESSION['simple_form_search_status'] = "hidden";
	$_SESSION['advanced_form_search_status'] = "hidden";
	$_SESSION['select_menu_none'] = "selected";
	$_SESSION['select_menu_simple'] = "";
	$_SESSION['select_menu_advanced'] = "";
}
?>
<!-- page content -->
<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3><?php echo $_SESSION['page_title']; ?></h3>
			</div>
			<!-- <div class="title_right"> -->
			<!-- <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search"> -->
			<!-- <div class="input-group"> -->
			<!-- <input type="text" class="form-control" placeholder="Search for..."> -->
			<!-- <span class="input-group-btn"> -->
			<!-- <button class="btn btn-default" type="button">Go!</button> -->
			<!-- </span> -->
			<!-- </div> -->
			<!-- </div> -->
			<!-- </div> -->
		</div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<select class="form-control selectpicker" id="kod_pilihan_carian" name="kod_pilihan_carian" required="required">
						<option value="1" <?php echo $_SESSION['select_menu_none']; ?>>Sila pilih...</option>
						<option value="2" <?php echo $_SESSION['select_menu_simple']; ?>>Carian Mudah</option>
						<option value="3" <?php echo $_SESSION['select_menu_advanced']; ?>>Carian Lengkap</option>
					</select>
				</div>
			</div>
		</div>
		<?php  
		# mesej 'tiada pilihan dibuat'
		if ($_SESSION['search_form_opened'] == "none") {
			?>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12" id="divmsgpilihan">
					<div class="x_panel">
						<h2>Sila pilih jenis carian untuk meneruskan operasi.</h2>
					</div>
				</div>
			</div>
			<?php
		}
		?>
		<!-- paparan session penting -->
		<div class="row"><!-- div to display the value for each of the selected sessions -->
			<div class="col-md-12 col-sm-12 col-xs-12" hidden>
				<div class="x_panel">
					<table>
						<caption>SESSIONS</caption>
						<thead>
							<tr>
								<th width="50">sfo</th>
								<th width="50">sfss</th>
								<th width="50">afss</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php echo $_SESSION['search_form_opened']; ?></td>
								<td><?php echo $_SESSION['simple_form_search_status']; ?></td>
								<td><?php echo $_SESSION['advanced_form_search_status']; ?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<!-- borang carian mudah -->
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12" id="divsimplesearch" <?php echo $_SESSION['simple_form_search_status']; ?>>
				<div class="x_panel">
					<div class="x_title">
						<h2><?php echo $_SESSION['addnew_form_title']; ?><small><?php echo $_SESSION['addnew_form_action']; ?></small></h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<form id="form-data-baharu" action="<?php echo $actionfilename; ?>" method="POST" data-parsley-validate class="form-horizontal form-label-left">
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" for="kata_kunci_mudah">Kata Kunci Carian <span class="required">*</span>
								</label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" id="kata_kunci_mudah" name="kata_kunci_mudah" required="required" autofocus class="form-control col-md-7 col-xs-12">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
									<input type="submit" id="sbmt_cari_mudah" name="sbmt_cari_mudah" class="btn btn-success" value="Cari">
									<button type="reset" class="btn btn-danger">Kosongkan</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<!-- borang carian lengkap -->
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12" id="divadvancedsearch" <?php echo $_SESSION['advanced_form_search_status']; ?>>
				<div class="x_panel">
					<div class="x_title">
						<h2><?php echo $_SESSION['update_form_title']; ?><small><?php echo $_SESSION['update_form_action']; ?></small></h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<form id="form-data-baharu" action="<?php echo $actionfilename; ?>" method="POST" data-parsley-validate class="form-horizontal form-label-left">
							<!-- kategori, tahun, tajuk, kementerian, agensi, sektor, bahagian, status -->
			                <?php  
			                ?>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cl_tajuk_dokumen">Tajuk Dokumen <span class="required">*</span>
								</label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="text" id="cl_tajuk_dokumen" name="cl_tajuk_dokumen" class="form-control col-md-7 col-xs-12" maxlength="150">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cl_tahun_dokumen">Tahun Dokumen <span class="required">*</span>
								</label>
								<div class="col-md-6 col-sm-6 col-xs-12">
								<input value="" type="text" id="cl_tahun_dokumen" name="cl_tahun_dokumen" class="form-control col-md-7 col-xs-12" maxlength="4" pattern="\d{1,4}">
								</div>
							</div>
							<?php  
			                fnDropdownKategori($DBServer,$DBUser,$DBPass,$DBName); 
			                // fnDropdownKem($DBServer,$DBUser,$DBPass,$DBName);
			                // fnDropdownJab($DBServer,$DBUser,$DBPass,$DBName,'kod_jab');
			                fnDropdownSektor($DBServer,$DBUser,$DBPass,$DBName); 
			                fnDropdownBahagian($DBServer,$DBUser,$DBPass,$DBName); 
			                fnDropdownStatusDok($DBServer,$DBUser,$DBPass,$DBName);
							?>
							<div class="form-group">
								<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
									<input type="submit" id="sbmt_cari_lengkap" name="sbmt_cari_lengkap" class="btn btn-success" value="Cari">
									<button type="reset" class="btn btn-danger">Kosongkan</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<?php  
		# mesej hasil carian lengkap
		// if ((isset($_SESSION['bil_dok_carian_mudah']) AND $_SESSION['bil_dok_carian_mudah'] == "z") OR (isset($_SESSION['bil_dok_carian_lengkap']) AND $_SESSION['bil_dok_carian_lengkap'] == "z")) {
			?>
            <!-- <br>&nbsp;<br>&nbsp; -->
            <?php
		// }
		if (isset($_SESSION['search_form_opened']) AND $_SESSION['search_form_opened'] == "simple") {
			if (!isset($_SESSION['bil_dok_carian_mudah'])) {
				$_SESSION['bil_dok_carian_mudah'] = "z";
			}
			// fnRunAlert("Form yang dibuka $_SESSION[search_form_opened]");
			// fnRunAlert("$_SESSION[bil_dok_carian_mudah] carian mudah");
			if ($_SESSION['bil_dok_carian_mudah'] >= 0 AND $_SESSION['bil_dok_carian_mudah'] != "z") {
				// fnRunAlert("Ada bil hasil carian mudah.");
				?>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12" id="divmsgbilcarianmudah" >
						<div class="x_panel">
							<h4><?php echo $_SESSION['bil_dok_carian_mudah']; ?> rekod "<?php echo $_SESSION['kata_kunci_mudah']; ?>" rekod carian mudah telah ditemui.</h2>
						</div>
						<?php  
						// unset($_SESSION['bil_dok_carian_mudah']);
						?>
					</div>
				</div>
				<?php
			}
			else {

			}
		}
		if (isset($_SESSION['search_form_opened']) == "advanced") {
			if (!isset($_SESSION['bil_dok_carian_lengkap'])) {
				$_SESSION['bil_dok_carian_lengkap'] = "z";
			}
			// fnRunAlert("Form yang dibuka $_SESSION[search_form_opened]");
			// fnRunAlert("$_SESSION[bil_dok_carian_lengkap] carian lengkap");
			if (isset($_SESSION['bil_dok_carian_lengkap']) AND $_SESSION['bil_dok_carian_lengkap'] >= 0 AND $_SESSION['bil_dok_carian_lengkap'] != "z") {
				// fnRunAlert("paparkan mesej hasil carian lengkap");
				?>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12" id="divmsgbilcarianlengkap" > 
						<div class="x_panel">
							<h4><?php echo $_SESSION['bil_dok_carian_lengkap']; ?> rekod carian lengkap telah ditemui.</h2>
						</div>
						<?php  
						// $_SESSION['bil_dok_carian_lengkap'] = -1; // tak tahu kenapa ini ada di sini
						// unset($_SESSION['bil_dok_carian_lengkap']); // tak tahu kenapa ini ada di sini
						?>
					</div>
				</div>
				<?php
			}
		}
		# buffer
		if ((isset($_SESSION['bil_dok_carian_lengkap']) < 0 AND $_SESSION['search_form_opened'] == "advanced") OR (isset($_SESSION['bil_dok_carian_mudah']) < 0 AND $_SESSION['search_form_opened'] == "simple")) {
			?>
            <br>&nbsp;<br>&nbsp;
            <?php
		}
		?>
		<div class="clearfix"></div>
		<?php  
		// if (!isset($_SESSION['bil_dok_carian_mudah']) > 0 AND $_SESSION['search_form_opened'] == "simple") {
		if (isset($_SESSION['bil_dok_carian_mudah']) AND $_SESSION['bil_dok_carian_mudah'] >= 0 AND $_SESSION['bil_dok_carian_mudah'] != "z" AND $_SESSION['search_form_opened'] == "simple") {
			# paparkan jadual rekod hasil daripada carian mudah
			?>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12" id="divhasilcarianmudah" >
					<div class="x_panel">
						<div class="x_title">
							<h2><?php echo $_SESSION['ss_table_title']; ?> <small><?php echo $_SESSION['ss_table_action']; ?></small></h2>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<p class="text-muted font-13 m-b-30">
								<!-- The Buttons extension for DataTables provides a common set of options, API methods and styling to display buttons on a page that will interact with a DataTable. The core library provides the based framework upon which plug-ins can built. -->
							</p>
							<form id="form-jadual-data" action="<?php echo $actionfilename; ?>" method="POST" data-parsley-validate class="form-horizontal form-label-left">
								<div class="title_right">
								</div>
								<table id="myTable" class="table table-striped table-bordered">
									<thead>
										<tr>
											<th width="40">Bil</th>
											<th width="100" hidden>Kod</th>
											<th>Tajuk</th>
											<th width="115" hidden>Tindakan</th>
										</tr>
									</thead>


									<tbody id="myTableBody">
										<?php 
						                // fnShowDocTableContent($DBServer,$DBUser,$DBPass,$DBName,$table01name,$field01name,$field02name); 
										fnShowDocTableContentForSimpleSearch($DBServer,$DBUser,$DBPass,$DBName,$table01name,$field01name,$field02name); 
										?>
									</tbody>
								</table>
					            <div class="col-md-12 text-center">
					              <ul class="pagination pagination-lg pager" id="myPager"></ul>
					            </div>
							</form>
						</div>
					</div>
				</div>
			</div>
            <br>&nbsp;<br>&nbsp;<br>&nbsp;
			<?php
		}
		?>
		<div class="clearfix"></div>
		<?php  
		// untuk ujian sahaja
		// $_SESSION['bil_dok_carian_lengkap'] = 2;
		// $_SESSION['search_form_opened'] = "advanced";
		if (isset($_SESSION['bil_dok_carian_lengkap'])) {
					# code...
			// fnRunAlert("$_SESSION[bil_dok_carian_lengkap] hasil carian lengkap");
		}		
		else {
			// fnRunAlert("Tiada nilai bagi bil hasil carian lengkap.");
		}
		// fnRunAlert("Borang $_SESSION[search_form_opened] dibuka");
	    # papar perincian rekod untuk lihat sahaja
	    if ($_SESSION['status_papar_perincian_dokumen'] == 1) {
	      $_SESSION['status_papar_perincian_dokumen'] = 0;
	      $kod_dok_untuk_dipapar = $_SESSION['kod_dok_untuk_dipapar'];
	      ?>
	      <div class="row">
	        <div class="col-md-12 col-sm-12 col-xs-12">
	          <div class="x_panel">
	            <div class="x_title">
	              <h2><?php echo $_SESSION['preview_doc_title']; ?><small><?php echo $_SESSION['preview_doc_action']; ?></small></h2>
	              <div class="clearfix"></div>
	            </div>
	            <div class="x_content">
	              <br />
	              <form id="form-kemaskini-data" action="<?php echo $actionfilename; ?>" enctype="multipart/form-data" method="POST" data-parsley-validate class="form-horizontal form-label-left">
	                <?php 
	                fnClearTerasDokSessionForUpdateForm();
	                fnShowViewDocContent($DBServer,$DBUser,$DBPass,$DBName,"dokumen","kod_dok","tajuk_dok"); 
	                ?>
	                <div class="form-group">
	                  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3" align="center">
	                    <input type="submit" id="btn_papar_borang_kemaskini_dari_perincian_dokumen" name="btn_papar_borang_kemaskini_dari_perincian_dokumen" class="btn btn-success" title="Buka Borang Kemaskini Dokumen" value="Buka Borang Kemaskini" hidden>
	                    <input type="submit" id="btn_tutup_perincian_dokumen" name="btn_tutup_perincian_dokumen" class="btn btn-danger" title="Tutup" value="Tutup">
	                  </div>
	                </div>
	              </form>
	            </div>
	          </div>
	        </div>
	      </div>
            <!-- <br>&nbsp;<br>&nbsp;<br>&nbsp; -->
	      <?php
	      fnSearchDocAdvancedRepeat();
	    }

		# paparan senarai dokumen hasil carian lengkap
		if (isset($_SESSION['bil_dok_carian_lengkap']) AND $_SESSION['bil_dok_carian_lengkap'] >= 0 AND $_SESSION['bil_dok_carian_lengkap'] != "z" AND $_SESSION['search_form_opened'] == "advanced") {
			# paparkan jadual rekod hasil daripada carian lengkap
			// fnRunAlert("paparkan hasil carian lengkap");
			?>
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12" id="divhasilcarianlengkap" >
					<div class="x_panel">
						<div class="x_title">
							<h2><?php echo $_SESSION['as_table_title']; ?> <small><?php echo $_SESSION['as_table_action']; ?></small></h2>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<p class="text-muted font-13 m-b-30">
								<!-- The Buttons extension for DataTables provides a common set of options, API methods and styling to display buttons on a page that will interact with a DataTable. The core library provides the based framework upon which plug-ins can built. -->
							</p>
							<form id="form-jadual-data" action="<?php echo $actionfilename; ?>" method="POST" data-parsley-validate class="form-horizontal form-label-left">
								<div class="title_right">
								</div>
								<table id="myTable" class="table table-striped table-bordered">
									<thead>
										<tr>
											<th width="40">Bil</th>
											<th width="100" hidden>Kod</th>
											<th>Tajuk</th>
											<th width="115">Tindakan</th>
										</tr>
									</thead>


									<tbody id="myTableBody">
										<?php 
										fnShowDocTableContentForAdvancedSearch($DBServer,$DBUser,$DBPass,$DBName); 
										?>
									</tbody>
								</table>
					            <div class="col-md-12 text-center">
					              <ul class="pagination pagination-lg pager" id="myPager"></ul>
					            </div>
							</form>
						</div>
					</div>
				</div>
			</div>
            <br>&nbsp;<br>&nbsp;<br>&nbsp;
			<?php
			fnClearAdvancedDocSearchResult();

		}
		?>
		<!-- /page content -->
	    <script src="../vendors/jquery/dist/jquery.min.js"></script>
	    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
	    <script src="../engine/bootstrap.tablesorter.js"></script>
	    <script>
	    	$(document).ready(function(){
	    		$('#kod_pilihan_carian').on('change', function () {
	    			switch ($(this).val()) {
	    				case '1':
	    				$('#divmsgpilihan').prop('hidden', false);
	    				$('#divsimplesearch').prop('hidden', true);
	    				$('#divadvancedsearch').prop('hidden', true);
	    				$('#divmsgbilcarianmudah').prop('hidden', true);
	    				$('#divmsgbilcarianlengkap').prop('hidden', true);
	    				$('#divhasilcarianmudah').prop('hidden', true);
	    				$('#divhasilcarianlengkap').prop('hidden', true);
	    				<?php 
	    				$_SESSION['search_form_opened'] = "none"; 
						fnClearSimpleDocSearchSessions();
						fnClearAdvancedDocSearchSessions();
						// fnRefreshPgMeta();
	    				?>
	    				// $('.selectpicker').selectpicker('refresh');
	    				break;
	    				case '2':
	    				$('#divmsgpilihan').prop('hidden', true);
	    				$('#divsimplesearch').prop('hidden', false);
	    				$('#divadvancedsearch').prop('hidden', true);
	    				$('#divmsgbilcarianmudah').prop('hidden', false);
	    				$('#divmsgbilcarianlengkap').prop('hidden', true);
	    				$('#divhasilcarianmudah').prop('hidden', false);
	    				$('#divhasilcarianlengkap').prop('hidden', true);
	    				<?php 
	    				$_SESSION['search_form_opened'] = "simple"; 
						fnClearAdvancedDocSearchSessions();
						// include ("searchdoc_simple.php");
						// fnRefreshPgMeta();
	    				?>
	    				// $('.selectpicker').selectpicker('refresh');
	    				break;
	    				case '3':
	    				$('#divmsgpilihan').prop('hidden', true);
	    				$('#divsimplesearch').prop('hidden', true);
	    				$('#divadvancedsearch').prop('hidden', false);
	    				$('#divmsgbilcarianmudah').prop('hidden', true);
	    				$('#divmsgbilcarianlengkap').prop('hidden', false);
	    				$('#divhasilcarianmudah').prop('hidden', true);
	    				$('#divhasilcarianlengkap').prop('hidden', false);
	    				<?php 
	    				$_SESSION['search_form_opened'] = "advanced"; 
						fnClearSimpleDocSearchSessions();
						// include ("searchdoc_advanced.php");
						// fnRefreshPgMeta();
	    				?>
	    				// $('.selectpicker').selectpicker('refresh');
	    				break;
	    			}
	    		}); 
	    	});
			$('#myTableBody').pageMe({pagerSelector:'#myPager',showPrevNext:true,hidePageNumbers:false,perPage:4});
	    </script>
		<?php require "../layouts/lay_adminmainbottom.php"; ?>