"use strict";

const trailaerVideo = document.querySelector(".trailler-video");
const movieList = document.querySelector(".movie-cards");


const options = {method: 'GET', headers: {accept: 'application/json'}};


fetch('https://api.themoviedb.org/3/discover/movie?include_adult=false&include_video=false&language=en-US&page=1&sort_by=popularity.desc&api_key=a7bd821b8f02542c2ebda2c469352fe0', options)
  .then(response => response.json())
  .then(data => {
    
    const movieArr = data.results;
    
    movieArr.forEach(element => {
      let rate = element.vote_average;
      let colorClass;

      if(rate >= 7 && rate <= 10)
        colorClass = "heigh";
      else if(rate >= 5 && rate < 7)
        colorClass = "mediume";
      else
        colorClass = "low";


      const card =
      `   <div class="card">
            <p class="rate ${colorClass}">${(rate).toFixed(1)}</p>
            <img src= "https://image.tmdb.org/t/p/original/${element.poster_path}" class="card-poster">
            <div class= "movie-info">
                <p class="card-titl">${element.title}</p>
                <button class="mainBtn Btn-${element.id} watch-trailer"><img src="img/play.png" width="16px" height="16px"></button>
                <div class="towBtnContainer">
                  <button class="towBtn wL"><img src="img/unActiveAdd.png" width="12px" height="12px"><p>Watch List</p></button>
                  <button class="towBtn mF"><img src="img/unActivHeart.png" width="12px" height="12px"></button>
            </div>
        </div>`;

        movieList.insertAdjacentHTML("beforeend",card);

        
        const btn = document.querySelector(`.Btn-${element.id}`);
        
        btn.addEventListener('click',function(){

          fetch(`https://api.themoviedb.org/3/movie/${element.id}/videos?language=en-US&api_key=a7bd821b8f02542c2ebda2c469352fe0`, options)
          .then(response => response.json())
          .then(dataTrailer => {
      
            trailaerVideo.src = `https://www.youtube.com/embed/${dataTrailer.results[0].key}`;
          })
          trailerWindow.classList.remove("hide");
        });

    });
  })
  .catch(err => console.error(err));


  


  function endTrailer(){
    trailerWindow.classList.add("hide");
  }


