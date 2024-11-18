<?php
    session_start();
    require_once 'dbconn.php';
    require_once 'authMiddleware.php';

    $searchMode = false;
    $searchResult = [];
    $userId = authenticate($_SESSION['access_token']);

    if(isset($_GET["query"])){
        $searchMode = true;
        $searchQuery = $_GET["query"];

        $query = "select * from `uploads` where `upload_name` like '%$searchQuery%' and `user_id`='$userId'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $searchResult[] = $row;
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cookie Jar-A Cloud Storage Solution</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="more-option-style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="w-full flex justify-between">
        <div class="w-[16%] relative"><?php include_once 'sidebar.php';?></div>
        <?php
            if($searchMode){?>
                <?php include_once 'search-result-page.php';?>
            <?php
            } else {
            ?>
                <?php include_once 'dashboard.php';?>
                <div class="w-[25%] relative"><?php include_once 'overview.php';?></div>
            <?php
            }
        ?>
    </div>

    <div id="delete-popup" class="w-[25rem] h-[13rem] fixed top-[50%] left-[50%] transition-all translate-x-[-50%] translate-y-[-50%] bg-blue-100 rounded-md grid place-items-center py-[1rem] scale-0">
        <p class="text-blue-500">Are you sure you want to delete the file?</p>
        <div class="flex items-center gap-[3rem]">
            <p class="py-[0.7rem] px-[2rem] rounded-md bg-blue-500 text-white cursor-pointer" onclick="deleteRecord(setFileId, setFileName, '<?php echo $_SESSION['access_token'];?>', setFileSize, setFileType)">Yes</p>
            <p class="py-[0.7rem] px-[2rem] rounded-md bg-red-500 text-white cursor-pointer" onclick="closeDelete()">No</p>
        </div>
    </div>

    <script>
        let setFileId;
        let setFileName;
        let setFileSize;
        let setFileType;

        const closeDelete = () => {
            document.getElementById('delete-popup').classList.add('scale-0');

            setFileId = null;
            setFileName = null;
            setFileSize = null;
            setFileType = null;
        }
    </script>
    <script src="./scripts/file-delete.js"></script>
</body>
</html>
