<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment 8 - Wataru Okada</title>
</head>

<body>
    <h1>Assignment 8 - Wataru Okada</h1>
    <form action="process.php" method="post">
        <label for="mac_address">MAC Address: </label>
        <input type="text" name="mac_address1" id="mac_address1" maxlength="2"> :
        <input type="text" name="mac_address2" id="mac_address2" maxlength="2"> :
        <input type="text" name="mac_address3" id="mac_address3" maxlength="2"> :
        <input type="text" name="mac_address4" id="mac_address4" maxlength="2"> :
        <input type="text" name="mac_address5" id="mac_address5" maxlength="2"> :
        <input type="text" name="mac_address6" id="mac_address6" maxlength="2"><br />
        <label for="dhcp_version">DHCP Version: </label>
        <select name="dhcp_version" id="dhcp_version">
            <option value="dhcpv4">DHCPv4</option>
            <option value="dhcpv6">DHCPv6</option>
        </select><br />
        <input type="submit" value="Get IP Address">
    </form>
</body>

</html>