<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Word Definitions</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <style>
        .container {
            margin-top: 30px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Word Definitions</h1>
    <p>Showing definitions in blocks of <?php echo $step; ?> rows.</p>

    <table id="definitionsTable" class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Lg</th>
            <th>Src</th>
            <th>Word</th>
            <th>Definition</th>
        </tr>
        </thead>
        <tbody>
        <!-- Table data will be loaded via AJAX -->
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#definitionsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/api/definitions',
                type: 'GET'
            },
            columns: [
                { data: 'language' },
                { data: 'source' },
                { data: 'word' },
                { data: 'definition' },
            ],
            pageLength: <?php echo $step; ?>,
            language: {
                paginate: {
                    next: '&raquo;',
                    previous: '&laquo;'
                }
            }
        });
    });
</script>
</body>
</html>