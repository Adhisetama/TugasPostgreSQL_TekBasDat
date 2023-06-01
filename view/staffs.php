<?php require_once __DIR__ . '/../modules/dbConnection.php' ?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Hello, world!</title>
    <link rel="icon" type="image/x-icon" href="https://i.ibb.co/X87dZV7/croppedbook.png">

  </head>
  <body style="height: 100%; width: 100%; box-sizing: border-box;">
  <div class="container">

    
    <h1 class="mt-4 mb-3">
        <img src="https://i.ibb.co/X87dZV7/croppedbook.png" alt="croppedbook" style="height:70px; width:70px;"/>
        Azka's Bookstore
    </h1>

    <ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link" href="./">Cabang Toko</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="./books.php">Daftar Buku</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="./authors.php">Daftar Penulis</a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="#">Daftar Staff</a>
    </li>
    </ul>

    <div class="container-fluid" style="height: 100%;">
        <div class="row">
            <div class="col">
                <table class="table table-hover mt-4">
                    <thead>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Tanggal Lahir</th>
                        <th>Penempatan Cabang</th>
                    </thead>
                    <tbody>
                        <?php 
                            $storeBranches = postgreQuery('SELECT public."Staffs".*, public."StoreBranches"."address", public."StoreBranches"."branch_id"
                                                                FROM public."Staffs"
                                                                JOIN public."StoreBranches" ON public."Staffs"."FK_branch_id"=public."StoreBranches".branch_id');
                            foreach ($storeBranches as $index => $row):
                        ?>
                        <tr>
                            <th scope="row" id="<?= $row['staff_id'] ?>" ><?= $index + 1 ?></th>
                            <td><?= substr($row['name'], 2, -2) ?></td>
                            <td><?= $row['birthdate'] ?></td>
                            <td>
                                <a href="./index.php?selected_id=<?= $row['branch_id'] ?>">
                                    <?= strtok(substr($row['address'],2,-2), ':') ?>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</div>
</body>
</html>