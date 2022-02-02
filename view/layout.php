<!doctype html>
<html lang="en">

  <head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <link rel="stylesheet" href="css/style.css">

    <title>Hospitale2n</title>

  </head>

  <body>

    <header>
      <nav class="navbar navbar-light ">

          <div class="container-fluid">

          <p><a class="navbar-brand" href="index.php">HOSPITALE2N</a></p>

          </div>

      </nav>
    </header>
   
    <main>
      <!-- On déclare dans la balise main la variable $content définie dans la méthode render() et qui contient toutes les informations des différents templates du dossier view. La libération sur le navigateur se fera en fonction des informations transmises dans l'URL -->
      <?= $content; ?>

    </main>

    <footer>

    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

  </body>
</html>
