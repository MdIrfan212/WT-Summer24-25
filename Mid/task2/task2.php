<!DOCTYPE html>
<html lang="en">
<head>
 
  <title>Bank Registration</title>

  <style>
    body {
      background-color: lightblue;
      font-family: Arial, Helvetica, sans-serif;
    }

    h1, h3 {
      color: darkblue;
      font-family: 'Times New Roman', Times, serif;
      text-align: center;
    }

    table {
      background-color: white;
      margin: 0 left;
      border: 1px solid black;
      padding: 10px;
    }

    div {
      background-color: lightblue;
      width: 190px;
      height: 60px;
      border: 1px solid red;
      overflow: scroll;
      margin-top: 10px;
    }
  </style>
</head>

<body>
  <h1>Bank Management System</h1>
  <h3>Your Trusted Financial Partner</h3>

  <p style="text-align:left;"><b>Customer Registration Form</b></p>

  <form>
    <table>
      <tr>
        <td>Full Name:</td>
        <td><input type="text" placeholder="Enter Full Name"></td>
      </tr>

      <tr>
        <td>Date of Birth:</td>
        <td><input type="date"></td>
      </tr>

      <tr>
        <td>Gender:</td>
        <td>
          <input type="radio" name="gender">Male
          <input type="radio" name="gender">Female
          <input type="radio" name="gender">Other
        </td>
      </tr>

      <tr>
        <td>Marital Status:</td>
        <td>
          <select>
            <option>Single</option>
            <option>Married</option>
          </select>
        </td>
      </tr>

      <tr>
        <td>Account Type:</td>
        <td>
          <select>
            <option>Savings</option>
            <option>Fixed</option>
          </select>
        </td>
      </tr>

      <tr>
        <td>Initial Deposit Amount:</td>
        <td><input type="text" placeholder="Enter amount"></td>
      </tr>

      <tr>
        <td>Mobile Number:</td>
        <td><input type="text" placeholder="Enter mobile no"></td>
      </tr>

      <tr>
        <td>Email Address:</td>
        <td><input type="email" placeholder="Enter email"></td>
      </tr>

      <tr>
        <td>Address:</td>
        <td><textarea placeholder="Enter address"></textarea></td>
      </tr>

      <tr>
        <td>Occupation:</td>
        <td><input type="text"></td>
      </tr>

      <tr>
        <td>National ID (NID):</td>
        <td><input type="text"></td>
      </tr>

      <tr>
        <td>Set Password:</td>
        <td><input type="password" placeholder="Set password"></td>
      </tr>

      <tr>
        <td>Upload ID Proof:</td>
        <td><input type="file"></td>
      </tr>

      <tr>
        <td colspan="2">
          <input type="checkbox"> I agree to terms and conditions
        </td>
      </tr>

      <tr>
        <td colspan="2">
          <input type="submit" value="Register">
          <input type="reset" value="Clear">
        </td>
      </tr>
    </table>
  </form>

 
    <div>
      This is a demo text to show the overflow works in a small container with a lot of content...
    </div>
 
</body>
</html>