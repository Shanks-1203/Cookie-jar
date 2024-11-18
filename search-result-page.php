<?php
    function iconSelect($type) {
        switch ($type) {
            case "audio": return './Images/music.png';
            case "video": return './Images/video.png';
            case "image": return './Images/image.png';
            case "document": return './Images/document.png';
            default: return './Images/file.png';
        }
    }
?>

<div class="w-[84%] min-h-screen p-[2rem]">
    <div><?php include_once 'search.php'?></div>
    <p class="my-[2rem] font-medium text-2xl">Search Result for: <span class="text-blue-500"><?php echo $searchQuery;?></span></p>
    <?php if(!empty($searchResult)){
        foreach($searchResult as $index => $file){ ?>
            <div class="cursor-pointer w-[13rem]">
                <div class="w-[13rem] h-[13rem] grid place-items-center bg-[#80808020] relative overflow-hidden" onmouseenter="onHover(<?php echo $index; ?>)" onmouseleave="onLeave(<?php echo $index; ?>)">
                    <img src="<?php echo iconSelect($file['type']); ?>" alt="icon" class="w-[3rem]">
                    <div id="more-icon<?php echo $index;?>" class="transition-all top-[-1.3rem] right-[-5rem] closed-more" onclick="openMore(<?php echo $index; ?>)" onmouseleave="closeMore(<?php echo $index; ?>)">
                        <img id="more-img<?php echo $index;?>" src="./Images/more.png" alt="more" class="w-[1.2rem] invert">
                        <ul id="open-options<?php echo $index;?>" class="text-lg text-center flex flex-col items-center gap-4 hidden">
                            <li class="text-white bg-blue-500 px-[1rem] py-[0.5rem] w-[8rem] rounded-md" onclick="fetchDownloadUrl(<?php echo $file['id'];?>, '<?php echo $file['upload_name'];?>', '<?php echo $_SESSION['access_token'];?>')">Download</li>
                            <li class="text-white bg-red-500 px-[1rem] py-[0.5rem] w-[8rem] rounded-md" onclick="setDelete(<?php echo $file['id'];?>, '<?php echo $file['upload_name'];?>', <?php echo $file['size'];?>, '<?php echo $file['type'];?>')">Delete</li>
                        </ul>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="font-medium"><?php echo $file['upload_name'];?></p>
                    <p class="opacity-65 text-sm mt-1">Added on <?php echo $file['upload_date'];?></p>
                </div>
            </div>
        <?php }
    } else{?>
        <div class="flex flex-col items-center justify-center gap-[1rem] h-[15rem] opacity-65">
            <img src="./Images/not-found.png" alt="no-result" class="w-[4rem]">
            <p>Not Found</p>
        </div>
    <?php }?>
</div>
