PopulateList();

// Will populate list on load
function PopulateList()
{

  // Create a request variable and assign a new XMLHttpRequest object to it.
  var xhr = new XMLHttpRequest();
  var url = "api/getAllContact.php";

  // Sending and receiving data in JSON format using POST method
  xhr.open("POST", url, true);
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
          var json = JSON.parse(xhr.responseText);
          if(json.status != 0)
            {
                alert(json.message);
                return;
            }
          if(json.contacts)
          {
            var contacts = json.contacts;
            contacts.forEach( function (obj)
            {
              CreateAccordion(obj.contact_id, obj.name, obj.phone,
                obj.address, obj.email, obj.website);
            }
            );
          }
          else
          {
            alert(json.message);
          }
      }
      else {
        console.log('error')
      }
  };
  var data = JSON.stringify({"user_id": readCookie("user_id")});
  xhr.send(data);
}

function AddContact()
{
  
  // Create a request variable and assign a new XMLHttpRequest object to it.
  var xhr = new XMLHttpRequest();
  var url = "api/createContact.php";

  var name = document.getElementById("a-name").value;
  var number = document.getElementById("a-number").value;
  var address = document.getElementById("a-address").value;
  var email = document.getElementById("a-email").value;
  var website = document.getElementById("a-website").value;

  // Sending and receiving data in JSON format using POST method
  xhr.open("POST", url, true);
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.onload = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
          var json = JSON.parse(xhr.responseText);
          console.log(json.message);
          if(json.status != 0)
            {
                alert(json.message);
                return;
            }
          location.replace("contact-page.html");
      }
      else {
        alert("ERROR");
      }
  };
  var data = JSON.stringify({"user_id": readCookie("user_id"),
   "name": name,"phone": number, "address": address,"email": email, "website": website});
  xhr.send(data);
}

function EditContact()
{
  
  // Create a request variable and assign a new XMLHttpRequest object to it.
  var xhr = new XMLHttpRequest();
  var url = "api/createContact.php";

  var name = document.getElementById("a-name").value;
  var number = document.getElementById("a-number").value;
  var address = document.getElementById("a-address").value;
  var email = document.getElementById("a-email").value;
  var website = document.getElementById("a-website").value;

  // Sending and receiving data in JSON format using POST method
  xhr.open("POST", url, true);
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.onload = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
          var json = JSON.parse(xhr.responseText);
          console.log(json.message);
          if(json.status != 0)
            {
                alert(json.message);
                return;
            }
          location.replace("contact-page.html");
      }
      else {
        alert("ERROR");
      }
  };
  var data = JSON.stringify({"user_id": readCookie("user_id"),
   "name": name,"phone": number, "address": address,"email": email, "website": website});
  xhr.send(data);
}

function DeleteContact()
{
  var idContainer = document.getElementById("myLI").parentElement;
  console.log(idContainer.nodeName);

  // Create a request variable and assign a new XMLHttpRequest object to it.
  var xhr = new XMLHttpRequest();
  var url = "api/deleteContact.php";

  var name = document.getElementById("a-name").value;
  var number = document.getElementById("a-number").value;
  var address = document.getElementById("a-address").value;
  var email = document.getElementById("a-email").value;
  var website = document.getElementById("a-website").value;

  // Sending and receiving data in JSON format using POST method
  xhr.open("POST", url, true);
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.onload = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
          var json = JSON.parse(xhr.responseText);
          console.log(json.message);
          if(json.status != 0)
            {
                alert(json.message);
                return;
            }
          location.replace("contact-page.html");
      }
      else {
        alert("ERROR");
      }
  };
  var data = JSON.stringify({"user_id": readCookie("user_id"),
   "name": name,"phone": number, "address": address,"email": email, "website": website});
  xhr.send(data);
}

// Will populate list on search
function SearchPopulateList()
{

  var user_id = readCookie("user_id");
  var search = document.getElementById("search-input").value;

  // Create a request variable and assign a new XMLHttpRequest object to it.
  var xhr = new XMLHttpRequest();
  var url = "api/searchContact.php";

  // Sending and receiving data in JSON format using POST method
  xhr.open("POST", url, true);
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.onload = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
          ClearAccordions();
          var json = JSON.parse(xhr.responseText);
          if(json.status != 0)
            {
                alert(json.message);
                return;
            }
            if(json.search_results)
            {
              var contacts = json.search_results;
              contacts.forEach( function (obj)
              {
                CreateAccordion(obj.contact_id, obj.name, obj.phone,
                  obj.address, obj.email, obj.website);
              }
              );
            }
      }
      else {
        console.log('error')
      }
  };
  var data = JSON.stringify({"user_id": user_id, "search": search});
  xhr.send(data);
}

function ClearAccordions()
{
  var mainContainer = document.getElementById("main-container");

  //e.firstElementChild can be used. 
  var child = mainContainer.lastElementChild;  
  while (child) { 
      mainContainer.removeChild(child); 
      child = mainContainer.lastElementChild; 
  }
}

function CreateAccordion(contactId, contactName, contactNumber, contactAddress, contactEmail, contactWebsite)
{
  var mainContainer = document.getElementById("main-container");
  
  var accordion = document.createElement("button");
  accordion.className = "accordion";
  mainContainer.appendChild(accordion);

  var name = document.createElement("span");
  name.className = "contact-name";
  name.id = contactId;
  name.innerHTML = contactName;
  accordion.appendChild(name);

  var contactContainer = document.createElement("div");
  contactContainer.className = "contact-content";
  
  var itemName = document.createElement("p");
  itemName.className = "contact-item";
  itemName.innerHTML = contactName;

  var itemPhone = document.createElement("p");
  itemPhone.className = "contact-item";
  itemPhone.innerHTML = contactNumber;

  var itemAddress = document.createElement("p");
  itemAddress.className = "contact-item";
  itemAddress.innerHTML = contactAddress;

  var itemEmail = document.createElement("p");
  itemEmail.className = "contact-item";
  itemEmail.innerHTML = contactEmail;

  var itemWebsite = document.createElement("p");
  itemWebsite.className = "contact-item";
  itemWebsite.innerHTML = contactWebsite;

  contactContainer.append(itemName);
  contactContainer.append(itemPhone);
  contactContainer.append(itemAddress);
  contactContainer.append(itemEmail);
  contactContainer.append(itemWebsite);
  mainContainer.append(contactContainer);

  var editButton = document.createElement("button");
  editButton.onclick = EditModal;
  editButton.className = "contact-item edit-button";
  editButton.innerHTML = "Edit";
  contactContainer.appendChild(editButton);

  var editModal = document.createElement("div");
  editModal.className = "editModal modal";
  contactContainer.appendChild(editModal);

  var modalContent = document.createElement("div");
  modalContent.className = "modal-content";
  editModal.appendChild(modalContent);

  var closeModal = document.createElement("span");
  closeModal.className = "closeEModal close";
  closeModal.innerHTML = "&times;";
  modalContent.appendChild(closeModal);

  var formContainer = document.createElement("div");
  formContainer .className = "form-container";
  modalContent.appendChild(formContainer);

  var formTitle = document.createElement("span");
  formTitle.className = "form-title";
  formTitle.innerHTML = "Edit Contact Information";
  formContainer.appendChild(formTitle);

  var form = document.createElement("form");
  form.action = "";
  form.className = "contact-edit-form";
  formContainer.appendChild(form);

  var nameContainer = document.createElement("div");
  nameContainer.className = "name-input form-item";
  nameContainer.innerHTML =  "Name: ";
  form.appendChild(nameContainer);

  var nameInput = document.createElement("input");
  nameInput.type = "text";
  nameInput.name = "name";
  nameContainer.appendChild(nameInput);

  var phoneContainer = document.createElement("div");
  phoneContainer.className = "phone-input form-item";
  phoneContainer.innerHTML =  "Phone Number: ";
  form.appendChild(phoneContainer);

  var phoneInput = document.createElement("input");
  phoneInput.type = "text";
  phoneInput.name = "name";
  phoneContainer.appendChild(phoneInput);

  var addressContainer = document.createElement("div");
  addressContainer.className = "address-input form-item";
  addressContainer.innerHTML =  "Address: ";
  form.appendChild(addressContainer);

  var addressInput = document.createElement("input");
  addressInput.type = "text";
  addressInput.name = "name";
  addressContainer.appendChild(addressInput);

  var emailContainer = document.createElement("div");
  emailContainer.className = "email-input form-item";
  emailContainer.innerHTML =  "Email: ";
  form.appendChild(emailContainer);

  var emailInput = document.createElement("input");
  emailInput.type = "text";
  emailInput.name = "name";
  emailContainer.appendChild(emailInput);

  var websiteContainer = document.createElement("div");
  websiteContainer.className = "website-input form-item";
  websiteContainer.innerHTML =  "Website: ";
  form.appendChild(websiteContainer);

  var websiteInput = document.createElement("input");
  websiteInput.type = "text";
  websiteInput.name = "name";
  websiteContainer.appendChild(websiteInput);

  var submitButton = document.createElement("input");
  submitButton.className = "submit-button";
  submitButton.type = "submit";
  submitButton.value = "Confirm Changes";
  form.appendChild(submitButton);

  var deleteButton = document.createElement("button");
  deleteButton.onclick = DeleteModal;
  deleteButton.className = "contact-item delete-button";
  deleteButton.innerHTML = "Delete";
  contactContainer.appendChild(deleteButton);

  var deleteModal = document.createElement("div");
  deleteModal.className = "deleteModal modal";
  contactContainer.appendChild(deleteModal);

  var modalContent = document.createElement("div");
  modalContent.className = "modal-content";
  deleteModal.appendChild(modalContent);

  var closeModal = document.createElement("span");
  closeModal.className = "closeDModal close";
  closeModal.innerHTML = "&times;";
  modalContent.appendChild(closeModal);

  var formContainer = document.createElement("div");
  formContainer.className = "form-container delete-form";
  modalContent.appendChild(formContainer);
  
  var formTitle = document.createElement("span");
  formTitle.className = "form-title";
  formTitle.innerHTML = "Delete Contact?";
  formContainer.appendChild(formTitle);

  var form = document.createElement("form");
  form.action = "";
  form.className = "contact-edit-form";
  formContainer.appendChild(form);

  var submitButton = document.createElement("input");
  submitButton.className = "submit-button";
  submitButton.type = "submit";
  submitButton.value = "Delete Contact";
  form.appendChild(submitButton);

  // Add our event listener to our accordion
  accordion.addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight){
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    }
  });
}

function AddModal()
{
    // Get the modal
  var modal = document.getElementById("addModal");

  // Get the <span> element that closes the modal
  var span = document.getElementById("closeAModal");
  
  modal.style.display = "block";

  // When the user clicks on <span> (x), close the modal
  span.onclick = function() {
    modal.style.display = "none";
  }

  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }
}

function EditModal()
{
    // Get the modal
  var modal = document.getElementsByClassName("editModal")[0];

  // Get the <span> element that closes the modal
  var span = document.getElementsByClassName("closeEModal")[0];
  
  modal.style.display = "block";

  // When the user clicks on <span> (x), close the modal
  span.onclick = function() {
    modal.style.display = "none";
  }

  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }
}

function DeleteModal()
{
    // Get the modal
  var modal = document.getElementsByClassName("deleteModal")[0];

  // Get the <span> element that closes the modal
  var span = document.getElementsByClassName("closeDModal")[0];
  
  modal.style.display = "block";

  // When the user clicks on <span> (x), close the modal
  span.onclick = function() {
    modal.style.display = "none";
  }

  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }
}
	
function readCookie(name) {
  let key = name + "=";
  let cookies = document.cookie.split(';');
  for (let i = 0; i < cookies.length; i++) {
    let cookie = cookies[i];
    while (cookie.charAt(0) === ' ') {
            cookie = cookie.substring(1, cookie.length);
        }
    if (cookie.indexOf(key) === 0) {
            return cookie.substring(key.length, cookie.length);
        }
  }
  return null;
}

