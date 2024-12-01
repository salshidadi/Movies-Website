"use strict";


const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const id = urlParams.get('id')
const IMG_PATH_SMALL = 'https://image.tmdb.org/t/p/w500';
const containor = document.querySelector('.container');

const API_KEY = 'api_key=a7bd821b8f02542c2ebda2c469352fe0';
const BASE_URL = 'https://api.themoviedb.org/3';
const API_URL = `${BASE_URL}/movie/${id}?${API_KEY}`;



const options = { method: 'GET', headers: { accept: 'application/json' } };
getMovies(API_URL);








// function to get the data from the api
function getMovies(url){
    fetch(url, options)
    .then(response => response.json())
    .then(data => {

      disPlayMovies(data);
  
    })
    .catch(err => console.error(err));
  
  }

  



  // function to display the movies in the templets
async function disPlayMovies(data){
  containor.innerHTML = '';

  
      //let rate = element.vote_average;
      const genres = data.genres.map(genre => {
        return genre.name;
      });
      
      console.log(genres)

      const card =
        `   <div class="one-card">
            <div class="poster">
                <img src="https://image.tmdb.org/t/p/original/${data.poster_path}">
            </div>

            <div class="data">
                <div class="part-one">
                    <p class="title">${data.title}</p>
                    <span class="data-set">
                        <p><img src="img/calendar.png" width="20px" height="20px">${data.release_date}</p>
                        <p><img src="img/history.png" width="20px" height="20px">${Math.floor(data.runtime/60)}</p>
                        <p><img src="img/film.png" width="20px" height="20px">Movie</p>
                    </span>

                    <span class="data-set">
                        <p id="rate-text"><img src="img/star.png" width="20px" height="20px">Rate ${(data.vote_average).toFixed(1)} (${data.vote_count}k)</p>
                        <button class="rate">Rate</button>
                    </span>
                </div>
                
                <div class="partTow">

                </div>
                <p class="description">${data.overview}</p>

                <div class="options">
                    <button class="towBtn wL"><img src="img/bookmark.png" width="20px" height="20px"></button>
                    <button class="towBtn mF"><img src="img/ActivHeart.png" width="20px" height="20px"></button>
                </div>
            </div>
        </div>`;
  
    containor.insertAdjacentHTML("beforeend", card);
    const genre = document.querySelector(".partTow");

    genres.forEach(element => {
      let type = `<p class="type">${element}</p>`
      genre.insertAdjacentHTML("beforeend", type);
    });

    popUP();
  }


function popUP(){
    // Get the modal
  var modal = document.getElementById("myModal");

  // Get the button that opens the modal
  var btn = document.querySelector(".rate");

    // Get the <span> element that closes the modal
  var span = document.getElementsByClassName("close")[0];

  // When the user clicks the button, open the modal 
  btn.addEventListener('click',() =>{
    modal.style.display = "block";
  });

  // When the user clicks on <span> (x), close the modal
  span.addEventListener('click', () =>{
    modal.style.display = "none";
  })

  // When the user clicks anywhere outside of the modal, close it
  window.addEventListener('click', (event) =>{
    if (event.target == modal) {
      modal.style.display = "none";
    }
  })
}

