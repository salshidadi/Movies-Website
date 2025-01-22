// Get modal and button
var modal = document.getElementById("myModal");
var submitButton = document.getElementById("submitRating");
let currentRating;
let currentCount;

setTimeout(() =>{
    const rateCon = document.getElementById("rate-text");
    currentRating = rateCon.getAttribute('rate');
    currentCount = rateCon.getAttribute('count');
},1000);





// Open modal (this can be triggered by a button elsewhere in your HTML)
function openModal(movieId) {
    modal.style.display = "block";
    // You can store the movie ID in a hidden variable or pass it dynamically
    document.getElementById("movieId").value = movieId;
}

// Close modal when 'X' is clicked
var span = document.getElementsByClassName("close")[0];
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks on submit rating
submitButton.onclick = function() {
    var selectedRating = document.querySelector('input[name="rate"]:checked');
    if (selectedRating) {
        var ratingValue = selectedRating.value;
        var movieId = modal.getAttribute('movieId'); // assuming you store the movieId

        // Send AJAX request to submit the rating
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "submitRating.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                alert(xhr.responseText); // Handle response (success/error)
                modal.style.display = "none"; // Close modal
            }
        };
        
        //xhr.send("movieId=" + movieId + "&rating=" + ratingValue);
        xhr.send("movieId=" + movieId + "&rating=" + ratingValue + "&apiRating=" + currentRating + "&apiCount=" + currentCount);
    } else {
        alert("Please select a rating!");
    }
};
