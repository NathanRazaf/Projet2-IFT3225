<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation</title>
    <link rel="icon" href="/views/doc/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <style>
        .endpoint {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 5px;
            background-color: #f8f9fa;
        }
        .method {
            font-weight: bold;
            color: #0d6efd;
        }
        .path {
            font-family: monospace;
            background-color: #e9ecef;
            padding: 5px;
            border-radius: 3px;
        }
        .description {
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1>API Documentation</h1>
    <p>This page describes the available API endpoints and their usage.</p>

    <h2 class="mt-4">Player Management</h2>

    <div class="endpoint">
        <div class="method">GET</div>
        <div class="path">/gamers/{joueur}</div>
        <div class="description">
            Returns information about a player including login, game stats, and score.
            <br>
            <strong>Example:</strong> <code>/gamers/Asp3rity</code>
        </div>
    </div>

    <div class="endpoint">
        <div class="method">GET</div>
        <div class="path">/gamers/add/{joueur}/{pwd}</div>
        <div class="description">
            Adds a new player to the system and returns their unique ID.
            <br>
            <strong>Example:</strong> <code>/gamers/add/Asp3rity/secret123</code>
        </div>
    </div>

    <div class="endpoint">
        <div class="method">GET</div>
        <div class="path">/gamers/login/{joueur}/{pwd}</div>
        <div class="description">
            Logs in a player with the given credentials.
            <br>
            <strong>Example:</strong> <code>/gamers/login/Asp3rity/secret123</code>
        </div>
    </div>

    <div class="endpoint">
        <div class="method">GET</div>
        <div class="path">/gamers/logout/{joueur}/{pwd}</div>
        <div class="description">
            Logs out the player.
            <br>
            <strong>Example:</strong> <code>/gamers/logout/Asp3rity/secret123</code>
        </div>
    </div>

    <h2 class="mt-4">Admin Functions</h2>

    <div class="endpoint">
        <div class="method">GET</div>
        <div class="path">/admin/top/{nb}</div>
        <div class="description">
            Returns the top N players by score.
            <br>
            <strong>Example:</strong> <code>/admin/top/10</code>
        </div>
    </div>

    <div class="endpoint">
        <div class="method">GET</div>
        <div class="path">/admin/delete/joueur/{joueur}</div>
        <div class="description">
            Deletes a player from the system.
            <br>
            <strong>Example:</strong> <code>/admin/delete/joueur/Asp3rity</code>
        </div>
    </div>

    <div class="endpoint">
        <div class="method">GET</div>
        <div class="path">/admin/delete/def/{id}</div>
        <div class="description">
            Deletes a definition by its ID.
            <br>
            <strong>Example:</strong> <code>/admin/delete/def/123</code>
        </div>
    </div>

    <h2 class="mt-4">Word & Definition Access</h2>

    <div class="endpoint">
        <div class="method">GET</div>
        <div class="path">/word/{nb}/{from}</div>
        <div class="description">
            Returns N words starting from index M.
            <br>
            <strong>Example:</strong> <code>/word/10/1</code>
        </div>
    </div>

    <h2 class="mt-4">Games</h2>

    <div class="endpoint">
        <div class="method">GET</div>
        <div class="path">/jeu/word/{lg}/{time}/{hint}</div>
        <div class="description">
            Word guessing game interface.
            <br>
            <strong>Example:</strong> <code>/jeu/word/en/60/10</code>
        </div>
    </div>

    <div class="endpoint">
        <div class="method">GET</div>
        <div class="path">/jeu/def/{lg}/{time}</div>
        <div class="description">
            Definition creation game interface.
            <br>
            <strong>Example:</strong> <code>/jeu/def/en/60</code>
        </div>
    </div>

    <h2 class="mt-4">Data Viewing</h2>

    <div class="endpoint">
        <div class="method">GET</div>
        <div class="path">/dump/{step}</div>
        <div class="description">
            DataTable view of word definitions.
            <br>
            <strong>Example:</strong> <code>/dump/10</code>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>