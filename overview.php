<?php

        $types = [
            [
                "name"=> "Documents",
                "size"=> "250 MB",
                "number"=> 98,
                "icon"=> "./Images/document.png",
                "color"=> "yellow"
            ],
            [
                "name"=> "Images",
                "size"=> "200 MB",
                "number"=> 100,
                "icon"=> "./Images/image.png",
                "color"=> "blue"

            ],
            [
                "name"=> "Videos",
                "size"=> "100 MB",
                "number"=> 12,
                "icon"=> "./Images/video.png",
                "color"=> "red"
            ],
            [
                "name"=> "Music",
                "size"=> "90 MB",
                "number"=> 52,
                "icon"=> "./Images/music.png",
                "color"=> "violet"
            ],
            [
                "name"=> "Others",
                "size"=> "10 MB",
                "number"=> 1,
                "icon"=> "./Images/file.png",
                "color"=> "green"
            ]
        ];
    ?>

<div class="w-[25%] fixed h-screen border-l-2 border-[#80808020]">
    <div class="p-[2rem] flex justify-end items-center gap-3">
        <p class="text-lg">Shanks</p>
        <div class="w-[3rem] h-[3rem] rounded-full bg-blue-300"></div>
    </div>
    <div class="mt-[5rem] w-[85%] rounded-full h-[1rem] bg-[#80808050] mx-auto flex">
        <div class="w-[25%] h-full bg-yellow-300 rounded-l-full"></div>
        <div class="w-[20%] h-full bg-blue-400"></div>
        <div class="w-[10%] h-full bg-red-400"></div>
        <div class="w-[9%] h-full bg-violet-400"></div>
        <div class="w-[1%] h-full bg-green-400"></div>

    </div>
    <p class="text-center text-2xl mt-6 font-bold text-blue-500">650 MB / 1 GB</p>

    
    <div class="flex flex-col gap-8 mt-[4rem]">
        <?php foreach ($types as $type) { ?>
            <div class="w-[80%] mx-auto flex items-center gap-4">
                <div class='p-4 rounded-md bg-<?php echo $type['color']; ?>-100'>
                    <?php echo '<img src="'. $type['icon'] .'" alt="icon" class="w-[2rem]"/>'?>
                </div>
                <div>
                    <p class="font-medium"><?php echo $type['name']?></p>
                    <p class="text-sm opacity-60"><?php echo $type['number']?> Files</p>
                </div>
                <p class="ml-auto font-medium text-blue-500"><?php echo $type['size']?></p>
            </div>
        <?php }?>
    </div>
    
</div>