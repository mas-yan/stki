<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <title>Document</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a class="nav-link active" aria-current="page" href="/">Home</a>
        <a class="nav-link" href="tfidf.php">Features</a>
        <a class="nav-link" href="#">Pricing</a>
        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
      </div>
    </div>
  </div>
</nav>
<div class="container my-5">
  <form action="" method="post">
    <div class="mb-3">
      <label for="cari">Cari Kata Kunci</label>
      <input type="text" class="form-control" name="cari" placeholder="Cari Kata Kunci" id="cari">
    </div>
    <button class="btn btn-primary" name="submit">Cari</button>
  </form>
</div>
<?php 

function tokenisasi()
{
    include 'koneksi.php';
    $query = mysqli_query($conn, "SELECT * FROM korpus");
    
    if ($query->num_rows > 0) {
        while ($word = mysqli_fetch_assoc($query)) {
            
            // menghilangkan angka dan tanda baca
            $data = preg_replace('/[^a-z]+/i', ' ', $data = preg_replace('/[0-9]+/', '', strtolower($word['isi'])));
            
            // jadikan array
            $data = explode(" ", $data);
            $results[] = [
                'document' => $word['document'],
                'tf' => $data
            ];
        }
        return filtering($results);
    }
}

function filtering($data)
{
    include 'stopword.php';

    foreach ($data as $value) {
        $item = array_diff($value['tf'], $stopword);
        $results[] = [
            'document' => $value['document'],
            'tf' => array_values($item)
        ];
    }
    // return $results;
    return stemming($results);
}

function stemming($data)
{
    // menggunakan library sastrawi untuk stemming
    require_once __DIR__ . '/vendor/autoload.php';
    $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();

    $dictionary = $stemmerFactory->createStemmer();

    foreach ($data as $value) {
        $kalimat = implode(" ", $value['tf']);
        $stemmer = $dictionary->stem($kalimat);
        $results[] = [
            'document' => $value['document'],
            'tf' => explode(" ", $stemmer)
        ];
    }
    return totf($results);
    
}

function toTf($data)
{
      $newData = [];
      foreach ($data as $item) {
          $newData[] = [
              "document" => $item["document"],
              "tf" => array_count_values($item["tf"])
          ];
      }
      $data = $newData;

      // return $data;
    return tf($data);
}

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
      'document' => $value['document'],
      'idf' => $w
    ];
  }

  return $result;
  // return result($result);
}

function result($data, $kk)
{
  $kk = strtolower($kk);
  $kk = explode(" ",$kk);

  foreach ($data as $item) {
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

function main($kk)
{
  $tfIdf = tokenisasi();
  $result = result($tfIdf, $kk);
  
  return $result;
}

if (isset($_POST['submit'])) {
  $cari = $_POST['cari'];

  $res = main($cari);
  $d = [];
  foreach ($res as $key => $value) {
    if ($value) { 
      $d[] = "'$key'";
    }
  }
  if (count($d) < 1) :?>
    <div class="container">
      <div class="alert alert-danger">
        Kata Kunci <b><i><?= $cari ?></i></b> Tidak Ditemukan
        <?php die() ?>
      </div>
    </div>
  <?php
  endif;
  include 'koneksi.php';
  $document = '(' . implode(',', $d) .')';
  $order = implode(',',$d);
    $query = mysqli_query($conn, "SELECT * FROM korpus where document IN $document order by FIELD(document,$order)") or die(mysqli_error($conn));

    if ($query->num_rows > 0) {
        while ($word = mysqli_fetch_assoc($query)) :?>
        <div class="container">
          <div class="list-group">
            <a href="#" class="list-group-item list-group-item-action" aria-current="true">
              <?= $word['isi'] ?>
            </a>
          </div>
        </div>
        <?php
        endwhile;
    }
}
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>