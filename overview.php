<?php

    session_start();
    require 'vendor/autoload.php';
    use GuzzleHttp\Client;

    $types = [
        [
            "name"=> "Documents",
            "icon"=> "./Images/document.png",
            "color"=> "yellow",
            "type"=>"document"
        ],
        [
            "name"=> "Images",
            "icon"=> "./Images/image.png",
            "color"=> "blue",
            "type"=>"image"
        ],
        [
            "name"=> "Videos",
            "icon"=> "./Images/video.png",
            "color"=> "red",
            "type"=>"video"
        ],
        [
            "name"=> "Music",
            "icon"=> "./Images/music.png",
            "color"=> "violet",
            "type"=>"audio"
        ],
        [
            "name"=> "Others",
            "icon"=> "./Images/file.png",
            "color"=> "green",
            "type"=>"other"
        ]
    ];

    $client = new Client();
    $response = $client->get('http://localhost/Cookie%20Jar/api/overview-data.php', [
        'headers' => [
            'Authorization' => 'Bearer ' . $_SESSION['access_token'],
        ],
    ]);

    $data = json_decode($response->getBody(), true);
?>

<div class="w-[25%] fixed h-screen border-l-2 border-[#80808020]">
    <div class="p-[2rem] flex justify-end items-center gap-3">
        <p class="text-lg"><?php echo $_SESSION['username']; ?></p>
        <div class="w-[3rem] h-[3rem] rounded-full bg-blue-100">
            <img id="profilePic" src="image-loader.php" alt="profile" class="w-full h-full rounded-full" />
        </div>
    </div>
    <div class="mt-[5rem] w-[85%] rounded-full h-[1rem] bg-[#80808050] mx-auto flex overflow-hidden">
        <?php foreach ($types as $type) { ?>
            <div class="w-[<?php echo (round($data[$type['type'].'_size']) / 1000) * 100; ?>%] h-full bg-<?php echo $type['color']; ?>-300"></div>
        <?php }?>
    </div>
    <p class="text-center text-2xl mt-6 font-bold text-blue-500">
        <?php
            $storageUsed = round($data['storage_used']);
            echo $storageUsed === 1000 ? '1' : $storageUsed;
        ?>
        <?php
            echo $storageUsed === 1000 ? 'GB' : 'MB';
        ?> / 1 GB
    </p>
    
    <div class="flex flex-col gap-8 mt-[4rem]">
        <?php foreach ($types as $type) { ?>
            <div class="w-[80%] mx-auto flex items-center gap-4">
                <div class='p-4 rounded-md bg-<?php echo $type['color']; ?>-100'>
                    <?php echo '<img src="'. $type['icon'] .'" alt="icon" class="w-[2rem]"/>'?>
                </div>
                <div>
                    <p class="font-medium"><?php echo $type['name']?></p>
                    <p class="text-sm opacity-60"><?php echo $data[$type['type']]?> Files</p>
                </div>
                <p class="ml-auto font-medium text-blue-500"><?php echo round($data[$type['type'].'_size'])?> MB</p>
            </div>
        <?php }?>
    </div>
    
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const profilePic = document.getElementById("profilePic");

    fetch("image-loader.php", { method: "HEAD" })
        .then((response) => {
            if (response.headers.get("X-Fallback-Image") === "true") {
                profilePic.classList.add("border-2");
            }
        })
        .catch((error) => {
            console.error("Error fetching profile picture:", error);
        });
});
</script>
