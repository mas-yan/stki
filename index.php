<?php 
include 'koneksi.php';
?>
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
    <div class="container my-3">
        <?php 
        if (isset($_POST['tambah'])) {
            $insert = mysqli_query($conn, 'INSERT INTO korpus (judul,isi,document)
            VALUES ("' . $_POST['judul'] . '","' . $_POST['isi'] . '","' . $_POST['document'] . '")') or die(mysqli_error($conn));
            if ($insert) {
                header("location:index.php?pesan=input");
            }else{
                header("location:index.php?pesan=inputfail");
            }
          }
        ?>
        <?php 
        if (isset($_GET['pesan'])) :?>
            <?php 
            if ($_GET['pesan'] == 'input') {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Berhasil Ditambahkan!</strong> Data korpus berhasil ditambahkan.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            }elseif (isset($_GET['inputfail'])){
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Gagal Ditambahkan!</strong> Data korpus Gagal ditambahkan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            }
            ?>
        <?php endif ?>
    <form action="" method="POST">
          <div class="mb-3">
            <input placeholder="Masukkan Judul" type="text" name="judul" class="form-control">
          </div>
          <div class="mb-3">
            <input placeholder="Masukkan Kalimat" type="text" name="isi" class="form-control">
          </div>
          <div class="mb-3">
            <input placeholder="Masukkan Document" type="text" name="document" class="form-control">
          </div>
          <button class="btn-success btn" name="tambah">tambah</button>
        </form>
    </div>

    <?php 
        $query = mysqli_query($conn, "SELECT * FROM korpus");
    
        
    ?>
    <!-- tampilkan data -->
    <div class="container my-5">
        <div class="table-resposnsive">
            <table class="table table-hover table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>isi</th>
                        <th>document</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        if ($query->num_rows > 0) :
                        $number = 1;
                        while ($word = mysqli_fetch_assoc($query)) :
                        // menghilangkan angka dan tanda baca
                        $data = preg_replace('/[^a-z]+/i', ' ', $data = preg_replace('/[0-9]+/', '', strtolower($word['isi'])));
                    
                        // jadikan array
                        $data = explode(" ", $data);
                     ?>
                    <tr>
                        <td><?= $number++ ?></td>
                        <td><?= $word['judul'] ?></td>
                        <td>
                            <span class="d-inline-block text-truncate" style="max-width: 150px;">
                                <?= $word['isi'] ?>
                            </span>    
                        </td>
                        <td><?= $word['document'] ?></td>
                        <td>
                            <a href="controller.php?document=<?= $word['document'] ?>" class="btn btn-primary">Lihat Process</a></td>
                    </tr>
                    <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Belum Ada Data</td>
                        </tr>
                    <?php
                        endif;
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>
<!-- input -->