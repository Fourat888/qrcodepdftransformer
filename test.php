<!DOCTYPE html>
<html>
<head>
<style>
/* The Modal (background) */
.modalmodal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 30%; /* Location of the box */
  padding-left: 30%; /* Location of the box */

  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}
.modalbox {
  
  background-color: #fff;
  padding: 20px;
  border: 1px solid #ccc;
  width: 22%;
  align-items: center;
  justify-content: center;
  text-align: center;
}
/* Modal Content */
.modalmodal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

/* The Close Button */
.closemodal {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.closemodal:hover,
.closemodal:focus
{
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

/* Add Zoom Animation */
.animatemodal {
  -webkit-animation: animatezoom 0.6s;
  animation: animatezoom 0.6s
}

@-webkit-keyframes animatemodalzoom {
  from {-webkit-transform: scale(0)} 
  to {-webkit-transform: scale(1)}
}
  
@keyframes animatemodalzoom {
  from {transform: scale(0)} 
  to {transform: scale(1)}
}
.custom-red-btn {
    background-color: #ff0000;
    color: #fff;
    border: none;
}
.custom-blue-btn {
    background-color: #0000ff;
    color: #fff;
    border: none;
}

</style>
</head>
<body>

<h2>Modal Example</h2>

<!-- Trigger/Open The Modal -->
<button id="myBtn">Open Modal</button>

<!-- The Modal -->
<div id="myModal" class="modalmodal">

  <!-- Modal content -->
  <div class="modalmodal-contentmodal animatemodal">
  <div class="modalbox">

    <span class="closemodal" onclick="hideModal()">&times;</span>
    <p>Comment vous voulez importer votre pdf ?</p>
<button type="button" onclick="yesOption()" class="custom-red-btn">Avec menu du jour</button>
<button type="button" onclick="noOption()" class="custom-blue-btn">Sans menu du jour</button>
</div>
  </div>

</div>

<script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("closemodal")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

function hideModal() {
  modal.style.display = "none";
}
function yesOption() {

  hideModal();
}
function noOption() {

  hideModal();

}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

</script>

</body>
</html>
