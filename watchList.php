<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location:index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Watchlist</title>
    <link rel="stylesheet" href="list2.css" type="text/css">

    <style>
        .towBtn {
            background-color: #8b1d31; /* Dark red background */
            color: white; /* White text */
            font-size: 14px; /* Slightly larger font */
            padding: 12px 30px; /* Increased padding for more space */
            border: none; /* Remove the default border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Change cursor on hover */
            transition: background-color 0.3s ease, transform 0.2s ease; /* Smooth transition for hover effects */
            text-align: center;
            display: inline-block;
            width: auto; /* Allow width to grow based on content */
            min-width: 200px; /* Ensure the button is at least 200px wide */
            font-family:Arial, Helvetica, sans-serif;   
        }

        .towBtn:hover {
            background-color: #c34a5a; /* Lighter red on hover */
            transform: scale(1.05); /* Slight zoom-in effect */
        }

        .towBtn:focus {
            outline: none; /* Remove focus outline */
        }

        .towBtn.wL {
            font-weight: bold; /* Make text bold for clarity */
            text-transform: uppercase; /* Make the text all uppercase */
        }

        #search-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 20px 0;
        }

        #search-bar {
            background-color: #0d162b; /* Dark background */
            border: 2px solid #1a2949; /* Slightly lighter border for depth */
            border-radius: 50px; /* Rounded edges */
            padding: 15px 20px; /* Add padding for better spacing */
            font-size: 16px; /* Comfortable font size */
            color: #ffffff; /* White text color */
            width: 80%; /* Adjust the width as needed */
            max-width: 600px; /* Limit the width on larger screens */
            outline: none; /* Remove default outline */
            transition: all 0.3s ease; /* Smooth hover and focus effects */
            font-family: Arial, sans-serif; /* Ensure a clean font */
        }

        #search-bar::placeholder {
            color: #7b8ba1; /* Placeholder color */
            font-style: italic; /* Slightly styled placeholder */
        }

        #search-bar:focus {
            border-color: #4a90e2; /* Blue glow effect on focus */
            box-shadow: 0 0 10px rgba(74, 144, 226, 0.8); /* Outer glowing shadow */
        }

    </style>
</head>

<body>
    <div class="container">

        <nav>
            <img src="logo.png" class="logo" alt="Logo">

            <div class="logedin">
                <a href="index.php" class="btn btn-1"> Home</a>
                <a href="watchList.php" class="btn btn-1"> My watch list</a>
                <a href="favoriteMovie.php" class="btn btn-1"> My favorite</a>
                <a href="logout.php" class="btn btn-1"> Logout</a>

                <img class="profile-photo" src="img/userIcons/icon-1.png" width="50px" height="50px">
            </div>
        </nav>

        <div id="search-container">
            <input type="text" id="search-bar" placeholder="Search watchlist movies..." />
        </div>

        <div class="innerContainer">
            <div class="empty hide">
                <img src="img/ghost.png" width="200px">
                <p>add some movie to watch later</p>
            </div>

            <div class="watch-list-cards">
                <!-- Movie cards will be dynamically added here -->
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const watchListContainer = document.querySelector('.watch-list-cards');
            const emptyMessage = document.querySelector('.empty');
            const searchBar = document.getElementById('search-bar');

            let movieData = []; // Store movie data globally

            // Fetch the watchlist data for the logged-in user
            fetch('get_watchlist.php')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error("Error:", data.error);
                        emptyMessage.classList.remove('hide');
                        return;
                    }

                    movieData = data; // Store the fetched data in the global variable

                    if (data.length === 0) {
                        // Show empty message if no movies in the watchlist
                        emptyMessage.classList.remove('hide');
                    } else {
                        // Hide the empty message
                        emptyMessage.classList.add('hide');

                        // Populate the watchlist container with movie cards
                        data.forEach(movie => {
                            const card = `
                                <div class="one-card" title="${movie.title}" id="movie-${movie.MovieID}">
                                    <div class="poster">
                                        <img src="https://image.tmdb.org/t/p/original${movie.poster_path}" alt="${movie.title}">
                                    </div>

                                    <div class="data">               
                                        <p class="title">${movie.title}</p>       
                                    </div>

                                    <div class="options">
                                        <button class="towBtn wL remove" data-movie-id="${movie.MovieID}">
                                            Remove From My Watch List
                                        </button>
                                    </div>
                                </div>
                            `;

                            watchListContainer.insertAdjacentHTML('beforeend', card);
                        });
                    }
                })
                .catch(err => {
                    console.error('Error fetching watchlist:', err);
                    emptyMessage.classList.remove('hide');
                });

            // Event delegation for remove buttons
            watchListContainer.addEventListener('click', (e) => {
                if (e.target.classList.contains('remove')) {
                    const movieID = e.target.getAttribute('data-movie-id');
                    const card = document.getElementById(`movie-${movieID}`);

                    // Remove the movie from the database
                    fetch('remove_from_watchlist.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ movieID }),
                    })
                        .then(response => response.json())
                        .then(result => {
                            if (result.success) {
                                // Remove the card from the DOM
                                card.remove();

                                // Show empty message if no more movies left
                                if (watchListContainer.children.length === 0) {
                                    emptyMessage.classList.remove('hide');
                                }
                            } else {
                                console.error("Failed to remove movie:", result.error);
                            }
                        })
                        .catch(err => console.error("Error removing movie:", err));
                }
            });

            // Dynamic search functionality for filtering movies
            searchBar.addEventListener('input', () => {
                const searchTerm = searchBar.value.toLowerCase(); // Get the search term
                const movieCards = watchListContainer.querySelectorAll('.one-card');

                movieCards.forEach((card, index) => {
                    const movieTitle = movieData[index].title.toLowerCase(); // Get the movie title
                    if (movieTitle.includes(searchTerm)) {
                        card.style.display = ''; // Show the card if it matches
                    } else {
                        card.style.display = 'none'; // Hide the card if it doesn't match
                    }
                });

                // Check if any cards are still visible and show empty message if none are
                const visibleCards = Array.from(movieCards).filter(card => card.style.display !== 'none');
                if (visibleCards.length === 0) {
                    emptyMessage.classList.remove('hide');
                } else {
                    emptyMessage.classList.add('hide');
                }
            });
        });
    </script>

</body>

</html>
