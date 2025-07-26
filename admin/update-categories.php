<?php
require 'vendor/autoload.php';
include('partials/admin-menu.php');

$mongoClient = new MongoDB\Driver\Manager("mongodb://localhost:27017/");

if (isset($_GET['id'])) {
    $id = new MongoDB\BSON\ObjectId($_GET['id']);
    $filter = ['_id' => $id];
    $query = new MongoDB\Driver\Query($filter);
    $rows = $mongoClient->executeQuery('tiffin.categorie', $query);
    $category = current($rows->toArray());

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $title = $_POST['title'];
    //    $featured = $_POST['featured'];
     //   $active = $_POST['active'];

        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $image = $_FILES['image'];
            $imageName = $image['name'];
            $imageType = $image['type'];
            $imagePath = 'uploads/' . $imageName;
            move_uploaded_file($image['tmp_name'], $imagePath);
        } else {
            $imageName = $category->image->name;
            $imageType = $category->image->type;
            $imagePath = $category->image->path;
        }

        $bulkWrite = new MongoDB\Driver\BulkWrite;
        $bulkWrite->update(
            ['_id' => $id],
            ['$set' => [
                'title' => $title,
                'image' => [
                    'name' => $imageName,
                    'type' => $imageType,
                    'path' => $imagePath
                ],
              //  'featured' => $featured,
                ///'active' => $active,
                'c_id' => $category->c_id // Ensure c_id is updated if needed
            ]]
        );
        $mongoClient->executeBulkWrite('tiffin.categorie', $bulkWrite);

        echo "<script>alert('Category updated successfully!'); window.location.href = 'manage-categories.php';</script>";
    }
}
?>

<div class="main-content">
    <div class="wraper">
        <h3>Update Category</h3>
        <br/><br/>
        <form action="" method="post" enctype="multipart/form-data">
            <table class="tbl-30">
                <tr>
                    <td>Title:</td>
                    <td>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($category->title); ?>" placeholder="Category Title">
                    </td>
                </tr>
                <tr>
                    <td>Current Image:</td>
                    <td>
                        <img src="<?php echo htmlspecialchars($category->image->path); ?>" width="100">
                    </td>
                </tr>
                <tr>
                    <td>New Image:</td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>
               
            </table>
            <br>
            <input type="submit" value="Update Category" class="btn-primary">
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>
