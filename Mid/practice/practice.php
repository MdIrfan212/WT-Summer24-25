<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registration Form</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(to right, #6a11cb, #2575fc);
      color: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .form-container {
      background-color: #ffffff10;
      backdrop-filter: blur(10px);
      padding: 30px 40px;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.3);
      width: 350px;
    }

    h1 {
      text-align: center;
      margin-bottom: 25px;
      color: #fff;
    }

    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }

    input, select {
      width: 100%;
      padding: 8px 10px;
      margin-top: 5px;
      border-radius: 8px;
      border: none;
      font-size: 16px;
    }

    button {
      width: 100%;
      margin-top: 20px;
      padding: 10px;
      background-color: #ff6a00;
      border: none;
      color: white;
      font-size: 16px;
      font-weight: bold;
      border-radius: 10px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #e65c00;
    }

    .error {
      color: #ffcccb;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h1>Student Registration</h1>
    <form onsubmit="return validateForm()">
      <label>Name:</label>
      <input type="text" id="name" />
      <div id="nameError" class="error"></div>

      <label>Age:</label>
      <input type="number" id="age" />
      <div id="ageError" class="error"></div>

      <label>Student ID:</label>
      <input type="text" id="Sid" />
      <div id="sidError" class="error"></div>

      <label>Department:</label>
      <select id="dept">
        <option value="">--Select Department--</option>
        <option value="CSE">CSE</option>
        <option value="EEE">EEE</option>
        <option value="IPE">IPE</option>
        <option value="BBA">BBA</option>
      </select>
      <div id="deptError" class="error"></div>

      <button type="submit">Submit</button>
    </form>
  </div>

  <script>
    function validateForm() {
      // Get values
      let name = document.getElementById("name").value.trim();
      let age = parseInt(document.getElementById("age").value.trim());
      let sid = document.getElementById("Sid").value.trim();
      let dept = document.getElementById("dept").value;

      // Error elements
      let nameError = document.getElementById("nameError");
      let ageError = document.getElementById("ageError");
      let sidError = document.getElementById("sidError");
      let deptError = document.getElementById("deptError");

      // Reset error messages
      nameError.textContent = "";
      ageError.textContent = "";
      sidError.textContent = "";
      deptError.textContent = "";

      let isValid = true;

      // Validations
      if (name === "") {
        nameError.textContent = "Name is required.";
        isValid = false;
      }

      if (isNaN(age) || age < 10 || age > 100) {
        ageError.textContent = "Enter a valid age between 10 and 100.";
        isValid = false;
      }

      if (!/^[A-Za-z0-9]{4,10}$/.test(sid)) {
        sidError.textContent = "Student ID should be 4-10 characters long, alphanumeric.";
        isValid = false;
      }

      if (dept === "") {
        deptError.textContent = "Please select a department.";
        isValid = false;
      }

      if (isValid) {
        alert(
          "Registration Complete!\n" +
            "Name: " + name + "\n" +
            "Age: " + age + "\n" +
            "Student ID: " + sid + "\n" +
            "Department: " + dept
        );
      }

      return false; // Prevent actual form submission
    }
  </script>
</body>
</html>
