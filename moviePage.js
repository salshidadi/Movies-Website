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
      let poster;
      let name;

      if(data.belongs_to_collection == null){
        poster = 'img/notloded.jpg';
        name = "not found";
      }
      else{
        poster = "https://image.tmdb.org/t/p/original/" + data.belongs_to_collection.poster_path;
        name = data.belongs_to_collection.name;
      }
      

      const card =
        `   <!-- Movie Header -->
        <div class="movie-header">
            <img src=${poster} alt="Moana 2 Poster" class="movie-poster">
            <div class="movie-info">
                <h1>${name}</h1>
                <p class="details">PG | ${data.release_date} ${data.spoken_languages[0].iso_639_1} | ${genres.join(',')} • 1h 40m</p>
                <div class="rating">
                    <span class="score">${data.vote_average}</span>
                    <span>User ${data.vote_count}</span>
                </div>
                <p class="overview-title">Overview</p>
                <p class="overview">
                    ${data.overview}
                </p>
                    <div class="options">
                        <button class="towBtn wL"><img src="img/bookmark.png" width="20px" height="20px"></button>
                        <button class="towBtn mF"><img src="img/ActivHeart.png" width="20px" height="20px"></button>
                    </div>
            </div>
        </div>
        ${await mainFunction(id)};
`;
  
    containor.insertAdjacentHTML("beforeend", card);

  }


  const timeout = function (s) {
    return new Promise(function (_, reject) {
      setTimeout(function () {
        reject(new Error(Request `took too long! Timeout after ${s} second`));
      }, s * 1000);
    });
  };
  
  const getJSON = async function (url) {
    try {
      const options = {
        method: 'GET',
        headers: {
          accept: 'application/json',
          Authorization:
            'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJjOTYzOTUxZjRkMDRjZmE5Nzk5MDZjYzUxZWMzNTFjNiIsIm5iZiI6MTcyODc2NTA3MS4yMjgxMzgsInN1YiI6IjY3MGE5ZTNmZjU4YTkyMDZhYTQwODUzOSIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.XL8gTG5nYX4frmu8RIiUI5ikSCnLSc04MEnT5jHTuMQ',
        },
      };
  
      const fetchPro = fetch(url, options);
      const res = await Promise.race([fetchPro, timeout(10)]);
  
      const data = await res.json();
  
      if (!res.ok) throw new Error(`${data.message} (${res.status})`);
      return data;
    } catch (err) {
      throw err;
    }
  };





  const createRecommendationsObject = function (data){
    return {
      id: data.id,
      title: data.title,
      backdrop_path: data.backdrop_path,
      rating: data.vote_average,
    }
  }

  const mainFunction = async function (id) {
    const data = await getJSON(`
      https://api.themoviedb.org/3/movie/${id}/recommendations?language=en-US&page=1
    `);
    const arr = data.results.map(result => createRecommendationsObject(result));
    console.log(arr)            
    const card = `      <div class="show-row">
        <p> Rewcomendation </p>
        
        <div class="show-cards">
          ${arr.map(ob => {
            return` 
                    <div class="show-card" data-id="${ob.id}">
            <div class="hideOverflow">
                <img
                    class="card-img"
                    src="${IMG_PATH_SMALL}${ob.backdrop_path}"
                    alt="movie background image"
                />
            </div>
            <div class="show-card-content">
                <p class="show-card-content-title">${ob.title}</p>
                <div class="show-card-content-icons">
                    <div class="show-card-content-icon">
                        <ion-icon
                            class="show-icon"
                            name="star-outline"
                        ></ion-icon>
                        <p class="show-card-content-rating">${(ob.rating).toFixed(1)}</p>
                    </div>
                </div>
                <a class="go-to-product" href="product.html?id=${ob.id}">See details &rArr;</a>
            </div>
        </div>
            
          `}).join('')}
        </div>
      </div>
    `;
            return card;
  };