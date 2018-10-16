public function hitungKpr(){
    $harga_properti = 595000000; //harga properti
    $dp = 20/100*$harga_properti; //dp 20%
    $harga_pokok_pinjaman = $harga_properti - $dp; //total pinjaman
    $tenor = 15; //jangka waktu
    $bunga_fixed = 9.5; //bunga fixed selama rentan waktu
    $period_bunga_fixed = 3 ; //rentan waktu untuk bunga fix
    $bunga_float = 12.25; //bunga float setelah batas rentan waktu bunga fix
    $angs_bunga = 0;
    $angs_pokok = 0;
    $total_angs = 0;
    $sisa_pinjaman = $harga_pokok_pinjaman;
    $total_bunga = 0;

    $installment = array();

    for ($i=0; $i <= $tenor*12; $i++) {

      if ($i == 0){
        $installment[$i]['bulan'] = $i;
        $installment[$i]['angsuran_bunga'] = $angs_bunga;
        $installment[$i]['angsuran_pokok'] = $angs_pokok;
        $installment[$i]['total_angsuran'] = $total_angs;
        $installment[$i]['sisa_pinjaman']  = $sisa_pinjaman;

      } else {

        if($i <= $period_bunga_fixed*12){

          $periodOnMonth = $tenor * 12;
          $interestMonth = ($bunga_fixed / 12) / 100;
          $divider = 1-(1/pow(1+$interestMonth,$periodOnMonth));
          $total_angs = round($harga_pokok_pinjaman / ($divider / $interestMonth));

          $angs_bunga = round($bunga_fixed/12/100*$sisa_pinjaman);
          $angs_pokok = $total_angs - $angs_bunga;
          $sisa_pinjaman = round($sisa_pinjaman - $angs_pokok);

          $sisa_pinjaman_fixrate = round($sisa_pinjaman);
          $sisa_pinjaman_bunga_float = round($sisa_pinjaman);

          $installment[$i]['bulan'] = $i;
          $installment[$i]['angsuran_bunga'] = $angs_bunga;
          $installment[$i]['angsuran_pokok'] = $angs_pokok;
          $installment[$i]['total_angsuran'] = $total_angs;
          $installment[$i]['sisa_pinjaman']  = $sisa_pinjaman;

          $angsuran_fixed = $total_angs;
          
        }

        if($i > $period_bunga_fixed*12){
          $periodOnMonth = ($tenor - $period_bunga_fixed)  * 12;
          $interestMonth = ($bunga_float / 12) / 100;
          $divider = 1-(1/pow(1+$interestMonth,$periodOnMonth));
          $angs_bunga = round($bunga_float/12/100*$sisa_pinjaman_bunga_float);
          $total_angs = round($sisa_pinjaman_fixrate / ($divider / $interestMonth));
          $angs_pokok = $total_angs - $angs_bunga;
          $sisa_pinjaman_bunga_float = round($sisa_pinjaman_bunga_float - $angs_pokok);

          $installment[$i]['bulan'] = $i;
          $installment[$i]['angsuran_bunga'] = $angs_bunga;
          $installment[$i]['angsuran_pokok'] = $angs_pokok;
          $installment[$i]['total_angsuran'] = $total_angs;
          $installment[$i]['sisa_pinjaman']  = $sisa_pinjaman_bunga_float;

          $angsuran_floated = $total_angs;

        }
        
      }

      $total_bunga = $total_bunga + $angs_bunga;

    }

    $data = array(
      "harga_properti"              => $harga_properti,
      "dp"                          => $dp,
      "pokok_pinjaman"              => $harga_pokok_pinjaman,
      "angsuran_perbulan_fixed"     => $angsuran_fixed,
      "angsuran_perbulan_floated"   => $angsuran_floated,
      "total_bunga"                 => $total_bunga,
      "installment"                 => $installment
    );
    
    return $data;

  }
