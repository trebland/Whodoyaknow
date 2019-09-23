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
            createCookie("jwt", json.jwt);
            createCookie("expireAt", json.expireAt);
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
  var data = JSON.stringify({"jwt": readCookie("jwt")});
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
            
          createCookie("jwt", json.jwt);
          createCookie("expireAt", json.expireAt);
          location.replace("contact-page.html");
      }
      else {
        alert("ERROR");
      }
  };
  var data = JSON.stringify({"jwt": readCookie("jwt"),
   "name": name,"phone": number, "address": address,"email": email, "website": website});
  xhr.send(data);
}

function EditContact(contactId, name, number, address, email, website)
{
  
  // Create a request variable and assign a new XMLHttpRequest object to it.
  var xhr = new XMLHttpRequest();
  var url = "api/editContact.php";

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
          createCookie("jwt", json.jwt);
          createCookie("expireAt", json.expireAt);
          location.replace("contact-page.html");
      }
      else {
        alert("ERROR");
      }
  };
  
  var data = JSON.stringify({"jwt": readCookie("jwt"), "contact_id": contactId,
   "name": name,"phone": number, "address": address,"email": email, "website": website});
  xhr.send(data);
}

function DeleteContact(contactId)
{
  // Create a request variable and assign a new XMLHttpRequest object to it.
  var xhr = new XMLHttpRequest();
  var url = "api/deleteContact.php";

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
            
          createCookie("jwt", json.jwt);
          createCookie("expireAt", json.expireAt);
          location.replace("contact-page.html");
      }
      else {
        alert("ERROR");
      }
  };
  var data = JSON.stringify({"jwt": readCookie("jwt"), "contact_id": contactId});
  xhr.send(data);
}

// Will populate list on search
function SearchPopulateList()
{

  var jwt = readCookie("jwt");
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
              createCookie("jwt", json.jwt);
              createCookie("expireAt", json.expireAt);
            }

      }
      else {
        console.log('error')
      }
  };
  var data = JSON.stringify({"jwt": readCookie("jwt"), "search": search});
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
  
  contactName = (contactName == "") ? ("N/A") : (contactName);
  contactNumber = (contactNumber == "") ? ("N/A") : (contactNumber);
  contactAddress = (contactAddress == "") ? ("N/A") : (contactAddress);
  contactEmail = (contactEmail == "") ? ("N/A") : (contactEmail);
  contactWebsite = (contactWebsite == "") ? ("N/A") : (contactWebsite);

  var accordion = document.createElement("button");
  accordion.className = "accordion";
  mainContainer.appendChild(accordion);

  var name = document.createElement("span");
  name.className = "contact-name";
  name.innerHTML = contactName;
  accordion.appendChild(name);

  var contactContainer = document.createElement("div");
  contactContainer.className = "contact-content";
  
  var itemName = document.createElement("p");
  itemName.className = "contact-item";
  itemName.innerHTML = "<strong>Name:<br></strong>" + contactName;

  var itemPhone = document.createElement("p");
  itemPhone.className = "contact-item";
  itemPhone.innerHTML = "<strong>Phone:<br></strong>" + contactNumber;

  var itemAddress = document.createElement("p");
  itemAddress.className = "contact-item";
  itemAddress.innerHTML = "<strong>Address:<br></strong>" + contactAddress;

  var itemEmail = document.createElement("p");
  itemEmail.className = "contact-item";
  itemEmail.innerHTML = "<strong>Email:<br></strong>" + contactEmail;

  var itemWebsite = document.createElement("p");
  itemWebsite.className = "contact-item";
  itemWebsite.innerHTML = "<strong>Website:<br></strong>" + contactWebsite;

  contactContainer.append(itemName);
  contactContainer.append(itemPhone);
  contactContainer.append(itemAddress);
  contactContainer.append(itemEmail);
  contactContainer.append(itemWebsite);
  mainContainer.append(contactContainer);

  var editButton = document.createElement("button");
  editButton.onclick = function () { EditModal(contactId) };
  editButton.className = "contact-item edit-button";
  editButton.innerHTML = "Edit";
  contactContainer.appendChild(editButton);

  var editModal = document.createElement("div");
  editModal.className = "editModal modal s" + contactId;
  contactContainer.appendChild(editModal);

  var modalContent = document.createElement("div");
  modalContent.className = "modal-content";
  editModal.appendChild(modalContent);

  var closeModal = document.createElement("span");
  closeModal.className = "closeEModal close s" + contactId;
  closeModal.innerHTML = "&times;";
  modalContent.appendChild(closeModal);

  var formContainer = document.createElement("div");
  formContainer .className = "form-container";
  modalContent.appendChild(formContainer);

  var formTitle = document.createElement("span");
  formTitle.className = "form-title";
  formTitle.innerHTML = "Edit Contact Information";
  formContainer.appendChild(formTitle);

  var nameContainer = document.createElement("div");
  nameContainer.className = "name-input form-item";
  nameContainer.innerHTML =  "Name: ";
  formContainer.appendChild(nameContainer);

  var nameInput = document.createElement("input");
  nameInput.type = "text";
  nameInput.name = "name";
  nameContainer.appendChild(nameInput);

  var phoneContainer = document.createElement("div");
  phoneContainer.className = "phone-input form-item";
  phoneContainer.innerHTML =  "Phone Number: ";
  formContainer.appendChild(phoneContainer);

  var phoneInput = document.createElement("input");
  phoneInput.type = "text";
  phoneInput.name = "name";
  phoneContainer.appendChild(phoneInput);

  var addressContainer = document.createElement("div");
  addressContainer.className = "address-input form-item";
  addressContainer.innerHTML =  "Address: ";
  formContainer.appendChild(addressContainer);

  var addressInput = document.createElement("input");
  addressInput.type = "text";
  addressInput.name = "name";
  addressContainer.appendChild(addressInput);

  var emailContainer = document.createElement("div");
  emailContainer.className = "email-input form-item";
  emailContainer.innerHTML =  "Email: ";
  formContainer.appendChild(emailContainer);

  var emailInput = document.createElement("input");
  emailInput.type = "text";
  emailInput.name = "name";
  emailContainer.appendChild(emailInput);

  var websiteContainer = document.createElement("div");
  websiteContainer.className = "website-input form-item";
  websiteContainer.innerHTML =  "Website: ";
  formContainer.appendChild(websiteContainer);

  var websiteInput = document.createElement("input");
  websiteInput.type = "text";
  websiteInput.name = "name";
  websiteContainer.appendChild(websiteInput);

  var submitButton = document.createElement("button");
  submitButton.className = "submit-button";
  submitButton.type = "submit";
  submitButton.innerHTML = "Confirm Changes";
  submitButton.onclick = function () { EditContact(contactId, nameInput.value, phoneInput.value, addressInput.value, emailInput.value, websiteInput.value) };
  formContainer.appendChild(submitButton);

  var deleteButton = document.createElement("button");
  deleteButton.onclick = function () { DeleteModal(contactId) };
  deleteButton.className = "contact-item delete-button";
  deleteButton.innerHTML = "Delete";
  contactContainer.appendChild(deleteButton);

  var deleteModal = document.createElement("div");
  deleteModal.className = "deleteModal modal s" + contactId;
  contactContainer.appendChild(deleteModal);

  var modalContent = document.createElement("div");
  modalContent.className = "modal-content";
  deleteModal.appendChild(modalContent);

  var closeModal = document.createElement("span");
  closeModal.className = "closeDModal close s" + contactId;
  closeModal.innerHTML = "&times;";
  modalContent.appendChild(closeModal);

  var formContainer = document.createElement("div");
  formContainer.className = "form-container delete-form";
  modalContent.appendChild(formContainer);
  
  var formTitle = document.createElement("span");
  formTitle.className = "form-title";
  formTitle.innerHTML = "Delete Contact?";
  formContainer.appendChild(formTitle);

  var submitButton = document.createElement("button");
  submitButton.className = "submit-button";
  submitButton.type = "submit";
  submitButton.innerHTML = "Delete Contact";
  submitButton.onclick = function () { DeleteContact(contactId) };
  formContainer.appendChild(submitButton);

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

function EditModal(contactId)
{
  // Get the modal
  var modalA = document.querySelectorAll(".editModal.s" + contactId);
  modal = modalA[0];

  // Get the <span> element that closes the modal
  var spanA = document.querySelectorAll(".closeEModal.s" + contactId);
  span = spanA[0];

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

function DeleteModal(contactId)
{
  // Get the modal
  var modalA = document.querySelectorAll(".deleteModal.s" + contactId);
  modal = modalA[0];

  // Get the <span> element that closes the modal
  var spanA = document.querySelectorAll(".closeDModal.s" + contactId);
  span = spanA[0];
  
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

function createCookie(key, value) {
  let cookie = escape(key) + "=" + escape(value) + ";";
  document.cookie = cookie;
  console.log(cookie);
  console.log("Creating new cookie with key: " + key + " value: " + value);
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

