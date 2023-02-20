<?php
    $description = $_GET['descrizione'];
    $stato = $_GET['stato'];
?>

<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="assets/luogo.css">
        <script src="https://cdn.tailwindcss.com"></script>
        <title>Luogo</title>
    </head>
    <body>

        <div class="lugo_description text-white grid h-screen place-items-center">
            <div class="m-8">
                <p class="mb-4 text-xl font-bold"><?php echo $stato; ?></p>
                <div class="descrizione"><?php echo $description; ?></p>
            </div>
        </div>
    </body>
</html>