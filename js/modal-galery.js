// Get the modal
var modal = document.getElementById("myModal");

// Get the image and insert it inside the modal - use its "alt" text as a caption
var img = document.getElementsByClassName("gallery-img");
var modalImg = document.getElementById("modalImage");
var captionText = document.getElementById("caption");
var currentIndex = 0;

for (var i = 0; i < img.length; i++) {
  img[i].onclick = function(){
    modal.style.display = "block";
    modalImg.src = this.src;
    currentIndex = Array.from(img).indexOf(this);
  }
}

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// Next and previous controls
document.getElementById("nextButtonModal").onclick = function() {
  currentIndex = (currentIndex + 1) % img.length;
  modalImg.src = img[currentIndex].src;
}

document.getElementById("prevButtonModal").onclick = function() {
  currentIndex = (currentIndex - 1 + img.length) % img.length;
  modalImg.src = img[currentIndex].src;
}

// Download button
document.getElementById("downloadButton").onclick = function() {
  var link = document.createElement('a');
  link.href = modalImg.src;
  link.download = 'image.jpg';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}
