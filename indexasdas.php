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
  <div class="container">

    <form action="" method="POST" class="my-3">
      <div class="mb-3">
        <input placeholder="Masukkan Kalimat" type="text" name="isi" class="form-control">
      </div>
      <button class="btn-success btn" name="tambah">tambah</button>
    </form>
  </div>

  <?php
  include 'koneksi.php';

  // stopword tala
  include 'stopword.php';
  
  $query = mysqli_query($conn, "SELECT * FROM korpus");
  
  if ($query->num_rows > 0) :
  $number = 1;
  while ($word = mysqli_fetch_assoc($query)) :
    // menghilangkan angka dan tanda baca
    $data = preg_replace('/[^a-z]+/i', ' ', $data = preg_replace('/[0-9]+/', '', strtolower($word['isi'])));

    // jadikan array
    $data = explode(" ", $data);

    ?>
    <div class="container my-5">
      <div class="alert alert-success">
        <p><span class="fw-bold">Kalimat <?= $number++ ?>: </span><?= $word['isi'] ?></p>
      </div>
      <div class="row">
        <div class="col-6">
          <div class="card shadow rounded-md">
            <div class="card-body">
              <h3>Tokenisasi</h3>
              <table class="table table-striped">
                <tbody>
                  <?php foreach ($data as $key => $value) : ?>
                    <tr>
                      <td><?= $key + 1 ?></td>
                      <td><?= $value ?></td>
                    </tr>
                  <?php endforeach ?>
                  <tr>
                    <td></td>
                    <td>Jumlah: <?= count($data) ?> kata</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-6">
          <div class="card shadow rounded-md">
            <div class="card-body">
              <h3>Filtering</h3>

              <?php

              // hilangkan kata jika ada yg sama dengan stopword
              $filter = array_diff($data, $stopword);

              ?>
              <table class="table table-striped">
                <tbody>
                  <?php $no = 1;
                  foreach ($filter as $value) : ?>
                    <tr>
                      <td><?= $no++ ?></td>
                      <td><?= $value ?></td>
                    </tr>
                  <?php endforeach ?>
                  <tr>
                    <td>total: </td>
                    <td><?= count($filter) ?> kata</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php
    // menggunakan library sastrawi untuk stemming
    require_once __DIR__ . '/vendor/autoload.php';
    $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();

    $dictionary = $stemmerFactory->createStemmer();

    $kalimat = implode(" ", $filter);
    $stemmer = $dictionary->stem($kalimat);
    $stemming = explode(" ", $stemmer);
    ?>
    <div class="container mb-5">
      <div class="card shadow rounded-md mt-5">
        <div class="card-body">
          <h3>Stemming</h3>
          <table class="table table-striped">
            <tbody>
              <?php $no = 1;
              foreach ($stemming as $value) : ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td><?= $value ?></td>
                </tr>
              <?php endforeach ?>
              <tr>
                <td>total: </td>
                <td><?= count($filter) ?> kata</td>
              </tr>
            </tbody>
          </table>

          Hasil Tokenisasi, Filtering Stemming Adalah:<h4 style="display: inline"> <?= $stemmer ?></h4>

        </div>
      </div>
    </div>

    



</html>

<?php 
endwhile;
endif;
?>