<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wikipedia</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div id="main">
        <aside class="recent-search-container translated">
            <div class="recent-search-container__title">
                Búsquedas recientes
                <span class="close-recent-search-container">&times;</span>
            </div>
            <hr>
            <div>
                <div class="recent-search-alert alert alert-info hidden">
                    No se han encontrado búsquedas recientes
                </div>
                <div class="recent-search-alert-error alert alert-error hidden">
                    Hubo un error al recuperar la lista de búsquedas recientes. Inténtelo de nuevo más tarde o contacte con el administrador del sitio.
                </div>
                <ul class="recent-search-list hidden">
                </ul>
            </div>
            
        </aside>
        <div class="search-box alert alert-info">
            <div>
                Introduce un término de búsqueda en el formulario
            </div>
            <form action="" method="POST" id="searchForm">
                <input type="text" name="searchTerm" id="searchTerm" class="search-term" required minlength="2">
                <button type="submit">
                    Buscar
                </button>
                <button type="button" id="toggleRecentSearchButton">
                    Mostrar búsquedas recientes
                </button>
            </form>
        </div>
        <div class="search-box-error alert alert-error hidden">
            Ha ocurrido un error y no se pudo guardar el último término de búsqueda introducido.
        </div>
        <div class="results-box">
            <div class="results-box-overlay hidden">
                Espere, por favor...
            </div>
            <div class="results-table-info alert alert-warning">
                Primero debe realizar una búsqueda
            </div>
            <div class="results-table-error alert alert-error hidden">
                Hubo un error al recuperar los resultados desde Wikipedia.
            </div>
            <table class="results-table hidden" border-collapse="collapse">
            <thead>
                <tr>
                    <th>
                        Título
                    </th>
                    <th>
                        Snippet
                    </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        </div>
    </div>
    <script src="js/app.js"></script>
</body>
</html>