<!DOCTYPE html>
<html lang="en">
<head>
   
    <title>Task 1 - Registration Form</title>
    
</head>
<body>

    <center>
        <h1 style ="color: blue;">AIUB</h1>
        <h2 style ="color: blue;">Registration Form</h2>
    </center>

    <form>
        <table>
            <tr>
                <td colspan="2"><b>Complete the Registration</b></td>
            </tr>

            <tr>
                <td>Full Name:</td>
                <td><input type="text" placeholder="Enter Full Name" required></td>
            </tr>

            <tr>
                <td>Email:</td> 
                <td><input type="email" placeholder="Enter Email" required></td>
            </tr>

            <tr>
                <td>Password:</td>
                <td><input type="password" placeholder="Enter Password" required></td>
            </tr>

            <tr>
                <td>Gender:</td>
                <td>
                    <input type="radio" name="gender" value="male"> Male
                    <input type="radio" name="gender" value="female"> Female
                    <input type="radio" name="gender" value="other"> Other
                </td>
            </tr>

            <tr>
                <td>Language Know:</td>
                <td>
                    <input type="checkbox" name="lang" value="english"> English
                    <input type="checkbox" name="lang" value="bangla"> Bangla
                    <input type="checkbox" name="lang" value="hindi"> Hindi
                </td>
            </tr>

            <tr>
                <td>Country:</td>
                <td>
                    <select required>
                        <option value="">Select Country</option>
                        <option value="bd">Bangladesh</option>
                        <option value="in">India</option>
                        <option value="pk">Pakistan</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>Date of Birth:</td>
                <td><input type="date" required></td>
            </tr>

            <tr>
                <td colspan="2">
                    <input type="submit" value="Submit">
                </td>
            </tr>
        </table>
    </form>

</body>
</html>
