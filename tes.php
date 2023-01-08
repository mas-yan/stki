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

    function df($dataTf)
    {
      $arrayKey  = [];
      foreach ($dataTf as $item) {
        foreach ($item['tf'] as $key => $value) {
          if (!in_array($key, $arrayKey)) {
            $arrayKey[] = $key;
          }
        }
      }

      return ddf($arrayKey, $dataTf);
    }

      
    function ddf($arrayKey, $dataTf)
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
          'document' => $value['document'],
          'idf' => $w
        ];
      }

      return result($result);
    }

    function result($data)
    {
      $kk = strtolower("Pengetahuan Logistik");
      $kk = explode(" ",$kk);

      $newDataTf = [];
      foreach ($data as $item) {
          $newItem = [
              'document' => $item['document'],
              'idf' => []
          ];
          $sum = 0;
          foreach ($item['idf'] as $key => $idf) {
              if (in_array($key, $kk)) {
                  $sum += $idf;
              }
          }
          $result[$item['document']] = $sum;
      }
      arsort($result);
      return $result;
    }
    include 'koneksi.php';
    header("Content-Type: application/json");
    print_r(df($dataTf));
    include 'koneksi.php';
    $document = '(' . implode(',', $d) .')';
      $query = mysqli_query($conn, "SELECT * FROM korpus where document IN $document") or die(mysqli_error($conn));
  
      if ($query->num_rows > 0) {
          while ($word = mysqli_fetch_assoc($query)) :?>
          <div class="container">
            <div class="list-group">
              <a href="#" class="list-group-item list-group-item-action" aria-current="true">
                <?= $word['isi'] ?>
                <?= print_r($d) ?>
              </a>
            </div>
          </div>
          <?php
          endwhile;
      }