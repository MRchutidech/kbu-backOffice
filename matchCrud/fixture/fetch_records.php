<?php
require_once(__DIR__ . "/../../dbConfig.php");

if (isset($_GET['season'])) {
  $selectedSeason = $_GET['season'];

  // Modify your SQL query to fetch records based on the selected season
  $sql = "SELECT m.*, th.acronym AS homeTeam, ta.acronym AS awayTeam
          FROM match_fixture m
          LEFT JOIN match_team mt ON m.match_id = mt.match_id
          LEFT JOIN team th ON mt.team_id = th.team_id
          LEFT JOIN team ta ON mt.team_id = ta.team_id
          WHERE m.season = '$selectedSeason'  -- Add the WHERE condition
          ORDER BY m.match_id";

  $result = mysqli_query($con, $sql);

  if ($result) {
    $combinedRecords = array();
    while ($row = mysqli_fetch_assoc($result)) {
      $match_id = $row['match_id'];
      $title = $row['title'];
      $homeTeam = $row['homeTeam'];
      $homeScore = $row['homeScore'];
      $awayTeam = $row['awayTeam'];
      $awayScore = $row['awayScore'];

      // Combine date and time to form DateTime
      $matchDate = new DateTime($row['matchDate']);
      $matchTime = new DateTime($row['matchTime']);
      $matchDateTime = $matchDate->format('Y-m-d') . ' ' . $matchTime->format('H:i:s');

      $season = $row['season'];
      $status = $row['status'];

      // Check if the match_id already exists in the combinedRecords array
      if (isset($combinedRecords[$match_id])) {
        // Append data to the existing record
        $combinedRecords[$match_id]['awayTeam'] = $awayTeam;
        $combinedRecords[$match_id]['awayScore'] = $awayScore;
      } else {
        // Create a new record in the combinedRecords array
        $combinedRecords[$match_id] = array(
          'match_id' => $match_id,
          'title' => $title,
          'homeTeam' => $homeTeam,
          'homeScore' => $homeScore,
          'awayTeam' => $awayTeam,
          'awayScore' => $awayScore,
          'matchDateTime' => $matchDateTime,
          'season' => $season,
          'status' => $status,
        );
      }
    }

    // Display the combined records
    $tableRows = '';
    foreach ($combinedRecords as $record) {
      $tableRows .= '<tr>
        <th scope="row">' . $record['match_id'] . '</th>
        <td>' . $record['title'] . '</td>
        <td>' . $record['homeTeam'] . '</td>
        <td>' . $record['homeScore'] . '</td>
        <td>' . $record['awayTeam'] . '</td>
        <td>' . $record['awayScore'] . '</td>
        <td>' . $record['matchDateTime'] . '</td>
        <td>' . $record['season'] . '</td>
        <td>' . $record['status'] . '</td>
        <td>
          <button class="btn btn-primary btn-sm mr-1"><a href="updateMatch.php?updateid=' . $record['match_id'] . '" class="text-light">Update</a></button>
        </td>
      </tr>';
    }

    // Output the tableRows as a response to the AJAX request
    echo $tableRows;
  }
} else {
  // Handle the case where no season is selected (initial load)
  // You can modify this part based on your requirements
  echo '<tr><td colspan="10">Please select a season from the dropdown.</td></tr>';
}
?>

<!-- Add DataTables initialization script here -->

