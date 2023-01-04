<?php 
    $dataTf = [
      [
        "document" => "d1",
        "tf" => [
          "logistik" => 1,
          "manajemen" => 1,
          "transaksi" => 1
        ]
      ],
      [
        "document" => "d2",
        "tf" => [
          "individu" => 1,
          "pengetahuan" => 1
        ]
      ],
      [
        "document" => "d3",
        "tf" => [
          "logistik" => 1,
          "manajemen" => 1,
          "transfer" => 1,
          "pengetahuan" => 2
        ]
      ],
    ];

    function tf($dataTf)
    {
      $arrayKey  = [];
      foreach ($dataTf as $item) {
        foreach ($item['tf'] as $key => $value) {
          if (!in_array($key, $arrayKey)) {
            $arrayKey[] = $key;
          }
        }
      }

      return df($arrayKey, $dataTf);
    }

      
    function df($arrayKey, $dataTf)
    {
      $df = [];
      foreach ($arrayKey as $key) {
        foreach ($dataTf as $item) {
          foreach ($item['tf'] as $keyTf => $value) {
            if ($key == $keyTf) {
              $df[] = $key;
            }
          }
        }
      }

      $result = array_count_values($df);

      $resDf = [];
      foreach ($result as $key => $val) {
        $resDf[$key] = count($dataTf) / $val;
      }

      return idf($resDf,$dataTf);
    }
      
    function idf($resDf, $dataTf)
    {
      $idf = [];
      foreach ($resDf as $key => $resVal) {
        $idf[$key] = log10($resVal);
      }

      $result = [];
      foreach ($dataTf as $key => $value) {
        $w = [];
        foreach ($value['tf'] as $key => $tf) {
          $w[$key] = $idf[$key] * $tf;
        }
        $result[] = [
          'Document' => $value['document'],
          'idf' => $w
        ];
      }

      return json_encode($result);
    }
    header("Content-Type: application/json");
    echo tf($dataTf);


    // $words = ["Menko polhukam Mahfud md menyatakan pengelolaan otonomi khusus (otsus) papua tidak beres. karenanya, dana otsus dinaikkan menjadi 2,25 persen dari dana alokasi khusus apbn", "Piala Dunia 2022 telah memasuki fase semifinal. Seluruh pertandingan digelar tengah pekan ini. Berikut jadwal selengkapnya! Empat tim sudah memastikan tiket ke semifinal Piala Dunia 2022. Keempatnya antara lain Kroasia, Argentina, Maroko, dan Prancis.Kroasia lolos usai menumbangkan tim unggulan Brasil lewat drama adu penalti. Proses serupa turut dilalui Argentina yang mendepak Belanda di perempatfinal"];