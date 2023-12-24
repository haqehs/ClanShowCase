<?php
$clanTags = array("#P9LJC89Y", "#8P2QG08P", "#QLVR0LCC");  // Add more clan tags as needed
$token="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImtpZCI6IjI4YTMxOGY3LTAwMDAtYTFlYi03ZmExLTJjNzQzM2M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z2FtZWFwaSIsImp0aSI6ImNkZmUwZGMwLTJlMzItNDU3MS1hNTJhLThmNTU4MzY4MzJiMyIsImlhdCI6MTcwMzM1NjkyMiwic3ViIjoiZGV2ZWxvcGVyLzIxMjg3OTI3LTJkYTMtMWYwNi03MWM4LTM2ODJhZTc3MGQwZiIsInNjb3BlcyI6WyJjbGFzaCJdLCJsaW1pdHMiOlt7InRpZXIiOiJkZXZlbG9wZXIvc2lsdmVyIiwidHlwZSI6InRocm90dGxpbmcifSx7ImNpZHJzIjpbIjQ5LjM3LjcyLjI0Il0sInR5cGUiOiJjbGllbnQifV19.NHuSCYuKdrEtii3MAPrAWErpQp6CEbYeOKswe4yZvJDFkMtSedC29md-W1sOtdpuoAda8t96zXHFbkTblZJUsg";

$clansData = array();  // Initialize an array to store clan data

foreach ($clanTags as $clanTag) {
    $url = "https://api.clashofclans.com/v1/clans/" . urlencode($clanTag);
    $ch = curl_init($url);

    $headr = array();
    $headr[] = "Accept: application/json";
    $headr[] = "Authorization: Bearer ".$token;

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headr);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $res = curl_exec($ch);
    $data = json_decode($res, true);

    curl_close($ch);

    if (isset($data["reason"])) {
        echo "Error: ".$data["reason"];
        continue;  // Skip to the next clan if there's an error
    }

    $members = $data["memberList"];

    // Initialize totalDonation, totalDonationsReceived, and badgeUrl
    $totalDonation = 0;
    $totalDonationsReceived = 0;
    $badgeUrl = $data["badgeUrls"]["small"];

    // Initialize arrays for leaders and members
    $leaders = array();
    $normalMembers = array();

    // Separate leaders and normal members based on the role
    foreach ($members as $member) {
        if ($member['role'] === 'leader') {
            $leaders[] = $member['name'];
        } else {
            $normalMembers[] = $member;
        }

        $totalDonation += $member['donations'];
        $totalDonationsReceived += $member['donationsReceived'];
    }

    // Store the clan data in the array
    $clansData[] = array(
        "ClanName" => $data["name"],
        "TotalDonation" => $totalDonation,
        "TotalDonationsReceived" => $totalDonationsReceived,
        "BadgeUrl" => $badgeUrl,
        "Leaders" => $leaders,
        // Add more fields as needed
    );
}

// Print the table header
echo "<table border='1'>";
echo "<tr><th>Clan Name</th><th>Total Donation</th><th>Total Donations Received</th><th>Badge</th><th>Leaders</th></tr>";

// Print the data in the table rows
foreach ($clansData as $clan) {
    echo "<tr>";
    echo "<td>{$clan['ClanName']}</td>";
    echo "<td>{$clan['TotalDonation']}</td>";
    echo "<td>{$clan['TotalDonationsReceived']}</td>";
    echo "<td><img src='{$clan['BadgeUrl']}' alt='Clan Badge' height='50' width='50'></td>";
    echo "<td>" . implode(", ", $clan['Leaders']) . "</td>";
    echo "</tr>";
}

// Print the table closing tag
echo "</table>";
?>
