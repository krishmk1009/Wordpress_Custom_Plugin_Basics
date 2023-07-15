<?php
global $wpdb, $table_prefix;
$wp_emp = $table_prefix . "emp";

// Check if search query parameter is provided
if (isset($_GET['search_name'])) {
    $search_name = $_GET['search_name'];

    // Modify your query to include the search condition
    $q = "SELECT * FROM `$wp_emp` WHERE Name = '$search_name'";
    $results = $wpdb->get_results($q);
} else {
    // If search query parameter is not provided, retrieve all results
    $q = "SELECT * FROM `$wp_emp`";
    $results = $wpdb->get_results($q);
}
print_r($results);
// Display the form and results
ob_start();
?>
<form method="GET" action="">
    <label for="search_name">Search by Name:</label>
    <input type="text" name="search_name" id="search_name">
    <input type="submit" value="Search">
</form>

<table>
    <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Age</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($results as $result) : ?>
            <tr>
                <td><?php echo $result->Id; ?></td>
                <td><?php echo $result->Name; ?></td>
                <td><?php echo $result->Age; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php
echo ob_get_clean();
?>
