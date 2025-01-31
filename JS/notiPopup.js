function displayMessage(message, type = "info") {
  // Get the userHome and header-banner elements and add the blurred class
  const userHome = document.querySelector(".userHome");
  const headerBanner = document.querySelector(".header-banner");

  if (userHome) {
      userHome.classList.add("blurred");
  }

  if (headerBanner) {
      headerBanner.classList.add("blurred");
  }

  // Create the notification container if it doesn't already exist
  let container = document.querySelector(".notification-container");
  if (!container) {
      container = document.createElement("div");
      container.className = "notification-container";
      document.body.appendChild(container);
  }

  // Create the notification element with the correct class based on the type
  const notification = document.createElement("div");
  notification.className = `notification ${type}`; // Fixed the class name here
  notification.innerHTML = `
    <span class="message">${message}</span>
    <button class="close-btn" onclick="closeNotification(this);">&times;</button>
  `;

  // Append the notification to the container
  container.appendChild(notification);

  // Automatically remove the notification after 5 seconds
  setTimeout(() => {
      notification.style.opacity = "0"; // Fade out the notification
      container.style.opacity = "0"; // Fade out the container as well
      setTimeout(() => {
          notification.remove(); // Remove the notification after fade-out
          if (container.children.length === 0) {
              container.remove(); // Remove the container when there are no more notifications
          }
      }, 300); // Delay to match the fade-out duration
  }, 5000);

  // Remove the blurred effect from userHome and header-banner after notification disappears
  setTimeout(() => {
      if (userHome) {
          userHome.classList.remove("blurred");
      }
      if (headerBanner) {
          headerBanner.classList.remove("blurred");
      }
  }, 5500); // Remove blur slightly after the notification disappears
}

// Function to close notification manually
function closeNotification(button) {
  const notification = button.parentElement;
  notification.style.opacity = "0"; // Fade-out effect
  const container = document.querySelector(".notification-container");
  container.style.opacity = "0"; // Fade-out the container as well

  setTimeout(() => {
      notification.remove(); // Remove notification after fade-out
      if (container.children.length === 0) {
          container.remove(); // Remove the container when there are no more notifications
      }
  }, 300);

  // Remove blur effect from userHome and header-banner if they exist
  const userHome = document.querySelector(".userHome");
  const headerBanner = document.querySelector(".header-banner");

  if (userHome) {
      userHome.classList.remove("blurred");
  }
  if (headerBanner) {
      headerBanner.classList.remove("blurred");
  }
}
