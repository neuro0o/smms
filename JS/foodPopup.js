// PopUp Script
function openFoodPopup(food) {
  // Populate popup with food details
  document.getElementById("food-popup-img").src = "../../../SMMS/images/food/" + food.foodImg;
  document.getElementById("food-popup-name").innerText = food.foodName;
  document.getElementById("food-popup-price").innerText = food.foodPrice;
  document.getElementById("food-popup-desc").innerText = food.foodDesc || "No description available.";
  document.getElementById("food-popup-category").innerText = food.categoryName; 

  // Populate the hidden input fields for wishlist form
  document.getElementById("food-popup-itemID").value = food.foodID;
  document.getElementById("food-popup-itemName").value = food.foodName;
  document.getElementById("food-popup-itemPrice").value = food.foodPrice;
  document.getElementById("food-popup-itemImg").value = food.foodImg;

  // Set the food ID in the hidden input
  document.getElementById('food-popup-itemID').value = food.foodID;

  // Show the popup
  document.getElementById("food-popup").style.display = "block";
  document.getElementById("food-popup-overlay").style.display = "block";
}
  // show the close button
  function closeFoodPopup() {
    document.getElementById("food-popup").style.display = "none";
    document.getElementById("food-popup-overlay").style.display = "none";
  }