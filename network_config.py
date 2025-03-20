import sys
import json
import re

# Predefined IPv4 and IPv6 subnets
DHCPv4_SUBNET = "192.168.10."
DHCPv6_SUBNET = "2001:db8::"

# Lease database (MAC -> Assigned IP)
lease_db = {}

def validate_mac(mac_address):
    """Validate MAC address format."""
    return re.match(r"^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$", mac_address) is not None

def generate_ipv4():
    """Generate a dynamic IPv4 address."""
    for i in range(100, 200):  # IP range 192.168.10.100 - 192.168.10.199
        ip = f"{DHCPv4_SUBNET}{i}"
        if ip not in lease_db.values():
            return ip
    return None  # No available IPs

def generate_ipv6(mac_address):
    """Generate an EUI-64 IPv6 address."""
    mac = mac_address.replace(":", "").replace("-", "")  # Remove separators
    mac = mac[:6] + "fffe" + mac[6:]  # Insert FFFE in the middle
    ipv6_address = f"{DHCPv6_SUBNET}{mac[:4]}:{mac[4:8]}:{mac[8:12]}"
    return ipv6_address

def assign_ip(mac_address, dhcp_version):
    """Assign an IP address based on DHCP version."""
    if mac_address in lease_db:
        assigned_ip = lease_db[mac_address]  # Reuse existing lease
        
    else:
        if dhcp_version == "dhcpv4":
            assigned_ip = generate_ipv4()
            key = "assigned_ipv4"
        elif dhcp_version == "dhcpv6":
            assigned_ip = generate_ipv6(mac_address)
            key = "assigned_ipv6"
        else:
            return {"error": "Invalid DHCP version."}

        if assigned_ip is None:
            return {"error": "No available IP addresses."}

        lease_db[mac_address] = assigned_ip

    return {
        "mac_address": mac_address,
        key: assigned_ip,
        "lease_time": "3600 seconds" if dhcp_version == "dhcpv4" else "N/A",
        "subnet": DHCPv4_SUBNET + "0/24" if dhcp_version == "dhcpv4" else DHCPv6_SUBNET + "/64"
    }

if __name__ == "__main__":
    input_data = json.loads(sys.argv[1])
    mac_address = input_data.get("mac_address")
    dhcp_version = input_data.get("dhcp_version")

    if not validate_mac(mac_address):
        print(json.dumps({"error": "Invalid MAC address format."}))
        sys.exit(1)

    result = assign_ip(mac_address, dhcp_version)
    print(json.dumps(result))
