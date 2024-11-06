const randomNumberElement = document.getElementById("randomNumber");
const generateButton = document.getElementById("generateButton");

function generateRandomNumber() {
    const randomNumber = Math.floor(Math.random() * 100) + 1; // Generate random number between 1 and 100
    randomNumberElement.textContent = randomNumber;
}

generateButton.addEventListener("click", generateRandomNumber);
