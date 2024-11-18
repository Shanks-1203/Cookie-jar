<?php
    session_start();

    $username = $_SESSION["username"];
?>

<div class="w-full p-[2rem] flex justify-between items-center">
    <div class="flex items-center gap-[2rem]">
        <?php include_once 'search.php'; ?>
        <p class="flex items-center gap-[0.5rem] p-[0.6rem] bg-blue-500 rounded-md text-white cursor-pointer" onclick="document.getElementById('fileInput').click()">Add Files <img src="./Images/plus.png" alt="add" class="w-[1.1rem] invert"></p>
    </div>
    <div class="flex justify-end items-center gap-3">
        <p class="text-lg"><?php echo $username; ?></p>
        <div class="w-[3rem] h-[3rem] rounded-full bg-blue-100">
            <img id="profilePic" src="image-loader.php" alt="profile" class="w-full h-full rounded-full" />
        </div>
    </div>
</div>
