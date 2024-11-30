"use strict";

const API_KEY = 'api_key=a7bd821b8f02542c2ebda2c469352fe0';
const BASE_URL = 'https://api.themoviedb.org/3';
const API_URL = BASE_URL + '/discover/movie?include_adult=false&include_video=false&language=en-US&page=1&sort_by=popularity.desc&' + API_KEY;
const search_URL = BASE_URL + '/search/movie?include_adult=false&' + API_KEY;
const search_actor_URL = BASE_URL + '/search/person?' + API_KEY;
const actor_movies_url = BASE_URL + '/person/';
const genres_movie_URL = BASE_URL + '/genre/movie/list?' + API_KEY;



const cover = document.querySelector(".cover");
const title = document.querySelector(".movie-title");
const dsc = document.querySelector(".movie-dsc");
const trailaerVideo = document.querySelector(".trailler-video");
const movieList = document.querySelector(".movie-cards");
const traillerBtn = document.querySelector(".btnMain");
const trailerWindow = document.querySelector(".trailler-c");
const input = document.querySelector(".search-bar");
const checkbox = document.querySelector(".checkbox");
const list = document.querySelector('.list-items');
const selectBtn = document.querySelector(".select-btn");
let items = document.querySelectorAll(".item");



const options = { method: 'GET', headers: { accept: 'application/json' } };


let counter = 1;
let slectedGenera = [];

getMovies(API_URL);
getGener(genres_movie_URL);








movieList.addEventListener('click',(e) =>{
  console.log(e.target.parentNode.className);
  console.log(e.target.parentNode.tagName);
  if(e.target.parentNode.className == 'card' || e.target.parentNode.className == 'movie-info' && e.target.tagName.toLowerCase() != 'button')
    window.location.replace(`moviePage.php?id=${e.target.parentNode.id}`);
})




input.addEventListener("input", (e)=>{

  items = document.querySelectorAll(".item");

  items.forEach(item =>{
    if(item.classList.contains("checked"))
      item.classList.toggle("checked");
  })

  let btnText = document.querySelector(".btn-text");
  btnText.innerText = "Select The Genre";

  setTimeout(()=>{
    if(e.target.value)
      if(checkbox.checked)
        searchTMDb(e.target.value)
      else
        getMovies(search_URL + '&query=' + e.target.value);
    else
    getMovies(API_URL);
  },500)

});



movieList.addEventListener('click', function (e) {
  let className = e.target.getAttribute("class");
  className = getID(className);

  if (e.target.getAttribute("class") === `mainBtn Btn-${className}/ watch-trailer` || e.target.getAttribute("class") === `i${className}/`) {
    fetch(`https://api.themoviedb.org/3/movie/${className}/videos?language=en-US&api_key=a7bd821b8f02542c2ebda2c469352fe0`, options)
      .then(response => response.json())
      .then(dataTrailer => {

        trailaerVideo.src = `https://www.youtube.com/embed/${dataTrailer.results[0].key}`;
      })
      .catch(err => console.error(err));

    trailerWindow.classList.remove("hide");
  }
});






trailerWindow.addEventListener('click', endTrailer);

function endTrailer() {
  trailerWindow.classList.add("hide");
}




// to extract the movie id from a class name
function getID(className) {
  if (className[0] === "m") {
    let index1 = className.indexOf("-") + 1;
    let index2 = className.indexOf("/");
    className = className.slice(index1, index2);
    return className;

  } else if (className[0] === "i") {
    console.log(className[0]);
    console.log(className[0] === "i");
    let index1 = className.indexOf("i") + 1;
    let index2 = className.indexOf("/");
    className = className.slice(index1, index2);
    return className;
  }
}




// for the gener menue

selectBtn.addEventListener("click", () => {
    selectBtn.classList.toggle("open");
});




// function to get the data from the api
function getMovies(url){
  fetch(url, options)
  .then(response => response.json())
  .then(data => {

    if(counter == 1){
      displayHeroBanner(data.results);
      counter--;
    }

    
    disPlayMovies(data.results?data.results:data.cast);

  })
  .catch(err => console.error(err));

}


function displayMoviesForActor(data){
  let id = data[0].id;
  getMovies(`${actor_movies_url}${id}/combined_credits?${API_KEY}&language=en-US&include_adult=false`);
  

}


// function to display the movies in the templets
function disPlayMovies(data){
  movieList.innerHTML = '';

  data.forEach(element => {
    let rate = element.vote_average;
    
    const card =
      `   <div class="card" id=${element.id}>
          <p class="rate ${rateColor(rate)}">${(rate).toFixed(1)}</p>
          <img src= "https://image.tmdb.org/t/p/original/${element.poster_path}" class="card-poster">
          <div class= "movie-info">
              <p class="card-titl">${element.title}</p>
              <button class="mainBtn Btn-${element.id}/ watch-trailer"><img class="i${element.id}/" src="img/play.png" width="16px" height="16px"></button>
          </div>
      </div>`;

    movieList.insertAdjacentHTML("beforeend", card);

  });
  
}


// set the color based on the rate
function rateColor(rate){
  if (rate >= 7 && rate <= 10)
    return "heigh";
  else if (rate >= 5 && rate < 7)
    return "mediume";
  else
    return "low";
}


// to dispaly movie for the frist banner
function displayHeroBanner(data){
  let option = Math.floor(Math.random() * 5);

    const poster = data[option]?.backdrop_path;
    cover.src = `https://image.tmdb.org/t/p/original/${poster}`;

    traillerBtn.addEventListener('click', function () {

      fetch(`https://api.themoviedb.org/3/movie/${data[option].id}/videos?language=en-US&api_key=a7bd821b8f02542c2ebda2c469352fe0`, options)
        .then(response => response.json())
        .then(dataTrailer => {

          trailaerVideo.src = `https://www.youtube.com/embed/${dataTrailer.results[0].key}`;
        })
      trailerWindow.classList.remove("hide");
    });

    title.textContent = data[option].title;
    dsc.textContent = data[option].overview;
}



// Function to search TMDb
async function searchTMDb(query) {
  try {
      // Call the /search/multi endpoint
      const response = await fetch(`${search_actor_URL}&query=${encodeURIComponent(query)}`);
      const data = await response.json();

      // Check the results
      displayMoviesForActor(data.results);


  } catch (error) {
      console.error('Error fetching data from TMDb:', error);
  }
}

function getGener(url){
  
  fetch(url, options)
  .then(response => response.json())
  .then(data => {
    
    data.genres.forEach(element =>{
      let li =
          `<li class="item" id=${element.id}>
              <span class="checkbox">
                <i class="fa-solid fa-check check-icon"></i>
              </span>
              
              <span class="item-text">${element.name}</span>
            </li>`;

      list.insertAdjacentHTML("beforeend", li);

      li = document.getElementById(element.id);
      li.addEventListener('click', () =>{

        li.classList.toggle("checked");

        let checked = document.querySelectorAll(".checked"),
            btnText = document.querySelector(".btn-text");

            if(checked && checked.length > 0){
                btnText.innerText = `${checked.length} Selected`;
            }else{
                btnText.innerText = "Select The Genre";
            }
        
        if(slectedGenera.length == 0){
          slectedGenera.push(element.id);
        }else{
          if(slectedGenera.includes(element.id)){
            slectedGenera.forEach((id ,index) =>{
              if(id == element.id)
                slectedGenera.splice(index,1);
            })
          }else{
            slectedGenera.push(element.id);
          }
        }

        if(slectedGenera.length != 0)
          getMovies(API_URL + '&with_genres=' + slectedGenera.join(','));
        else
          getMovies(API_URL);
      })
      
      
    })

  })
  .catch(err => console.error(err)); 
}
