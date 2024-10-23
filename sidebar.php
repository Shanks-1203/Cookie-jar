<div class="h-screen w-[16%] fixed border-r-2 border-[#80808040]">
    <p class="text-2xl p-[2rem] font-medium text-blue-500">CookieJar.</p>
    <?php

        $script_name = basename($_SERVER['PHP_SELF']);

        $options = [
            [
                "name"=> "Dashboard",
                "icon"=> "./Images/dashboard.png",
                "path"=> "index.php"
            ],
            [
                "name"=> "My Files",
                "icon"=> "./Images/file.png",
                "path"=> "files.php"
            ]
        ];

    ?>
    <ul class="flex flex-col gap-2 mt-[2rem]">
        <?php foreach($options as $option) { ?>
            <li class="px-4 transition-all py-3 cursor-pointer flex items-center gap-3 hover:bg-[#80808020] <?php echo $option['path'] == $script_name ? "border-r-4 border-blue-400 bg-blue-100" : "" ?>">
                <?php echo '<img src="'.$option['icon'].'" alt="icon" class="w-[2rem]"/>'?>
                <?php echo $option['name']?>
            </li>
        <?php }?>
    </ul>
</div>



<!-- style="filter: brightness(0) saturate(100%) invert(37%) sepia(77%) saturate(617%) hue-rotate(191deg) brightness(92%) contrast(83%)" -->