<?php
$nav_selected = "HELP";
$left_buttons = "NO";
$left_selected = "";

include("./nav.php");
?>

<div class="right-content">
    <div class="container">

        <h2 style="color: #01B0F1; margin-bottom: 25px;">
            OMDB Help
        </h2>

        <p>
            Welcome to the Online Movie Database help page.
            Use the sections below to learn how to use the main features of OMDB.
        </p>

        <hr>

        <h3 style="color: #01B0F1;">Movies</h3>
        <p>
            The Movies section allows you to browse and manage movies stored in the database.
        </p>

        <ul>
            <li>View the complete movie list.</li>
            <li>Create a new movie.</li>
            <li>View detailed movie information.</li>
            <li>Modify an existing movie.</li>
            <li>Delete a movie.</li>
            <li>Attach people to a movie.</li>
            <li>Attach songs to a movie.</li>
        </ul>

        <hr>

        <h3 style="color: #01B0F1;">People</h3>
        <p>
            The People section stores information about actors, actresses,
            directors, lyricists, and other people associated with movies.
        </p>

        <ul>
            <li>Browse all people.</li>
            <li>Create a new person.</li>
            <li>View a person's information.</li>
            <li>Modify an existing person.</li>
            <li>Delete a person.</li>
        </ul>

        <hr>

        <h3 style="color: #01B0F1;">Songs</h3>
        <p>
            The Songs section allows you to manage songs and connect them
            with movies and people.
        </p>

        <ul>
            <li>Browse the song library.</li>
            <li>Create a new song.</li>
            <li>View song information.</li>
            <li>Modify a song.</li>
            <li>Delete a song.</li>
            <li>Attach songs to movies.</li>
        </ul>

        <hr>

        <h3 style="color: #01B0F1;">Search</h3>
        <p>
            Use Search to find information in the OMDB database.
        </p>

        <ul>
            <li>Search movies by movie information.</li>
            <li>Search people by name.</li>
            <li>Search songs by title or other song information.</li>
        </ul>

        <hr>

        <h3 style="color: #01B0F1;">Reports</h3>
        <p>
            Reports allow you to view relationships and statistics stored
            in the database.
        </p>

        <ul>
            <li>View the number of movies by year.</li>
            <li>Find movies associated with an actor.</li>
            <li>Find movies shared by an actor and actress.</li>
            <li>Find movies and songs associated with a lyricist.</li>
        </ul>

        <hr>

        <h3 style="color: #01B0F1;">Games</h3>
        <p>
            OMDB includes movie-related games that use information stored
            in the database.
        </p>

        <ul>
            <li>
                <strong>Base Characters:</strong>
                Search for movies using characters from a movie title.
            </li>

            <li>
                <strong>Anagrammer:</strong>
                Work with movie-title anagrams.
            </li>

            <li>
                <strong>Movie in Movies:</strong>
                Play the movie-title guessing game.
            </li>
        </ul>

        <hr>

        <h3 style="color: #01B0F1;">Administrator Features</h3>
        <p>
            Administrator accounts have access to additional database
            management features.
        </p>

        <ul>
            <li>Import Movies, People, and Songs using Setup.</li>
            <li>Use the Base Characters administrator tools.</li>
            <li>Create and manage movie anagrams.</li>
            <li>Access administrator-only movie anagram data.</li>
        </ul>

        <p>
            If you receive an <strong>Access Denied</strong> message,
            the feature requires an administrator account.
        </p>

        <hr>

        <h3 style="color: #01B0F1;">Account and Login</h3>

        <ul>
            <li>You must be signed in to use protected OMDB features.</li>
            <li>Use Sign Up to create a new account.</li>
            <li>Use Log Out when you are finished using OMDB.</li>
        </ul>

        <hr>

        <h3 style="color: #01B0F1;">Common Problems</h3>

        <p>
            <strong>A page redirects me to Login:</strong><br>
            The page requires an authenticated user. Sign in and try again.
        </p>

        <p>
            <strong>I receive Access Denied:</strong><br>
            The page requires administrator privileges.
        </p>

        <p>
            <strong>I cannot attach a person or song:</strong><br>
            Open the movie first and use the Add Person or Add Song option
            for that movie.
        </p>

        <p>
            <strong>A search returns no results:</strong><br>
            Check your spelling and try a shorter search value.
        </p>

        <hr>

        <p style="margin-top: 25px; color: #777;">
            Online Movie Database (OMDB) — Help and User Guide
        </p>

    </div>
</div>

<?php
db_disconnect($db);
include("./footer.php");
?>