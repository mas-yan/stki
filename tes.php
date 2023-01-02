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

        $p = [];
        foreach ($dataTf as $key => $value) {
          $w = [];
          foreach ($value['tf'] as $key => $tf) {
            $w[$key] = $idf[$key] * $tf;
          }
          $p[] = [
            'Document' => $value['document'],
            'idf' => $w
          ];
        }

        return $p;
      }
      
      header("Content-Type: application/json");
      echo json_encode(tf($dataTf));