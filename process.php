<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Set content type to HTML
    header("Content-Type: text/html");

    // Start HTML output
    echo "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><title>Assignment8 - Wataru Okada</title></head><body>";

    $mac_address = $_POST["mac_address1"] . ":" . $_POST["mac_address2"] . ":" . $_POST["mac_address3"] . ":" . $_POST["mac_address4"] . ":" . $_POST["mac_address5"] . ":" . $_POST["mac_address6"];
    $dhcp_version = $_POST["dhcp_version"];

    // Validate MAC address format (basic check)
    if (!preg_match('/^([0-9A-Fa-f]{2}:){5}([0-9A-Fa-f]{2})$/i', $mac_address)) {
        die(json_encode(["error" => "Invalid MAC address format."]));
    }

    // Prepare data for Python script
    $data = json_encode(["mac_address" => $mac_address, "dhcp_version" => $dhcp_version]);

    // Call Python script and get response
    $command = "python3 network_config.py " . escapeshellarg($data);
    $output = shell_exec($command);

    // Decode JSON response from Python
    $response = json_decode($output, true);

    // Display response in HTML
    if (isset($response["error"])) {
        echo "<h1>Error</h1>";
        echo "<p>Error: " . htmlspecialchars($response["error"]) . "</p>";
    } else {
        echo "<h1>Assignment8 - Wataru Okada</h1>";
        echo "<h2>Assigned IP and Lease Info</h2>";
        echo "<p><strong>MAC Address:</strong> " . htmlspecialchars($response["mac_address"]) . "</p>";
        if (isset($response["assigned_ipv4"])) {
            echo "<p><strong>Assigned IPv4:</strong> " . htmlspecialchars($response["assigned_ipv4"]) . "</p>";
        }
        if (isset($response["assigned_ipv6"])) {
            echo "<p><strong>Assigned IPv6:</strong> " . htmlspecialchars($response["assigned_ipv6"]) . "</p>";
        }
        echo "<p><strong>Lease Time:</strong> " . htmlspecialchars($response["lease_time"]) . "</p>";
        echo "<p><strong>Subnet:</strong> " . htmlspecialchars($response["subnet"]) . "</p>";
    }
    echo "<a href=/form.php>Back to Form</a></body></html>";
}
?>