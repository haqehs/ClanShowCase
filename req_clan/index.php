<?php
$clanTags = array("#P9LJC89Y", "#8P2QG08P", "#QLVR0LCC","#PG2V0RGR","#L0UJG2J9","#8Q00C02");  // Add more clan tags as needed
$token="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImtpZCI6IjI4YTMxOGY3LTAwMDAtYTFlYi03ZmExLTJjNzQzM2M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z2FtZWFwaSIsImp0aSI6ImNkZmUwZGMwLTJlMzItNDU3MS1hNTJhLThmNTU4MzY4MzJiMyIsImlhdCI6MTcwMzM1NjkyMiwic3ViIjoiZGV2ZWxvcGVyLzIxMjg3OTI3LTJkYTMtMWYwNi03MWM4LTM2ODJhZTc3MGQwZiIsInNjb3BlcyI6WyJjbGFzaCJdLCJsaW1pdHMiOlt7InRpZXIiOiJkZXZlbG9wZXIvc2lsdmVyIiwidHlwZSI6InRocm90dGxpbmcifSx7ImNpZHJzIjpbIjQ5LjM3LjcyLjI0Il0sInR5cGUiOiJjbGllbnQifV19.NHuSCYuKdrEtii3MAPrAWErpQp6CEbYeOKswe4yZvJDFkMtSedC29md-W1sOtdpuoAda8t96zXHFbkTblZJUsg";

$clansData = array();  // Initialize an array to store clan data

foreach ($clanTags as $index => $clanTag) {
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

    // Initialize totalDonation, totalDonationsReceived
    $totalDonation = 0;
    $totalDonationsReceived = 0;

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
        "ClanNumber" => $index + 1,
        "ClanName" => $data['name'],
        "ClanTag" => $clanTag,
        "Leaders" => $leaders,
        "TotalDonation" => $totalDonation,
        "TotalDonationsReceived" => $totalDonationsReceived,
        "Members" => $normalMembers,
        "BadgeUrl" => $data['badgeUrls']['small'],
        // Add more fields as needed
    );
}

// Sort the clansData array in descending order based on TotalDonation
usort($clansData, function ($a, $b) {
    return $b['TotalDonation'] - $a['TotalDonation'];
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clan Information</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Add Google Fonts (optional) -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(245.59deg, #4d9559 0%, #38703d 28.53%, #133917 75.52%);
            color: #fff;
            font-family: 'Roboto', sans-serif;
        }

        th, td {
            font-weight: bold;
            font-size: 20px;
            text-align: center;
            color: #fff;
            padding: 10px;
        }

        .table {
            background-color: rgba(0, 0, 0, 0.6); /* Adjust the opacity if needed */
        }

        .thead-dark th {
            background-color: transparent !important;
        }

        .crown-icon {
            color: gold;
        }

        /* Alternate row colors */
        tbody tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Hover effect */
        tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="table-responsive">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th class="crown-icon">Rank <i class="fa-solid fa-ranking-star" style="color: #f8e61b;"></i></th>
                        <th>Clan Name</th>
                        <th>Leaders</th>
                        <th>Troops Donated <i class="fas fa-heart"></i></th>
                        <th>Troops Received <i class="fas fa-shield-alt"></i></th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($clansData as $index => $clan): ?>
                        <tr>
                            <td><?= $clan['ClanNumber'] ?></td>
                            <td style="font-size: 18px;">
                                <?php if ($index === 0): ?>
                                    <i class="fas fa-crown crown-icon"></i> <!-- King icon -->
                                <?php endif; ?>
                                <img src='<?= $clan['BadgeUrl'] ?>' alt='Clan Badge' height='50' width='50'>
                                <?= $clan['ClanName'] ?><br>
                                Tag: <?= $clan['ClanTag'] ?>
                            </td>
                            <td><?= implode(", ", $clan['Leaders']) ?></td>
                            
                            <?php
                                // Calculate troops donated and received
                                $troopsDonated = 0;
                                $troopsReceived = 0;
                                foreach ($clan['Members'] as $member) {
                                    $troopsDonated += $member['donations'];
                                    $troopsReceived += $member['donationsReceived'];
                                }
                            ?>
                            
                            <td><?= $troopsDonated ?></td>
                            <td><?= $troopsReceived ?></td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Bootstrap JS (optional) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
