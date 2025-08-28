// Get modal element
var modal = document.getElementById("myModal");

// Get open modal button
var btn = document.getElementById("openModalBtn");

// Get close button
var closeBtn = document.getElementsByClassName("close")[0];

// Get form and input container
var form = document.getElementById("categoryForm");
var inputContainer = document.getElementById("inputContainer");

// Open modal when button is clicked
btn.onclick = function() {
    modal.style.display = "flex";
}

// Close modal when close button is clicked
closeBtn.onclick = function() {
    modal.style.display = "none";
}

// Close modal when user clicks outside of the modal
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// Add new input field when the + button is clicked
document.getElementById("addInputBtn").onclick = function() {
    var inputCount = inputContainer.getElementsByClassName("input-group").length;
    if (inputCount < 5) {
        var newInputNumber = inputCount + 1;

        var newInputGroup = document.createElement("div");
        newInputGroup.className = "input-group";

        var newLabel = document.createElement("label");
        newLabel.htmlFor = "name" + newInputNumber;
        newLabel.textContent = "Kategori " + newInputNumber + ":";

        var newInput = document.createElement("input");
        newInput.type = "text";
        newInput.id = "name" + newInputNumber;
        newInput.name = "name[]";
        newInput.required = true;

        var deleteBtn = document.createElement("button");
        deleteBtn.type = "button";
        deleteBtn.textContent = "Hapus";
        deleteBtn.className = "delete-btn";
        deleteBtn.onclick = function() {
            inputContainer.removeChild(newInputGroup);
        }

        newInputGroup.appendChild(newLabel);
        newInputGroup.appendChild(newInput);
        newInputGroup.appendChild(deleteBtn);

        inputContainer.appendChild(newInputGroup);
    } else {
        alert("Maksimal 5 kategori yang bisa ditambahkan.");
    }
}
