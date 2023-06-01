<?php require_once __DIR__ . '/../modules/dbConnection.php' ?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'> 

    <title>Hello, world!</title>
    <link rel="icon" type="image/x-icon" href="https://i.ibb.co/X87dZV7/croppedbook.png">

  </head>
  <body style="height: 100%; width: 100%; box-sizing: border-box;">

  <div class="container">
    <?php 
        // fetch data
        $authors = array_map(function($e) {
            $e['name'] = substr($e['name'], 2, -2);
            return $e;
        } , postgreQuery('SELECT * FROM public."Authors"'));
    ?>
    <form action="./query.php" method="post" id="editauth" onsubmit="return confirm('Do you really want to submit the form?');">

    <div id="original-data-trick-hehehe" style="display:none">
        <?php 
            echo json_encode($authors)
        ?>
    </div>
    <script>
        const originalData = JSON.parse(document.getElementById("original-data-trick-hehehe").innerText)
        console.log(originalData)
    </script>

    <h1 class="mt-4 mb-3">
        <img src="https://i.ibb.co/X87dZV7/croppedbook.png" alt="croppedbook" style="height:70px; width:70px;"/>
        Azka's Bookstore
    </h1>

    <div class="d-flex justify-content-between">
        <h3>Edit daftar penulis</h3>
        <div>
            <a href="../view/authors.php">
                <button type="button" class="btn btn-danger">Cancel</button>
            </a>
            <input class="btn btn-primary" type="submit" value="Submit">
        </div>
    </div>

    <div class="container-fluid" style="height: 100%;">
        <div class="row">
            <div class="col">
                <table class="table table-hover mt-3">
                    <thead>
                        <th><i class="fa fa-trash"></i></th>
                        <th>Nama</th>
                        <th>Tanggal lahir</th>
                    </thead>
                    <tbody>
                        <?php foreach ($authors as $index => $author): ?>
                        <tr>
                            <td><input type="checkbox" value="<?= $author['author_id'] ?>" id="delete-<?= $author['author_id'] ?>"></td>
                            <td><input type="text" value="<?= $author['name'] ?>" id="name-<?= $author['author_id'] ?>"></td>
                            <td><input type="text" value="<?= $author['birthdate'] ?>" id="birth-<?= $author['author_id'] ?>"></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

            <div class="col-6 mt-3" id="tes2">
                <input type="hidden" name="redirect-to" value="../view/authors.php">
                <textarea name="query-string" id="query-string" style="display: none" form="editauth"></textarea>
                <textarea readonly id="dummy-query" style="font-family: monospace; font-size: 0.75rem; width:100%; height:100%"></textarea>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    
    <script>
        let segoGoreng = String();
        const BIJI = { // if you trying to understand this code, just... dont.
            data: {},
            detectChange($id) { // return false jika g berubah, true jika di delete, assoc array jika berubah
                let row = BIJI.data[String($id)]
                if (row.form.delete.checked)
                    return true
                else if (
                    row.form.name.value == row.name &&
                    row.form.birthdate.value == row.birthdate
                )   return false
                else {
                    let changed = {}
                    if (row.form.name.value != row.name) changed['name'] = row.form.name.value
                    if (row.form.birthdate.value != row.birthdate) changed['birthdate'] = row.form.birthdate.value
                    return changed
                }
            },
            buildRowQuery(id) { // buat query berdasar perubahan form, jika tidak ada return empty string
                const tengkorak = BIJI.detectChange(id)
                console.log(tengkorak)
                if (tengkorak === true)
                    return `DELETE FROM public."Authors"\n`
                        +  `    WHERE author_id = ${id};`
                else if (tengkorak === false)
                    return ''
                else {
                    let updateStr = String()
                    updateStr += typeof tengkorak.name !== 'undefined' ? `"name" = '{${tengkorak.name}}'` : ''
                    updateStr += typeof tengkorak.name !== 'undefined' && typeof tengkorak.birthdate !== 'undefined' ? ', ' : ''
                    updateStr += typeof tengkorak.birthdate !== 'undefined' ? `"birthdate" = '${tengkorak.birthdate}}'` : ''
                    return `UPDATE public."Authors"\n`
                        +  `    SET ${updateStr}\n`
                        +  `    WHERE author_id = ${id};`
                }
            },
            buildQuery() {
                let queryString = String()
                for (const id in BIJI.data) {
                    const rowQuery = BIJI.buildRowQuery(id)
                    if (rowQuery) {
                        segoGoreng += rowQuery // the real query to passed in pdo->prepare()
                        queryString += `\n${rowQuery.replace(/{|}/g, '')}\n`
                    }
                }
                return queryString ? "BEGIN TRANSACTION;\n" + queryString + "\nCOMMIT;" : ''
            }
        }

        function printQuery(queryString) {
            const blin = document.getElementById("dummy-query")
            const blad = document.getElementById("query-string")
            segoGoreng = String()
            blin.value = BIJI.buildQuery()
            blad.value = JSON.stringify(segoGoreng)
        }

        originalData.forEach(e => {
            BIJI.data[`${e.author_id}`] = {
                name: e.name,
                birthdate: e.birthdate,
                form: {
                    delete: document.getElementById(`delete-${e.author_id}`),
                    name: document.getElementById(`name-${e.author_id}`),
                    birthdate: document.getElementById(`birth-${e.author_id}`)
                }
            }
            BIJI.data[`${e.author_id}`].form.delete.addEventListener('change', printQuery)
            BIJI.data[`${e.author_id}`].form.name.addEventListener('input', printQuery)
            BIJI.data[`${e.author_id}`].form.birthdate.addEventListener('input', printQuery)
        })

    </script>
    
    </form>
</div>
</body>
</html>