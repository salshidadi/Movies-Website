"use strict";

const cover = document.querySelector(".cover");
const title = document.querySelector(".movie-title");
const dsc = document.querySelector(".movie-dsc");
const trailaerVideo = document.querySelector(".trailler-video");
const movieList = document.querySelector(".movie-cards");
const traillerBtn = document.querySelector(".btnMain");
const trailerWindow = document.querySelector(".trailler-c");


const options = { method: 'GET', headers: { accept: 'application/json' } };



fetch('https://api.themoviedb.org/3/movie/now_playing?language=en-US&page=1&api_key=a7bd821b8f02542c2ebda2c469352fe0', options)
    .then(response => response.json())
    .then(data => {

        let option = Math.floor(Math.random() * 5);

        const movies = data.results;
        const poster = movies[option]?.backdrop_path;
        cover.src = `https://image.tmdb.org/t/p/original/${poster}`;

        traillerBtn.addEventListener('click', function () {

            fetch(`https://api.themoviedb.org/3/movie/${movies[option].id}/videos?language=en-US&api_key=a7bd821b8f02542c2ebda2c469352fe0`, options)
                .then(response => response.json())
                .then(dataTrailer => {

                    trailaerVideo.src = `https://www.youtube.com/embed/${dataTrailer.results[0].key}`;
                })
            trailerWindow.classList.remove("hide");
        });

        title.textContent = movies[option].title;
        dsc.textContent = movies[option].overview;

    })
    .catch(err => console.error(err));



fetch('https://api.themoviedb.org/3/discover/movie?include_adult=false&include_video=false&language=en-US&page=1&sort_by=popularity.desc&api_key=a7bd821b8f02542c2ebda2c469352fe0', options)
    .then(response => response.json())
    .then(data => {

        const movieArr = data.results;

        movieArr.forEach(element => {
            let rate = element.vote_average;
            let colorClass;

            if (rate >= 7 && rate <= 10)
                colorClass = "heigh";
            else if (rate >= 5 && rate < 7)
                colorClass = "mediume";
            else
                colorClass = "low";


            const card =
                `   <div class="card ${element.id}">
            <p class="rate ${colorClass}">${(rate).toFixed(1)}</p>
            <img src= "https://image.tmdb.org/t/p/original/${element.poster_path}" class="card-poster">
            <div class= "movie-info">
                <p class="card-titl">${element.title}</p>
                <button class="mainBtn Btn-${element.id}/ watch-trailer"><img class="i${element.id}/" src="img/play.png" width="16px" height="16px"></button>
                
        </div>`;

            movieList.insertAdjacentHTML("beforeend", card);

        });
    })
    .catch(err => console.error(err));






//const btn = document.querySelector(`.Btn-${element.id}`);

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