<?php

$files = [
    [
        "id"=> "1",
        "name"=> "Call Recording.mp3",
        "date"=> "Oct 22, 11:42 AM",
        "type"=> "audio"
    ],
    [
        "id"=> "2",
        "name"=> "Group-pic.jpg",
        "date"=> "Oct 22, 11:42 AM",
        "type"=> "image"
    ],
    [
        "id"=> "3",
        "name"=> "Image23322.jpg",
        "date"=> "Oct 21, 7:03 PM",
        "type"=> "image"
    ],
    [
        "id"=> "4",
        "name"=> "Instructions.pdf",
        "date"=> "Oct 20, 1:22 PM",
        "type"=> "document"
    ],
    [
        "id"=> "5",
        "name"=> "Intro.mp4",
        "date"=> "Oct 18, 12:56 PM",
        "type"=> "video"
    ]
];

function iconSelect($type) {
    switch ($type) {
        case "audio": return './Images/music.png';
        case "video": return './Images/video.png';
        case "image": return './Images/image.png';
        case "document": return './Images/document.png';
        default: return null;
    }
}

?>

<div class="w-[59%] min-h-screen p-[2rem]">
    <?php include('search.php') ?>

    <p class="mt-[3rem] font-medium text-xl flex items-center justify-between">Recently Added <span class="text-blue-500 hover:underline text-sm cursor-pointer">View all</span></p>
    <div class="w-full flex items-center overflow-x-auto recents gap-12 mt-6">
        <?php foreach ($files as $file) { ?>
            <div class="cursor-pointer">
                <div class="w-[13rem] h-[13rem] grid place-items-center bg-[#80808020]">
                    <img src="<?php echo iconSelect($file['type']); ?>" alt="icon" class="w-[3rem]">
                </div>
                <div class="mt-3">
                    <p class="font-medium"><?php echo $file['name']?></p>
                    <p class="opacity-65 text-sm mt-1">Added on <?php echo $file['date']?></p>
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="mt-[3rem] w-full h-[15rem] border-2 border-blue-300 border-dashed rounded-md bg-blue-50 flex flex-col justify-center items-center gap-2">
            <img src="./Images/upload.png" alt="upload" class="w-[3rem] opacity-50">
            <p class="text-[#00000065]">Drag and drop files or <span class="text-blue-500 font-medium cursor-pointer hover:underline">Browse.</span></p>
    </div>
</div>
