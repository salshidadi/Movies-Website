
//const cover = document.querySelector(".cover");



//const options = {method: 'GET', headers: {accept: 'application/json'}};


/*fetch('https://api.themoviedb.org/3/movie/now_playing?language=en-US&page=1&api_key=a7bd821b8f02542c2ebda2c469352fe0', options)
  .then(response => response.json())
  .then(data => {
    
    
    let option = Math.floor(Math.random()*20);

    const movies = data.results;
    const poster = movies[option]?.backdrop_path;
    cover.src = `https://image.tmdb.org/t/p/original/${poster}`;
    
    })
  .catch(err => console.error(err));*/